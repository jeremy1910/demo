<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 29/06/19
 * Time: 14:07
 */

namespace App\Entity\User\Filter;


class UserFilter
{
    /**
     * @var null|int
     */
    private $id;

    /**
     * @var string|null
     */
    private $username;

    /**
     * @var int|null
     */
    private $roles;


    /**
     * @var bool
     */
    private $enable;

    /**
     * @var string|null
     */
    private $adresseMail;



    public function __construct() {

    }


    /**
     * @return null|string
     */
    public function getAdresseMail() :?string
    {
        return $this->adresseMail;
    }


    /**
     * @param $adresseMail
     * @return $this
     */
    public function setAdresseMail($adresseMail) :self
    {
        $this->adresseMail = $adresseMail;
        return $this;
    }


    /**
     * @return bool
     */
    public function getEnable(): ?bool
    {
        return $this->enable;
    }


    /**
     * @param $enable
     * @return UserFilter
     */
    public function setEnable($enable): self
    {
        $this->enable = $enable;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return UserFilter
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }


    /**
     * @return int|null
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $roles
     * @return UserFilter
     */
    public function setRoles($roles): self
    {
        $this->roles = $roles;
        return $this;
    }



}