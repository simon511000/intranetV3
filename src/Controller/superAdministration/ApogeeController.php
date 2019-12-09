<?php

// Copyright (C) 11 / 2019 | David annebicque | IUT de Troyes - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetv3/src/Controller/superAdministration/ApogeeController.php
// @author     David Annebicque
// @project intranetv3
// @date 25/11/2019 10:20
// @lastUpdate 23/11/2019 09:14

namespace App\Controller\superAdministration;

use App\Controller\BaseController;
use App\Entity\Adresse;
use App\Entity\Etudiant;
use App\MesClasses\Apogee\MyApogee;
use App\MesClasses\Tools;
use App\Repository\DiplomeRepository;
use App\Repository\EtudiantRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/super-administration/apogee")
 */
class ApogeeController extends BaseController
{
    /**
     * @Route("/", methods={"GET"}, name="sa_apogee_index")
     * @IsGranted("ROLE_SUPER_ADMIN")
     *
     * @param DiplomeRepository $diplomeRepository
     *
     * @return Response
     */
    public function index(DiplomeRepository $diplomeRepository): Response
    {
        return $this->render('super-administration/apogee/index.html.twig', [
            'diplomes' => $diplomeRepository->findAll()
        ]);
    }

    /**
     * @Route("/import/diplome/{type}", methods={"POST"}, name="sa_apogee_maj")
     * @IsGranted("ROLE_SUPER_ADMIN")
     *
     * @param Request            $request
     * @param DiplomeRepository  $diplomeRepository
     *
     * @param EtudiantRepository $etudiantRepository
     * @param                    $type
     *
     * @return Response
     * @throws Exception
     */
    public function importMaj(
        Request $request,
        DiplomeRepository $diplomeRepository,
        EtudiantRepository $etudiantRepository,
        $type
    ): Response {

        $diplome = $diplomeRepository->find($request->request->get('diplomeforce'));
        if ($diplome) {
            $etudiants = [];
            //requete pour récupérer les étudiants de la promo.
            //pour chaque étudiant, s'il existe, on update, sinon on ajoute (et si type=force).
            $stid = MyApogee::getEtudiantsDiplome($diplome);
            while ($row = $stid->fetch()) {
                if (Tools::convertDateToObject($row['DAT_MOD_IND'])->format('Y') === (string)$diplome->getAnneeUniversitaire()->getAnnee()) {
                    $dataApogee = MyApogee::transformeApogeeToArray($row);
                    $numEtudiant = $dataApogee['etudiant']['setNumEtudiant'];
                    $etudiant = $etudiantRepository->findOneBy(['numEtudiant' => $numEtudiant]);
                    if ($etudiant && $type === 'force') {
                        //todo: une classe ?
                        //on met à jour
                        $etudiant->updateFromApogee($dataApogee['etudiant']);
                        $etudiant->getAdresse()->updateFromApogee($dataApogee['adresse']);
                        $etudiants[$numEtudiant]['etat'] = 'maj';
                        $etudiants[$numEtudiant]['data'] = $etudiant;
                    } else {
                        //n'existe pas on ajoute.
                        $etudiant = new Etudiant();
                        $etudiant->updateFromApogee($dataApogee['etudiant']);
                        $adresse = new Adresse();
                        $adresse->updateFromApogee($dataApogee['adresse']);
                        $this->entityManager->persist($adresse);
                        $etudiant->setAdresse($adresse);
                        $this->entityManager->persist($etudiant);
                        $etudiants[$numEtudiant]['etat'] = 'add';
                        $etudiants[$numEtudiant]['data'] = $etudiant;
                    }
                }
            }
            $this->entityManager->flush();
            $this->addFlashBag('success', 'import.etudiant.apogee.ok');

            return $this->render('super-administration/apogee/confirmation.html.twig', [
                'etudiants' => $etudiants
            ]);
        }
        $this->addFlashBag('error', 'import.etudiant.apogee.erreur.diplome');

        return $this->redirectToRoute('sa_apogee_index');
    }

