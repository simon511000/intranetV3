<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/administration/SemestreController.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 08/10/2021 10:57
 */

namespace App\Controller\administration;

use App\Controller\BaseController;
use App\Entity\Semestre;
use App\Repository\AbsenceJustificatifRepository;
use App\Repository\RattrapageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SemestreController.
 *
 * @Route("/administration/semestre")
 */
class SemestreController extends BaseController
{
    /**
     *
     * @Route("/active/{semestre}/{actif}",
     *     name="administration_semestre_actif",
     *     methods={"GET"})
     */
    public function activeSemestreAction(Semestre $semestre, bool $actif): RedirectResponse
    {
        $this->denyAccessUnlessGranted('MINIMAL_ROLE_ASS', $semestre);

        $semestre->setActif($actif);
        $this->entityManager->persist($semestre);
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('administration_semestre_index', ['semestre' => $semestre->getId()]));
    }

    /**
     * @Route("/{semestre}", name="administration_semestre_index", requirements={"semestre" : "\d+"})
     *
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function index(
        AbsenceJustificatifRepository $absenceJustificatifRepository,
        RattrapageRepository $rattrapageRepository,
        Semestre $semestre
    ): Response
    {
        $this->denyAccessUnlessGranted('MINIMAL_ROLE_SCOL', $semestre);

        return $this->render('administration/semestre/index.html.twig', [
            'semestre' => $semestre,
            'nbJustificatifs' => $absenceJustificatifRepository->findBySemestreCount($semestre),
            'nbRattrapages' => $rattrapageRepository->findBySemestreCount($semestre),
        ]);
    }
}
