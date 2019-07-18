<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 16/05/19
 * Time: 22:17
 */

namespace App\Service\DataRequest;

use App\Entity\User;

class DataRequestUserService extends DataRequestClassService
{

    protected $permitedOptions = ['id', 'enable', 'username', 'adresseMail', 'nbResult', 'pageSelected', 'roles'];



    public function setFilter(){

        if ($this->option != []) {

            foreach ($this->option as $key => $line) {
                if (!$this->isOptionValide($key)) {
                    dd('option non valide : ' . $key);
                }

                if ($key == 'id') {
                    if (\preg_match('/^[0-9]+$/', $this->option['id'])) {

                        $this->validedOptions['id'] = $this->option['id'];
                    } else {
                        dd('mauvais parametre : ' . $this->option['id']);
                    }
                }

                if ($key == 'enable') {
                    if (\preg_match('/^[0-1]+$/', $this->option['enable']) OR $this->option['enable'] === null) {
                        $this->validedOptions['enable'] = $this->option['enable'];
                    } else {
                        dd('argument enable : ' . $this->option['enable']);
                    }
                }

                if ($key == 'username') {
                    if (\preg_match("/[A-Za-z0-9]+/", $this->option['username'])) {
                        $this->validedOptions['username'] = $this->option['username'];
                    } else {
                        dd('argument username : ' . $this->option['username'] . ' invalide');
                    }
                }

                if ($key == 'adresseMail') {
                    if (\preg_match(" /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ ", $this->option['mail'])) {
                        $this->validedOptions['adresseMail'] = $this->option['mail'];
                    } else {
                        dd('argument mail : ' . $this->option['mail'] . ' invalide');
                    }
                }
                if ($key == 'roles') {
                    if (\preg_match("/[A-Za-z0-9]+/", $this->option['roles'])) {
                        $this->validedOptions['roles'] = $this->option['roles'];
                    } else {
                        dd('argument roles : ' . $this->option['roles'] . ' invalide');
                    }
                }


            }
        }
    }


}