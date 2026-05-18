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
            return back()->with('error', 'You are not authorized to verify this participant.');
        }

        if ($wantsJson) {
            return response()->json([
                'valid' => (bool) $participant,
                'participant' => $participant ? [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'event_id' => $participant->event_id,
                    'event_name' => $participant->event->title,
                ] : null,
            ]);
        }

        return view('admin.digital-id-verify', [
            'participant' => $participant,
            'submittedToken' => $request->input('token'),
            'submittedPayload' => $request->input('payload'),
            'verificationAttempted' => $request->filled('token') || $request->filled('payload'),
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

        if (! $participant->event->hasEnded() && ! $participant->evaluations()->exists()) {
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

        return $pdf->download($filename);
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

        if ($participant->attended) {
            return response()->json([
                'valid' => true,
                'message' => 'Attendance already recorded.',
                'participant' => [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'event_id' => $participant->event_id,
                    'event_name' => $participant->event->title,
                    'attended' => true,
                ],
            ]);
        }

        $participant->update([
            'attended' => true,
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
                'attended' => true,
            ],
        ]);
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
        return extension_loaded('imagick');
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
            return Participant::query()
                ->with('event:id,title')
                ->where('digital_id_token', $token)
                ->first();
        }

        return null;
    }
}
