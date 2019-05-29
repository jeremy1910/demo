<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 16/05/19
 * Time: 13:17
 */

namespace App\Service\DataRequest;


use App\Service\ServiceHandler;
use Doctrine\ORM\EntityManagerInterface;


abstract class DataRequestClassService
{

    protected $target;
    protected $option;
    protected $em;
    protected $permitedOptions;
    protected $validedOptions;


    public function __construct(EntityManagerInterface $em, $target, $option)
    {

        $this->em = $em;
        $this->target = $target;
        $this->option = $option;
    }


    /**
     * @param string $options
     * @return boolean
     */
    protected function isOptionValide(string $options){
        if($this->permitedOptions != NULL) {
            $permitedOptions_sting = implode('$|^', $this->permitedOptions);
            $permitedOptions_sting = '#^' . $permitedOptions_sting . '$#';


            return \preg_match($permitedOptions_sting, $options);
        }
        else{
            dd('Merci de définir des option de validitée');
        }


    }

    abstract public function getResult();


}