<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\DRH;

class DRHTest extends TestCase
{
    public function testGetSetNom(): void
    {
        $entity = new DRH();
        $entity->setNom('HR');
        $this->assertEquals('HR', $entity->getNom());
    }

    public function testGetIdViaReflection(): void
    {
        $entity = new DRH();
        $ref = new \ReflectionClass($entity);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($entity, 123);
        $this->assertEquals(123, $entity->getId());
    }
}
