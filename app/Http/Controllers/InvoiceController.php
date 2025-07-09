<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\invoice;
use App\Models\Po_ttd_quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index() {
        $invoice = invoice::with(['document', 'poTtdQuotation', 'user'])->get();


        if(!$invoice) {
            return response()->json([
                'success' => true,
                'message' => 'Documents not found',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Documents fetched successfully',
            'data_invoice' => $invoice
        ], 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'tgl_invoice' => 'required|max:255',
            'ket_invoice' => 'required|max:255',
        ]);
    
        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new invoice',
                'data_invoice' => $validator->errors()
            ], 401); 
        }
    
        $user = Auth::user();
    
        $document = Document::where('user_id', $user->id)->first();
        if (!$document) {
            return response()->json(['message' => 'Document not found for this user'], 404);
        }
    
        // Perbaikan disini: cari PO berdasarkan document_id
        $po = Po_ttd_quotation::where('document_id', $document->id)->first();
        if (!$po) {
            return response()->json(['message' => 'PO TTD not found for this document'], 404);
        }
    
        $validatedData = $validator->validated();
        $validatedData["user_id"] = $user->id;
        $validatedData["document_id"] = $document->id;
        $validatedData["po_id"] = $po->id;
        $validatedData["tgl_invoice"] = now()->format('Y-m-d');
    
        $createdData = invoice::create($validatedData);
    
        return response()->json([
            'success' => true,
            'message' => 'Invoice created successfully',
            'data_invoice' => $createdData
        ], 200);
    }
    
}
