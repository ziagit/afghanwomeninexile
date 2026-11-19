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
    // CR80 ID card in portrait orientation, in points (1mm = 72 / 25.4pt).
    private const CARD_WIDTH_PT = 153.07;

    private const CARD_HEIGHT_PT = 242.65;

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

        $pdf = Pdf::loadView('admin.verifications.card-pdf', [
            'verification' => $verification,
            'verifyUrl' => $verifyUrl,
            'qrCode' => $this->qrCode($verifyUrl),
            'photo' => $this->photo($verification->picture),
            'logo' => 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('logo.png'))),
            'fontDir' => resource_path('fonts'),
        ])->setPaper([0, 0, self::CARD_WIDTH_PT, self::CARD_HEIGHT_PT]);

        $filename = 'member-card-'.Str::slug($verification->member_id).'.pdf';

        return request()->boolean('inline') ? $pdf->stream($filename) : $pdf->download($filename);
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
