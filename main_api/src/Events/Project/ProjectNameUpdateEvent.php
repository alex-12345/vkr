<?php
declare(strict_types=1);

namespace App\Events\Project;

use App\Entity\Project;
use Symfony\Contracts\EventDispatcher\Event;

class ProjectNameUpdateEvent extends Event
{
    private Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getProject(): Project
    {
        return $this->project;
    }


}