<?php
declare(strict_types=1);

namespace App\Events;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class AppSecretCheckEvent extends Event
{
    protected ?string $appUserKey;
    protected ?Response $response = null;

    public function __construct(?string $appUserKey)
    {
        $this->appUserKey = $appUserKey;
    }

    public function getAppUserKey()
    {
        return $this->appUserKey;
    }

    public function setResponse(Response $response){
        $this->response = $response;
    }

    public function hasResponse()
    {
        return !is_null($this->response);
    }

    public function getResponse()
    {
        return $this->response;
    }
}