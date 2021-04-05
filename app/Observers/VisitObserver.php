<?php

namespace App\Observers;

use App\Models\Visit;
use App\Models\Link;

class VisitObserver
{
    /**
     * Handle the Visit "created" event.
     *
     * @param  \App\Models\Visit  $visit
     * @return void
     */
    public function created(Visit $visit)
    {
        $request = request();
        $url = $request->get('link');
        $link_type = $request->get('link_type');
        $existingLink = Link::where('url', $url)->get();
        if($existingLink->isEmpty()) {
            $existingLink = Link::create(['url' => $url, 'type' => $link_type]);
        } else {
            $existingLink = $existingLink->first();
        }
        $visit->link_id = $existingLink->id;
        $visit->save();
    }

    /**
     * Handle the Visit "updated" event.
     *
     * @param  \App\Models\Visit  $visit
     * @return void
     */
    public function updated(Visit $visit)
    {
        //
    }

    /**
     * Handle the Visit "deleted" event.
     *
     * @param  \App\Models\Visit  $visit
     * @return void
     */
    public function deleted(Visit $visit)
    {
        //
    }

    /**
     * Handle the Visit "restored" event.
     *
     * @param  \App\Models\Visit  $visit
     * @return void
     */
    public function restored(Visit $visit)
    {
        //
    }

    /**
     * Handle the Visit "force deleted" event.
     *
     * @param  \App\Models\Visit  $visit
     * @return void
     */
    public function forceDeleted(Visit $visit)
    {
        //
    }
}
