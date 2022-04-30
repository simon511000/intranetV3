<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Classes/Previsionnel/PrevisionnelSaeManager.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 31/08/2021 22:50
 */

namespace App\Classes\Previsionnel;

use App\Adapter\PrevisionnelSaeAdapter;
use App\DTO\PrevisionnelCollection;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Personnel;
use App\Entity\Semestre;
use App\Repository\PrevisionnelSaeRepository;

class PrevisionnelSaeManager extends AbstractPrevisionnelManager implements PrevisionnelManagerInterface
{
    public final const TYPE = 'sae';

    public function __construct(private readonly PrevisionnelSaeRepository $previsionnelRepository, private readonly PrevisionnelSaeAdapter $previsionnelSaeAdapter)
    {
    }

    public function getPrevisionnelPersonnelAnnee(Personnel $personnel, int $annee = 0): PrevisionnelCollection
    {
        $data = $this->previsionnelRepository->findPrevisionnelEnseignantComplet($personnel, $annee);

        return $this->previsionnelSaeAdapter->collection($data);
    }

    public function getPrevisionnelPersonnelDepartementAnnee(
        Personnel $personnel,
        Departement $departement,
        int $annee = 0
    ): PrevisionnelCollection {
        $previs = $this->previsionnelRepository->findPrevisionnelEnseignantDepartementAnnee($personnel, $departement,
            $annee);

        return $this->previsionnelSaeAdapter->collection($previs);
    }

    public function findPrevisionnelMatiere(string | int $matiere, int $anneePrevisionnel): PrevisionnelCollection
    {
        $data = $this->previsionnelRepository->findPrevisionnelMatiere($matiere, $anneePrevisionnel);

        return $this->previsionnelSaeAdapter->collection($data);
    }

    public function findPrevisionnelMatierePersonnelAnnee(
        string | int $matiere,
        Personnel $personnel,
        int $anneePrevisionnel
    ): PrevisionnelCollection {
        $data = $this->previsionnelRepository->findPrevisionnelMatierePersonnelAnnee($matiere, $personnel,
            $anneePrevisionnel);

        return $this->previsionnelSaeAdapter->collection($data);
    }

    public function getPrevisionnelSemestre(Semestre $semestre, int $annee = 0): PrevisionnelCollection
    {
        $data = $this->previsionnelRepository->findPrevisionnelSemestre($semestre, $annee);

        return $this->previsionnelSaeAdapter->collection($data);
    }

    public function getPrevisionnelPersonnelSemestre(Personnel $personnel, Semestre $semestre, int $annee): PrevisionnelCollection
    {
        $data = $this->previsionnelRepository->findPrevisionnelPersonnelSemestre($personnel, $semestre, $annee);

        return $this->previsionnelSaeAdapter->collection($data);
    }

    public function findByDepartement(Departement $departement, int $annee = 0): PrevisionnelCollection
    {
        $data = $this->previsionnelRepository->findByDepartement($departement, $annee);

        return $this->previsionnelSaeAdapter->collection($data);
    }

    public function findByDiplome(Diplome $diplome, int $annee = 0): PrevisionnelCollection
    {
        $data = $this->previsionnelRepository->findByDiplome($diplome, $annee);

        return $this->previsionnelSaeAdapter->collection($data);
    }

    public function findByDiplomeToDelete(Diplome $diplome, int $annee = 0): array
    {
        return $this->previsionnelRepository->findByDiplomeToDelete($diplome, $annee);
    }
}
