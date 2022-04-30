<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Entity/Absence.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 23/10/2021 12:18
 */

namespace App\Entity;

use App\Entity\Traits\LifeCycleTrait;
use App\Entity\Traits\MatiereTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\AbsenceRepository;
use Carbon\CarbonInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Serializable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: AbsenceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Absence extends BaseEntity implements Serializable
{
    use UuidTrait;
    use LifeCycleTrait;
    use MatiereTrait;

    public final const STATUS_COLORS = [
        self::ABSENCE_JUSTIFIE => 'success',
        self::ABSENCE_INJUSTIFIEE => 'danger',
        self::ABSENCE_EN_ATTENTE => 'warning',
    ];
    public final const ABSENCE_JUSTIFIE = 'justifie';
    public final const ABSENCE_INJUSTIFIEE = 'injustifie';
    public final const ABSENCE_EN_ATTENTE = '-';

    #[Groups(groups: ['absences_administration'])]
    #[ORM\Column(name: 'dateHeure', type: \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE)]
    private ?CarbonInterface $dateHeure = null;

    #[Groups(groups: ['absences_administration'])]
    #[ORM\Column(name: 'duree', type: \Doctrine\DBAL\Types\Types::TIME_MUTABLE)]
    private ?CarbonInterface $duree = null;

    #[Groups(groups: ['absences_administration'])]
    #[ORM\Column(name: 'justifie', type: \Doctrine\DBAL\Types\Types::BOOLEAN)]
    private bool $justifie = false;

    #[MaxDepth(2)]
    #[Groups(groups: ['absences_administration'])]
    #[ORM\ManyToOne(targetEntity: Personnel::class)]
    private Personnel $personnel;

    #[MaxDepth(2)]
    #[Groups(groups: ['absences_administration'])]
    #[ORM\ManyToOne(targetEntity: Etudiant::class, inversedBy: 'absences')]
    private Etudiant $etudiant;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE, nullable: true)]
    private ?CarbonInterface $dateJustifie = null;

    #[ORM\ManyToOne(targetEntity: AnneeUniversitaire::class)]
    private ?AnneeUniversitaire $anneeUniversitaire = null;

    #[ORM\ManyToOne(targetEntity: Semestre::class, inversedBy: 'absences')]
    private ?Semestre $semestre = null;

    /**
     * Absence constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid(Uuid::uuid4());
    }

    public function setUuid(UuidInterface $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public static function getIconStatus(string $status): string
    {
        return isset(self::STATUS_COLORS[$status])
            ? sprintf('<i class="fas fa-circle text-%s me-1"></i> %s', self::STATUS_COLORS[$status], $status)
            : $status;
    }

    public function __clone()
    {
        $this->setUuid(Uuid::uuid4());
    }

    public function getDuree(): ?CarbonInterface
    {
        return $this->duree;
    }

    public function setDuree(CarbonInterface $duree): void
    {
        $this->duree = $duree;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): void
    {
        $this->etudiant = $etudiant;
    }

    public function getDateJustifie(): ?CarbonInterface
    {
        return $this->dateJustifie;
    }

    public function setDateJustifie(CarbonInterface $dateJustifie): self
    {
        $this->dateJustifie = $dateJustifie;

        return $this;
    }

    public function getJson(): array
    {
        return [
            'id' => $this->getId(),
            'justifie' => $this->isJustifie(),
            'uuidString' => $this->getUuidString(),
            'date' => null !== $this->getDateHeure() ? $this->getDateHeure()->format('d/m/Y') : '-',
            'heure' => null !== $this->getDateHeure() ? $this->getDateHeure()->format('H:i') : '-',
            'personnel' => null !== $this->getPersonnel() ? $this->getPersonnel()->getDisplay() : '-',
            'codeMatiere' => null !== $this->getIdMatiere(),
            'typeIdMatiere' => null !== $this->getTypeIdMatiere(),
        ];
    }

    public function getDateHeure(): ?CarbonInterface
    {
        return $this->dateHeure;
    }

    public function setDateHeure(CarbonInterface $dateHeure): void
    {
        $this->dateHeure = $dateHeure;
    }

    public function getPersonnel(): ?Personnel
    {
        return $this->personnel;
    }

    public function setPersonnel(?Personnel $personnel): void
    {
        $this->personnel = $personnel;
    }

    public function getAnneeUniversitaire(): ?AnneeUniversitaire
    {
        return $this->anneeUniversitaire;
    }

    public function setAnneeUniversitaire(?AnneeUniversitaire $anneeUniversitaire): self
    {
        $this->anneeUniversitaire = $anneeUniversitaire;

        return $this;
    }

    public function getJustifie(): ?bool
    {
        return $this->justifie;
    }

    public function isJustifie(): bool
    {
        return $this->justifie;
    }

    public function setJustifie(bool $justifie): void
    {
        $this->justifie = $justifie;
    }

    public function serialize(): string | null
    {
        //todo: a ajouter ou retirer ?
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($data): void
    {
        //todo: a ajouter ou retirer ?
    }

    public function getSemestre(): ?Semestre
    {
        return $this->semestre;
    }

    public function setSemestre(?Semestre $semestre): self
    {
        $this->semestre = $semestre;

        return $this;
    }
}
