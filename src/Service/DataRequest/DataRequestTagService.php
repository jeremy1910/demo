<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 30/05/19
 * Time: 22:56
 */

namespace App\Service\DataRequest;


use App\Entity\Tag;

class DataRequestTagService extends DataRequestClassService
{

    protected $permitedOptions = ['id', 'name'];

    public function setFilter()
    {

        if($this->target == 'tag') {

            $repository = $this->em->getRepository(Tag::class);
            /* Si il n'y a pas d'option */
            if ($this->option == []) {
                return $repository->findAll();
            }

            foreach($this->option as $key => $line)
            {
                if(!$this->isOptionValide($key)){
                    dd('option non valide : '.$key);
                }

                if($key == 'id'){
                    if (\preg_match('/^[0-9]+$/', $line))
                    {
                        $this->validedOptions['id'] = $line;
                    }
                    else{
                        dd('argument username : '. $line . ' invalide');
                    }
                }
                elseif ($key == 'name'){
                    if (\preg_match("/[A-Za-z0-9]+/", $line))
                    {
                        $this->validedOptions['name'] = $line;
                    }
                    else{
                        dd('argument username : '. $line . ' invalide');
                    }
                }
                else{
                    dd('argument non valide in : '.var_dump($this->option));
                }

            }

            return $repository->findTagByCondition($this->validedOptions);
        }
    }
}