<?php
/*
 * Copyright (C) 7 / 2019 | David annebicque | IUT de Troyes - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetv3/src/Controller/administration/PpnController.php
 * @author     David Annebicque
 * @project intranetv3
 * @date 7/12/19 11:23 AM
 * @lastUpdate 7/12/19 11:21 AM
 */

namespace App\Controller\administration;

use App\Controller\BaseController;
use App\Entity\Constantes;
use App\Entity\Ppn;
use App\Form\PpnType;
use App\Repository\PpnRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administration/programme-pedagogique")
 */
class PpnController extends BaseController
{
    /**
     * @Route("/", name="administration_ppn_index", methods="GET")
     * @param PpnRepository $ppnRepository
     *
     * @return Response
     */
    public function index(PpnRepository $ppnRepository): Response
    {
        return $this->render('administration/ppn/index.html.twig', ['ppns' => $ppnRepository->findAll()]);
    }

    /**
     * @Route("/export.{_format}", name="administration_ppn_export", methods="GET",
     *                             requirements={"_format"="csv|xlsx|pdf"})
     */
    public function export(): Response
    {
        //save en csv
        return new Response('', Response::HTTP_OK);
    }

    /**
     * @Route("/copie", name="administration_ppn_copie_integrale", methods="POST")
     *
     * @param PpnRepository $ppnRepository
     * @param Request       $request
     *
     * @return Response
     * @throws Exception
     */
    public function copieIntegrale(PpnRepository $ppnRepository, Request $request): Response
    {
        $ppnOrigine = $ppnRepository->find($request->request->get('ppn_origine'));
        $ppnDest = $ppnRepository->find($request->request->get('ppn_dest'));

        if ($ppnDest !== null && $ppnOrigine !== null) {
            //effacer contenu PPN de destination
            foreach ($ppnDest->getMatieres() as $matiere) {
                $this->entityManager->remove($matiere);
            }
            $this->entityManager->flush();

            //Copie PPN
            foreach ($ppnOrigine->getMatieres() as $matiere) {
                $newMatiere = clone $matiere;
                $newMatiere->setPpn($ppnDest);
                $ppnDest->addMatiere($newMatiere);
                $this->entityManager->persist($newMatiere);
                $this->entityManager->persist($ppnDest);
            }
            $this->entityManager->flush();

            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'ppn.copie.integrale.success.flash');

            return $this->redirectToRoute('administration_ppn_index');
        }

        throw new Exception('Pas de PPN trouvé');
    }

    /**
     * @Route("/new", name="administration_ppn_new", methods="GET|POST")
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $ppn = new Ppn();
        $form = $this->createForm(PpnType::class, $ppn, [
            'departement' => $this->dataUserSession->getDepartement(),
            'attr'        => [
                'data-provide' => 'validation'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ppn);
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'ppn.add.success.flash');

            return $this->redirectToRoute('administration_ppn_index');
        }

        return $this->render('administration/ppn/new.html.twig', [
            'ppn'  => $ppn,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_ppn_show", methods="GET")
     * @param Ppn $ppn
     *
     * @return Response
     */
    public function show(Ppn $ppn): Response
    {
        return $this->render('administration/ppn/show.html.twig', ['ppn' => $ppn]);
    }

    /**
     * @Route("/{id}/edit", name="administration_ppn_edit", methods="GET|POST")
     * @param Request $request
     * @param Ppn     $ppn
     *
     * @return Response
     */
    public function edit(Request $request, Ppn $ppn): Response
    {
        $form = $this->createForm(PpnType::class, $ppn, [
            'departement' => $this->dataUserSession->getDepartement(),
            'attr'        => [
                'data-provide' => 'validation'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'ppn.edit.success.flash');

            return $this->redirectToRoute('administration_ppn_index');
        }

        return $this->render('administration/ppn/edit.html.twig', [
            'ppn'  => $ppn,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_ppn_delete", methods="DELETE")
     * @param Request $request
     * @param Ppn     $ppn
     *
     * @return Response
     */
    public function delete(Request $request, Ppn $ppn): Response
    {
        //suppression uniquement si vide.
        //feature: gérer une suppression plus complete en super-admin
        $id = $ppn->getId();
        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $this->entityManager->remove($ppn);
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'ppn.delete.success.flash');

            return $this->json($id, Response::HTTP_OK);
        }

        $this->addFlashBag(Constantes::FLASHBAG_ERROR, 'ppn.delete.error.flash');

        return $this->json(false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/{id}/duplicate", name="administration_ppn_duplicate", methods="GET|POST")
     * @param Ppn $ppn
     *
     * @return Response
     */
    public function duplicate(Ppn $ppn): Response
    {
        $newPpn = clone $ppn;

        $this->entityManager->persist($newPpn);
        $this->entityManager->flush();
        $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'ppn.duplicate.success.flash');

        return $this->redirectToRoute('administration_ppn_edit', ['id' => $newPpn->getId()]);
    }


}
