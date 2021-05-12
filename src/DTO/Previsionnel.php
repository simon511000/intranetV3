<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/DTO/Previsionnel.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 11/05/2021 08:46
 */

namespace App\DTO;


use App\Entity\Constantes;


class Previsionnel
{
    public int $id;
    public int $personnel_id;
    public int $matiere_id;
    public string $type_matiere;
    public float $nbHCm;
    public float $nbHTd;
    public float $nbHTp;
    public int $nbGrCm;
    public int $nbGrTd;
    public int $nbGrTp;
    public int $annee;
    public bool $referent;
    public string $matiere_libelle;
    public string $matiere_code;
    public string $matiere_code_element;
    public string $personnel_nom;
    public string $personnel_prenom;
    public int $personnel_numeroHarpege;
    public string $personnel_mail;
    public float $nbHeuresService;
    public int $semestre_id;
    public string $semestre_libelle;
    public int $annee_id;
    public string $annee_libelle;
    public int $diplome_id;
    public string $diplome_libelle;
    public string $annee_code_etape;
    public string $annee_libelle_long;

    public function getNbSeanceCm(): string
    {
        return number_format($this->nbHCm / Constantes::DUREE_COURS, 2);
    }

    public function getNbSeanceTd(): string
    {
        return number_format($this->nbHTd / Constantes::DUREE_COURS, 2);
    }

    public function getNbSeanceTp(): string
    {
        return number_format($this->nbHTp / Constantes::DUREE_COURS, 2);
    }

    /**
     * @return float
     */
    public function getTotalEqTd(): float
    {
        return $this->getTotalHCm() * Constantes::MAJORATION_CM + $this->getTotalHTd() + $this->getTotalHTp();
    }

    /**
     * @return float
     */
    public function getTotalHCm(): float
    {
        return $this->nbHCm * $this->nbGrCm;
    }

    /**
     * @return float
     */
    public function getTotalHTd(): float
    {
        return $this->nbHTd * $this->nbGrTd;
    }

    /**
     * @return float
     */
    public function getTotalHTp(): float
    {
        return $this->nbHTp * $this->nbGrTp;
    }

    public function getTotalEtudiant(): float
    {
        return $this->nbHCm + $this->nbHTd + $this->nbHTp;
    }

    public function getDisplay()
    {
        return $this->matiere_code . ' | ' . $this->matiere_libelle;
    }

    public function getTypeIdMatiere()
    {
        return $this->type_matiere . '_' . $this->matiere_id;
    }
}