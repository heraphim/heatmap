<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Link;
use App\Models\Visit;
class HitsController extends Controller
{
    public function links(Request $request)
    {
        if(!$request->has('link')) {
            return response()->json([
                'error' => 'Please provide a link'
            ], 404);
        }
        $startDate = $request->has('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->has('end_date') ? Carbon::parse($request->get('end_date')) : null;
        $link = Link::where('url', $request->get('link'))->withCount(['visits' => function($query) use ($startDate, $endDate) {
            if($startDate) {
                $query->where('timestamp', '>' , $startDate->format('Y-m-d H:i:s'));
            }
            if($endDate) {
                $query->where('timestamp', '<' , $endDate->format('Y-m-d H:i:s'));
            }
        }])->first();
        if(!$link) {
            return response()->json([
                'error' => 'Could not find a link with ' . urldecode($request->get('link')) . ' url'
            ], 404);
        }
        return $link;
    }
    public function linkTypes(Request $request, $type)
    {
        $startDate = $request->has('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->has('end_date') ? Carbon::parse($request->get('end_date')) : null;
        $visitNo = Visit::where(function($query) use ($startDate, $endDate) {
            if($startDate) {
                $query->where('timestamp', '>' , $startDate->format('Y-m-d H:i:s'));
            }
            if($endDate) {
                $query->where('timestamp', '<' , $endDate->format('Y-m-d H:i:s'));
            }
        });
        $visitNo = $visitNo->whereHas('link', function($query) use ($type) {
            $query->where('type', $type);
        })->count();
        return ['type' => $type, 'visits_count' => $visitNo];
    }
}