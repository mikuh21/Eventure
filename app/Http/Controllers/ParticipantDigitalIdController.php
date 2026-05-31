<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ParticipantDigitalIdController extends Controller
{
    public function verifyForm()
    {
        return view('admin.digital-id-verify');
    }

    public function verify(Request $request)
    {
        $participant = $this->resolveParticipantFromInput($request);
        $wantsJson = $request->expectsJson() || $request->is('api/*');

        // Event Staff can only verify participants of their own events
        if ($participant && auth()->user()->hasRole('event_staff') && $participant->event->created_by !== auth()->id()) {
            if ($wantsJson) {
                return response()->json(['message' => 'You are not authorized to verify this participant.'], 403);
            }
            // Set participant to null so the view shows "No record available." error
            $participant = null;
        }

        $verificationCheckedAt = null;

        // If verification was attempted (form POST or payload), record first-verified timestamp
        // and ensure a participant is marked attended on first staff verification.
        if ($participant && ($request->filled('token') || $request->filled('payload'))) {
            $verificationCheckedAt = now();

            if ($participant instanceof \App\Models\Participant) {
                $participant->update([
                    'attended' => true,
                    'digital_id_verified_at' => $participant->digital_id_verified_at ?? $verificationCheckedAt,
                ]);
            } elseif (empty($participant->digital_id_verified_at)) {
                $participant->update(['digital_id_verified_at' => $verificationCheckedAt]);
            }
        }

        if ($wantsJson) {
            return response()->json([
                'valid' => (bool) $participant,
                'participant' => $participant ? [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'event_id' => $participant->event_id ?? $participant->event->id ?? null,
                    'event_name' => $participant->event->title ?? null,
                    'event_date' => $participant->event->start_date ? $participant->event->start_date->format('F j, Y') : null,
                    'event_location' => $participant->event->location ?? null,
                    'type' => $participant instanceof \App\Models\Guest ? 'guest' : 'participant',
                    'role' => $participant->participant_type ?? $participant->role ?? null,
                    'institution' => $participant->institution ?? null,
                    'digital_id_verified_at' => $participant->digital_id_verified_at ? $participant->digital_id_verified_at->toDateTimeString() : null,
                    'verified_at' => $verificationCheckedAt ? $verificationCheckedAt->toDateTimeString() : null,
                ] : null,
            ]);
        }

        return view('admin.digital-id-verify', [
            'participant' => $participant,
            'submittedToken' => $request->input('token'),
            'submittedPayload' => $request->input('payload'),
            'verificationAttempted' => $request->filled('token') || $request->filled('payload'),
            'verificationCheckedAt' => $verificationCheckedAt,
        ]);
    }

    public function show(Request $request, Participant $participant)
    {
        if (! $participant->digital_id_token) {
            $participant->update([
                'digital_id_token' => Str::uuid()->toString(),
            ]);
        }

        if ($request->query('format') === 'qr') {
            return $this->generateQrResponse($this->qrPayload($participant));
        }

        if ($request->query('download') === '1') {
            return $this->downloadQrResponse($participant);
        }

        $payload = $this->qrPayload($participant);
        $qrSvg = $this->generateSvg($payload);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'participant_id' => $participant->id,
                'participant_name' => $participant->name,
                'event_name' => $participant->event->title,
                'attended' => $participant->attended,
                'token' => $participant->digital_id_token,
                'qr_payload' => $payload,
                'download_url' => route('participants.digital-id.download', $participant),
            ]);
        }

        return view('participants.digital-id', [
            'participant' => $participant,
            'qrSvg' => $qrSvg,
            'payload' => $payload,
        ]);
    }

    public function download(Participant $participant)
    {
        if (! $participant->digital_id_token) {
            $participant->update([
                'digital_id_token' => Str::uuid()->toString(),
            ]);
        }

        return $this->downloadQrResponse($participant);
    }

    public function certificate(string $token, string $type)
    {
        $fontCacheDir = storage_path('framework/fonts');
        if (! file_exists($fontCacheDir)) {
            @mkdir($fontCacheDir, 0775, true);
        }
        foreach (glob($fontCacheDir.'/*') as $file) { @unlink($file); }

        $participant = Participant::query()
            ->with(['event'])
            ->where('digital_id_token', $token)
            ->firstOrFail();

        if (! $participant->hasSubmittedSurvey()) {
            abort(403, 'Certificate is not available yet.');
        }

        if ($type !== $participant->event->certificateRouteType()) {
            abort(404);
        }

        $event = $participant->event;
        $eventDate = $event->dateRangeLabel();
        $eventLocation = $event->location ?: 'TBA';

        $pages = [];

        if ($event->attendance_type === Event::ATTENDANCE_VIRTUAL) {
            $pages[] = [
                'certificateType' => 'Participation',
                'description' => 'This certificate is awarded to '.$participant->name.' in recognition of their valuable participation in the '.$event->title.' held on '.$eventDate.' at '.$eventLocation.'. Their involvement, cooperation, and contribution throughout the activity demonstrated enthusiasm, dedication, and support toward the success of the event.',
            ];
        } elseif ($event->attendance_type === Event::ATTENDANCE_BOTH) {
            $pages[] = [
                'certificateType' => 'Attendance',
                'description' => 'This certificate is awarded to '.$participant->name.' in recognition of their active participation and attendance during the '.$event->title.' held on '.$eventDate.' at '.$eventLocation.'. Their presence and engagement contributed to the success of the event and demonstrated their commitment to learning, professional growth, and continuous development.',
            ];
            $pages[] = [
                'certificateType' => 'Participation',
                'description' => 'This certificate is awarded to '.$participant->name.' in recognition of their valuable participation in the '.$event->title.' held on '.$eventDate.' at '.$eventLocation.'. Their involvement, cooperation, and contribution throughout the activity demonstrated enthusiasm, dedication, and support toward the success of the event.',
            ];
        } else {
            $pages[] = [
                'certificateType' => 'Attendance',
                'description' => 'This certificate is awarded to '.$participant->name.' in recognition of their active participation and attendance during the '.$event->title.' held on '.$eventDate.' at '.$eventLocation.'. Their presence and engagement contributed to the success of the event and demonstrated their commitment to learning, professional growth, and continuous development.',
            ];
        }

        $pdf = Pdf::loadView('participants.certificate', [
            'participant' => $participant,
            'eventDate' => $eventDate,
            'eventLocation' => $eventLocation,
            'pages' => $pages,
        ]);

        $pdf->setPaper([0, 0, 841.89, 595.28], 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Montserrat',
            'dpi' => 150,
            'fontDir' => $fontCacheDir,
            'fontCache' => $fontCacheDir,
        ]);

        $filename = match ($participant->event->attendance_type ?? Event::ATTENDANCE_FACE_TO_FACE) {
            Event::ATTENDANCE_VIRTUAL => 'certificate-of-participation-'.Str::slug($participant->name ?: 'participant').'.pdf',
            Event::ATTENDANCE_BOTH => 'certificates-'.Str::slug($participant->name ?: 'participant').'.pdf',
            default => 'certificate-of-attendance-'.Str::slug($participant->name ?: 'participant').'.pdf',
        };

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function scan(Request $request)
    {
        $participant = $this->resolveParticipantFromInput($request);

        if (! $participant) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid digital ID payload.',
            ], 404);
        }

        $verificationCheckedAt = now();

        // If the resolved model is a Participant, we mark attendance. For Guests, we only record verification.
        if ($participant instanceof \App\Models\Participant) {
            if ($participant->attended) {
                // Ensure first-verified timestamp is recorded
                if (empty($participant->digital_id_verified_at)) {
                    $participant->update(['digital_id_verified_at' => $verificationCheckedAt]);
                }

                return response()->json([
                    'valid' => true,
                    'message' => 'Attendance already recorded.',
                    'participant' => [
                        'id' => $participant->id,
                        'name' => $participant->name,
                        'email' => $participant->email,
                        'event_id' => $participant->event_id,
                        'event_name' => $participant->event->title,
                        'event_date' => $participant->event->start_date ? $participant->event->start_date->format('F j, Y') : null,
                        'event_location' => $participant->event->location ?? null,
                        'attended' => true,
                        'type' => 'participant',
                        'role' => $participant->participant_type ?? null,
                        'institution' => $participant->institution ?? null,
                        'digital_id_verified_at' => $participant->digital_id_verified_at ? $participant->digital_id_verified_at->toDateTimeString() : null,
                        'verified_at' => $verificationCheckedAt->toDateTimeString(),
                    ],
                ]);
            }

            $participant->update([
                'attended' => true,
                'digital_id_verified_at' => $participant->digital_id_verified_at ?? $verificationCheckedAt,
            ]);

            return response()->json([
                'valid' => true,
                'message' => 'Attendance marked successfully.',
                'participant' => [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'event_id' => $participant->event_id,
                    'event_name' => $participant->event->title,
                    'event_date' => $participant->event->start_date ? $participant->event->start_date->format('F j, Y') : null,
                    'event_location' => $participant->event->location ?? null,
                    'attended' => true,
                    'type' => 'participant',
                    'role' => $participant->participant_type ?? null,
                    'institution' => $participant->institution ?? null,
                    'digital_id_verified_at' => $participant->digital_id_verified_at ? $participant->digital_id_verified_at->toDateTimeString() : null,
                    'verified_at' => $verificationCheckedAt->toDateTimeString(),
                ],
            ]);
        }

        // If it's a Guest
        if ($participant instanceof \App\Models\Guest) {
            // Record first verification timestamp if not already set
            if (empty($participant->digital_id_verified_at)) {
                $participant->update(['digital_id_verified_at' => $verificationCheckedAt]);
            }

            return response()->json([
                'valid' => true,
                'message' => 'Guest token verified.',
                'participant' => [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'event_id' => $participant->event_id,
                    'event_name' => $participant->event->title,
                    'event_date' => $participant->event->start_date ? $participant->event->start_date->format('F j, Y') : null,
                    'event_location' => $participant->event->location ?? null,
                    'type' => 'guest',
                    'role' => $participant->role ?? null,
                    'institution' => null,
                    'digital_id_verified_at' => $participant->digital_id_verified_at ? $participant->digital_id_verified_at->toDateTimeString() : null,
                    'verified_at' => $verificationCheckedAt->toDateTimeString(),
                ],
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Invalid digital ID payload.',
        ], 404);
    }

    private function qrPayload(Participant $participant): string
    {
        return json_encode([
            'participant_id' => $participant->id,
            'event_id' => $participant->event_id,
            'token' => $participant->digital_id_token,
        ]);
    }

    private function generateSvg(string $payload): string
    {
        $qrFacade = '\\SimpleSoftwareIO\\QrCode\\Facades\\QrCode';

        if (class_exists($qrFacade)) {
            return $qrFacade::size(220)->generate($payload);
        }

        return '<p>QR package not installed. Run: ./vendor/bin/sail composer require simplesoftwareio/simple-qrcode</p>';
    }

    private function generatePng(string $payload): string
    {
        $qrFacade = '\\SimpleSoftwareIO\\QrCode\\Facades\\QrCode';

        if (! class_exists($qrFacade)) {
            abort(500, 'QR package not installed. Run: ./vendor/bin/sail composer require simplesoftwareio/simple-qrcode');
        }

        return $qrFacade::format('png')
            ->size(320)
            ->margin(1)
            ->generate($payload);
    }

    private function generateQrResponse(string $payload)
    {
        if ($this->canGeneratePng()) {
            return response($this->generatePng($payload), 200, [
                'Content-Type' => 'image/png',
            ]);
        }

        return response($this->generateSvg($payload), 200, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }

    private function downloadQrResponse(Participant $participant)
    {
        $payload = $this->qrPayload($participant);

        if ($this->canGeneratePng()) {
            $filename = 'digital-id-participant-'.$participant->id.'.png';

            return response($this->generatePng($payload), 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);
        }

        $filename = 'digital-id-participant-'.$participant->id.'.svg';

        return response($this->generateSvg($payload), 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function canGeneratePng(): bool
    {
        return extension_loaded('gd') || extension_loaded('imagick');
    }

    private function resolveParticipantFromInput(Request $request): ?Participant
    {
        $token = $request->input('token');

        if (! $token && $request->filled('payload')) {
            $decoded = json_decode((string) $request->input('payload'), true);

            if (is_array($decoded) && isset($decoded['token']) && is_string($decoded['token'])) {
                $token = $decoded['token'];
            } elseif (is_string($request->input('payload'))) {
                $token = $request->input('payload');
            }
        }

        if ($token) {
            $participant = Participant::query()
                ->with('event:id,title,start_date,location')
                ->where('digital_id_token', $token)
                ->first();

            if ($participant) {
                return $participant;
            }

            // Try guests as fallback
            $guest = \App\Models\Guest::query()
                ->with('event:id,title,start_date,location')
                ->where('digital_token', $token)
                ->first();

            if ($guest) {
                return $guest;
            }
        }

        return null;
    }
}
