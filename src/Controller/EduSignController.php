<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Classes\EduSign\Adapter\IntranetGroupeEduSignAdapter;
use App\Classes\EduSign\ApiEduSign;
use App\Classes\EduSign\CreateEnseignant;
use App\Classes\EduSign\FixCourses;
use App\Classes\EduSign\UpdateEdt;
use App\Classes\EduSign\UpdateEtudiant;
use App\Classes\EduSign\UpdateGroupe;
use App\Entity\Constantes;
use App\Entity\Departement;
use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\GroupeRepository;
use App\Repository\PersonnelDepartementRepository;
use App\Repository\PersonnelRepository;
use App\Repository\SemestreRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\throwException;

#[Route('/administratif/edusign')]
class EduSignController extends BaseController
{
    public function __construct(
        protected DiplomeRepository              $diplomeRepository,
        protected DepartementRepository          $departementRepository,
        protected PersonnelDepartementRepository $personnelDepartementRepository,
        protected PersonnelRepository            $personnelRepository,
        protected CreateEnseignant               $createEnseignant,
        protected ApiEduSign                     $apiEduSign,
        protected SemestreRepository             $semestreRepository,
        protected GroupeRepository               $groupeRepository,
    )
    {

    }

    #[Route('/', name: 'app_edu_sign')]
    public function index(Request $request): Response
    {
        $diplomes = $this->diplomeRepository->findAllWithEduSign();
        $departements = [];
        foreach ($diplomes as $diplome) {
            $departements[$diplome->getDepartement()->getId()] = $this->departementRepository->find($diplome->getDepartement()->getId());
        }

        if ($request->query->get('dept')) {
            $departement = $this->departementRepository->findOneBy(['id' => $request->query->get('dept')]);
        } else {
            $departement = $departements[array_key_first($departements)];
        }

        $personnelsDepartement = $this->personnelDepartementRepository->findBy(['departement' => $departement]);

        // Filter personnelsDepartement
        $filteredPersonnelsDepartement = array_filter($personnelsDepartement, function ($p) use ($departement) {
            $idEduSignArray = $p->getPersonnel()->getIdEduSign() ?? [];
            return empty($idEduSignArray) || !array_key_exists($departement->getId(), $idEduSignArray);
        });

        return $this->render('super-administration/edu_sign/index.html.twig', [
            'departements' => $departements,
            'departementSelect' => $departement ?? null,
            'personnelsDepartement' => $filteredPersonnelsDepartement,
        ]);
    }

    #[Route('/init/{id}', name: 'app_edu_sign_init')]
    public function init(?int $id, UpdateGroupe $updateGroupe, UpdateEtudiant $updateEtudiant)
    {
        if ($id !== 0) {
            $departement = $this->departementRepository->find($id);
            $diplomes = $this->diplomeRepository->findByDepartement($departement);
            $keyEduSign = null;
            foreach ($diplomes as $diplome) {
                if ($diplome->getKeyEduSign() !== null) {
                    $keyEduSign = $diplome->getKeyEduSign();
                }
            }
            $updateGroupe->update($keyEduSign);
            $updateEtudiant->update($keyEduSign);

            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'Les données ont été mises à jour sur EduSign');
        }

