<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tags
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\TagRepository")
 */
class Tag
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity="Marque", mappedBy="tags")
     */
    private $marques;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Tag
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add marque
     *
     * @param \Troiswa\BackBundle\Entity\Marque $marque
     *
     * @return Tag
     */
    public function addMarque(\Troiswa\BackBundle\Entity\Marque $marque)
    {
        $this->marques[] = $marque;
        $marque->addTags($this);
        return $this;
    }

    /**
     * Remove marque
     *
     * @param \Troiswa\BackBundle\Entity\Marque $marque
     */
    public function removeMarque(\Troiswa\BackBundle\Entity\Marque $marque)
    {
        $this->marques->removeElement($marque);
    }

    /**
     * Get marques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMarques()
    {
        return $this->marques;
    }
}
