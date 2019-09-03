<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 03/09/19
 * Time: 21:51
 */

namespace App\Entity\Security;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */

class ForgottenPassword
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $hash;


    /**
     * @ORM\OneToOne(targetEntity="\App\Entity\User", inversedBy="forgottenPassword")
     */
    private $user;

    /**
     * @Assert\Email(
     *     message = "l'adresse mail : '{{ value }}' n'est pas valide",
     *     checkMX = true
     *     )
     * @Assert\NotBlank(message="Entrez une adresse mail")
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ForgottenPassword
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     * @return ForgottenPassword
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return ForgottenPassword
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * @return ForgottenPassword
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }





    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return ForgottenPassword
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }


}