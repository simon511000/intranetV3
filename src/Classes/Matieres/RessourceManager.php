<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Classes/Matieres/RessourceManager.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 24/10/2021 11:36
 */

namespace App\Classes\Matieres;

use App\Adapter\MatiereRessourceAdapter;
use App\DTO\Matiere;
use App\DTO\MatiereCollection;
use App\Entity\ApcRessource;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Semestre;
use App\Repository\ApcRessourceRepository;
use App\Utils\Tools;
use Doctrine\ORM\EntityManagerInterface;

class RessourceManager extends AbstractMatiereManager implements MatiereInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly ApcRessourceRepository $apcRessourceRepository, private readonly MatiereRessourceAdapter $ressourceAdapter)
    {
    }

    public function find(int | string $id): ?Matiere
    {
        $matiere = $this->apcRessourceRepository->find($id);

        return $this->ressourceAdapter->single($matiere);
    }

    public function findBySemestre(Semestre $semestre): MatiereCollection
    {
        $data = $this->apcRessourceRepository->findBySemestre($semestre);

        return $this->ressourceAdapter->collection($data);
    }

    public function findByDepartement(Departement $departement): MatiereCollection
    {
        $data = $this->apcRessourceRepository->findByDepartement($departement);

        return $this->ressourceAdapter->collection($data);
    }

    public function findByDiplome(Diplome $diplome): MatiereCollection
    {
        $data = $this->apcRessourceRepository->findByDiplome($diplome);

        return $this->ressourceAdapter->collection($data);
    }

    public function findByCodeApogee(string $code): ?Matiere
    {
        $matiere = $this->apcRessourceRepository->findOneBy(['codeElement' => $code]);

        return $this->ressourceAdapter->single($matiere);
    }

    public function update(string $name, mixed $value, ApcRessource $apcRessource): bool
    {
        $method = 'set'.$name;
        if (method_exists($apcRessource, $method)) {
            $apcRessource->$method(Tools::convertToFloat($value));
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
