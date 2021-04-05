<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Link;
use App\Models\Visit;
use App\Http\Controllers\HitsController;
use App\Http\Controllers\VisitController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('journey/{customer_id}', function(Request $request, $customer_id) {
    $visits = Visit::where('customer_id', $customer_id)->orderBy('timestamp', 'ASC')->get(['link_id', 'timestamp']);
    $flatJourney = implode(',', $visits->pluck('link_id')->flatten()->toArray());
    $visitsTable = app(Visit::class)->getTable();
    $statement = "
        SELECT visits.customer_id, GROUP_CONCAT(visits.link_id ORDER BY visits.timestamp) as journey FROM visits
        WHERE visits.customer_id != {$customer_id}
        AND visits.link_id IN ({$flatJourney})
        GROUP BY visits.customer_id
        HAVING journey = '{$flatJourney}'
    ";
    $query = DB::select($statement);
    return [
        'customer_id' => $customer_id,
        'journey' => $visits,
        'identical_journeys' => Arr::pluck($query, 'customer_id')
    ];
});
Route::get('link_types/hits/{type}', [HitsController::class, 'linkTypes'])->name('links_types.hits');
Route::get('links/hits/', [HitsController::class, 'links'])->name('links.hits');
Route::post('visits', [VisitController::class, 'store'])->name('visit.store');