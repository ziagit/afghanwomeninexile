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
 * All positions are in millimetres from the card's top-left corner; text keeps at least 1.5 mm from the edge.
 */
class MemberCard
{
    public const DPI = 600;

    private const WIDTH_MM = 54;

    private const HEIGHT_MM = 85.6;

    private const CENTER = self::WIDTH_MM / 2;

    private const ORGANIZATION = ['The movement of Afghanistan', 'women in exile'];

    private const EMAIL = 'info@afghanwomeninexile.org';

    private const WEBSITE = 'afghanwomeninexile.org';

    private const PHONE = '+923290933010';

    private const FOUNDER = 'Farzana Rezaei';

    private const COLORS = [
        'purple' => '8f1eae',
        'plum-dark' => '3b1f3a',
        'ink' => '3a2436',
        'ink-soft' => '7d5f74',
        'brick' => 'c65a72',
        'shade' => 'ebebeb',
        'ring' => 'd4d4d4',
        'photo-empty' => 'eeeeee',
        'muted' => '8a8a8a',
        'white' => 'ffffff',
        'black' => '000000',
    ];

    // One typeface throughout: the website's Work Sans, SemiBold for headings and emphasis.
    private const FONTS = [
        'sans' => 'WorkSans-Regular.ttf',
        'sans-bold' => 'WorkSans-SemiBold.ttf',
    ];

    private const CERTIFICATE = [
        'This card represents membership in the Movement of Afghan Women in Exile.',
        'The bearer is committed to the principles of freedom, equality, and social justice for Afghan women.',
        'This membership symbolizes solidarity and cooperation toward the empowerment of women in exile.',
    ];

    public function __construct(
        private Verification $verification,
        private string $qrUrl,
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
        $card = $this->canvas();
        $member = $this->verification;

        $this->header($card, shadeTop: 16.7, centerY: -15.8, radiusX: 48.6, radiusY: 39);

        // Round photo in a thin ring of the header and footer purple, clear of the name above it.
        $this->disc($card, self::CENTER, 21.6, 10.85, 'purple');
        $this->photo($card, self::CENTER, 21.6, 10.6);

        $name = trim($member->first_name.' '.$member->last_name);
        $this->text($card, $name, 'sans-bold', $this->fitSize($name, 'sans-bold', 12, 48), 'plum-dark', self::CENTER, 39.6, 'center');

        $this->roundedRect($card, 20.6, 42.6, 12.8, 3.5, 0.7, 'purple');
        $this->text($card, 'Member', 'sans-bold', 7.4, 'white', self::CENTER, 45.15, 'center');

        $details = [
            'ID No' => $member->member_id,
            'Email' => self::EMAIL,
            'Web' => self::WEBSITE,
            'Phone' => self::PHONE,
        ];
        // Quiet labels, and one size for every value, small enough for the longest to fit; the member ID stands out.
        $valueSize = min(array_map(fn ($value) => $this->fitSize((string) $value, 'sans', 8, 36), $details));
        $baseline = 52;
        foreach ($details as $label => $value) {
            $isId = $label === 'ID No';
            $this->text($card, $label, 'sans-bold', 7, 'ink-soft', 4.2, $baseline);
            $this->text($card, ':', 'sans-bold', 7, 'ink-soft', 13.4, $baseline);
            $this->text($card, (string) $value, $isId ? 'sans-bold' : 'sans', $valueSize, $isId ? 'brick' : 'ink', 14.8, $baseline);
            $baseline += 4.1;
        }

        // Footer band with a raised tab holding the QR code.
        $this->rect($card, 0, 72.8, self::WIDTH_MM, self::HEIGHT_MM - 72.8, 'purple');
        $this->tab($card, 33.6, 52, 69.7, 72.8, 1.6, 'purple');
        $this->roundedRect($card, 37.1, 71.6, 11.6, 11.6, 1.2, 'white');
        $this->qrCode($card, 37.7, 72.2, 10.4);

        $this->badge($card, 'logo-badge.png', self::CENTER, 78.6, 3.8);

        return $this->png($card);
    }

