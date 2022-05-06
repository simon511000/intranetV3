<?php
/*
 * Copyright (c) 2022. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/Repository/CovidAttestationEtudiantRepository.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 06/05/2022 14:27
 */

namespace App\Repository;

use App\Entity\CovidAttestationEtudiant;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CovidAttestationEtudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method CovidAttestationEtudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method CovidAttestationEtudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<CovidAttestationEtudiant>
 */
class CovidAttestationEtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CovidAttestationEtudiant::class);
    }

    public function findByDepartement(Departement $departement): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin(Diplome::class, 'd', 'WITH', 'p.diplome=d.id')
            ->where('d.departement = :departement')
            ->setParameter('departement', $departement->getId())
            ->orderBy('p.created', Criteria::DESC)
            ->getQuery()
            ->getResult();
    }

    public function findAll(): array
    {
        return $this->findBy([], ['created' => 'DESC']);
    }

    public function findNext(Etudiant $etudiant): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.groupes', 'c')// récupération de la jointure dans la table dédiée
            ->innerJoin(Groupe::class, 'g', 'WITH', 'c.id = g.id')
            ->innerJoin('g.etudiants', 'f')
            ->innerJoin(Etudiant::class, 'e', 'WITH', 'e.id = f.id')
            ->where('e.id = :etudiant')
            ->andWhere('a.datePresence >= :date')
            ->setParameter('etudiant', $etudiant->getId())
            ->setParameter('date', new DateTime('now'))
            ->getQuery()
            ->getResult();
    }
}
