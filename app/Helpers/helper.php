<?php

use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

function generateQrcode($url)
{
    // إنشاء QR Code
    $qrCode = QrCode::size(300)->generate($url);

    $fileName = 'qrcodes/' . uniqid() . '.svg';
    Storage::put("public/{$fileName}", $qrCode);
    return $fileName;
}
