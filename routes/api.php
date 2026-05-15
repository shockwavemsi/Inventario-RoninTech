<?php

use App\Http\Controllers\PdfController;

// ============================================
// RUTAS DE GENERACIÓN DE PDFs CON CARBONE
// ============================================

Route::prefix('pdf')->group(function () {

    // Facturas
    Route::get('/factura/{id}', [PdfController::class, 'descargarFactura'])
        ->name('pdf.factura');

    // Pedidos
    Route::get('/pedido/{id}', [PdfController::class, 'descargarPedido'])
        ->name('pdf.pedido');

    // Albaranes
    Route::get('/albarani/{id}', [PdfController::class, 'descargarAlbarani'])
        ->name('pdf.albarani');
});