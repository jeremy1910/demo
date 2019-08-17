<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class ImageArticle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;



    private $imageFile;

    /**
     * @return null|UploadedFile
     */

    public function getImageFile(): ?UploadedFile
    {

        return $this->imageFile;
    }


    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {

        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        if($this->getFileName())
        {
            unlink("../public/images/".$this->getFileName());
        }
        $this->fileName = $fileName;

        return $this;
    }




}
