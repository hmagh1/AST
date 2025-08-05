<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\AdministrateurUCAC;
use App\Entity\Astreignable;

class UserTest extends TestCase
{
    public function testGetSetEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('test@example.com', $user->getUserIdentifier());
        $this->assertEquals('test@example.com', $user->getUsername()); // deprecated but still tested
    }

    public function testGetSetPassword(): void
    {
        $user = new User();
        $user->setPassword('securepass');
        $this->assertEquals('securepass', $user->getPassword());
    }

    public function testGetSetRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $roles = $user->getRoles();

        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles); // always added
    }

    public function testEraseCredentials(): void
    {
        $user = new User();
        $this->assertNull($user->eraseCredentials()); // just test that it doesn't crash
    }

    public function testGetSalt(): void
    {
        $user = new User();
        $this->assertNull($user->getSalt()); // should return null
    }

    public function testAdminProfileRelation(): void
    {
        $user = new User();
        $admin = new AdministrateurUCAC();
        $user->setAdminProfile($admin);
        $this->assertSame($admin, $user->getAdminProfile());
    }

    public function testAstreignableProfileRelation(): void
    {
        $user = new User();
        $astreignable = new Astreignable();
        $user->setAstreignableProfile($astreignable);
        $this->assertSame($astreignable, $user->getAstreignableProfile());
    }
}