    public function back(): string
    {
        $card = $this->canvas();
        $member = $this->verification;

        $this->header($card, shadeTop: 12.9, centerY: -14.7, radiusX: 44.8, radiusY: 34.4);
        $this->badge($card, 'logo-badge.png', self::CENTER, 15.3, 3.6);

        $title = 'MEMBERSHIP CERTIFICATE';
        $this->text($card, $title, 'sans-bold', $this->fitSize($title, 'sans-bold', 9.4, 44), 'plum-dark', self::CENTER, 24.4, 'center');

        $baseline = 28.9;
        foreach (self::CERTIFICATE as $sentence) {
            $baseline = $this->paragraph($card, $sentence, 'sans', 5.2, 'ink', 5, 44, $baseline, 2.1) + 0.5;
        }

        $baseline += 2.2;
        $this->labelled($card, 'Joined:', $member->joined_at?->format('d/m/Y') ?? '-', 6.8, $baseline);
        $this->labelled($card, 'Expires:', $member->expirs_at?->format('d/m/Y') ?? 'No expiry', 6.8, $baseline + 3.4);

        $this->text($card, 'Sincerely,', 'sans', 6.2, 'ink', self::CENTER, 59.2, 'center');
        $this->image($card, 'signature.png', self::CENTER - 2.9, 60.1, 5.8, 5.8);
        $this->text($card, self::FOUNDER, 'sans', 4.6, 'ink', self::CENTER, 67.8, 'center');
        $this->text($card, 'Founder & President', 'sans-bold', 4.6, 'ink', self::CENTER, 69.8, 'center');
        $this->image($card, 'seal.png', self::CENTER - 2.5, 70.8, 5, 5);

        // Footer band with a raised tab holding the website's social icons, centred in the tab.
        $this->rect($card, 0, 81.6, self::WIDTH_MM, self::HEIGHT_MM - 81.6, 'purple');
        $this->tab($card, 15.35, 38.65, 77.1, 81.6, 1.6, 'purple');
        $this->image($card, 'social-icons.png', self::CENTER - 6.56, 78.1, 13.12, 2.5);
        $this->text($card, self::WEBSITE, 'sans-bold', 5.2, 'white', self::CENTER, 84.2, 'center');

        return $this->png($card);
    }

    // Purple arc across the top with the organisation's name, over a soft grey shadow.
    private function header(GdImage $card, float $shadeTop, float $centerY, float $radiusX, float $radiusY): void
    {
        $this->gradient($card, $shadeTop, $centerY + $radiusY + 4, 'shade', 'white');
        imagefilledellipse($card, $this->px(self::CENTER), $this->px($centerY), $this->px($radiusX * 2), $this->px($radiusY * 2), $this->color($card, 'purple'));

        $size = min(array_map(fn ($line) => $this->fitSize($line, 'sans-bold', 9, 48), self::ORGANIZATION));
        $this->text($card, self::ORGANIZATION[0], 'sans-bold', $size, 'white', self::CENTER, 5, 'center');
        $this->text($card, self::ORGANIZATION[1], 'sans-bold', $size, 'white', self::CENTER, 8.3, 'center');
    }