    /**
     * @Route("/import/etudiant", methods={"POST"}, name="sa_apogee_import_etudiant")
     * @IsGranted("ROLE_SUPER_ADMIN")
     *
     * @param Request            $request
     *
     * @param EtudiantRepository $etudiantRepository
     *
     * @return Response
     * @throws Exception
     */
    public function importEtudiant(Request $request, EtudiantRepository $etudiantRepository): Response
    {
        $listeetudiants = explode(';', $request->request->get('listeetudiants'));
        $etudiants = [];
        foreach ($listeetudiants as $etudiant) {
            $stid = MyApogee::getEtudiant($etudiant);

            while ($row = $stid->fetch()) {
                //requete pour récupérer les datas de l'étudiant et ajouter à la BDD.
                $dataApogee = MyApogee::transformeApogeeToArray($row);
                $numEtudiant = $dataApogee['etudiant']['setNumEtudiant'];

                //Stocker réponse dans un tableau pour page confirmation
                $etudiant = $etudiantRepository->findOneBy(['numEtudiant' => $numEtudiant]);
                if ($etudiant) {
                    $etudiants[$numEtudiant]['etat'] = 'deja';
                    $etudiants[$numEtudiant]['data'] = $etudiant;
                } else {
                    //n'existe pas on ajoute.
                    $etudiant = new Etudiant();
                    $etudiant->updateFromApogee($dataApogee['etudiant']);
                    $adresse = new Adresse();
                    $adresse->updateFromApogee($dataApogee['adresse']);
                    $this->entityManager->persist($adresse);
                    $etudiant->setAdresse($adresse);
                    $this->entityManager->persist($etudiant);
                    $etudiants[$numEtudiant]['etat'] = 'add';
                    $etudiants[$numEtudiant]['data'] = $etudiant;
                }
            }
        }
        $this->entityManager->flush();
        $this->addFlashBag('success', 'import.etudiant.apogee.ok');

        return $this->render('super-administration/apogee/confirmation.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    /**
     * @Route("/ldap")
     */
    public function ldap() {
        echo '<h3>requête de test de LDAP</h3>';
        echo 'Connexion ...';
        $ds=ldap_connect("ldap://ldap.univ-reims.fr");  // doit être un serveur LDAP valide !
        echo 'Le résultat de connexion est ' . $ds . '<br />';

        if ($ds) {
            echo 'Liaison ...';
            $ldaprdn = 'uid=app-intranet-iut,ou=account,ou=app,dc=univ-reims,dc=fr';
            $ldappass = 'heXzHr7p7MKuccQ2UqKu';
            $r = ldap_bind($ds, $ldaprdn, $ldappass);     // connexion anonyme, typique
            // pour un accès en lecture seule.
            echo 'Le résultat de connexion est ' . $r . '<br />';

            echo 'Recherchons (sn=S*) ...';
            // Recherche par nom de famille
            $sr=ldap_search($ds, "ou=people,dc=univ-reims,dc=fr", "sn=A*");
            echo 'Le résultat de la recherche est ' . $sr . '<br />';

            echo 'Le nombre d\'entrées retournées est ' . ldap_count_entries($ds,$sr)
                . '<br />';

            echo 'Lecture des entrées ...<br />';
            $info = ldap_get_entries($ds, $sr);
            echo 'Données pour ' . $info["count"] . ' entrées:<br />';

            for ($i=0; $i<$info["count"]; $i++) {
                echo 'dn est : ' . $info[$i]["dn"] . '<br />';
                echo 'premiere entree cn : ' . $info[$i]["cn"][0] . '<br />';
                echo 'premier email : ' . $info[$i]["mail"][0] . '<br />';
            }

            echo 'Fermeture de la connexion';
            ldap_close($ds);

        } else {
            echo '<h4>Impossible de se connecter au serveur LDAP.</h4>';
        }
    }


}
