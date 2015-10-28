<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Troiswa\BackBundle\Validator\PositionCategory;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\CategorieRepository")
 */
class Categorie
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
     * @Assert\NotBlank(message="Obligatoire")
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Your title must be at least {{ limit }} characters long",
     * )
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var string
     * @Assert\NotBlank(message="Obligatoire")
     * @Assert\Regex(
     *     pattern="/^((?!catégorie).)*$/",
     *     message="La description ne doit pas contenir le mot << catégorie >>.")
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     * @Assert\Regex(
     *     pattern="/^(^|[^- \t])\s*\d+$/",
     *     message="La position doit être positive")
     * @PositionCategory()
     * @ORM\Column(name="position", type="smallint")
     */
    private $position;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToOne(targetEntity="Image" ,cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $image;

    public function __construct()
    {
        $this->dateCreated = new \DateTime("now");
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
     * Set title
     *
     * @param string $title
     *
     * @return Categorie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Product
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Categorie
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Categorie
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Categorie
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }


    /*
    public  function __toString()
    {
        return $this->title;
    }*/

    /**
     * Set image
     *
     * @param \Troiswa\BackBundle\Entity\Image $image
     *
     * @return Categorie
     *
     */
    public function setImage(\Troiswa\BackBundle\Entity\Image $image = null)
    {
        if ($image == null || !$image->getFichier())
        {
            $image = null;
        }

        $this->image = $image;

        return $this;
    }

    public function setImageFaker(\Troiswa\BackBundle\Entity\Image $image = null)
    {

        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Troiswa\BackBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }


}
