<?php

namespace Troiswa\BackBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllPerso()
    {
        //die("ok");

        /*
        $query=$this->getEntityManager()->createQuery("
                SELECT prod
                FROM TroiswaBackBundle:Product prod
        ");
        return $query->getResult();
        */

        //autre méthode
        $query= $this->createQueryBuilder("prod")->getQuery();
        return $query->getResult();
    }

    public function findPerso($id)
    {
        //_em = raccourci de getEntityManager()
        /*
        $query = $this->_em->createQuery("
            SELECT prod
            FROM TroiswaBackBundle:Product prod
            WHERE prod.id = :idProd
            ")
            //->setParameter("idProd",$id);
            ->setParameters(["idProd"=>$id]);
        dump($query->getResult());
        die(dump($query->getSingleResult()));
        return $query->getSingleResult();
        */

        //autre méthode
        $query= $this->createQueryBuilder("prod")
            ->where("prod.id = :idProd")
            ->setParameters(["idProd"=>$id])
            ->getQuery();
        //dump($query->getResult());
        //die(dump($query->getSingleResult()));
        return $query->getSingleResult();
    }

    // Afficher les produits dont la quantité est inférieur à 5
    public function findProductQuantity($quantity = 5)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT prod
              FROM TroiswaBackBundle:Product prod
              WHERE prod.quantity < :qty"
            )
            ->setParameter('qty', $quantity);
        return $query->getResult();
    }

    // Afficher le nombre de produit dont la quantité est à 0
    public function findProductNoQuantity()
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT prod
            FROM TroiswaBackBundle:Product prod
            WHERE prod.quantity = 0"
        );

        return $query->getResult();
    }

    // Afficher le total des prix des produits
    public function findTotalPriceProduct()
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT SUM(prod.price)
            FROM TroiswaBackBundle:Product prod"
        );

        return $query->getSingleScalarResult();
    }


    // Afficher la quantité total des produits
    public function findTotalQuantityProduct()
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT SUM(prod.quantity)
            FROM TroiswaBackBundle:Product prod"
        );

        return $query->getSingleScalarResult();
    }

    // Afficher le prix du produit le plus cher et le prix du produit le moins cher
    public function findBestAndLessProductPrice()
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT MAX(prod.price), MIN(prod.price)
            FROM TroiswaBackBundle:Product prod"
        );

        return $query->getScalarResult();
    }

    // Afficher le produit le plus cher et ayant la quantité la plus élevé
    public function findBestProductPriceAndQuantity()
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT prod
            FROM TroiswaBackBundle:Product prod
            ORDER BY prod.price DESC, prod.quantity DESC"
        )->setMaxResults(1);

        return $query->getSingleResult();
    }
        //->setMaxResults(1)
        //->setFirstResult(2)
        // équivalent à LIMIT 2,4
        //die(dump($query->getSingleResult()));

    public function findAllProductWithCategory()
    {
        $query=$this->getEntityManager()->createQuery("
                SELECT prod, cat
                FROM TroiswaBackBundle:Product prod
                LEFT JOIN prod.categorie cat
        ");
        //die(dump($query->getResult()));
        return $query->getResult();
    }
}
