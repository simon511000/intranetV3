<?php
/*
 * Copyright (C) 7 / 2019 | David annebicque | IUT de Troyes - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetv3/src/Controller/DefaultController.php
 * @author     David Annebicque
 * @project intranetv3
 * @date 7/12/19 11:23 AM
 * @lastUpdate 7/12/19 11:21 AM
 */

namespace App\Controller;

use App\Repository\ActualiteRepository;
use App\Repository\DateRepository;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends BaseController
{
    /**
     * @Route("/tableau-de-bord", name="default_homepage")
     * @param ActualiteRepository $actualiteRepository
     * @param DateRepository      $dateRepository
     *
     * @return Response
     */
    public function index(ActualiteRepository $actualiteRepository, DateRepository $dateRepository): Response
    {
        return $this->render('default/index.html.twig', [
            'actualites' => $actualiteRepository->findByDepartement($this->dataUserSession->getDepartement()),
            'dates'      => $dateRepository->findByDepartement($this->dataUserSession->getDepartement())
        ]);
    }

    /**
     *
     * @param DepartementRepository $departementRepository
     *
     * @return Response
     *
     * @Route("/super-administration/", name="super_admin_homepage")
     */
    public function superAdminHomepage(DepartementRepository $departementRepository): Response
    {
        return $this->render('super-administration/super_admin_homepage.html.twig', [
            'departements' => $departementRepository->findAll()
        ]);
    }

    /**
     * @Route("/administratif/", name="administratif_homepage")
     */
    public function administratifHomepage(): Response
    {
        return $this->render('default/administratif_homepage.html.twig', [

        ]);
    }
}
