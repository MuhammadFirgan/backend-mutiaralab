<?php

namespace App\Http\Controllers;

use App\Models\marketing;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function index($id) {
        $document = marketing::with("document")->where('marketing_id', $id)->get();
        
        @dd($document);
    }

    public function show() {

    }

    public function store() {

    }

    public function update() {

    }

    public function destroy() {

    }
}
