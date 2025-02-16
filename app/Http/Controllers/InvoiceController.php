<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Models\InvoiceRecord;
use Filament\Notifications\Notification;

class InvoiceController extends Controller
{
    public function printInvoice($id) {
        $purchase = Purchase::with(['provider', 'product'])->find($id);
        if($purchase){
            InvoiceRecord::create([
                'user_id' => auth()->user()->id,
                'purchase_id' => $id,
            ]);

            $pdf = \PDF::loadView('pdf.purchase_invoice', compact('purchase'));
            return $pdf->stream();
        } else {
            Notification::make()
                ->title('No purchase record found')
                ->danger()
                ->send();

            return redirect()->back();
        }
    }

}
