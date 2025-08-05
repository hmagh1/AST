<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\ServiceFait;
use App\Entity\Astreignable;

class ServiceFaitTest extends TestCase
{
    public function testGetSetDate(): void
    {
        $entity = new ServiceFait();
        $date = new \DateTime('2025-08-05');
        $entity->setDate($date);
        $this->assertSame($date, $entity->getDate());
    }

    public function testGetSetHeuresEffectuees(): void
    {
        $entity = new ServiceFait();
        $entity->setHeuresEffectuees(8);
        $this->assertEquals(8, $entity->getHeuresEffectuees());
    }

    public function testGetSetValide(): void
    {
        $entity = new ServiceFait();
        $entity->setValide(true);
        $this->assertTrue($entity->getValide());
        $this->assertTrue($entity->isValide());
    }

    public function testGetSetAstreignable(): void
    {
        $entity = new ServiceFait();
        $astreignable = new Astreignable();
        $entity->setAstreignable($astreignable);
        $this->assertSame($astreignable, $entity->getAstreignable());
    }
}
