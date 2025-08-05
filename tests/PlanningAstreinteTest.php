<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\PlanningAstreinte;
use App\Entity\Astreignable;

class PlanningAstreinteTest extends TestCase
{
    public function testGetSetDateDebutEtFin(): void
    {
        $entity = new PlanningAstreinte();
        $dateDebut = new \DateTime('2025-01-01');
        $dateFin = new \DateTime('2025-01-07');

        $entity->setDateDebut($dateDebut);
        $entity->setDateFin($dateFin);

        $this->assertSame($dateDebut, $entity->getDateDebut());
        $this->assertSame($dateFin, $entity->getDateFin());
    }

    public function testGetSetTheme(): void
    {
        $entity = new PlanningAstreinte();
        $entity->setTheme('Urgence');
        $this->assertEquals('Urgence', $entity->getTheme());
    }

    public function testGetSetStatut(): void
    {
        $entity = new PlanningAstreinte();
        $entity->setStatut('validé');
        $this->assertEquals('validé', $entity->getStatut());
    }

    public function testGetSetAstreintNom(): void
    {
        $entity = new PlanningAstreinte();
        $entity->setAstreintNom('Dupont Jean');
        $this->assertEquals('Dupont Jean', $entity->getAstreintNom());
    }

    public function testBinomeRelation(): void
    {
        $entity = new PlanningAstreinte();
        $a1 = new Astreignable();
        $a1->setNom('Dupont')->setPrenom('Jean');

        $a2 = new Astreignable();
        $a2->setNom('Durand')->setPrenom('Claire');

        $entity->addBinome($a1)->addBinome($a2);
        $this->assertCount(2, $entity->getBinome());

        $entity->removeBinome($a1);
        $this->assertCount(1, $entity->getBinome());
        $this->assertTrue($entity->getBinome()->contains($a2));
    }

    public function testGetBinomeList(): void
    {
        $entity = new PlanningAstreinte();
        $a1 = new Astreignable();
        $a1->setNom('Smith')->setPrenom('Alice');

        $a2 = new Astreignable();
        $a2->setNom('Jones')->setPrenom('Bob');

        $entity->addBinome($a1)->addBinome($a2);

        $expected = 'Alice Smith, Bob Jones';
        $this->assertEquals($expected, $entity->getBinomeList());
    }

    public function testGetBinomeNames(): void
    {
        $entity = new PlanningAstreinte();
        $a1 = new Astreignable();
        $a1->setNom('Smith')->setPrenom('Alice');

        $a2 = new Astreignable();
        $a2->setNom('Jones')->setPrenom('Bob');

        $entity->addBinome($a1)->addBinome($a2);

        $expected = 'Alice Smith, Bob Jones';
        $this->assertEquals($expected, $entity->getBinomeNames());
    }
}
