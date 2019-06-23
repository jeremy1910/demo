<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 21/06/19
 * Time: 18:54
 */

namespace App\Service\session\flashMessage;




use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class flashMessage
{

    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function getFlashMessage($type, $message)
    {
        $this->flashBag->add($type, $message);
        return $this->flashBag->all();
    }

}