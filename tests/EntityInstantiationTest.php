<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\AdministrateurUCAC;
use App\Entity\Astreignable;
use App\Entity\DRH;
use App\Entity\MainCourante;
use App\Entity\PlanningAstreinte;
use App\Entity\ServiceFait;
use App\Entity\User;

class EntityInstantiationTest extends TestCase
{
    public function testAdministrateurUCACInstance(): void
    {
        $entity = new AdministrateurUCAC();
        $this->assertInstanceOf(AdministrateurUCAC::class, $entity);
    }

    public function testAstreignableInstance(): void
    {
        $entity = new Astreignable();
        $this->assertInstanceOf(Astreignable::class, $entity);
    }

    public function testDRHInstance(): void
    {
        $entity = new DRH();
        $this->assertInstanceOf(DRH::class, $entity);
    }

    public function testMainCouranteInstance(): void
    {
        $entity = new MainCourante();
        $this->assertInstanceOf(MainCourante::class, $entity);
    }

    public function testPlanningAstreinteInstance(): void
    {
        $entity = new PlanningAstreinte();
        $this->assertInstanceOf(PlanningAstreinte::class, $entity);
    }

    public function testServiceFaitInstance(): void
    {
        $entity = new ServiceFait();
        $this->assertInstanceOf(ServiceFait::class, $entity);
    }

    public function testUserInstance(): void
    {
        $entity = new User();
        $this->assertInstanceOf(User::class, $entity);
    }
}
