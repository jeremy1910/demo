<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 01/05/19
 * Time: 23:26
 */

namespace App\DataFixtures;


use App\Entity\Roles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $useradmin = new User();

        $useradmin->setUsername('admin');
        echo '1';
        $useradmin->setPassword($this->encodePasswordFixture($useradmin, 'admin'));
        echo '2';
        $role = new Roles();
        echo '3';
        $role->setRoleName('ROLE_ADMIN');
        $role->setLibele('Role Administrateur');
        echo '4';
        $useradmin->addRoles($role);
        echo '5';
        $useradmin->setEnable(true);
        echo '6';
        $useradmin->setLastName("Administrateur");
        $useradmin->setFirstName('');
        $useradmin->setAdresseMail('jeremy1910@gmail.com');
        echo '7';
        $manager->persist($useradmin);
        echo '8';

        $manager->flush();
        echo '9';
    }

    private function encodePasswordFixture(User $user, string $password)
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }
}