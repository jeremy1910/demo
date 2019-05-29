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

class ImageArticleHandler
{

    public function save(ImageArticle $image, string $folder){


        $imageFileName = $this->rename($image->getImageFile());
        $image->getImageFile()->move($folder , $imageFileName);
        $image->setFileName($imageFileName);

    }

    private function rename(UploadedFile $image){

        return md5(uniqid()) . '.' . $image->guessExtension();
    }
}