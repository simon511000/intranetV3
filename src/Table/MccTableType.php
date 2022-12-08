<?php
/*
 * Copyright (c) 2022. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/Table/MccTableType.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 07/12/2022 17:45
 */

namespace App\Table;

use App\Classes\Matieres\TypeMatiereManager;
use App\DTO\Matiere;
use App\Entity\AnneeUniversitaire;
use App\Entity\Semestre;
use DavidAnnebicque\TableBundle\Column\PropertyColumnType;
use DavidAnnebicque\TableBundle\Column\WidgetColumnType;
use DavidAnnebicque\TableBundle\DTO\TableResult;
use DavidAnnebicque\TableBundle\DTO\TableState;
use DavidAnnebicque\TableBundle\TableBuilder;
use DavidAnnebicque\TableBundle\TableType;
use DavidAnnebicque\TableBundle\Widget\Type\RowShowLinkType;
use DavidAnnebicque\TableBundle\Widget\WidgetBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MccTableType extends TableType
{
    protected Semestre $semestre;
    protected AnneeUniversitaire $anneeUniversitaire;

    public function __construct(
        private readonly TypeMatiereManager $typeMatiereManager
    ) {
    }

    public function buildTable(TableBuilder $builder, array $options): void
    {
        $this->semestre = $options['semestre'];
        $this->anneeUniversitaire = $options['anneeUniversitaire'];

        $builder->addColumn('codeElement', PropertyColumnType::class, [
            'order' => 'ASC',
            'label' => 'table.code_element',
            'translation_domain' => 'messages',
        ]);
        $builder->addColumn('codeMatiere', PropertyColumnType::class, [
            'order' => 'ASC',
            'label' => 'table.code_element',
            'translation_domain' => 'messages',
        ]);
        $builder->addColumn('libelle', PropertyColumnType::class,
            ['label' => 'table.matiere_libelle', 'translation_domain' => 'messages']);

        $builder->addColumn('links', WidgetColumnType::class, [
            'build' => function (WidgetBuilder $builder, Matiere $s) {
                $builder->add('show', RowShowLinkType::class, [
                    'route' => 'administration_mcc_show_matiere',
                    'route_params' => [
                        'typeIdMatiere' => $s->getTypeIdMatiere(),
                        'semestre' => $this->semestre->getId(),
                    ],
                    'xhr' => false,
                ]);
            },
        ]);
        $builder->setLoadUrl('administration_mcc_index', ['semestre' => $this->semestre->getId()]);

        $builder->useAdapter(function (TableState $state) {
            $matieres = $this->typeMatiereManager->findBySemestreAndReferentiel($this->semestre, $this->semestre->getDiplome()->getReferentiel());

            return new TableResult($matieres, count($matieres));
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'orderable' => true,
            'semestre' => null,
            'anneeUniversitaire' => null,
            'exportable' => true,
        ]);
    }
}