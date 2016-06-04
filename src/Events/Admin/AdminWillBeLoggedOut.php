<?php

namespace ZEDx\Events\Admin;

use ZEDx\Events\Event;
use Illuminate\Queue\SerializesModels;
use ZEDx\Models\Admin;

class AdminWillBeLoggedOut extends Event
{
    use SerializesModels;

    public $actorName;
    public $actorId;
    public $actorRole;
    public $admin;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($actorName, $actorId, $actorRole, Admin $admin)
    {
        $this->actorName = $actorName;
        $this->actorId = $actorId;
        $this->actorRole = $actorRole;
        $this->admin = $admin;
    }
}