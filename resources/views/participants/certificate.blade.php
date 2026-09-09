<!DOCTYPE html>
<html>
<head>
<style>
@font-face { font-family: 'Montserrat'; font-style: normal; font-weight: 400; src: url('{{ public_path("fonts/Montserrat-Regular.ttf") }}') format('truetype'); }
@font-face { font-family: 'Montserrat'; font-style: normal; font-weight: 700; src: url('{{ public_path("fonts/Montserrat-Bold.ttf") }}') format('truetype'); }
@font-face { font-family: 'Sora'; font-style: normal; font-weight: 400; src: url('{{ public_path("fonts/Sora-Regular.ttf") }}') format('truetype'); }
@font-face { font-family: 'Sora'; font-style: normal; font-weight: 700; src: url('{{ public_path("fonts/Sora-Bold.ttf") }}') format('truetype'); }
@page { size: 841.89pt 595.28pt; margin: 0; }
* { margin: 0; padding: 0; }
html, body { width: 841.89pt; height: 595.28pt; overflow: hidden; }
</style>
</head>
<body>
@if (!empty($isCodite))
<table width="841" height="595" cellpadding="0" cellspacing="0" style="background-color:#0d2d55;width:841pt;height:595pt;overflow:hidden;">
  <tr><td style="vertical-align:middle;height:595pt;text-align:center;padding:0;">
    <div style="position:relative;width:841pt;height:595pt;box-sizing:border-box;padding:34pt 72pt 28pt;border:1.5pt solid #00C896;">
      <div style="position:absolute;top:6pt;left:6pt;right:6pt;bottom:6pt;border:1pt solid #C9A84C;"></div>
      <div style="position:absolute;top:-55pt;right:-55pt;width:190pt;height:190pt;border-radius:50%;background-color:#1B6CA8;opacity:0.4;"></div>
      <div style="position:absolute;bottom:-55pt;left:-55pt;width:175pt;height:175pt;border-radius:50%;background-color:#1B6CA8;opacity:0.3;"></div>
      <div style="position:relative;z-index:2;text-align:center;">
        <p style="font-family:'Sora',sans-serif;font-weight:700;font-size:10pt;letter-spacing:5pt;color:#ffffff;margin:0;">EVENTURE</p>
        <div style="width:80pt;height:1pt;background-color:#00C896;margin:6pt auto 0;"></div>
        <p style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:25pt;letter-spacing:2.5pt;color:#ffffff;margin:10pt 0 0;">CERTIFICATE OF {{ strtoupper($pages[0]['certificateType']) }}</p>
        <p style="font-family:'Sora',sans-serif;font-size:7.5pt;letter-spacing:2.5pt;color:#00C896;margin:8pt 0 0;">THIS CERTIFICATE IS AWARDED TO</p>
        <p style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:25pt;color:#ffffff;margin:5pt 0 0;">{{ $participant->name }}</p>
        <div style="width:145pt;height:1pt;background-color:#00C896;margin:5pt auto 0;"></div>
        <p style="font-family:'Montserrat',sans-serif;font-weight:400;font-size:8.2pt;line-height:1.45;color:#d0e8f8;text-align:center;margin:9pt auto 0;max-width:650pt;">{!! nl2br(e($pages[0]['description'])) !!}</p>
        <div style="width:100%;height:1pt;background-color:#00C896;margin:9pt auto 0;"></div>
        @if (!empty($coditeSignatureData))
          <img src="{{ $coditeSignatureData }}" alt="Signature" style="display:block;width:115pt;height:auto;max-height:34pt;object-fit:contain;margin:7pt auto 1pt;">
        @endif
        <p style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:10pt;color:#ffffff;margin:0;">Dr. Alice M. Lacorte</p>
        <p style="font-family:'Sora',sans-serif;font-size:7pt;color:#d0e8f8;margin:2pt 0 0;">President, CODITE [AY 2025-2026]</p>
      </div>
    </div>
  </td></tr>
