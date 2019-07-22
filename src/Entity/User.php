<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\ManyToMany(targetEntity="\App\Entity\Roles", inversedBy="users", cascade={"persist"})
     *
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Serializer\Exclude()
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseMail;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="user")
     * @Serializer\Exclude()
     */
    private $articles;


    public function __construct() {
        $this->articles = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles()
    {
        return $this->articles;
    }


    public function setArticles($articles): void
    {
        $this->articles = $articles;
    }

    public function addArticles(Article $article)
    {
        if (!$this->articles->contains($article))
        {
            $this->articles->add($article);
            $article->setUser($this);

        }
        return $this;
    }




    public function getAdresseMail()
    {
        return $this->adresseMail;
    }



    public function setAdresseMail($adresseMail)
    {
        $this->adresseMail = $adresseMail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param mixed $enable
     * @return User
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }


    public function getRoles()
    {

        $roles = $this->roles->toArray();
        foreach ($roles as $key => $role)
        {
            /**
             * @var $role Roles
             */
            $roles[$key] = $role->getRoleName();
        }
        $roles[] = "ROLE_USER";

        return $roles;

    }

    public function setRoles($roles): self
    {
        $rolesArray = new ArrayCollection();
        $rolesArray->add($roles);
        $this->roles = $rolesArray;

        return $this;
    }



    public function addRoles(Roles $role)
    {
        if (!$this->roles->contains($role))
        {
            $this->roles[] = $role;
            $role->addUsers($this);

        }
        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }





}
