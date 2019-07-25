<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=254, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min="8", minMessage="Le nom d'utilisateur doit comporter au moins 8 caractères.", max="254", maxMessage="Le nom d'utilsateur doit comporter moins de 254 caractères")
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
     * @Assert\NotBlank(message="New password can not be blank.")
     * @Assert\Regex(pattern="/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,50}$/", message="New password is required to be minimum 6 chars in length and to include at least one letter and one number.")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="L'adresse mail n'est pas valide.")
     */
    private $adresseMail;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="user")
     * @Serializer\Exclude()
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;


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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }





}
