<?php
namespace App\Tests;

use App\Entity\Astreignable;
use App\Entity\PlanningAstreinte;
use App\Entity\ServiceFait;
use App\Entity\MainCourante;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class AstreignableTest extends TestCase
{
    public function testGetSetNom(): void
    {
        $entity = new Astreignable();
        $entity->setNom('John');
        $this->assertEquals('John', $entity->getNom());
    }

    public function testGetSetPrenom(): void
    {
        $entity = new Astreignable();
        $entity->setPrenom('Doe');
        $this->assertEquals('Doe', $entity->getPrenom());
    }

    public function testGetSetEmail(): void
    {
        $entity = new Astreignable();
        $entity->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $entity->getEmail());
    }

    public function testGetSetTelephone(): void
    {
        $entity = new Astreignable();
        $entity->setTelephone('0600000000');
        $this->assertEquals('0600000000', $entity->getTelephone());
    }

    public function testGetSetSeniorite(): void
    {
        $entity = new Astreignable();
        $entity->setSeniorite('Senior');
        $this->assertEquals('Senior', $entity->getSeniorite());
    }

    public function testGetSetDirection(): void
    {
        $entity = new Astreignable();
        $entity->setDirection('Finance');
        $this->assertEquals('Finance', $entity->getDirection());
    }

    public function testGetSetDisponible(): void
    {
        $entity = new Astreignable();
        $entity->setDisponible(true);
        $this->assertTrue($entity->getDisponible());
        $this->assertTrue($entity->isDisponible());
    }

    public function testToString(): void
    {
        $entity = new Astreignable();
        $entity->setNom('Doe')->setPrenom('John');
        $this->assertEquals('John Doe', (string)$entity);
    }

    public function testPlanningRelation(): void
    {
        $entity = new Astreignable();
        $planning = new PlanningAstreinte();

        $entity->addPlanning($planning);
        $this->assertTrue($entity->getPlannings()->contains($planning));

        $entity->removePlanning($planning);
        $this->assertFalse($entity->getPlannings()->contains($planning));
    }

    public function testServiceRelation(): void
    {
        $entity = new Astreignable();
        $service = new ServiceFait();
        $service->setAstreignable($entity);

        $entity->addService($service);
        $this->assertTrue($entity->getServices()->contains($service));

        $entity->removeService($service);
        $this->assertFalse($entity->getServices()->contains($service));
    }

    public function testMainCouranteRelation(): void
    {
        $entity = new Astreignable();
        $mainCourante = new MainCourante();
        $mainCourante->setAstreignable($entity);

        $entity->addMainCourante($mainCourante);
        $this->assertTrue($entity->getMainCourantes()->contains($mainCourante));

        $entity->removeMainCourante($mainCourante);
        $this->assertFalse($entity->getMainCourantes()->contains($mainCourante));
    }

    public function testUserRelation(): void
{
    $entity = new Astreignable();

    /** @var User&\PHPUnit\Framework\MockObject\MockObject $user */
    $user = $this->getMockBuilder(User::class)
                 ->disableOriginalConstructor()
                 ->onlyMethods(['getAstreignableProfile', 'setAstreignableProfile'])
                 ->getMock();

    $user->expects($this->once())
         ->method('getAstreignableProfile')
         ->willReturn(null);

    // @phpstan-ignore-next-line
    $user->expects($this->once())
         ->method('setAstreignableProfile')
         ->with($this->callback(fn($arg) => $arg === $entity));

    $entity->setUser($user);

    $this->assertSame($user, $entity->getUser());
}


    public function testPlainPassword(): void
    {
        $entity = new Astreignable();
        $entity->setPlainPassword('secret123');
        $this->assertEquals('secret123', $entity->getPlainPassword());
    }

    public function testGetIdViaReflection(): void
    {
        $entity = new Astreignable();
        $reflection = new \ReflectionClass($entity);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, 101);

        $this->assertEquals(101, $entity->getId());
    }
}
