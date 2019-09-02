<?php

namespace App\Entity;

use App\Entity\History\HistorySearchArticle;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username", message="Ce nom d'utilisateur existe déjà, merci d'en choisir un autre.")
 * @ORM\HasLifecycleCallbacks()
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
     * @Assert\NotBlank(message="Veuillez entrer un nom d'utilisateur")
     * @Assert\Length(min="6", minMessage="Le nom d'utilisateur doit comporter au moins 6 caractères.", max="254", maxMessage="Le nom d'utilsateur doit comporter moins de 254 caractères")
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
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Assert\NotNull(message="Vous devez selectionner un choix.")
     */
    private $enable;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="L'adresse mail n'est pas valide.")
     * @Assert\NotBlank(message="Veuillez entrer une adresse mail")
     */
    private $adresseMail;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="user")
     * @Serializer\Exclude()
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez entrer un nom")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez entrer un prénom")
     */
    private $firstName;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified_at;


    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $created_user;




    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $modified_user;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\History\HistorySearchArticle", mappedBy="by_user")
     */
    private $searched_articles;



    public function __construct() {
        $this->articles = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->searched_articles = new ArrayCollection();
    }


    public function getSearchedArticles()
    {
        return $this->searched_articles;
    }


    public function setSearchedArticles($searched_articles)
    {
        $this->searched_articles = $searched_articles;
        return $this;
    }



    public function addSearchedArticles(HistorySearchArticle $searched_articles)
    {
        if (!$this->searched_articles->contains($searched_articles))
        {
            $this->searched_articles->add($searched_articles);
            $searched_articles->setByUser($this);

        }
        return $this;
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

    /**
     * @param $username string|null
     * @return User
     */
    public function setUsername($username): self
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
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * @param mixed $modified_at
     */
    public function setModifiedAt($modified_at): void
    {
        $this->modified_at = $modified_at;
    }


    /**
     * @return mixed
     */
    public function getCreatedUser()
    {
        return $this->created_user;
    }

    /**
     * @param mixed $created_user
     * @return User
     */
    public function setCreatedUser($created_user)
    {
        $this->created_user = $created_user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModifiedUser()
    {
        return $this->modified_user;
    }

    /**
     * @param mixed $modified_user
     * @return User
     */
    public function setModifiedUser($modified_user)
    {
        $this->modified_user = $modified_user;
        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword($password): self
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

    public function setLastName( $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName( $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }



    /**
     * @Assert\Callback()
     */
    public function ValidePassword(ExecutionContextInterface $context, $payload){

        if(is_null($this->id)){
            if(!preg_match("/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,50}$/", $this->password)){

                $context->buildViolation("New password is required to be minimum 6 chars in length and to include at least one letter and one number.")
                    ->atPath('password')
                    ->addViolation();


            }
        }
    }


}
