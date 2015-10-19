<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Commentaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @ORM\Column(name="auteur", type="string", length=100)
     */
    private $auteur;

    /**
     * @var string
     * @Assert\NotBlank(message="Obligatoire")
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var integer
     * @Assert\NotBlank(message="Obligatoire")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     * )
     * @ORM\Column(name="note", type="smallint")
     */
    private $note;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id",referencedColumnName="id",nullable=false)
     */
    private $product;

    public function __construct()
    {
        $this->dateCreation = new \DateTime("now");
        $this->active = false;
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
     * Set auteur
     *
     * @param string $auteur
     *
     * @return Commentaire
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Commentaire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Commentaire
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Commentaire
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set product
     *
     * @param \Troiswa\BackBundle\Entity\Product $product
     *
     * @return Commentaire
     */
    public function setProduct(\Troiswa\BackBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Troiswa\BackBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Commentaire
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

    /**
     * @Assert\Callback
     */
    public function isValidate(ExecutionContextInterface $context)
    {
        $grosMots = array("putain", "merde", "fait chier");

        if (in_array($this->getContenu(), $grosMots)) {
            $context->buildViolation('Le commentaire ne doit pas contenir de gros mots!')
                ->atPath('contenu')
                ->addViolation();
        }
    }
}
