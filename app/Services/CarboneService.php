<?php

namespace App\Services;

class CarboneService
{
    public function render($template, $datos, $nombreArchivo)
    {
        $templatePath = resource_path("views/carbone/$template.docx");
        $docxPath = storage_path("app/public/temp/{$nombreArchivo}.docx");
        $pdfPath = storage_path("app/public/pdfs/{$nombreArchivo}.pdf");

        if (!file_exists($templatePath)) {
            throw new \Exception("Template no encontrado: $templatePath");
        }

        // Crear directorios
        foreach ([storage_path('app/public/temp'), storage_path('app/public/pdfs')] as $dir) {
            if (!is_dir($dir)) mkdir($dir, 0755, true);
        }

        // ✅ PASO 1: Carbone genera DOCX
        $dataJson = json_encode($datos);
        $templatePath = escapeshellarg($templatePath);
        $docxPath = escapeshellarg($docxPath);
        $dataJson = escapeshellarg($dataJson);

        $command = "/usr/bin/node " . base_path('carbone-render.js') . " {$templatePath} {$docxPath} {$dataJson} 2>&1";

        $output = array();
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Error Carbone: " . implode("\n", $output));
        }

        // ✅ PASO 2: LibreOffice convierte DOCX → PDF (con directorio temporal)
        $docxPathReal = storage_path("app/public/temp/{$nombreArchivo}.docx");
        $pdfDir = storage_path('app/public/pdfs');

        // Crear directorio de caché para LibreOffice
        $cacheDir = storage_path('app/public/libreoffice-cache');
        if (!is_dir($cacheDir)) mkdir($cacheDir, 0777, true);

        $command = "HOME={$cacheDir} soffice --headless --convert-to pdf " . escapeshellarg($docxPathReal) . " --outdir " . escapeshellarg($pdfDir) . " 2>&1";

        $output = array();
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Error LibreOffice: " . implode("\n", $output));
        }

        // Limpiar archivos temporales
        if (file_exists($docxPathReal)) {
            unlink($docxPathReal);
        }

        // Verificar que el PDF se creó
        if (!file_exists($pdfPath)) {
            throw new \Exception("PDF no se creó.");
        }

        return $pdfPath;
    }
}