<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/administration/apc/ApcParcoursController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 19/12/2020 14:57

namespace App\Controller\administration\apc;

use App\Controller\BaseController;
use App\Entity\ApcParcours;
use App\Entity\Constantes;
use App\Entity\Diplome;
use App\Form\ApcParcoursType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/apc/parcours", name="administration_")
 */
class ApcParcoursController extends BaseController
{
    /**
     * @Route("/{diplome}/new", name="apc_parcours_new", methods={"GET","POST"})
     * @param Request $request
     * @param Diplome $diplome
     *
     * @return Response
     */
    public function new(Request $request, Diplome $diplome): Response
    {
        $apcParcour = new ApcParcours($diplome);
        $form = $this->createForm(ApcParcoursType::class, $apcParcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($apcParcour);
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'apcParcours.create.success.flash');

            return $this->redirectToRoute('administration_apc_parcours_show', ['id' => $apcParcour->getId()]);
        }

        return $this->render('administration/apc/apc_parcours/new.html.twig', [
            'apc_parcour' => $apcParcour,
            'form'        => $form->createView(),
            'diplome'     => $diplome
        ]);
    }

    /**
     * @Route("/{id}", name="apc_parcours_show", methods={"GET"})
     * @param ApcParcours $apcParcour
     *
     * @return Response
     */
    public function show(ApcParcours $apcParcour): Response
    {
        return $this->render('administration/apc/apc_parcours/show.html.twig', [
            'parcours' => $apcParcour,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="apc_parcours_edit", methods={"GET","POST"})
     * @param Request     $request
     * @param ApcParcours $apcParcour
     *
     * @return Response
     */
    public function edit(Request $request, ApcParcours $apcParcour): Response
    {
        $form = $this->createForm(ApcParcoursType::class, $apcParcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'apcParcours.edit.success.flash');

            return $this->redirectToRoute('administration_apc_parcours_show', ['id' => $apcParcour->getId()]);
        }

        return $this->render('administration/apc/apc_parcours/edit.html.twig', [
            'apc_parcour' => $apcParcour,
            'form'        => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="apc_parcours_delete", methods={"DELETE"})
     * @param Request     $request
     * @param ApcParcours $apcParcour
     *
     * @return Response
     */
    public function delete(Request $request, ApcParcours $apcParcour): Response
    {
        $diplome = $apcParcour->getDiplome();
        if ($this->isCsrfTokenValid('delete' . $apcParcour->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($apcParcour);
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'apcParcours.delete.success.flash');
        }
        $this->addFlashBag(Constantes::FLASHBAG_ERROR, 'apcParcours.delete.error.flash');

        return $this->redirectToRoute('administration_apc_referentiel_index', ['diplome' => $diplome->getId()]);
    }

    /**
     * @Route("/{id}/duplicate", name="apc_parcours_duplicate", methods="GET|POST")
     * @param ApcParcours $apcParcours
     *
     * @return Response
     */
    public function duplicate(ApcParcours $apcParcours): Response
    {
        $newApcParcours = clone $apcParcours;

        $this->entityManager->persist($newApcParcours);
        $this->entityManager->flush();
        $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'apcParcours.duplicate.success.flash');

        return $this->redirectToRoute('administration_apc_parcours_show', ['id' => $newApcParcours->getId()]);
    }
}