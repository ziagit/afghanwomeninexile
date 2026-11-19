<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Member Card · {{ $verification->member_id }}</title>
    <style>
        @font-face { font-family: 'Fraunces'; font-weight: 500; src: url('{{ $fontDir }}/Fraunces-Medium.ttf') format('truetype'); }
        @font-face { font-family: 'Fraunces'; font-weight: 600; src: url('{{ $fontDir }}/Fraunces-SemiBold.ttf') format('truetype'); }
        @font-face { font-family: 'Work Sans'; font-weight: 400; src: url('{{ $fontDir }}/WorkSans-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Work Sans'; font-weight: 600; src: url('{{ $fontDir }}/WorkSans-SemiBold.ttf') format('truetype'); }

        @page { margin: 0; }
        * { margin: 0; padding: 0; }
        body { font-family: 'Work Sans', sans-serif; font-weight: 400; color: #3a2436; }

        .side { position: relative; width: 54mm; height: 85.6mm; overflow: hidden; }
        .side--front { background: #faf1ea; }
        .side--back { background: #3b1f3a; color: #faf1ea; page-break-before: always; }
        .abs { position: absolute; }

        /* Front */
        .header { top: 0; left: 0; width: 54mm; height: 15mm; background: #3b1f3a; }
        .header-rule { top: 15mm; left: 0; width: 54mm; height: 0.7mm; background: #d4a86a; }
        .header-logo { top: 2.6mm; left: 3.6mm; width: 9.8mm; height: 9.8mm; }
        .header-names { top: 3.7mm; left: 15.4mm; width: 36mm; }
        .header-full { font-family: 'Fraunces', serif; font-weight: 500; font-size: 9.6pt; line-height: 1; color: #faf1ea; letter-spacing: 0.3pt; }
        .header-short { font-size: 5pt; font-weight: 600; line-height: 1; color: #d4a86a; letter-spacing: 1.3pt; text-transform: uppercase; margin-top: 1.3mm; }

        .eyebrow { top: 18.2mm; left: 0; width: 54mm; text-align: center; font-size: 4.8pt; font-weight: 600; letter-spacing: 1.6pt; text-transform: uppercase; color: #c65a72; }

        .photo { top: 22mm; left: 15.5mm; width: 23mm; height: 30.67mm; border: 0.6mm solid #d4a86a; background: #f4e0d9; }
        .photo img { width: 23mm; height: 30.67mm; }
        .photo-empty { padding-top: 13mm; text-align: center; font-size: 4.6pt; color: #7d5f74; letter-spacing: 0.6pt; text-transform: uppercase; }

        .name { top: 54.6mm; left: 2.5mm; width: 49mm; text-align: center; font-family: 'Fraunces', serif; font-weight: 600; line-height: 1.05; color: #3b1f3a; }
        .member-id { top: 60.6mm; left: 0; width: 54mm; text-align: center; font-size: 6.4pt; font-weight: 600; letter-spacing: 0.9pt; color: #c65a72; }
        .member-id span { font-size: 4.4pt; letter-spacing: 1.2pt; color: #7d5f74; text-transform: uppercase; }

        .divider { top: 64.6mm; left: 4mm; width: 46mm; height: 0.25mm; background: #e7d2c8; }

        .details { top: 66.6mm; left: 4mm; width: 28mm; }
        .details table { border-collapse: collapse; width: 28mm; }
        .details td { padding: 0 0 1.1mm; vertical-align: top; }
        .label { display: block; font-size: 3.9pt; font-weight: 600; letter-spacing: 0.9pt; text-transform: uppercase; color: #7d5f74; line-height: 1.1; }
        .value { display: block; font-size: 5.6pt; line-height: 1.15; color: #3a2436; }

        .qr { top: 65.8mm; left: 34.6mm; width: 15mm; height: 15mm; background: #ffffff; }
        .qr img { width: 15mm; height: 15mm; }
        .qr-caption { top: 81.2mm; left: 33.6mm; width: 17mm; text-align: center; font-size: 3.7pt; font-weight: 600; letter-spacing: 0.7pt; text-transform: uppercase; color: #7d5f74; }

        .footer-bar { bottom: 0; left: 0; width: 54mm; height: 1.6mm; background: #c65a72; }

        /* Back */
        .back-logo { top: 9mm; left: 18mm; width: 18mm; height: 18mm; }
        .back-title { top: 30mm; left: 3mm; width: 48mm; text-align: center; font-family: 'Fraunces', serif; font-weight: 500; font-size: 10pt; line-height: 1.1; }
        .back-short { top: 40.6mm; left: 0; width: 54mm; text-align: center; font-size: 5pt; font-weight: 600; letter-spacing: 1.6pt; text-transform: uppercase; color: #d4a86a; }
        .back-rule { top: 44.8mm; left: 22mm; width: 10mm; height: 0.4mm; background: #d4a86a; }
        .back-text { top: 47.6mm; left: 5mm; width: 44mm; text-align: center; font-size: 5.2pt; line-height: 1.45; color: #f4e0d9; }
        .back-text p { margin-bottom: 1.6mm; }
        .back-verify { color: #d4a86a; font-weight: 600; }
        .signature-line { top: 75mm; left: 12mm; width: 30mm; height: 0.25mm; background: #d4a86a; }
        .signature-label { top: 76.2mm; left: 0; width: 54mm; text-align: center; font-size: 4pt; letter-spacing: 1pt; text-transform: uppercase; color: #f4e0d9; }
        .back-bar { bottom: 0; left: 0; width: 54mm; height: 1.6mm; background: #c65a72; }
    </style>
</head>
<body>
    @php
        $fullName = trim($verification->first_name.' '.$verification->last_name);
        $nameSize = match (true) {
            mb_strlen($fullName) > 26 => '7pt',
            mb_strlen($fullName) > 20 => '8.2pt',
            default => '9.6pt',
        };
        $verifyHost = preg_replace('#^https?://#', '', strtok($verifyUrl, '?'));
    @endphp

    <div class="side side--front">
        <div class="abs header"></div>
        <div class="abs header-rule"></div>
        <img class="abs header-logo" src="{{ $logo }}" alt="">
        <div class="abs header-names">
            <div class="header-full">Afghan Women</div>
            <div class="header-short">In Exile · MAWIE</div>
        </div>

        <div class="abs eyebrow">Member Card</div>

        <div class="abs photo">
            @if ($photo)
                <img src="{{ $photo }}" alt="">
            @else
                <div class="photo-empty">No photo</div>
            @endif
        </div>

        <div class="abs name" style="font-size: {{ $nameSize }};">{{ $fullName }}</div>
        <div class="abs member-id"><span>ID</span>&nbsp; {{ $verification->member_id }}</div>

        <div class="abs divider"></div>

        <div class="abs details">
            <table>
                <tr><td><span class="label">Location</span><span class="value">{{ \Illuminate\Support\Str::limit($verification->location, 30) }}</span></td></tr>
                <tr><td><span class="label">Joined</span><span class="value">{{ $verification->joined_at?->format('d M Y') }}</span></td></tr>
                <tr><td><span class="label">Expires</span><span class="value">{{ $verification->expirs_at?->format('d M Y') ?? 'No expiry' }}</span></td></tr>
            </table>
        </div>

        <div class="abs qr"><img src="{{ $qrCode }}" alt=""></div>
        <div class="abs qr-caption">Scan to verify</div>

        <div class="abs footer-bar"></div>
    </div>

    <div class="side side--back">
        <img class="abs back-logo" src="{{ $logo }}" alt="">
        <div class="abs back-title">Movement of Afghan<br>Women in Exile</div>
        <div class="abs back-short">MAWIE</div>
        <div class="abs back-rule"></div>
        <div class="abs back-text">
            <p>The holder of this card is a registered member of the Movement of Afghan Women in Exile.</p>
            <p>Scan the QR code on the front, or visit<br><span class="back-verify">{{ $verifyHost }}</span><br>and enter the member ID to verify.</p>
            <p>This card remains the property of MAWIE.</p>
        </div>
        <div class="abs signature-line"></div>
        <div class="abs signature-label">Authorized signature</div>
        <div class="abs back-bar"></div>
    </div>
</body>
</html>