        return $this->redirectToRoute('app_edu_sign');
    }

    #[Route('/update/etudiants/{id}', name: 'app_edu_sign_update_etudiants')]
    public function updateEtudiants(?int $id, UpdateGroupe $updateGroupe, UpdateEtudiant $updateEtudiant)
    {
        if ($id !== 0) {
            $departement = $this->departementRepository->find($id);
            $diplomes = $this->diplomeRepository->findByDepartement($departement);
            $keyEduSign = null;
            foreach ($diplomes as $diplome) {
                if ($diplome->getKeyEduSign() !== null) {
                    $keyEduSign = $diplome->getKeyEduSign();
                }
            }

            $updateEtudiant->changeSemestre($keyEduSign);

            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'Les données ont été mises à jour sur EduSign');
        }

        return $this->redirectToRoute('app_edu_sign');
    }

    #[Route('/create-courses/{opt}/{id}', name: 'app_edu_sign_create_courses')]
    public function createCourses(?int $opt, ?int $id, UpdateEdt $updateEdt, FixCourses $fixCourses, MailerInterface $mailer): Response
    {
        if ($id !== null) {
            $departement = $this->departementRepository->find($id);
            $diplomes = $this->diplomeRepository->findByDepartement($departement);
            $keyEduSign = null;
            foreach ($diplomes as $diplome) {
                if ($diplome->getKeyEduSign() !== null) {
                    $keyEduSign = $diplome->getKeyEduSign();
                }
            }
            //créer les cours pour la semaine
            $updateEdt->update($keyEduSign, $opt);
//            dd($updateEdt->update($keyEduSign, $opt));

            $fixCourses->fixCourse($keyEduSign);

//            if ($updateEdt->update($keyEduSign, $opt) !== null) {
//                $data = $updateEdt->update($keyEduSign, $opt);
//            }
//            if ($fixCourses->fixCourse($keyEduSign) !== null) {
//                // ajouter le tableau retourné par la méthode fixCourse() dans le tableau data
//                $data['fix'] = $fixCourses->fixCourse($keyEduSign);
//            }
//            if (!empty($data)) {
//                // ajouter $departement->getLibelle() comme key du tableau data
//                $data['header']['dept'] = $departement->getLibelle();
//                $this->sendEmail($data, $mailer);
//            }
//                dd($data);

        }

        return $this->redirectToRoute('app_edu_sign');
    }

    #[Route('/create-personnel/{deptId}', name: 'app_edu_sign_create_personnel')]
    public function createPersonnel(?int $deptId, Request $request)
    {
        if ($deptId !== 0) {
            $departement = $this->departementRepository->find($deptId);
            $diplomes = $this->diplomeRepository->findByDepartement($departement);
            $keyEduSign = null;
            foreach ($diplomes as $diplome) {
                if ($diplome->getKeyEduSign() !== null) {
                    $keyEduSign = $diplome->getKeyEduSign();
                }
            }

            $personnelId = $request->get('personnel');

            $personnel = $this->personnelRepository->find($personnelId);

            $this->createEnseignant->update($personnel, $departement, $keyEduSign);

            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'Les données ont été mises à jour sur EduSign');
        }

        return $this->redirectToRoute('app_edu_sign');
    }

    #[Route('/update-annee/{id}', name: 'app_edu_sign_update_annee')]
    public function updateAnnee(?int $id, UpdateGroupe $updateGroupe, UpdateEtudiant $updateEtudiant)
    {
        if ($id !== 0) {
            $departement = $this->departementRepository->find($id);
            $diplomes = $this->diplomeRepository->findByDepartement($departement);
            $keyEduSign = null;
            foreach ($diplomes as $diplome) {
                if ($diplome->getKeyEduSign() !== null) {
                    $keyEduSign = $diplome->getKeyEduSign();
                }
            }
            $updateGroupe->update($keyEduSign);
            $updateEtudiant->update($keyEduSign);

            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'Les données ont été mises à jour sur EduSign');
        }

        return $this->redirectToRoute('app_edu_sign');
    }

    #[Route('/groupes/{id}', name: 'app_edu_sign_groupes')]
    public function showGroupes(?int $id)
    {
        $departement = $this->departementRepository->find($id);

        $diplomes = $this->diplomeRepository->findByDepartement($departement);
        $keyEduSign = null;
        foreach ($diplomes as $diplome) {
            if ($diplome->getKeyEduSign() !== null) {
                $keyEduSign = $diplome->getKeyEduSign();
                $groupesEduSign = $this->apiEduSign->getAllGroups($keyEduSign);

                $semestres = $this->semestreRepository->findByDiplome($diplome);

                foreach ($semestres as $semestre) {
                    $groupes[] = $semestre;
                }

                foreach ($semestres as $parent) {
                    // on vérifie si le groupe parent a un parcours pour récupérer les groupes
                    $parcours = $parent->getDiplome()->getApcParcours();
                    if ($parcours) {
                        $groupesElement = $parcours->getGroupes();
                    } else {
                        $groupesElement = $this->groupeRepository->findBySemestre($parent);
                    }
                    foreach ($groupesElement as $groupe) {
                        // on vérifie si le groupe est un TD ou un TP
                        if ($groupe->getTypeGroupe() !== null && ($groupe->getTypeGroupe()->getLibelle() === 'TD' || $groupe->getTypeGroupe()->getLibelle() === 'TP')) {
                            // on vérifie si le semestre du groupe est le même que le semestre parent
                            foreach ($groupe->getTypeGroupe()->getSemestres() as $semestre) {
                                if ($semestre === $parent) {
                                    // on ajoute le groupe dans le tableau
                                    $groupes[] = $groupe;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->render('super-administration/edu_sign/groupes.html.twig', [
            'departement' => $departement,
            'groupesEduSign' => $groupesEduSign ?? null,
            'groupes' => $groupes ?? null,
        ]);
    }

    private function sendEmail(array $data, MailerInterface $mailer)
    {
        $email = (new TemplatedEmail())
            ->from('no-reply@univ-reims.fr')
            ->to('cyndel.herolt@univ-reims.fr')
            ->subject($data['header']['type'])
            ->htmlTemplate('mails/edusign/update_bilan.html.twig')
            ->context([
                'dept' => $data['header']['dept'],
                'periode' => $data['header']['periode'],
                'success' => $data['success'] ?? [],
                'errors' => $data['error'] ?? [],
                'warnings' => $data['warning'] ?? [],
            ]);

        $mailer->send($email);
    }
}
