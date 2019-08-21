<?php

namespace App\Service\DataRequest;


use App\Service\ServiceHandler;
use Doctrine\ORM\EntityManagerInterface;


abstract class DataRequestClassService
{

    protected $target;
    protected $option = [];
    protected $em;
    protected $permitedOptions;
    protected $validedOptions;
    protected $entity;
    protected $offset = NULL;
    protected $maxResult = NULL;


    public function __construct(EntityManagerInterface $em, $target, $option, $entity)
    {

        $this->em = $em;
        $this->target = $target;
        $this->option = $option;
        $this->entity = $entity;
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

    public function getResult(){
        $repository = $this->em->getRepository($this->entity);

        $nbElement = $repository->findByCondition($this->validedOptions, null, null, TRUE);

        $result['result'] = $repository->findByCondition($this->validedOptions, $this->maxResult, ($this->offset-1)*$this->maxResult);

        $nbPage = (int) ceil($nbElement[0]['1'] / $this->maxResult);
        $result['nbPage'] = $nbPage;

        return $result;
    }

    public function handelMaxAndOffsetResult(){
        if (isset($this->option['nbResult']) && \preg_match("/[A-Za-z0-9]+/", $this->option['nbResult'])) {

            $this->maxResult = $this->option['nbResult'];
        }
        else{
            dd('argument username : '. $this->option['nbResult'] . ' invalide');
        }

        if (isset($this->option['pageSelected']) && \preg_match("/[A-Za-z0-9]+/", $this->option['pageSelected'])) {


            $this->offset = $this->option['pageSelected'];
        }
        else{
            dd('argument username : '. $this->option['nbResult'] . ' invalide');
        }

    }

    abstract public function setFilter();



}