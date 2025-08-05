<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\MainCourante;
use App\Entity\Astreignable;

class MainCouranteTest extends TestCase
{
    public function testSetGetDetails(): void
    {
        $entity = new MainCourante();
        $entity->setDetails('Test content');
        $this->assertEquals('Test content', $entity->getDetails());
    }

    public function testSetGetDate(): void
    {
        $entity = new MainCourante();
        $date = new \DateTime('2025-08-05');
        $entity->setDate($date);
        $this->assertSame($date, $entity->getDate());
    }

   public function testSetGetAstreignable(): void
{
    $entity = new MainCourante();
    $astreignable = new \App\Entity\Astreignable();

    $entity->setAstreignable($astreignable);
    $this->assertSame($astreignable, $entity->getAstreignable());
}


    public function testGetIdViaReflection(): void
    {
        $entity = new MainCourante();
        $ref = new \ReflectionClass($entity);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($entity, 10);
        $this->assertEquals(10, $entity->getId());
    }
}
