<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class DemoStoreDoc
{
    /**
     * Pastikan file dokument demo tersedia untuk path relatif pada disk public.
     * Seeders hanya menulis baris DB; helper ini menulis file placeholder agar
     * tidak menghasilkan 404 saat dokumen dilihat.
     */
    public static function ensure(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            return;
        }

        Storage::disk('public')->put($path, static::placeholderPdf($path));
    }

    public static function placeholderPdf(string $path): string
    {
        $label = 'Sertifikat - ' . ucwords(str_replace(['-', '_'], ' ', pathinfo($path, PATHINFO_FILENAME)));

        $content = "BT /F1 24 Tf 72 720 Td ({$label}) Tj ET";
        $objects = [];
        $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
        $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>';
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
        $objects[] = '<< /Length ' . strlen($content) . " >>\nstream\n" . $content . "\nendstream";
        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objects as $i => $o) {
            $offsets[] = strlen($pdf);
            $pdf .= ($i + 1) . " 0 obj\n" . $o . "\nendobj\n";
        }
        $xrefStart = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        foreach ($offsets as $off) {
            $pdf .= sprintf("%010d 00000 n \n", $off);
        }
        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n" . $xrefStart . "\n%%EOF";

        return $pdf;
    }
}