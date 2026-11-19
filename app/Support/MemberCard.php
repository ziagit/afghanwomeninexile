<?php

namespace App\Support;

use App\Models\Verification;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use GdImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Draws a member's ID card as print-ready PNGs: CR80 portrait (54 x 85.6 mm) at 600 DPI,
 * with the resolution stored in the file so it prints at exact card size at 100% scale.
 * All positions are in millimetres from the card's top-left corner; text keeps 3 mm from the edge.
 */
class MemberCard
{
    public const DPI = 600;

    private const WIDTH_MM = 54;

    private const HEIGHT_MM = 85.6;

    private const COLORS = [
        'plum-dark' => '3b1f3a',
        'parchment' => 'faf1ea',
        'parchment-dim' => 'f4e0d9',
        'brick' => 'c65a72',
        'gold' => 'd4a86a',
        'ink' => '3a2436',
        'ink-soft' => '7d5f74',
        'hairline' => 'e7d2c8',
        'white' => 'ffffff',
        'black' => '000000',
    ];

    private const FONTS = [
        'serif' => 'Fraunces-Medium.ttf',
        'serif-bold' => 'Fraunces-SemiBold.ttf',
        'sans' => 'WorkSans-Regular.ttf',
        'sans-bold' => 'WorkSans-SemiBold.ttf',
    ];

    public function __construct(
        private Verification $verification,
        private string $verifyUrl,
    ) {}

    // e.g. "shaima-rezaei-mawe-00131-2026-front.png" (non-Latin names are transliterated by Str::slug).
    public static function filename(Verification $verification, string $side): string
    {
        $parts = array_filter([
            Str::slug($verification->first_name.' '.$verification->last_name),
            Str::slug($verification->member_id),
            $side,
        ]);

        return implode('-', $parts).'.png';
    }

    public function front(): string
    {
        $card = $this->canvas('parchment');
        $member = $this->verification;

        // Header band with logo and name.
        $this->rect($card, 0, 0, self::WIDTH_MM, 15, 'plum-dark');
        $this->rect($card, 0, 15, self::WIDTH_MM, 0.7, 'gold');
        $this->logo($card, 3.6, 3, 9);
        $this->text($card, 'Afghan Women', 'serif', 9.6, 'parchment', 15, 7.1, spacing: 0.3);
        $this->text($card, 'IN EXILE · MAWIE', 'sans-bold', 5, 'gold', 15, 10.4, spacing: 1.3);

        $this->text($card, 'MEMBER CARD', 'sans-bold', 4.8, 'brick', 27, 20, 'center', 1.6);

        // Photo with gold frame.
        $this->rect($card, 15.5, 22, 24.2, 31.87, 'gold');
        $this->photo($card, 16.1, 22.6, 23, 30.67);

        $name = trim($member->first_name.' '.$member->last_name);
        $this->text($card, $name, 'serif-bold', $this->fitSize($name, 'serif-bold', 9.6, 48), 'plum-dark', 27, 58, 'center');
        $this->memberId($card, $member->member_id, 62.9);

        $this->rect($card, 4, 64.2, 46, 0.25, 'hairline');

        $details = [
            'LOCATION' => $member->location,
            'JOINED' => $member->joined_at?->format('d M Y') ?? '-',
            'EXPIRES' => $member->expirs_at?->format('d M Y') ?? 'No expiry',
        ];
        $baseline = 67.7;
        foreach ($details as $label => $value) {
            $this->text($card, $label, 'sans-bold', 3.9, 'ink-soft', 4, $baseline, spacing: 0.9);
            $this->text($card, $this->truncate((string) $value, 'sans', 5.6, 28), 'sans', 5.6, 'ink', 4, $baseline + 2.4);
            $baseline += 4.65;
        }

        $this->qrCode($card, 34.6, 65.6, 15);
        $this->text($card, 'SCAN TO VERIFY', 'sans-bold', 3.7, 'ink-soft', 42.1, 82.5, 'center', 0.7);

        $this->rect($card, 0, 84, self::WIDTH_MM, 1.6, 'brick');

        return $this->png($card);
    }

