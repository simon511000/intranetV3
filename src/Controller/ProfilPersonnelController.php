<?php
/*
 * Copyright (c) 2022. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/Controller/ProfilPersonnelController.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 14/05/2022 10:44
 */

namespace App\Controller;

use App\Classes\Hrs\HrsManager;
use App\Classes\Previsionnel\PrevisionnelManager;
use App\Classes\Previsionnel\PrevisionnelSynthese;
use App\Entity\Personnel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfilPersonnelController.
 */
#[Route(path: '/personnel')]
class ProfilPersonnelController extends BaseController
{
    #[Route(path: '/profil/{slug}/actions', name: 'profil_personnel_action')]
    #[ParamConverter('personnel', options: ['mapping' => ['slug' => 'slug']])]
    public function actions(Personnel $personnel): Response
    {
        return $this->render('user/composants/actions_personnel.html.twig', [
            'personnel' => $personnel,
        ]);
    }

    #[Route(path: '/profil/{slug}/a-propos', name: 'profil_personnel_about')]
    #[ParamConverter('personnel', options: ['mapping' => ['slug' => 'slug']])]
    public function about(Personnel $personnel): Response
    {
        return $this->render('user/composants/_apropos.html.twig', [
            'user' => $personnel,
        ]);
    }

    #[Route(path: '/profil/{slug}/previsionnel', name: 'profil_personnel_previsionnel')]
    #[ParamConverter('personnel', options: ['mapping' => ['slug' => 'slug']])]
    public function previsionnel(PrevisionnelManager $myPrevisionnel, PrevisionnelSynthese $previsionnelSynthese, HrsManager $hrsManager, Personnel $personnel): Response
    {
        $anneePrevisionnel = $this->dataUserSession->getAnneePrevisionnel();
        $previsionnels = $myPrevisionnel->getPrevisionnelEnseignantAnnee($personnel,
            $anneePrevisionnel);
        $hrs = $hrsManager->getPersonnelAnnee($personnel, $anneePrevisionnel);
        $synthsePrevisionnel = $previsionnelSynthese->getSynthese($previsionnels, $personnel)
            ->getHrsEnseignant($hrs);

        return $this->render('user/composants/previsionnel.html.twig', [
            'previsionnels' => $previsionnels,
            'synthsePrevisionnel' => $synthsePrevisionnel,
            'anneePrevisionnel' => $anneePrevisionnel,
            'semestres' => $this->dataUserSession->getSemestres(),
            // todo: doit être tous les semestres dans lequel le personnel intervient. Sans nuance de département
            'hrs' => $hrs,
            'personnel' => $personnel,
        ]);
    }
}
