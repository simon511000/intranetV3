<?php
/*
 * Copyright (c) 2022. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/Repository/TypeDiplomeRepository.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 06/05/2022 14:27
 */

namespace App\Repository;

use App\Entity\TypeDiplome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeDiplome|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDiplome|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDiplome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<TypeDiplome>
 */
class TypeDiplomeRepository extends ServiceEntityRepository
{
    /**
     * TypeDiplomeRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDiplome::class);
    }

    public function findAllBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.libelle', Criteria::ASC);
    }

    public function findAll(): array
    {
        return $this->findAllBuilder()
            ->getQuery()
            ->getResult();
    }
}