</table>
@else
@foreach ($pages as $page)
<table width="841" height="595" cellpadding="0" cellspacing="0" style="background-color:#0d2d55; width:841pt; height:595pt; overflow:hidden; {{ $loop->last ? '' : 'page-break-after: always;' }}">
  <tr>
    <td style="vertical-align:middle; height:595pt; text-align:center; padding:0; position:relative;">

      <!-- Decorative borders -->
      <div style="position:absolute; top:12pt; left:12pt; right:12pt; bottom:12pt; border:1.5pt solid #00C896; pointer-events:none;"></div>
      <div style="position:absolute; top:18pt; left:18pt; right:18pt; bottom:18pt; border:1pt solid #C9A84C; pointer-events:none;"></div>

      <!-- Top-right circle -->
      <div style="position:absolute; top:-60pt; right:-60pt; width:220pt; height:220pt; border-radius:50%; background-color:#1B6CA8; opacity:0.4;"></div>

      <!-- Bottom-left circle -->
      <div style="position:absolute; bottom:-60pt; left:-60pt; width:200pt; height:200pt; border-radius:50%; background-color:#1B6CA8; opacity:0.3;"></div>

      <!-- Main content wrapper -->
      <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 auto;">
        <tr><td>

      <!-- Main content table -->
      <table width="100%" cellpadding="0" cellspacing="0" style="position:relative; z-index:2;">

        <!-- EVENTURE -->
        <tr><td style="text-align:center; padding-top:20pt;">
          <p style="font-family:'Sora',sans-serif; font-weight:700; font-size:10pt; letter-spacing:5pt; color:#ffffff;">EVENTURE</p>
        </td></tr>

        <tr>
          <td style="text-align:center; padding-top:4pt;">
            <div style="display:inline-block; width:80pt; height:1pt; background-color:#00C896; font-size:0; line-height:0;">&nbsp;</div>
          </td>
        </tr>

        <!-- CERTIFICATE OF ATTENDANCE/PARTICIPATION -->
        <tr><td style="text-align:center; padding-top:10pt;">
          <p style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:36pt; letter-spacing:4pt; color:#ffffff;">CERTIFICATE OF {{ strtoupper($page['certificateType']) }}</p>
        </td></tr>

        <!-- THIS CERTIFICATE IS AWARDED TO -->
        <tr><td style="text-align:center; padding-top:10pt;">
          <p style="font-family:'Sora',sans-serif; font-weight:400; font-size:8pt; letter-spacing:3pt; color:#00C896;">THIS CERTIFICATE IS AWARDED TO</p>
        </td></tr>

        <!-- Participant Name -->
        <tr><td style="text-align:center; padding-top:6pt;">
          <p style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:32pt; color:#ffffff;">{{ $participant->name }}</p>
        </td></tr>

        <tr>
          <td style="text-align:center; padding-top:6pt;">
            <div style="display:inline-block; width:160pt; height:1pt; background-color:#00C896; font-size:0; line-height:0;">&nbsp;</div>
          </td>
        </tr>

        <!-- Description -->
        <tr><td style="text-align:center; padding-top:8pt; padding-left:100pt; padding-right:100pt;">
          <p style="font-family:'Montserrat',sans-serif; font-weight:400; font-size:9pt; line-height:1.7; color:#d0e8f8; text-align:center;">{{ $page['description'] }}</p>
        </td></tr>

        <!-- Full-width teal separator -->
        <tr><td style="text-align:center; padding-top:12pt; padding-left:40pt; padding-right:40pt;">
          <table width="100%" cellpadding="0" cellspacing="0"><tr><td style="height:1pt; background-color:#00C896;"></td></tr></table>
        </td></tr>

        <!-- Bottom row: EVENT DATE and LOCATION -->
        <tr><td style="padding-top:8pt; padding-left:60pt; padding-right:60pt; padding-bottom:20pt;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td style="text-align:left; width:50%;">
                <p style="font-family:'Sora',sans-serif; font-size:7pt; letter-spacing:2pt; color:#00C896;">EVENT DATE</p>
                <p style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:11pt; color:#ffffff; margin-top:3pt;">{{ $eventDate }}</p>
              </td>
              <td style="text-align:right; width:50%;">
                <p style="font-family:'Sora',sans-serif; font-size:7pt; letter-spacing:2pt; color:#00C896;">LOCATION</p>
                <p style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:11pt; color:#ffffff; margin-top:3pt;">{{ $eventLocation }}</p>
              </td>
            </tr>
          </table>
        </td></tr>

        <!-- Powered by Eventure -->
        <tr><td style="text-align:center; padding-top:6pt; padding-bottom:20pt;">
          <p style="font-family:'Sora',sans-serif; font-size:6pt; color:rgba(255,255,255,0.4); letter-spacing:1pt;">Powered by Eventure</p>
        </td></tr>

      </table>
        </td></tr>
      </table>
    </td>
  </tr>
</table>
@endforeach
@endif
</body>
</html>
