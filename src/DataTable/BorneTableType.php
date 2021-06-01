<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/DataTable/BorneTableType.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 23/05/2021 16:03
 */

namespace App\DataTable;

use App\DataTable\ColumnType\IconeColumnType;
use App\DataTable\ColumnType\SemestreColumnType;
use App\DataTable\Widget\RowDeleteLinkType;
use App\DataTable\Widget\RowDuplicateLinkType;
use App\DataTable\Widget\RowEditLinkType;
use App\DataTable\Widget\RowShowLinkType;
use App\Entity\Annee;
use App\Entity\Borne;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Semestre;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Umbrella\CoreBundle\Component\DataTable\Adapter\EntityAdapter;
use Umbrella\CoreBundle\Component\DataTable\Column\BooleanColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\DateColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\WidgetColumnType;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\DataTable\DataTableType;
use Umbrella\CoreBundle\Component\DataTable\ToolbarBuilder;
use Umbrella\CoreBundle\Component\Widget\Type\ButtonDropdownType;
use Umbrella\CoreBundle\Component\Widget\Type\LinkType;
use Umbrella\CoreBundle\Component\Widget\WidgetBuilder;
use Umbrella\CoreBundle\Form\DatepickerType;
use Umbrella\CoreBundle\Form\SearchType;

class BorneTableType extends DataTableType
{
    private ?Departement $departement;
    private CsrfTokenManagerInterface $csrfToken;

    public function __construct(CsrfTokenManagerInterface $csrfToken)
    {
        $this->csrfToken = $csrfToken;
    }

    public function buildToolbar(ToolbarBuilder $builder, array $options): void
    {
        $builder->addFilter('search', SearchType::class);
        $builder->addFilter('from', DatepickerType::class, [
            'input_prefix_text' => 'Du',
        ]);
        $builder->addFilter('to', DatepickerType::class, [
            'input_prefix_text' => 'Au',
        ]);

        $builder->addWidget('export', ButtonDropdownType::class, [
            'icon' => 'mdi mdi-download',
            'attr' => ['data-toggle' => 'dropdown'],
            'build' => function(WidgetBuilder $builder) {
                $builder->add('pdf', LinkType::class, [
                    'route' => 'administration_borne_export',
                    'route_params' => ['_format' => 'pdf'],
                ]);
                $builder->add('csv', LinkType::class, [
                    'route' => 'administration_borne_export',
                    'route_params' => ['_format' => 'csv'],
                ]);
                $builder->add('excel', LinkType::class, [
                    'route' => 'administration_borne_export',
                    'route_params' => ['_format' => 'xlsx'],
                ]);
            },
        ]);
    }

    public function buildTable(DataTableBuilder $builder, array $options): void
    {
        $this->departement = $options['departement'];

        $builder->add('icone', IconeColumnType::class, ['label' => 'icone']);
        $builder->add('message', PropertyColumnType::class, ['label' => 'message']);
        $builder->add('dateDebutPublication', DateColumnType::class, [
            'order' => 'DESC',
            'format' => 'd/m/Y',
            'label' => 'dateDebutPublication',
        ]);
        $builder->add('dateFinPublication', DateColumnType::class, [
            'format' => 'd/m/Y',
            'label' => 'dateFinPublication',
        ]);
        $builder->add('visible', BooleanColumnType::class, [
        ]);
        $builder->add('semestres', SemestreColumnType::class, [
            'label' => 'semestres',
        ]);

        $builder->add('links', WidgetColumnType::class, [
            'build' => function(WidgetBuilder $builder, Borne $s) {
                $builder->add('duplicate', RowDuplicateLinkType::class, [
                    'route' => 'administration_borne_duplicate',
                    'route_params' => ['id' => $s->getId()],
                    'xhr' => false,
                ]);
                $builder->add('show', RowShowLinkType::class, [
                    'route' => 'administration_borne_show',
                    'route_params' => [
                        'id' => $s->getId(),
                    ],
                    'xhr' => false,
                ]);
                $builder->add('edit', RowEditLinkType::class, [
                    'route' => 'administration_borne_edit',
                    'route_params' => [
                        'id' => $s->getId(),
                    ],
                    'xhr' => false,
                ]);
                $builder->add('delete', RowDeleteLinkType::class, [
                    'attr' => [
                        'data-href' => 'administration_borne_delete',
                        'data-uuid' => $s->getId(),
                        'data-csrf' => $this->csrfToken->getToken('delete' . $s->getId()),
                    ],
                ]);
            },
        ]);

        $builder->useAdapter(EntityAdapter::class, [
            'class' => Borne::class,
            'fetch_join_collection' => false,
            'query' => function(QueryBuilder $qb, array $formData) {
                $qb->innerJoin('e.semestres', 'c')//récupération de la jointure dans la table dédiée
                ->innerJoin(Semestre::class, 's', 'WITH', 'c.id = s.id')
                    ->innerJoin(Annee::class, 'a', 'WITH', 's.annee = a.id')
                    ->innerJoin(Diplome::class, 'd', 'WITH', 'a.diplome = d.id')
                    ->where('d.departement = :departement')
                    ->setParameter('departement', $this->departement->getId());

                if (isset($formData['search'])) {
                    $qb->andWhere('LOWER(e.message) LIKE :search');
                    $qb->setParameter('search', '%' . $formData['search'] . '%');
                }

                if (isset($formData['from'])) {
                    $qb->andWhere('e.dateDebutPublication >= :from');
                    $qb->orWhere('e.dateFinPublication >= :from');
                    $qb->setParameter('from', $formData['from']);
                }

                if (isset($formData['to'])) {
                    $qb->andWhere('e.dateDebutPublication <= :to');
                    $qb->orWhere('e.dateFinPublication <= :to');
                    $qb->setParameter('to', $formData['to']);
                }
            },
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'orderable' => true,
            'departement' => null,
        ]);
    }
}