<?php
/*
 * Copyright (c) 2022. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/Repository/ArticleCategorieRepository.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 06/05/2022 14:27
 */

namespace App\Repository;

use App\Entity\ArticleCategorie;
use App\Entity\Departement;
use Doctrine\Common\Collections\Criteria;
use function count;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleCategorie[]    findAll()
 * @method ArticleCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<ArticleCategorie>
 */
class ArticleCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleCategorie::class);
    }

    public function findByDepartementBuilder(Departement $departement): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.departement = :departement')
            ->setParameter('departement', $departement->getId())
            ->orderBy('c.libelle', Criteria::ASC);
    }

    public function findByDepartementJson(Departement $departement): array
    {
        $data = $this->findByDepartementBuilder($departement)->getQuery()->getResult();
        $t = [];
        /** @var ArticleCategorie $d */
        foreach ($data as $d) {
            $t[] = [
                'libelle' => $d->getLibelle(),
                'id' => $d->getId(),
                'nbArticles' => count($d->getArticles()),
            ];
        }

        return $t;
    }
}
