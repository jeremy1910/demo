<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RolesRepository")
 */
class Roles
{



    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="\App\Entity\User", mappedBy="roles", cascade={"persist"})
     *
     */
    private $users;

    /**
     * @ORM\Column(type="json")
     */
    private $roleName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libele;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function getUsers()
    {
        return $this->users;
    }


    public function setUsers($user)
    {
        $this->users = $user;
        return $this;
    }

    public function addUsers(User $user)
    {
        if (!$this->users->contains($user))
        {
            $this->users[] = $user;
            $user->addRoles($this);

        }
        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleName()
    {
        return $this->roleName;
    }

    public function setRoleName($roleName): self
    {
        $this->roleName = $roleName;

        return $this;
    }

    public function getLibele(): ?string
    {
        return $this->libele;
    }

    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

        return $this;
    }


}