    private function canvas(): GdImage
    {
        $card = imagecreatetruecolor($this->px(self::WIDTH_MM), $this->px(self::HEIGHT_MM));
        imagealphablending($card, true);
        imagefilledrectangle($card, 0, 0, imagesx($card) - 1, imagesy($card) - 1, $this->color($card, 'white'));

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

    private function rgb(string $name): array
    {
        return sscanf(self::COLORS[$name], '%02x%02x%02x');
    }

    private function color(GdImage $image, string $name, float $opacity = 1): int
    {
        [$r, $g, $b] = $this->rgb($name);

        return imagecolorallocatealpha($image, $r, $g, $b, (int) round(127 * (1 - $opacity)));
    }

    private function rect(GdImage $card, float $x, float $y, float $width, float $height, string $color): void
    {
        imagefilledrectangle($card, $this->px($x), $this->px($y), $this->px($x + $width) - 1, $this->px($y + $height) - 1, $this->color($card, $color));
    }

    // Vertical fade from one colour to another, one pixel row at a time.
    private function gradient(GdImage $card, float $top, float $bottom, string $from, string $to): void
    {
        [$top, $bottom] = [$this->px($top), $this->px($bottom)];
        $start = $this->rgb($from);
        $end = $this->rgb($to);

        for ($y = $top; $y < $bottom; $y++) {
            $t = ($y - $top) / max(1, $bottom - $top);
            [$r, $g, $b] = array_map(fn ($a, $z) => (int) round($a + ($z - $a) * $t), $start, $end);
            imageline($card, 0, $y, imagesx($card) - 1, $y, imagecolorallocate($card, $r, $g, $b));
        }
    }

    // Filled circle with a smooth (anti-aliased) edge.
    private function disc(GdImage $card, float $cx, float $cy, float $radius, string $color): void
    {
        $this->eachCirclePixel($cx, $cy, $radius, function (int $x, int $y, float $coverage) use ($card, $color) {
            imagesetpixel($card, $x, $y, $this->color($card, $color, $coverage));
        });
    }

    // Calls $plot for every pixel the circle touches, with how much of that pixel it covers (0-1).
    private function eachCirclePixel(float $cx, float $cy, float $radius, callable $plot): void
    {
        $centerX = $cx / 25.4 * self::DPI;
        $centerY = $cy / 25.4 * self::DPI;
        $r = $radius / 25.4 * self::DPI;

        for ($y = (int) floor($centerY - $r); $y <= (int) ceil($centerY + $r); $y++) {
            for ($x = (int) floor($centerX - $r); $x <= (int) ceil($centerX + $r); $x++) {
                $coverage = min(1, max(0, $r - hypot($x + 0.5 - $centerX, $y + 0.5 - $centerY) + 0.5));
                if ($coverage > 0) {
                    $plot($x, $y, $coverage);
                }
            }
        }
    }

    private function roundedRect(GdImage $card, float $x, float $y, float $width, float $height, float $radius, string $color): void
    {
        $this->rect($card, $x + $radius, $y, $width - 2 * $radius, $height, $color);
        $this->rect($card, $x, $y + $radius, $width, $height - 2 * $radius, $color);

        foreach ([[$x + $radius, $y + $radius], [$x + $width - $radius, $y + $radius], [$x + $radius, $y + $height - $radius], [$x + $width - $radius, $y + $height - $radius]] as [$cx, $cy]) {
            $this->disc($card, $cx, $cy, $radius, $color);
        }
    }

    // A tab rising from a footer band: slanted sides from $left/$right at $base, and top corners
    // rounded by an arc that meets both the side and the top edge smoothly.
    private function tab(GdImage $card, float $left, float $right, float $top, float $base, float $radius, string $color): void
    {
        $height = $base - $top;
        $inset = $height * 0.68;
        $length = hypot($inset, $height);
        [$normalX, $normalY] = [$height / $length, $inset / $length]; // left side's normal, pointing into the tab

        // Centre of the circle touching the left side and the top edge.
        $cx = $left + ($radius * $length + $inset * ($height - $radius)) / $height;
        $cy = $top + $radius;
        $from = atan2(-$normalY, -$normalX);
        $to = -M_PI / 2;

        $corner = [];
        for ($step = 0; $step <= 24; $step++) {
            $angle = $from + ($to - $from) * $step / 24;
            $corner[] = [$cx + $radius * cos($angle), $cy + $radius * sin($angle)];
        }
        $mirrored = array_map(fn ($point) => [$left + $right - $point[0], $point[1]], array_reverse($corner));

        $points = [[$left, $base], ...$corner, ...$mirrored, [$right, $base]];
        imagefilledpolygon($card, array_merge(...array_map(fn ($point) => array_map($this->px(...), $point), $points)), $this->color($card, $color));
    }

    // GD measures font sizes at 96 DPI, so scale points to the card's resolution.
    private function gdSize(float $points): float
    {
        return $points * self::DPI / 96;
    }

    private function font(string $font): string
    {
        return resource_path('fonts/'.self::FONTS[$font]);
    }

    private function width(string $text, string $font, float $points): float
    {
        if ($text === '') {
            return 0;
        }

        $box = imagettfbbox($this->gdSize($points), 0, $this->font($font), $text);

        return $box[2] - $box[0];
    }

    private function text(GdImage $card, string $text, string $font, float $points, string $color, float $x, float $baseline, string $align = 'left'): void
    {
        $left = $this->px($x) - ($align === 'center' ? $this->width($text, $font, $points) / 2 : 0);

        imagettftext($card, $this->gdSize($points), 0, (int) round($left), $this->px($baseline), $this->color($card, $color), $this->font($font), $text);
    }

    // Largest size up to $points (in 0.2pt steps) at which the text fits $maxWidth mm.
    private function fitSize(string $text, string $font, float $points, float $maxWidth): float
    {
        while ($points > 4 && $this->width($text, $font, $points) > $this->px($maxWidth)) {
            $points -= 0.2;
        }

        return $points;
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

    // A soft label and its value in brick, centred together on one line.
    private function labelled(GdImage $card, string $label, string $value, float $points, float $baseline): void
    {
        $gap = $this->px(1);
        $labelWidth = $this->width($label, 'sans', $points);
        $left = ($this->px(self::WIDTH_MM) - ($labelWidth + $gap + $this->width($value, 'sans-bold', $points))) / 2;

        $this->text($card, $label, 'sans', $points, 'ink-soft', $left / self::DPI * 25.4, $baseline);
        $this->text($card, $value, 'sans-bold', $points, 'brick', ($left + $labelWidth + $gap) / self::DPI * 25.4, $baseline);
    }

    // Wrapped, left-aligned paragraph; returns the baseline for the next paragraph.
    private function paragraph(GdImage $card, string $text, string $font, float $points, string $color, float $x, float $width, float $baseline, float $lineHeight): float
    {
        foreach ($this->wrap($text, $font, $points, $width) as $line) {
            $this->text($card, $line, $font, $points, $color, $x, $baseline);
            $baseline += $lineHeight;
        }

        return $baseline;
    }

    private function image(GdImage $card, string $file, float $x, float $y, float $width, float $height): void
    {
        $image = imagecreatefrompng(resource_path('images/card/'.$file));
        imagecopyresampled($card, $image, $this->px($x), $this->px($y), 0, 0, $this->px($x + $width) - $this->px($x), $this->px($y + $height) - $this->px($y), imagesx($image), imagesy($image));
    }

    // Round white badge with a light grey ring, the image cropped to the circle.
    private function badge(GdImage $card, string $file, float $cx, float $cy, float $radius): void
    {
        $this->disc($card, $cx, $cy, $radius, 'ring');
        $this->disc($card, $cx, $cy, $radius - 0.12, 'white');
        $this->circleImage($card, imagecreatefrompng(resource_path('images/card/'.$file)), $cx, $cy, $radius - 0.12);
    }

    // Crops $source to a square and draws it inside a circle with a smooth edge, so any photo comes out round.
    // Tall photos are cropped nearer the top, where the face usually is.
    private function circleImage(GdImage $card, GdImage $source, float $cx, float $cy, float $radius): void
    {
        $diameter = (int) ceil($radius * 2 / 25.4 * self::DPI) + 2;
        $side = min(imagesx($source), imagesy($source));
        $square = imagecreatetruecolor($diameter, $diameter);
        imagecopyresampled($square, $source, 0, 0, (int) ((imagesx($source) - $side) / 2), (int) ((imagesy($source) - $side) * 0.3), $diameter, $diameter, $side, $side);

        $left = (int) floor(($cx - $radius) / 25.4 * self::DPI);
        $top = (int) floor(($cy - $radius) / 25.4 * self::DPI);

        $this->eachCirclePixel($cx, $cy, $radius, function (int $x, int $y, float $coverage) use ($card, $square, $left, $top, $diameter) {
            $pixel = imagecolorat($square, min($diameter - 1, max(0, $x - $left)), min($diameter - 1, max(0, $y - $top)));
            imagesetpixel($card, $x, $y, imagecolorallocatealpha($card, ($pixel >> 16) & 0xFF, ($pixel >> 8) & 0xFF, $pixel & 0xFF, (int) round(127 * (1 - $coverage))));
        });
    }

    private function photo(GdImage $card, float $cx, float $cy, float $radius): void
    {
        $path = $this->verification->picture;
        $source = $path && Storage::disk('public')->exists($path)
            ? @imagecreatefromstring(Storage::disk('public')->get($path))
            : false;

        if (! $source) {
            $this->disc($card, $cx, $cy, $radius, 'photo-empty');
            $this->text($card, 'NO PHOTO', 'sans-bold', 5, 'muted', $cx, $cy + 0.7, 'center');

            return;
        }

        $this->circleImage($card, $source, $cx, $cy, $radius);
    }

    // Drawn module by module on whole pixels so every square prints sharp.
    private function qrCode(GdImage $card, float $x, float $y, float $size): void
    {
        $matrix = (new QRCode(new QROptions([
            'eccLevel' => EccLevel::M,
            'addQuietzone' => true,
            'quietzoneSize' => 1,
        ])))->addByteSegment($this->qrUrl)->getQRMatrix();

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
