<?php

namespace ZEDx\Events\Ad;

use ZEDx\Models\Ad;
use ZEDx\Events\Event;
use Illuminate\Queue\SerializesModels;

class AdWasCreated extends Event
{
    use SerializesModels;

    public $ad;
    public $actor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ad $ad, $actor)
    {
        $this->ad = $ad;
        $this->actor = $actor;
    }
}