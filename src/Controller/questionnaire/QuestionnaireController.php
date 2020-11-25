<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/questionnaire/QuestionnaireController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 24/11/2020 12:54

namespace App\Controller\questionnaire;

use App\Entity\Etudiant;
use App\Entity\QuestionnaireEtudiant;
use App\Entity\QuestionnaireEtudiantReponse;
use App\Entity\QuestionnaireQuestion;
use App\Entity\QuestionnaireQuestionnaireSection;
use App\Repository\EtudiantRepository;
use App\Repository\QuestionnaireEtudiantReponseRepository;
use App\Repository\QuestionnaireEtudiantRepository;
use App\Repository\QuestionnaireQualiteRepository;
use App\Repository\QuestionnaireQuestionRepository;
use App\Repository\QuestionnaireQuizzRepository;
use App\Repository\QuestionnaireReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QualiteController
 * @package App\Controller
 */
class QuestionnaireController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private QuestionnaireQualiteRepository $questionnaireQualiteRepository;

    private QuestionnaireQuizzRepository $questionnaireQuizzRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        QuestionnaireQualiteRepository $questionnaireQualiteRepository,
        QuestionnaireQuizzRepository $questionnaireQuizzRepository
    ) {
        $this->entityManager = $entityManager;
        $this->questionnaireQualiteRepository = $questionnaireQualiteRepository;
        $this->questionnaireQuizzRepository = $questionnaireQuizzRepository;
    }

    /**
     * @param QuestionnaireEtudiantRepository        $quizzEtudiantRepository
     * @param QuestionnaireEtudiantReponseRepository $quizzEtudiantReponseRepository
     * @param QuestionnaireQuestionnaireSection      $questionnaireSection
     *
     * @param                                        $type
     * @param Etudiant                               $etudiant
     *
     * @return Response
     */
    public function section(
        QuestionnaireEtudiantRepository $quizzEtudiantRepository,
        QuestionnaireEtudiantReponseRepository $quizzEtudiantReponseRepository,
        QuestionnaireQuestionnaireSection $questionnaireSection,
        $type,
        Etudiant $etudiant
    ): Response {

        switch ($type) {
            case 'quizz':
                $questionnaire = $questionnaireSection->getQuestionnaireQuizz()->getId();
                $quizzEtudiant = $quizzEtudiantRepository->findOneBy([
                    'questionnaireQuizz' => $questionnaire,
                    'etudiant'           => $etudiant->getId()
                ]);
                break;
            case 'qualite':
                $questionnaire = $questionnaireSection->getQuestionnaireQualite()->getId();
                $quizzEtudiant = $quizzEtudiantRepository->findOneBy([
                    'questionnaireQualite' => $questionnaire,
                    'etudiant'             => $etudiant->getId()
                ]);
                break;
        }

        if ($quizzEtudiant !== null) {
            $reponses = $quizzEtudiantReponseRepository->findByQuizzEtudiant($quizzEtudiant);
        } else {
            $reponses = [];
        }


        return $this->render('appEtudiant/qualite/section.html.twig', [
            'ordre'             => $questionnaireSection->getOrdre(),
            'section'           => $questionnaireSection->getSection(),
            // 'tPrevisionnel' => $previsionnelRepository->findByDiplomeArray($this->getConnectedUser()->getDiplome(),
            //     $this->dataUserSession->getAnneeUniversitaire()),
            'reponses'          => $reponses,
            'etudiant'          => $etudiant,
            'typeQuestionnaire' => $type
        ]);
    }

    /**
     * @Route("/api/ajax/reponse/{questionnaire}/{typeQuestionnaire}", name="app_etudiant_qualite_ajax_reponse",
     *                                                                 options={"expose"=true})
     * @param QuestionnaireQuestionRepository        $quizzQuestionRepository
     * @param QuestionnaireReponseRepository         $quizzReponseRepository
     * @param QuestionnaireEtudiantRepository        $quizzEtudiantRepository
     * @param QuestionnaireEtudiantReponseRepository $quizzEtudiantReponseRepository
     * @param Request                                $request
     * @param                                        $questionnaire
     *
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function sauvegardeReponse(
        EtudiantRepository $etudiantRepository,
        QuestionnaireQuestionRepository $quizzQuestionRepository,
        QuestionnaireReponseRepository $quizzReponseRepository,
        QuestionnaireEtudiantRepository $quizzEtudiantRepository,
        QuestionnaireEtudiantReponseRepository $quizzEtudiantReponseRepository,
        Request $request,
        $questionnaire,
        $typeQuestionnaire
    ): JsonResponse {
        $cleReponse = $request->request->get('cleReponse');
        $cleQuestion = $request->request->get('cleQuestion');
        $etudiant = $etudiantRepository->find($request->request->get('etudiant'));

        if ($etudiant !== null) {
            switch ($typeQuestionnaire) {
                case 'quizz':
                    $questionnaire = $this->questionnaireQuizzRepository->find($questionnaire);
                    $quizzEtudiant = $quizzEtudiantRepository->findOneBy([
                        'questionnaireQuizz' => $questionnaire->getId(),
                        'etudiant'           => $etudiant
                    ]);
                    break;
                case 'qualite':
                    $questionnaire = $this->questionnaireQualiteRepository->find($questionnaire);
                    $quizzEtudiant = $quizzEtudiantRepository->findOneBy([
                        'questionnaireQualite' => $questionnaire->getId(),
                        'etudiant'             => $etudiant
                    ]);
                    break;
            }

            if ($quizzEtudiant === null) {
                $quizzEtudiant = new QuestionnaireEtudiant($etudiant, $questionnaire, $typeQuestionnaire);
                $this->entityManager->persist($quizzEtudiant);
            }


            /** @var QuestionnaireEtudiantReponse $exist */
            $exist = $quizzEtudiantReponseRepository->findExistQuestion($cleQuestion, $quizzEtudiant->getId());

            $t = explode('_', $cleReponse);
            $question = $quizzQuestionRepository->find(substr($t[3], 1, strlen($t[0])));
            if (strpos($t[4], 'c') === 0) {
                $reponse = $quizzReponseRepository->find($t[5]);
            } else {
                $reponse = $quizzReponseRepository->find($t[4]);
            }

            if ($question !== null && $reponse !== null) {
                if ($exist === null) {
                    $qr = new QuestionnaireEtudiantReponse($quizzEtudiant);
                    $qr->setCleQuestion($cleQuestion);

                    if ($question->getType() === QuestionnaireQuestion::QUESTION_TYPE_QCM) {
                        $qr->setCleReponse(json_encode([$cleReponse]));
                        $qr->setValeur(json_encode([$reponse->getValeur()]));
                    } else {
                        $qr->setCleReponse($cleReponse);
                        $qr->setValeur($reponse->getValeur());
                    }

                    $this->entityManager->persist($qr);
                } else if ($question->getType() === QuestionnaireQuestion::QUESTION_TYPE_QCU || $question->getType() === QuestionnaireQuestion::QUESTION_TYPE_ECHELLE || $question->getType() === QuestionnaireQuestion::QUESTION_TYPE_YESNO) {
                    $exist->setCleReponse($cleReponse);
                    $exist->setValeur($reponse->getValeur());
                } elseif ($question->getType() === QuestionnaireQuestion::QUESTION_TYPE_QCM) {
                    //si c'est un QCM, on fait un tableau de réponse.
                    $cleReponses = json_decode($exist->getCleReponse(), false);
                    $valeurs = json_decode($exist->getValeur(), false);
                    $idCle = array_search($cleReponse, $cleReponses, true);
                    $idValeur = array_search($reponse->getValeur(), $valeurs, true);
                    if ($idCle !== false && $idValeur !== false) {
                        //réponse déjà présente on supprime
                        unset($cleReponses[$idCle], $valeurs[$idValeur]);
                        $cleReponses = array_values($cleReponses);
                        $valeurs = array_values($valeurs);
                    } else {
                        $cleReponses[] = $cleReponse;
                        $valeurs[] = $reponse->getValeur();
                    }

                    $exist->setCleReponse(json_encode($cleReponses));
                    $exist->setValeur(json_encode($valeurs));
                }
                $this->entityManager->flush();

                return $this->json(true, Response::HTTP_OK);
            }

            return $this->json(false, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/api/ajax/reponse-txt/{questionnaire}/{typeQuestionnaire}", name="app_etudiant_qualite_ajax_reponse_txt",
     *                                             options={"expose"=true})
     * @param QuestionnaireQuestionRepository        $quizzQuestionRepository
     * @param QuestionnaireEtudiantReponseRepository $quizzEtudiantReponseRepository
     * @param QuestionnaireEtudiantRepository        $quizzEtudiantRepository
     * @param Request                                $request
     * @param                                        $questionnaire
     *
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function sauvegardeReponseTxt(
        EtudiantRepository $etudiantRepository,
        QuestionnaireQuestionRepository $quizzQuestionRepository,
        QuestionnaireEtudiantReponseRepository $quizzEtudiantReponseRepository,
        QuestionnaireEtudiantRepository $quizzEtudiantRepository,
        Request $request,
        $questionnaire,
        $typeQuestionnaire
    ): JsonResponse {
        $cleQuestion = $request->request->get('cleQuestion');
        $etudiant = $etudiantRepository->find($request->request->get('etudiant'));

        if ($etudiant !== null) {
            switch ($typeQuestionnaire) {
                case 'quizz':
                    $questionnaire = $this->questionnaireQuizzRepository->find($questionnaire);
                    $quizzEtudiant = $quizzEtudiantRepository->findOneBy([
                        'questionnaireQuizz' => $questionnaire->getId(),
                        'etudiant'           => $etudiant
                    ]);
                    break;
                case 'qualite':
                    $questionnaire = $this->questionnaireQualiteRepository->find($questionnaire);
                    $quizzEtudiant = $quizzEtudiantRepository->findOneBy([
                        'questionnaireQualite' => $questionnaire->getId(),
                        'etudiant'             => $etudiant
                    ]);
                    break;
            }

            if ($quizzEtudiant === null) {
                $quizzEtudiant = new QuestionnaireEtudiant($etudiant, $questionnaire, $typeQuestionnaire);
                $this->entityManager->persist($quizzEtudiant);
            }
            /** @var QuestionnaireEtudiantReponse $exist */
            $exist = $quizzEtudiantReponseRepository->findExistQuestion($cleQuestion, $quizzEtudiant);

            $t = explode('_', $cleQuestion);
            $question = $quizzQuestionRepository->find(substr($t[3], 1, strlen($t[0])));
            if ($question !== null) {
                if ($exist === null) {
                    $qr = new QuestionnaireEtudiantReponse($quizzEtudiant);
                    $qr->setCleQuestion($cleQuestion);
                    $qr->setCleReponse(null);
                    $qr->setValeur($request->request->get('value'));
                    $this->entityManager->persist($qr);
                } else {
                    $exist->setValeur($request->request->get('value'));
                }
                $this->entityManager->flush();

                return $this->json(true, Response::HTTP_OK);
            }

            return $this->json(false, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}