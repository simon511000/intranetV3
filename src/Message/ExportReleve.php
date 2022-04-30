<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Message/ExportReleve.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 07/02/2021 10:59
 */

namespace App\Message;

class ExportReleve
{
    public function __construct(private readonly int $semestre, private readonly int $anneeUniversitaire, private readonly int $personnel)
    {
    }

    public function getSemestre(): int
    {
        return $this->semestre;
    }

    public function getAnneeUniversitaire(): int
    {
        return $this->anneeUniversitaire;
    }

    public function getPersonnel(): int
    {
        return $this->personnel;
    }
}
