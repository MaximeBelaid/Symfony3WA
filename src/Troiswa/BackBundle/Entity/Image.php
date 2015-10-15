<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Imagine\Gd\Imagine;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
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

    /**
     * @Assert\File(
     *     maxSize = "500k",
     *     mimeTypes = {"image/png", "image/jpg", "image/jpeg", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid image")
     *
     */
    private $fichier;

    private $thumbnails =
        [
            'thumb-small' => [100,100],
            'thumb-medium' => [400,400],
            'thumb-large' => [800,800],
        ];


    // propriété permettant de sauvegarder l'ancien nom de l'image (lorsqu'on fait une édition)
    private $oldName;

    public function getFichier()
    {
        return $this->fichier;
    }

    public function setFichier(UploadedFile $fichier)
    {
        $this->fichier = $fichier;

        // Si j'ai déjà un nom (édition), je sauvegarde celui-ci dans une propriété oldName
        if (null != $this->name)
        {
            $this->oldName = $this->name;

            // On effectue une modification fictive pour obliger doctrine à croire qu'il y a eu une modif et donc
            // faire la mise à jour de mon objet Image
            $this->name = "changement";
        }

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



    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function upload()
    {
        //die('upload');
        if (null == $this->fichier)
        {
            return;
        }

        $nameImage = str_replace('.','',uniqid("", true)).'.'.$this->fichier
                ->guessExtension();
        //die(dump($nameImage));
        $this->fichier->move(
            __DIR__."/../../../../web/".$this->getRootWebDir(),
            $nameImage
        );
        $this->name = $nameImage;
        //$this->caption = $nameImage;
        //die("ok");

        // Creation des thumbnails
        $imagine = new Imagine();
        $imagineOpen = $imagine->open(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameImage);
        $mode1    = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $mode2    = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

        $imagineOpen->effects()
            ->negative();

        foreach($this->thumbnails as $nameThumb => $size)
        {
            $imagineOpen->thumbnail(new \Imagine\Image\Box($size[0],$size[1]), $mode1)
                ->save(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameThumb.'-'.$nameImage);
        }
        // Fin de creation des thumbnails

        // Suppression de l'ancienne image
        if (!empty($this->oldName))
        {
            if (file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$this->oldName))
            {
                unlink(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$this->oldName);
            }

            foreach($this->thumbnails as $nameThumb => $size)
            {
                if (file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameThumb.'-'.$this->oldName))
                {
                    unlink(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameThumb.'-'.$this->oldName);
                }
            }

        }

    }


    public function webPath($thumb = null)
    {
        if ($thumb)
        {
            if (file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$thumb.'-'.$this->name))
            {
                return $this->getRootWebDir().'/'.$thumb.'-'.$this->name;
            }
        }
        if (file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$this->name))
        {
            return $this->getRootWebDir().'/'.$this->name;
        }
        // Photo par défaut
        return null;
    }


    private function getRootWebDir()
    {
        return "uploads/categories";
    }

    /**
     * @Assert\Callback
     */
    public function isValidate(ExecutionContextInterface $context)
    {
        if ($this->fichier != null && $this->caption == null) {
            $context->buildViolation('La légende est obligatoire si vous uploadez une image')
                ->atPath('caption')
                ->addViolation();
        }
    }

    /**
     * Méthode permettant d'éviter d'avoir l'objet proxy dans la méthode removeImage()
     * J'ai ainsi réellement l'objet image accessible dans la méthode removeImage()
     * @ORM\PreRemove
     */
    public function preRemoveImage()
    {
    }

    /**
     * @ORM\PostRemove
     */
    public function removeImage()
    {
        if (file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$this->name))
        {
            unlink(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$this->name);
        }

        foreach($this->thumbnails as $nameThumb => $size)
        {
            if (file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameThumb.'-'.$this->name))
            {
                unlink(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameThumb.'-'.$this->name);
            }
        }
    }
}
