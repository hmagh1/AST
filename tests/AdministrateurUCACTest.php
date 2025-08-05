<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\AdministrateurUCAC;
use App\Entity\User;

class AdministrateurUCACTest extends TestCase
{
    public function testGetSetNom(): void
    {
        $entity = new AdministrateurUCAC();
        $entity->setNom('Admin');
        $this->assertEquals('Admin', $entity->getNom());
    }

    public function testGetSetEmail(): void
    {
        $entity = new AdministrateurUCAC();
        $entity->setEmail('admin@example.com');
        $this->assertEquals('admin@example.com', $entity->getEmail());
    }

    public function testSetUserAlignsEmail(): void
{
    $entity = new AdministrateurUCAC();

    /** @var \PHPUnit\Framework\MockObject\MockObject&\App\Entity\User $user */
    $user = $this->createMock(User::class);
    $user->method('getUserIdentifier')->willReturn('admin@domain.com');

    $entity->setUser($user);

    $this->assertSame($user, $entity->getUser());
    $this->assertEquals('admin@domain.com', $entity->getEmail());
}


    public function testGetIdViaReflection(): void
    {
        $entity = new AdministrateurUCAC();
        $reflection = new \ReflectionClass($entity);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, 99);

        $this->assertEquals(99, $entity->getId());
    }
}
