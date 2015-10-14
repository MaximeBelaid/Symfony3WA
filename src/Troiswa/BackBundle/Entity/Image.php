<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=100)
     */
    private $caption;

    private $fichier;

    public function getFichier()
    {
        return $this->fichier;
    }

    public function setFichier(UploadedFile $fichier)
    {
        $this->fichier = $fichier;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set caption
     *
     * @param string $caption
     *
     * @return Image
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    public function upload()
    {
        $nameImage = $this->fichier->getClientOriginalName();
        $this->fichier->move(
            __DIR__."/../../../../web/".$this->getRootWebDir(),
            $nameImage
        );
        $this->name = $nameImage;
        $this->caption = $nameImage;
        //die("ok");

    }

    public function webPath()
    {
        return $this->getRootWebDir()."/".$this->name;
    }

    private function getRootWebDir()
    {
        return "uploads/categories";
    }
}
