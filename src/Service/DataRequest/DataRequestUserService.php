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

    protected $permitedOptions = ['id', 'enable', 'username', 'mail', 'created_before', 'created_after'];



    public function setFilter(){



        /* Si ?target = user*/
        if ($this->target == 'user') {

            $repository = $this->em->getRepository(User::class);
            /* Si il n'y a pas d'option */
            if ($this->option == []) {
                dd($repository->findAll());
            }
            /* On vérifie que les option son valide */
            foreach($this->option as $key => $line)
            {
                if(!$this->isOptionValide($key)){
                    dd('option non valide : '.$key);
                }
            }

            if(array_key_exists('id', $this->option)) {
                if (\preg_match('/^[0-9]+$/', $this->option['id'])) {

                    $this->validedOptions['id'] = $this->option['id'];
                }
                else{
                    dd('mauvais parametre : ' + $this->option['id']);
                }
            }

            if(array_key_exists('enable', $this->option)) {
                if($this->option['enable'] === 'true'){
                    $this->validedOptions['enable'] = true;
                }
                elseif ($this->option['enable'] === 'false'){
                    $this->validedOptions['enable'] = false;
                }
                else{
                    dd('argument enable : ' . $this->option['enable']);
                }
            }

            if(array_key_exists('username', $this->option)) {
                if (\preg_match("/[A-Za-z0-9]+/", $this->option['username']))
                {
                    $this->validedOptions['username'] = $this->option['username'];
                }
                else{
                    dd('argument username : '. $this->option['username'] . ' invalide');
                }
            }

            if(array_key_exists('mail', $this->option)) {
                if (\preg_match(" /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ ", $this->option['mail']))
                {
                    $this->validedOptions['adresseMail'] = $this->option['mail'];
                }
                else{
                    dd('argument mail : '. $this->option['mail'] . ' invalide');
                }
            }



            return $repository->findUserByCondition($this->validedOptions);
        }

    }
}