    public function back(): string
    {
        $card = $this->canvas('plum-dark');
        $verifyAddress = preg_replace('#^https?://#', '', strtok($this->verifyUrl, '?'));

        $this->logo($card, 18, 9, 18);
        $this->text($card, 'Movement of Afghan', 'serif', 10, 'parchment', 27, 33.4, 'center');
        $this->text($card, 'Women in Exile', 'serif', 10, 'parchment', 27, 37.3, 'center');
        $this->text($card, 'MAWIE', 'sans-bold', 5, 'gold', 27, 42.3, 'center', 1.6);
        $this->rect($card, 22, 44.8, 10, 0.4, 'gold');

        $lineHeight = 2.66;
        $paragraphs = [
            [[$this->wrap('The holder of this card is a registered member of the Movement of Afghan Women in Exile.', 'sans', 5.2, 46), 'sans', 'parchment-dim']],
            [
                [['Scan the QR code on the front, or visit'], 'sans', 'parchment-dim'],
                [[$this->truncate($verifyAddress, 'sans-bold', 5.2, 46)], 'sans-bold', 'gold'],
                [['and enter the member ID to verify.'], 'sans', 'parchment-dim'],
            ],
            [[['This card remains the property of MAWIE.'], 'sans', 'parchment-dim']],
        ];
        $baseline = 49.7;
        foreach ($paragraphs as $paragraph) {
            foreach ($paragraph as [$lines, $font, $color]) {
                foreach ($lines as $line) {
                    $this->text($card, $line, $font, 5.2, $color, 27, $baseline, 'center');
                    $baseline += $lineHeight;
                }
            }
            $baseline += 1.6;
        }

        $this->rect($card, 12, 75, 30, 0.25, 'gold');
        $this->text($card, 'AUTHORIZED SIGNATURE', 'sans', 4, 'parchment-dim', 27, 78.1, 'center', 1);

        $this->rect($card, 0, 84, self::WIDTH_MM, 1.6, 'brick');

        return $this->png($card);
    }

    private function canvas(string $background): GdImage
    {
        $card = imagecreatetruecolor($this->px(self::WIDTH_MM), $this->px(self::HEIGHT_MM));
        imagealphablending($card, true);
        imagefilledrectangle($card, 0, 0, imagesx($card) - 1, imagesy($card) - 1, $this->color($card, $background));

        return $card;
    }

    private function png(GdImage $card): string
    {
        imageresolution($card, self::DPI, self::DPI);

        ob_start();
        imagepng($card, null, 6);

        return ob_get_clean();
    }

    private function px(float $mm): int
    {
        return (int) round($mm / 25.4 * self::DPI);
    }

    private function color(GdImage $image, string $name): int
    {
        [$r, $g, $b] = sscanf(self::COLORS[$name], '%02x%02x%02x');

        return imagecolorallocate($image, $r, $g, $b);
    }

    private function rect(GdImage $card, float $x, float $y, float $width, float $height, string $color): void
    {
        imagefilledrectangle($card, $this->px($x), $this->px($y), $this->px($x + $width) - 1, $this->px($y + $height) - 1, $this->color($card, $color));
    }

    // GD measures font sizes at 96 DPI, so scale points to the card's resolution.
    private function gdSize(float $points): float
    {
        return $points * self::DPI / 96;
    }

    private function pointsToPx(float $points): float
    {
        return $points / 72 * self::DPI;
    }

    private function font(string $font): string
    {
        return resource_path('fonts/'.self::FONTS[$font]);
    }

    private function width(string $text, string $font, float $points, float $spacing = 0): float
    {
        if ($text === '') {
            return 0;
        }

        $box = imagettfbbox($this->gdSize($points), 0, $this->font($font), $text);
        $letterSpacing = (mb_strlen($text) - 1) * $this->pointsToPx($spacing);

        return $box[2] - $box[0] + $letterSpacing;
    }

    private function text(GdImage $card, string $text, string $font, float $points, string $color, float $x, float $baseline, string $align = 'left', float $spacing = 0): void
    {
        $width = $this->width($text, $font, $points, $spacing);
        $left = $this->px($x) - ($align === 'center' ? $width / 2 : 0);
        $y = $this->px($baseline);
        $ink = $this->color($card, $color);

        if ($spacing == 0) {
            imagettftext($card, $this->gdSize($points), 0, (int) round($left), $y, $ink, $this->font($font), $text);

            return;
        }

        $characters = mb_str_split($text);
        foreach ($characters as $index => $character) {
            $offset = $this->width(implode('', array_slice($characters, 0, $index)), $font, $points);
            $offset += $index * $this->pointsToPx($spacing);
            imagettftext($card, $this->gdSize($points), 0, (int) round($left + $offset), $y, $ink, $this->font($font), $character);
        }
    }

    // Largest size up to $points (in 0.2pt steps) at which the text fits $maxWidth mm.
    private function fitSize(string $text, string $font, float $points, float $maxWidth): float
    {
        while ($points > 4 && $this->width($text, $font, $points) > $this->px($maxWidth)) {
            $points -= 0.2;
        }

        return $points;
    }

