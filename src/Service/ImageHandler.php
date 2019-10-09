<?php
/**
 * Created by PhpStorm.
 * User: Jeremy
 * Date: 17/04/2019
 * Time: 22:47
 */

namespace App\Service;


use App\Entity\ImageArticle;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageHandler
{

    public function save(UploadedFile $image, string $folder) :string
    {


        $name = $imageFileName = $this->rename($image);
        $image->move($folder , $imageFileName);
        return $name;

    }



    private function rename(UploadedFile $image){

        return md5(uniqid()) . '.' . $image->guessExtension();
    }
}