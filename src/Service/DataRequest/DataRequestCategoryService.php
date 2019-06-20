<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 25/05/19
 * Time: 01:17
 */

namespace App\Service\DataRequest;


use App\Entity\Category;

class DataRequestCategoryService extends DataRequestClassService
{
    protected $permitedOptions = ['id', 'libele', 'nbResult', 'pageSelected'];

    public function setFilter()
    {


            if ($this->option != []) {


                foreach ($this->option as $key => $line) {
                    if (!$this->isOptionValide($key)) {
                        dd('option non valide : ' . $key);
                    }

                    if ($key == 'id') {
                        if (\preg_match('/^[0-9]+$/', $line)) {
                            $this->validedOptions['id'] = $line;
                        } else {
                            dd('argument id : ' . $line . ' invalide');
                        }
                    } elseif ($key == 'libele') {
                        if (\preg_match("/[A-Za-z0-9]+/", $line)) {
                            $this->validedOptions['libele'] = $line;
                        } else {
                            dd('argument libele : ' . $line . ' invalide');
                        }
                    }
                }

            }
    }
}