    private function truncate(string $text, string $font, float $points, float $maxWidth): string
    {
        if ($this->width($text, $font, $points) <= $this->px($maxWidth)) {
            return $text;
        }

        while ($text !== '' && $this->width($text.'…', $font, $points) > $this->px($maxWidth)) {
            $text = mb_substr($text, 0, -1);
        }

        return rtrim($text, " ,.;:-").'…';
    }

    private function wrap(string $text, string $font, float $points, float $maxWidth): array
    {
        $lines = [];
        $line = '';

        foreach (explode(' ', $text) as $word) {
            $candidate = $line === '' ? $word : $line.' '.$word;

            if ($line !== '' && $this->width($candidate, $font, $points) > $this->px($maxWidth)) {
                $lines[] = $line;
                $line = $word;
            } else {
                $line = $candidate;
            }
        }

        return [...$lines, $line];
    }

    // "ID" label and the member ID side by side, centred together.
    private function memberId(GdImage $card, string $memberId, float $baseline): void
    {
        $size = $this->fitSize($memberId, 'sans-bold', 6.4, 40);
        $labelWidth = $this->width('ID', 'sans-bold', 4.4, 1.2);
        $gap = $this->px(2);
        $idWidth = $this->width($memberId, 'sans-bold', $size, 0.9);
        $left = ($this->px(self::WIDTH_MM) - ($labelWidth + $gap + $idWidth)) / 2;

        $this->text($card, 'ID', 'sans-bold', 4.4, 'ink-soft', $left / self::DPI * 25.4, $baseline, spacing: 1.2);
        $this->text($card, $memberId, 'sans-bold', $size, 'brick', ($left + $labelWidth + $gap) / self::DPI * 25.4, $baseline, spacing: 0.9);
    }

    private function logo(GdImage $card, float $x, float $y, float $size): void
    {
        $logo = imagecreatefrompng(public_path('logo.png'));
        imagepalettetotruecolor($logo);
        imagecopyresampled($card, $logo, $this->px($x), $this->px($y), 0, 0, $this->px($size), $this->px($size), imagesx($logo), imagesy($logo));
    }

    // Center-crop the member picture to fill the frame.
    private function photo(GdImage $card, float $x, float $y, float $width, float $height): void
    {
        $path = $this->verification->picture;
        $source = $path && Storage::disk('public')->exists($path)
            ? @imagecreatefromstring(Storage::disk('public')->get($path))
            : false;

        if (! $source) {
            $this->rect($card, $x, $y, $width, $height, 'parchment-dim');
            $this->text($card, 'NO PHOTO', 'sans-bold', 4.6, 'ink-soft', $x + $width / 2, $y + $height / 2 + 0.6, 'center', 0.6);

            return;
        }

        $ratio = $width / $height;
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $cropWidth = min($sourceWidth, (int) round($sourceHeight * $ratio));
        $cropHeight = min($sourceHeight, (int) round($sourceWidth / $ratio));

        imagecopyresampled(
            $card,
            $source,
            $this->px($x),
            $this->px($y),
            (int) (($sourceWidth - $cropWidth) / 2),
            (int) (($sourceHeight - $cropHeight) / 2),
            $this->px($x + $width) - $this->px($x),
            $this->px($y + $height) - $this->px($y),
            $cropWidth,
            $cropHeight,
        );
    }

    // Drawn module by module on whole pixels so every square prints sharp.
    private function qrCode(GdImage $card, float $x, float $y, float $size): void
    {
        $matrix = (new QRCode(new QROptions([
            'eccLevel' => EccLevel::M,
            'addQuietzone' => true,
            'quietzoneSize' => 2,
        ])))->addByteSegment($this->verifyUrl)->getQRMatrix();

        $modules = $matrix->getSize();
        $box = $this->px($size);
        $module = intdiv($box, $modules);
        $left = $this->px($x) + intdiv($box - $module * $modules, 2);
        $top = $this->px($y) + intdiv($box - $module * $modules, 2);

        $this->rect($card, $x, $y, $size, $size, 'white');
        $dark = $this->color($card, 'black');

        for ($row = 0; $row < $modules; $row++) {
            for ($column = 0; $column < $modules; $column++) {
                if ($matrix->check($column, $row)) {
                    imagefilledrectangle($card, $left + $column * $module, $top + $row * $module, $left + ($column + 1) * $module - 1, $top + ($row + 1) * $module - 1, $dark);
                }
            }
        }
    }
}
