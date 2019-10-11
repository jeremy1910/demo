<?php
/**
 * Created by PhpStorm.
 * User: Jeremy
 * Date: 17/04/2019
 * Time: 22:01
 */

namespace App\Service;


class ImageProcessingHandler
{
    public const RESIZE_DIMENTION = '500x500';

    /**
     * @param string $fileName
     * @param null $dimention
     */
    public function resize(string $fileName, $dimention = null){

        if ($dimention === null){
            $dimention = self::RESIZE_DIMENTION;
        }
        if(PHP_OS_FAMILY !== "Windows") {
            $cmdResizeImg = "convert \"" . $fileName . "\" -resize " . $dimention . " \"" . $fileName . "\"";
        }else{
            $cmdResizeImg = "magick convert \"" . $fileName . "\" -resize " . $dimention . " \"" . $fileName . "\"";
        }
        shell_exec($cmdResizeImg);

    }

}