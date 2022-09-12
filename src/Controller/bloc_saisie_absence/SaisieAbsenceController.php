<?php
/*
 * Copyright (c) 2022. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/Controller/bloc_saisie_absence/SaisieAbsenceController.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 12/09/2022 12:13
 */

namespace App\Controller\bloc_saisie_absence;

use App\Classes\Etudiant\EtudiantAbsences;
use App\Classes\Matieres\TypeMatiereManager;
use App\Controller\BaseController;
use App\DTO\EvenementEdt;
use App\DTO\Matiere;
use App\Entity\Etudiant;
use App\Entity\Semestre;
use App\Repository\AbsenceRepository;
use App\Repository\TypeGroupeRepository;
use App\Utils\Tools;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use function count;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AbsenceController.
 */
#[Route(path: '/application/personnel/absence/ajax')]
#[IsGranted('ROLE_PERMANENT')]
class SaisieAbsenceController extends BaseController
{
    public function index(
        TypeMatiereManager $typeMatiereManager,
        TypeGroupeRepository $typeGroupeRepository,
        Semestre $semestre,
        ?Matiere $matiere = null,
        ?EvenementEdt $event = null
    ): Response {
        if (null !== $event) {
            $groupes = $typeGroupeRepository->findOneBy([
                'ordreSemestre' => $semestre->getOrdreLmd(),
                'diplome' => null === $semestre->getDiplome()->getParent() ? $semestre->getDiplome()->getId() : $semestre->getDiplome()->getParent()->getId(),
                'type' => $event->type_cours,
            ]);
        } else {
            $groupes = null;
        }

        if (true === $semestre->getDiplome()?->isApc()) {
            // todo: fusionner
            $typeGroupes = $typeGroupeRepository->findByDiplomeAndOrdreSemestre($semestre->getDiplome(), $semestre->getOrdreLmd());
        } else {
            $typeGroupes = $typeGroupeRepository->find($semestre);
        }

        return $this->render('bloc_saisie_absence/_saisie_absence.html.twig', [
            'matiere' => $matiere,
            'semestre' => $semestre,
            'matieres' => $typeMatiereManager->findBySemestre($semestre),
            'typeGroupes' => $typeGroupes,
            'event' => $event,
            'groupes' => $groupes,
            'options' => [
                'data-options' => [
                    'dateFormat' => 'd/m/Y',
                ],
            ],
        ]);
    }

    /**
     * @return JsonResponse|Response
     */
    #[Route(path: '/saisie/{matiere}/{etudiant}', name: 'application_personnel_absence_saisie_ajax', options: ['expose' => true], methods: 'POST')]
    public function ajaxSaisie(TypeMatiereManager $typeMatiereManager, EtudiantAbsences $etudiantAbsences, AbsenceRepository $absenceRepository, Request $request, string $matiere, Etudiant $etudiant): JsonResponse|Response
    {
        $mat = $typeMatiereManager->getMatiereFromSelect($matiere);
        $semestre = $etudiant->getSemestre();
        if (null !== $mat && null !== $semestre) {
            $dateHeure = Tools::convertDateHeureToObject($request->request->get('date'),
                $request->request->get('heure'));
            $absence = $absenceRepository->findBy([
                'idMatiere' => $mat->id,
                'typeMatiere' => $mat->typeMatiere,
                'etudiant' => $etudiant->getId(),
                'dateHeure' => $dateHeure,
                'anneeUniversitaire' => $etudiant->getSemestre() ? $etudiant->getSemestre()->getAnneeUniversitaire()->getId() : 0,
            ]);

            if ('saisie' === $request->get('action') && 0 === count($absence)) {
                if ($this->saisieAutorise($semestre->getOptNbJoursSaisieAbsence(), $dateHeure)) {
                    $etudiantAbsences->setEtudiant($etudiant);
                    $etudiantAbsences->addAbsence(
                        $dateHeure,
                        $mat,
                        $this->getUser()
                    );

                    $absences = $absenceRepository->getByMatiereArray(
                        $mat,
                        $this->getAnneeUniversitaire()
                    );

                    return $this->json($absences);
                }

                return new response('out', \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if (1 === count($absence)) {
                // un tableau, donc une absence ?
                $etudiantAbsences->removeAbsence($absence[0]);

                $absences = $absenceRepository->getByMatiereArray(
                    $mat,
                    $this->getAnneeUniversitaire()
                );

                return $this->json($absences);
            }
        }

        return new response('nok', \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function saisieAutorise(int $nbjour, CarbonInterface $datesymfony): bool
    {
        return 0 === $nbjour || $datesymfony->diffInDays(Carbon::now()) <= $nbjour;
    }
}
