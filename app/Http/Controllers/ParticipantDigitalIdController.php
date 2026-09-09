<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
                    'photo_url' => $this->participantPhotoUrl($participant),
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

        $event = $participant->event;
        $allowedTypes = $event->isCodite()
            ? ['participation', 'appearance']
            : [$event->certificateRouteType()];

        if (! in_array($type, $allowedTypes, true)) {
            abort(404);
        }

        // Custom image-based certificate for Converge 2026 (Event ID 27)
        if (in_array($event->id, [27, 36])) {
            return $this->generateConverge2026Certificate($participant, $event);
        }

        if ($event->isCodite()) {
            return $this->generateCoditeCertificate($participant, $event, $type);
        }
        
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
                        'photo_url' => $this->participantPhotoUrl($participant),
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
                    'photo_url' => $this->participantPhotoUrl($participant),
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
                    'photo_url' => $this->participantPhotoUrl($participant),
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

    public function confirmAttendance(Participant $participant, Request $request)
    {
        try {
            // Only allow virtual or both attendance types
            if (! in_array($participant->event->attendance_type, ['virtual', 'both'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance confirmation not available for this event type.'
                ], 400);
            }

            // Check if meet_link is set
            if (! $participant->event->meet_link) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meet link not available for this event.'
                ], 404);
            }

            // Get meet link before update (while relationship is fresh)
            $meetLink = $participant->event->meet_link;

            // Mark participant as attended
            $participant->update([
                'attended' => true,
                'digital_id_verified_at' => $participant->digital_id_verified_at ?? now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance confirmed! Redirecting to meeting...',
                'meet_link' => $meetLink,
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Attendance confirmation error: ' . $e->getMessage(), [
                'participant_id' => $participant->id,
                'error' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
            ], 500);
        }
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
        $headers = [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        try {
            if ($this->canGeneratePng()) {
                return response($this->generatePng($payload), 200, array_merge($headers, [
                    'Content-Type' => 'image/png',
                ]));
            }
        } catch (\Throwable $exception) {
            // Fall back to SVG when the PNG backend is unavailable or fails.
        }

        return response($this->generateSvg($payload), 200, array_merge($headers, [
            'Content-Type' => 'image/svg+xml',
        ]));
    }

    private function downloadQrResponse(Participant $participant)
    {
        $payload = $this->qrPayload($participant);

        if ($this->canGeneratePng()) {
            try {
                $filename = 'digital-id-participant-'.$participant->id.'.png';

                return response($this->generatePng($payload), 200, [
                    'Content-Type' => 'image/png',
                    'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                ]);
            } catch (\Throwable $exception) {
                // Fall back to SVG download if PNG generation fails.
            }
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

    private function participantPhotoUrl($participant): ?string
    {
        $photoPath = trim((string) ($participant->photo_path ?? ''));

        if ($photoPath === '') {
            return null;
        }

        if (str_starts_with($photoPath, 'http://') || str_starts_with($photoPath, 'https://')) {
            return $photoPath;
        }

        try {
            return Storage::disk('participant-photos')->url(basename($photoPath));
        } catch (\Throwable $exception) {
            return null;
        }
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

    /**
     * Generate a custom image-based certificate for Converge 2026 (Event ID 27).
     * Loads certificate background image from Supabase and overlays participant name.
     */
    private function generateConverge2026Certificate(Participant $participant, Event $event)
    {
        try {
            // Certificate image URL in Supabase Storage
            $certImageUrl = $event->id === 36 ? 'https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/event-posters/CFP-2026-cert.jpg' : 'https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/event-posters/converge-2026-cert.png';
            
            // Fetch the certificate background image
            $imageData = @file_get_contents($certImageUrl);
            if ($imageData === false) {
                abort(500, 'Unable to load certificate template image from storage.');
            }
            
            // Create image from data
            $img = @imagecreatefromstring($imageData);
            if ($img === false) {
                abort(500, 'Unable to process certificate template image.');
            }
            
            // Get image dimensions
            $width = imagesx($img);
            $height = imagesy($img);
            
            // Font configuration
            $fontPath = public_path($event->id === 36 ? "fonts/Montserrat-Bold-Static.ttf" : "fonts/Sora-Bold.ttf");
            if (!file_exists($fontPath)) {
            $fontPath = public_path($event->id === 36 ? "fonts/Montserrat-Bold-Static.ttf" : "fonts/Sora-Bold.ttf");
            }
            if (!file_exists($fontPath)) {
                abort(500, 'Certificate font file not found.');
            }
            
            // Color: Dark Navy (#0D1B3E)
            $color = imagecolorallocate($img, 13, 27, 62);
            
            // Font size and text
            // Font size - auto-scale for long names
            $fontSize = 38;
            $name = $participant->name;
            $maxWidth = (int)($width * 0.80);
            do {
                $bbox = imagettfbbox($fontSize, 0, $fontPath, $name);
                if ($bbox === false) { imagedestroy($img); abort(500, 'Unable to calculate text bounds.'); }
                $textWidth = abs($bbox[4] - $bbox[0]);
                if ($textWidth <= $maxWidth || $fontSize <= 20) break;
                $fontSize -= 2;
            } while (true);
            $x = ($width - $textWidth) / 2;
            $y = (int)($height * 0.46);
            
            // Draw text on image
            $result = imagettftext($img, $fontSize, 0, $x, $y, $color, $fontPath, $name);
            if ($result === false) {
                imagedestroy($img);
                abort(500, 'Unable to draw text on certificate.');
            }
            
            // Generate PNG output
            ob_start();
            imagepng($img, null, 9);
            $imageData = ob_get_clean();
            imagedestroy($img);
            
            // Prepare filename
            $filename = 'Certificate-of-Participation-' . Str::slug($participant->name) . '.png';
            
            // Return PNG response with mobile-friendly headers
            $fileSize = strlen($imageData);
            
            return response($imageData, 200, [
                'Content-Type' => 'image/png',
                'Content-Length' => $fileSize,
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Accept-Ranges' => 'bytes',
            ]);
        } catch (\Exception $e) {
            abort(500, 'Error generating certificate: ' . $e->getMessage());
        }
    }

    private function generateCoditeCertificate(Participant $participant, Event $event, string $type)
    {
        $fontCacheDir = storage_path('framework/fonts');

        try {
            $signatureResponse = Http::timeout(15)->get(
                'https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/eventure-assets/signatures/doc-alice-esign.png'
            );
            $signatureResponse->throw();
            $signatureContents = $signatureResponse->body();
            $signatureMime = $signatureResponse->header('Content-Type') ?: 'image/png';
        } catch (\Throwable $exception) {
            Log::error('CODITE certificate signature loading failed.', [
                'exception' => $exception,
            ]);
            abort(500, 'Unable to load the CODITE certificate signature.');
        }

        $signatureData = 'data:'.$signatureMime.';base64,'.base64_encode($signatureContents);
        $isParticipation = $type === 'participation';
        $description = $isParticipation
            ? 'In recognition of the active participation in the Council of Deans in IT Education (CODITE) Region IV General Assembly with the theme, “Beyond Academics: Strengthening Partnerships for Future-Ready Academe”, held on September 11, 2026, at National University – Laguna.\n\nThis general assembly serves as a venue for meaningful engagement, collaboration, and the exchange of ideas among academic leaders, faculty members and stakeholders in advancing excellence, innovation, and professional development in Computing and Information Technology education.\n\nGiven this 11th day of September 2026 at National University – Laguna.'
            : 'In recognition of their presence and attendance at the Council of Deans in IT Education (CODITE) Region IV General Assembly with the theme, “Beyond Academics: Strengthening Partnerships for Future-Ready Academe”, held on September 11, 2026, at National University – Laguna.\n\nGiven this 11th day of September 2026 at National University – Laguna.';

        $pdf = Pdf::loadView('participants.certificate', [
            'participant' => $participant,
            'eventDate' => $event->dateRangeLabel(),
            'eventLocation' => $event->location ?: 'TBA',
            'pages' => [[
                'certificateType' => $isParticipation ? 'Participation' : 'Appearance',
                'description' => $description,
            ]],
            'isCodite' => true,
            'coditeSignatureData' => $signatureData,
        ]);

        $pdf->setPaper([0, 0, 841.89, 595.28], 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Montserrat',
            'fontDir' => $fontCacheDir,
            'fontCache' => $fontCacheDir,
        ]);

        $filename = 'certificate-of-'.($isParticipation ? 'participation' : 'appearance').'-'.Str::slug($participant->name ?: 'participant').'.pdf';

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
