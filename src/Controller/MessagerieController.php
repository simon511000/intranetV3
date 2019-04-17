<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Message;
use App\Entity\MessageDestinataire;
use App\Entity\Personnel;
use App\MesClasses\MyMessagerie;
use App\Repository\MessageDestinataireEtudiantRepository;
use App\Repository\MessageDestinatairePersonnelRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessagerieController
 * @package App\Controller
 * @Route("/messagerie")
 *
 */
class MessagerieController extends BaseController
{
    /**
     * @Route("/", name="messagerie_index")
     */
    public function index() :Response
    {
        return $this->render('messagerie/index.html.twig', [
        ]);
    }

    /**
     * @Route("/ecrire", name="messagerie_nouveau", options={"expose":true})
     */
    public function nouveauMessage() :Response
    {
        return $this->render('messagerie/nouveauMessage.html.twig', [
        ]);
    }

    /**
     * @Route("/starred", name="messagerie_starred", options={"expose":true})
     */
    public function starred(MessageDestinatairePersonnelRepository $messagePersonnelRepository,
        MessageDestinataireEtudiantRepository $messageEtudiantRepository, Request $request) {
        $message = $request->request->get('message');
        $valeur = $request->request->get('valeur') === 'false' ? false : true;


        if ($this->getConnectedUser() instanceof Etudiant) {
            $messageDest = $messageEtudiantRepository->find($message);

        } elseif ($this->getConnectedUser() instanceof Personnel) {
            $messageDest = $messagePersonnelRepository->find($message);
        }

        if( $messageDest !== null) {
            $messageDest->setStarred($valeur);
            $this->entityManager->persist($messageDest);
            $this->entityManager->flush();

            return $this->json(true, Response::HTTP_OK);
        }
        return $this->json(false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    /**
     * @param MessageDestinatairePersonnelRepository $messagePersonnelRepository
     * @param MessageDestinataireEtudiantRepository  $messageEtudiantRepository
     * @param                                        $filtre
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/filtre/{filtre}", name="messagerie_filtre", options={"expose"=true})
     *
     */
    public function filtre(
        MessageRepository $messageRepository,
        MessageDestinatairePersonnelRepository $messagePersonnelRepository,
        MessageDestinataireEtudiantRepository $messageEtudiantRepository,
        $filtre
    ): Response {

        if ($filtre === 'sent'){
            $messages = $messageRepository->findBy(['expediteur' => $this->getConnectedUser(), 'etat' => 'E']);
        } elseif ($filtre === 'draft') {
            $messages = $messageRepository->findBy(['expediteur' => $this->getConnectedUser(), 'etat' => 'D']);
        } else {
            if ($this->getConnectedUser() instanceof Etudiant) {
                $messages = $messageEtudiantRepository->findLast($this->getConnectedUser(), 0, $filtre);
            } elseif ($this->getConnectedUser() instanceof Personnel) {
                $messages = $messagePersonnelRepository->findLast($this->getConnectedUser(), 0, $filtre);
            } else {
                $messages = null;
            }
        }

        return $this->render('messagerie/listeMessages.html.twig', [
            'filtre' => $filtre,
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/liste-messages/{filtre}/{page}", name="messagerie_liste_messages", options={"expose":true})
     * @param MessageDestinatairePersonnelRepository $messagePersonnelRepository
     * @param MessageDestinataireEtudiantRepository  $messageEtudiantRepository
     * @param int                                    $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeMessages(
        MessageRepository $messageRepository,
        MessageDestinatairePersonnelRepository $messagePersonnelRepository,
        MessageDestinataireEtudiantRepository $messageEtudiantRepository,
        $filtre = 'all',
        $page = 0
    ): Response {
        if ($filtre === 'send'){
            $messages = $messageRepository->findBy(['expediteur' => $this->getConnectedUser(), 'etat' => 'E']);
        } elseif ($filtre === 'draft') {
            $messages = $messageRepository->findBy(['expediteur' => $this->getConnectedUser(), 'etat' => 'D']);
        } else {
            if ($this->getConnectedUser() instanceof Etudiant) {
                $messages = $messageEtudiantRepository->findLast($this->getConnectedUser(), 0, $filtre);
            } elseif ($this->getConnectedUser() instanceof Personnel) {
                $messages = $messagePersonnelRepository->findLast($this->getConnectedUser(), 0, $filtre);
            } else {
                $messages = null;
            }
        }

        return $this->render('messagerie/listeMessages.html.twig', [
            'filtre' => $filtre,
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/envoyer", name="messagerie_sent", methods={"POST"}, options={"expose":true})
     */
    public function messageSend(Request $request, MyMessagerie $messagerie) {
        //todo: mercure pour notification...
        $destinataires = explode(',', $request->request->get('destinataires'));
        $sujet = $request->request->get('sujet');
        $copie = explode(',', $request->request->get('copie'));
        $message = $request->request->get('message');

        $messagerie->setMessage($sujet, $message, $this->getConnectedUser());
        $messagerie->sendToDestinataires($destinataires, $this->dataUserSession->getDepartement());


        if ($copie !== null) {
            $messagerie->setCopie($copie);
        }

        $messagerie->sendSynthese();

        return $this->json('ok', 200);
    }

    /**
     * @Route("/{message}", name="messagerie_message", options={"expose":true})
     * @param MessageDestinatairePersonnelRepository $messagePersonnelRepository
     * @param MessageDestinataireEtudiantRepository  $messageEtudiantRepository
     * @param Message                                $message
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function message(
        MessageDestinatairePersonnelRepository $messagePersonnelRepository,
        MessageDestinataireEtudiantRepository $messageEtudiantRepository,
        Message $message
    ): Response {
        if ($this->getConnectedUser() instanceof Etudiant) {
            $messaged = $messageEtudiantRepository->findDest($this->getConnectedUser(), $message);
        } elseif ($this->getConnectedUser() instanceof Personnel) {
            $messaged = $messagePersonnelRepository->findDest($this->getConnectedUser(), $message);
        } else {
            return $this->redirectToRoute('erreur_666');
        }

        if ($messaged->getEtat() === 'U') {
            $messaged->setEtat('R');
            $messaged->setDateLu(new \DateTime('now'));
            $this->entityManager->persist($messaged);
            $this->entityManager->flush();
        }

        return $this->render('messagerie/message.html.twig', [
            'message' => $message
        ]);
    }
}
