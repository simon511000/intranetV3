<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Form/ApcParcoursNiveauType.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 13/12/2020 20:18

namespace App\Form;

use App\Entity\ApcParcoursNiveau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApcParcoursNiveauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('niveau')
            ->add('parcours');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ApcParcoursNiveau::class,
        ]);
    }
}