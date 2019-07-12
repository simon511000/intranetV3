<?php
/**
 * Copyright (C) 7 / 2019 | David annebicque | IUT de Troyes - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetv3/src/Repository/DepartementRepository.php
 * @author     David Annebicque
 * @project intranetv3
 * @date 7/12/19 11:23 AM
 * @lastUpdate 4/30/19 2:35 PM
 */

namespace App\Repository;

use App\Entity\Annee;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Etudiant;
use App\Entity\Personnel;
use App\Entity\PersonnelDepartement;
use App\Entity\Semestre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Departement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departement[]    findAll()
 * @method Departement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartementRepository extends ServiceEntityRepository
{
    /**
     * DepartementRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Departement::class);
    }

    /**
     * @param Etudiant $etudiant
     *
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findDepartementEtudiant(Etudiant $etudiant)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin(Diplome::class, 'd', 'WITH', 'd.departement = f.id')
            ->innerJoin(Annee::class, 'a', 'WITH', 'a.diplome = d.id')
            ->innerJoin(Semestre::class, 's', 'WITH', 's.annee = a.id')
            ->innerJoin(Etudiant::class, 'e', 'WITH', 'e.semestre = s.id')
            ->where('e.id = :etudiant')
            ->setParameter('etudiant', $etudiant->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Personnel $personnel
     *
     * @return mixed
     */
    public function findDepartementPersonnelDefaut(Personnel $personnel)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin(PersonnelDepartement::class, 'p', 'WITH', 'p.departement = f.id')
            ->where('p.personnel = :personnel')
            ->andWhere('p.defaut = :defaut')
            ->setParameter('personnel', $personnel->getId())
            ->setParameter('defaut', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Personnel $personnel
     *
     * @return mixed
     */
    public function findDepartementPersonnel(Personnel $personnel)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin(PersonnelDepartement::class, 'p', 'WITH', 'p.departement = f.id')
            ->where('p.personnel = :personnel')
            ->setParameter('personnel', $personnel->getId())
            ->getQuery()
            ->getResult();
    }

    public function findActifs()
    {
        return $this->createQueryBuilder('d')
            ->where('d.actif = 1')
            ->orderBy('d.libelle')
            ->getQuery()
            ->getResult();
    }
}
