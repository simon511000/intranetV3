<?php
/*
 * Copyright (c) 2022. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/DTO/EvenementEdt.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 14/05/2022 10:52
 */

namespace App\DTO;

use App\Entity\Constantes;
use App\Entity\Groupe;
use App\Entity\Personnel;
use App\Entity\Semestre;
use Carbon\CarbonInterface;

class EvenementEdt
{
    public ?string $source = null;
    public ?int $id = null;

    public ?string $date = null;
    public ?string $jour = null;

    public ?CarbonInterface $heureDebut = null;
    public ?CarbonInterface $heureFin = null;
    public ?CarbonInterface $dateObjet = null;

    public ?string $matiere = '';
    public ?string $code_matiere = '';
    public ?string $salle = '';
    public ?string $personnel = null;
    public ?string $groupe = null;

    public ?Personnel $personnelObjet = null;
    public ?Groupe $groupeObjet = null;

    public ?int $groupeId = null;
    public ?string $typeIdMatiere = null;
    public ?string $type_cours = null;
    public ?string $texte = null;

    public ?Semestre $semestre = null;

    public ?string $gridStart = '';
    public ?string $gridEnd = '';

    public ?string $couleur = '';
    public bool $evaluation = false;

    /** @deprecated */
    public ?string $display = '';

    public ?string $codeelement = '';
    public ?int $ordreGroupe = 1;

    public function getDisplay(): void
    {
        // générer un affichage
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function getClassCss(): string
    {
        return strtolower($this->type_cours).'_'.$this->couleur;
    }

    public function getIdMatiere(): int
    {
        $t = explode('_', (string) $this->typeIdMatiere);

        return (int) $t[1];
    }

    public function getTypeMatiere(): string
    {
        $t = explode('_', (string) $this->typeIdMatiere);

        return $t[0];
    }

    public function debutToInt(): int
    {
        return Constantes::TAB_HEURES_INDEX[$this->heureDebut->format('H:i:s')];
    }

    public function largeur(): int
    {
        return match ($this->type_cours) {
            'TP', 'tp' => 1,
            'TD', 'td' => 2,
            default => 0,
        };
    }

    public function duree(): float|int
    {
        $d = $this->heureFin;
        $h = $d->diffInMinutes($this->heureDebut);

        return $h / 30;
    }

    public function texteEvt(): ?string
    {
        if (null !== $this->texte) {
            return $this->texte;
        }

        if ('' !== $this->matiere) {
            return $this->matiere;
        }

        return null;
    }

    public function couleurEdt(): string
    {
        return strtolower($this->type_cours).'_'.$this->couleur;
    }

    public function displayEdt(): string
    {
        if ('' === $this->personnel && null === $this->personnelObjet) {
            $inter = '';
        } elseif ('' !== $this->personnel) {
            $inter = $this->personnel;
        } else {
            $inter = $this->personnelObjet->getNom();
        }

        return $this->isEvaluation().'<br />'.$this->salle.'<br />'.$inter.'<br />'.$this->groupe;
    }

    public function isEvaluation(): string
    {
        if (false === $this->evaluation) {
            return $this->matiere;
        }

        return '* '.$this->matiere.' *';
    }
}
