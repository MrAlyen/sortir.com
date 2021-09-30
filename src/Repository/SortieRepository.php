<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function sortieFormNowtoDown()
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->andWhere("s.dateHeureDebut > DATE_SUB(CURRENT_DATE(), 30,'DAY')");
        $queryBuilder->addOrderBy('s.dateHeureDebut','ASC');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }


    public function whereFiltre($filtreForm, $userId, $etatRepository){

        $queryBuilder = $this->createQueryBuilder('s');


        if  ( $filtreForm->get('campus')->getData() != null){
            $campus = $filtreForm->get('campus')->getData();
            $idCampus = $campus->getId();
            $queryBuilder->andWhere("s.siteOrganisateur = '$idCampus' ");
        }

        if ( $filtreForm->get('boutNom')->getData() != null){
            $nom = $filtreForm->get('boutNom')->getData();
            $queryBuilder->andWhere("s.nom LIKE '%$nom%' ");
        }

        if ( $filtreForm->get('dateDebut')->getData() != null){
            $dateDebut = $filtreForm->get('dateDebut')->getData();
            $dateDebutU = date_format($dateDebut, 'Y-m-d H:i:s');
            $queryBuilder->andWhere("s.dateHeureDebut > '$dateDebutU' ");
        }

        if ( $filtreForm->get('dateFin')->getData()!= null){
            $dateFin = $filtreForm->get('dateFin')->getData();
            $dateFinU = date_format($dateFin, 'Y-m-d H:i:s');
            $queryBuilder->andWhere("s.dateLimiteInscription < '$dateFinU' ");
        }

        if ( $filtreForm->get('organisateur')->getData()!= null){

            $queryBuilder->andWhere("s.organisateur = '$userId' ");
        }

        if ( $filtreForm->get('inscrit')->getData()!= null){

            $queryBuilder->andWhere("s.id = '$userId' ");
        }

        if ( $filtreForm->get('pasInscrit')->getData()!= null){

            $queryBuilder->andWhere("s.id != '$userId' ");
        }

        if ($filtreForm->get('passee')->getData()!= null){
            $etat = $etatRepository->findEtat('Clôturée');
            $etatId = $etat[0]->getId();
            $queryBuilder->andWhere("s.etats = '$etatId' ");
        }

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function detailsSortie(int $sortieId){

        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder   ->leftJoin('s.lieu','l')
                        ->addSelect('l');
        $queryBuilder   ->leftJoin('l.ville','v')
                        ->addSelect('v');
        $queryBuilder   ->leftJoin('s.siteOrganisateur','c')
                        ->addSelect('c');
        $queryBuilder->andWhere("s.id = '$sortieId'");

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function modifSortie(int $sortieId){

        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder   ->leftJoin('s.lieu','l')
            ->addSelect('l');
        $queryBuilder   ->leftJoin('l.ville','v')
            ->addSelect('v');
        $queryBuilder->andWhere("s.id = '$sortieId'");

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

}
