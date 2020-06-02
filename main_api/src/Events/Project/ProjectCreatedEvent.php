<?php
declare(strict_types=1);

namespace App\Events\Project;

use App\Entity\Project;
use App\Entity\Topic;
use Symfony\Contracts\EventDispatcher\Event;

class ProjectCreatedEvent extends Event
{
    private Project $project;
    private Topic $mainTopic;

    public function __construct(Project $project, Topic $mainTopic)
    {
        $this->project = $project;
        $this->mainTopic = $mainTopic;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getMainTopic(): Topic
    {
        return $this->mainTopic;
    }



}