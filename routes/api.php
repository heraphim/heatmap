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
Route::get('links', function() {
    $links = Link::all();
    $faker = \Faker\Factory::create();
    $types = ['product', 'category', 'static-page', 'checkout', 'homepage'];
    foreach ($types as $type) {
        $params = [];
        if(rand(1, 10) > 2) {
            $params['start_date'] = $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now', $timezone = null)->format('Y-m-d H:i:s');
        }
        if(rand(1, 10) > 2) {
            $initial = !empty($params['start_date']) ? $params['start_date'] : '-30 days';
            $params['end_date'] = $faker->dateTimeBetween($initial, 'now', $timezone = null)->format('Y-m-d H:i:s');
        }
        $urlParams = http_build_query($params);
        echo '<a href="http://localhost/heatmap/public/api/link_types/hits/' . $type . '?'. $urlParams . '">' . $type . ' | ' . (isset($params['start_date']) ? $params['start_date'] : 'none' ) . ' - ' .  (isset($params['end_date']) ? $params['end_date'] : 'none' ) . '</a><br>';
    }
    foreach ($links as $link) {
        $params = [
            'link' => $link->url,
        ];
        if(rand(1, 10) > 2) {
            $params['start_date'] = $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now', $timezone = null)->format('Y-m-d H:i:s');
        }
        if(rand(1, 10) > 2) {
            $initial = !empty($params['start_date']) ? $params['start_date'] : '-30 days';
            $params['end_date'] = $faker->dateTimeBetween($initial, 'now', $timezone = null)->format('Y-m-d H:i:s');
        }
        $urlParams = http_build_query($params);
        echo '<a href="http://localhost/heatmap/public/api/links/hits?' . $urlParams . '">' . $link->type . ' | ' . (isset($params['start_date']) ? $params['start_date'] : 'none' ) . ' - ' .  (isset($params['end_date']) ? $params['end_date'] : 'none' ) . '</a><br>';
    }
});

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
        'identic_journeys' => Arr::pluck($query, 'customer_id')
    ];
});
Route::get('link_types/hits/{type}', [HitsController::class, 'linkTypes'])->name('links_types.hits');
Route::get('links/hits/', [HitsController::class, 'links'])->name('links.hits');
Route::post('visits', [VisitController::class, 'store'])->name('visit.store');