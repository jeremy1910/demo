<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 01/05/19
 * Time: 23:26
 */

namespace App\DataFixtures;


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
        $useradmin->setPassword($this->encodePasswordFixture($useradmin, 'admin'));
        $useradmin->setRoles(["ROLE_ADMIN"]);
        $useradmin->setEnable(true);
        $useradmin->setAdresseMail('jeremy1910@gmail.com');

        $manager->persist($useradmin);
        $manager->flush();
    }

    private function encodePasswordFixture(User $user, string $password)
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }
}