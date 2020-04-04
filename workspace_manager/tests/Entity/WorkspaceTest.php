<?php
namespace App\Tests\Entity;

use App\Entity\Workspace;
use PHPUnit\Framework\TestCase;

class WorkspaceTest extends TestCase
{

    public function testSetIP()
    {
        $workspace = new Workspace();
        $workspace->setIP('123.123.123.111');
        $this->assertEquals($workspace->getIP(),'123.123.123.111');
    }
    public function testsetRegistrationDate()
    {
        $workspace = new Workspace();
        $datatime = new \DateTime();
        $workspace->setRegistrationDate($datatime);
        $this->assertEquals($workspace->getRegistrationDate(),$datatime);
    }
    public function testsetAdminPassword()
    {
        $workspace = new Workspace();
        $workspace->setAdminPassword('123.123.123.111');
        $this->assertEquals($workspace->getAdminPassword(),'123.123.123.111');
    }
    public function testsetConfirmationStatus()
    {
        $workspace = new Workspace();
        $workspace->setConfirmationStatus(true);
        $this->assertEquals($workspace->getConfirmationStatus(),true);
    }
    public function testsetFalseConfirmationStatus()
    {
        $workspace = new Workspace();
        $workspace->setConfirmationStatus(false);
        $this->assertEquals($workspace->getConfirmationStatus(),false);
    }
    public function setAdminEmail()
    {
        $workspace = new Workspace();
        $workspace->setAdminEmail('vinnikov@yandex.ru');
        $this->assertEquals($workspace->getAdminEmail(),'vinnik2ov@yandex.ru');
    }
    public function setFalseAdminEmail()
    {
        $workspace = new Workspace();
        $workspace->setAdminEmail('vinnikov@yandex.ru');
        $this->assertNotEquals($workspace->getAdminEmail(),'vinniko2v@yandex.ru');
    }
    public function testsetName()
    {
        $workspace = new Workspace();
        $workspace->setName('alex');
        $this->assertEquals($workspace->getName(),'alex');
    }


}