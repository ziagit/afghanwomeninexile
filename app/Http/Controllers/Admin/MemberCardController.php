<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class MemberCardController extends Controller
{
    // CR80 ID card (ISO/IEC 7810 ID-1) in portrait orientation, in millimetres.
    private const CARD_WIDTH_MM = 54;

    private const CARD_HEIGHT_MM = 85.6;

    // Print-shop version: background bleed past the trim line, plus a margin for crop marks.
    private const PRESS_BLEED_MM = 3;

    private const PRESS_SLUG_MM = 6;

    public function show(Verification $verification): View
    {
        return view('admin.verifications.card', [
            'pageTitle' => 'Member Card',
            'verification' => $verification,
        ]);
    }

    public function pdf(Verification $verification): Response
    {
        $verifyUrl = $this->verifyUrl($verification);

        $press = request()->query('version') === 'press';
        $bleed = $press ? self::PRESS_BLEED_MM : 0;
        $slug = $press ? self::PRESS_SLUG_MM : 0;
        $pageWidth = self::CARD_WIDTH_MM + 2 * ($bleed + $slug);
        $pageHeight = self::CARD_HEIGHT_MM + 2 * ($bleed + $slug);

        $pdf = Pdf::loadView('admin.verifications.card-pdf', [
            'verification' => $verification,
            'verifyUrl' => $verifyUrl,
            'qrCode' => $this->qrCode($verifyUrl),
            'photo' => $this->photo($verification->picture),
            'logo' => 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('logo.png'))),
            'fontDir' => resource_path('fonts'),
            'press' => $press,
            'bleed' => $bleed,
            'slug' => $slug,
            'pageWidth' => $pageWidth,
            'pageHeight' => $pageHeight,
        ])->setPaper([0, 0, $this->points($pageWidth), $this->points($pageHeight)]);

        $filename = 'member-card-'.Str::slug($verification->member_id).($press ? '-print-shop' : '').'.pdf';

        return request()->boolean('inline') ? $pdf->stream($filename) : $pdf->download($filename);
    }

    private function points(float $millimetres): float
    {
        return $millimetres * 72 / 25.4;
    }

    private function verifyUrl(Verification $verification): string
    {
        return route('verify-member', ['member_id' => $verification->member_id]);
    }

    private function qrCode(string $data): string
    {
        return (new QRCode(new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'scale' => 12,
            'quietzoneSize' => 1,
            'outputBase64' => true,
        ])))->render($data);
    }

    // Center-crop the member picture to a 3:4 portrait, since dompdf has no object-fit.
    private function photo(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $source = @imagecreatefromstring(Storage::disk('public')->get($path));

        if (! $source) {
            return null;
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $cropWidth = min($width, (int) round($height * 3 / 4));
        $cropHeight = min($height, (int) round($width * 4 / 3));

        $target = imagecreatetruecolor(600, 800);
        imagefill($target, 0, 0, imagecolorallocate($target, 255, 255, 255));
        imagecopyresampled(
            $target,
            $source,
            0,
            0,
            (int) (($width - $cropWidth) / 2),
            (int) (($height - $cropHeight) / 2),
            600,
            800,
            $cropWidth,
            $cropHeight,
        );

        ob_start();
        imagejpeg($target, null, 92);
        $jpeg = ob_get_clean();

        return 'data:image/jpeg;base64,'.base64_encode($jpeg);
    }
}
