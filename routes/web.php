<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/print-invoice/{id}',[InvoiceController::class, 'printInvoice'])->name('print.purchase_invoice');
