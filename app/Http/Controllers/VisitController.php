<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $newVisit = Visit::create([
            'customer_id' => $request->get('customer_id'),
            'timestamp' => $request->get('timestamp')
        ]);
        return $newVisit;
    }
}
