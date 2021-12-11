<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/DTO/MoyenneUeApc.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 06/12/2021 16:26
 */

namespace App\DTO;

class MoyenneUeApc
{
    public \App\Entity\Ue $ue;
    public float $coefficient = 0;
    public array $matieres = [];
    public float $totalCoefficients = 0;
    public float $totalMoyennes = 0;
    public float $moyennePac = 0;
    public float $moyenne = 0;

    public function __construct(\App\Entity\Ue $ue, array $matieres) {
        $this->ue = $ue;
        foreach ($matieres as $matiere) {
            $this->matieres[$matiere->codeElement] = ['coefficient' => 0, 'moyenne' => 0];//objet
        }
    }
}
