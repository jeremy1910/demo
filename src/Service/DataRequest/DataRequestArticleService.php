<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 17/05/19
 * Time: 23:42
 */

namespace App\Service\DataRequest;


use App\Entity\Article;

class DataRequestArticleService extends DataRequestClassService
{

    protected $permitedOptions = ['title', 'author', 'tag[0-9]+', 'category[0-9]+', 'created_before', 'created_after'];

    public function getResult()
    {

        if($this->target == 'article') {

            $repository = $this->em->getRepository(Article::class);
            /* Si il n'y a pas d'option */
            if ($this->option == []) {
                return $repository->findAll();
            }

            foreach($this->option as $key => $line)
            {
                if(!$this->isOptionValide($key)){
                    dd('option non valide : '.$key);
                }

                if($key == 'title'){
                    if (\preg_match("/[A-Za-z0-9]+/", $line))
                    {
                        $this->validedOptions['title'] = $line;
                    }
                    else{
                        dd('argument username : '. $line . ' invalide');
                    }
                }
                elseif ($key == 'author'){
                    if (\preg_match("/[A-Za-z0-9]+/", $line))
                    {
                        $this->validedOptions['author'] = $line;
                    }
                    else{
                        dd('argument username : '. $line . ' invalide');
                    }
                }
                elseif (preg_match('/^category[0-9]+$/', $key)){
                    if (\preg_match("/[A-Za-z0-9]+/", $line))
                    {
                        $this->validedOptions['category'][] = $line;
                    }
                     else{
                        dd('argument category : '. $line . ' category');
                    }
                }
                elseif (preg_match('/^tag[0-9]+$/', $key)){
                    if (\preg_match("/[A-Za-z0-9]+/", $line))
                    {
                        $this->validedOptions['tags'][] = $line;
                    }
                    else{
                        dd('argument username : '. $line . ' invalide');
                    }
                }
                elseif($key === 'created_before') {
                    if (\preg_match("/^(19[5-9][0-9]|20[0-4][0-9]|2050)[-](0?[1-9]|1[0-2])[-](0?[1-9]|[12][0-9]|3[01])$/", $this->option['created_before']))
                    {
                        $this->validedOptions['created_before'] = $this->option['created_before'];
                    }
                    else{
                        dd('argument username : '. $this->option['created_before'] . ' invalide');
                    }
                }
                elseif($key === 'created_after') {
                    if (\preg_match("/^(19[5-9][0-9]|20[0-4][0-9]|2050)[-](0?[1-9]|1[0-2])[-](0?[1-9]|[12][0-9]|3[01])$/", $this->option['created_after']))
                    {
                        $this->validedOptions['created_after'] = $this->option['created_after'];
                    }
                    else{
                        dd('argument username : '. $this->option['created_after'] . ' invalide');
                    }
                }
                else{
                    dd('argument non valide in : '.var_dump($this->option));
                }

            }


            return $repository->findArticleByCondition($this->validedOptions);
        }
    }

}