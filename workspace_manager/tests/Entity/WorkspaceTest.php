<?php
namespace App\Tests\Entity;

use App\Entity\Workspace;
use PHPUnit\Framework\TestCase;

class WorkspaceTest extends TestCase
{

    public function testSetRegistrationDate()
    {
        $workspace = new Workspace();
        $workspace->setIP('123.123.123.111');
        $this->assertEquals($workspace->getIP(),'123.123.123.111');
    }



}