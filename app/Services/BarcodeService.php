<?php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorSVG;

class BarcodeService
{
    public function generateBarcode(): string
    {
        // Generate unique 13-digit EAN-13 barcode
        $prefix = '200'; // Company prefix
        $random = str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
        $barcode = $prefix . $random;
        
        // Calculate check digit
        $checkDigit = $this->calculateEAN13CheckDigit($barcode);
        $barcode .= $checkDigit;
        
        return $barcode;
    }

    private function calculateEAN13CheckDigit(string $barcode): int
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $barcode[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        $remainder = $sum % 10;
        return $remainder === 0 ? 0 : 10 - $remainder;
    }

    public function generateBarcodeImage(string $barcode, string $type = 'png'): string
    {
        if ($type === 'svg') {
            $generator = new BarcodeGeneratorSVG();
            return $generator->getBarcode($barcode, $generator::TYPE_EAN_13);
        }
        
        $generator = new BarcodeGeneratorPNG();
        return base64_encode($generator->getBarcode($barcode, $generator::TYPE_EAN_13));
    }
}

