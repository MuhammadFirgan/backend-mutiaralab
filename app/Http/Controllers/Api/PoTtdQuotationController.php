<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Po_Ttd_Quotation;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PoTtdQuotationController extends Controller
{
    public function index() {
        // $data = Po_Ttd_Quotation::with(['document', 'quotation'])->get();
        $data = Quotation::with(['marketing', 'penyedia_sampling'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Documents fetched successfully',
            'data_po' => $data
        ], 200);
    }

    public function store(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'tgl_ttd' => 'required|max:255',
            'ket_ttd' => 'required|max:255',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data' => $validator->errors()
            ], 401); 
        }
            $user = Auth::user();

        $document = Document::where('id', $id)->first();
        if (!$document) {
            return response()->json(['message' => 'document not found for this user'], 404);
        }

        $quotation = quotation::where('marketing_id', $document->id)->first();
        if (!$quotation) {
            return response()->json(['message' => 'document not found for this user'], 404);
        }

        $validatedData = $validator->validated();

        $validatedData["user_id"] = $request->user()->id;
        $validatedData["document_id"] = $document->id;
        $validatedData["quotation_id"] = $quotation->id;

        $createdData = Po_ttd_quotation::create($validatedData);

        // $invoice = invoice::create([
        //     'user_id' => $user->id,
        //     'document_id' => $document->id,
        //     'po_id' => $createdData->id,
        //     'tgl_invoice' => now()->format('Y-m-d'),
        //     'ket_invoice' => 'Invoice otomatis dari PO TTD',
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Success to add new document',
            'data_po' => $createdData
        ], 200);

    }

    public function update(Request $request, $id) {
        $pottd = Po_ttd_quotation::find($id);
        if (!$pottd) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tgl_ttd' => 'required|max:255',
            'ket_ttd' => 'required|max:255',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data' => $validator->errors()
            ], 401); 
        }

        $validatedData = $validator->validated();

        $pottd->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'data_quotation' => $pottd
        ], 200);


    }

    public function destroy($id) {
        $pottd = Po_ttd_quotation::find($id);

        if (!$pottd) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        $pottd->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully'
        ], 200);
    }
}
