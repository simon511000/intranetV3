<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Entity/Alternance.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 29/06/2021 17:30
 */

namespace App\Entity;

use App\Entity\Traits\LifeCycleTrait;
use App\Repository\AlternanceRepository;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: AlternanceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Alternance extends BaseEntity
{
    use LifeCycleTrait;

    public final const ALTERNANCE_APPRENTISSAGE = 'apprentissage';
    public final const ALTERNANCE_PROFESSIONALISATION = 'professionalisation';
    public final const ALTERNANCE_ETAT_INITIALISE = 'init';
    public final const ALTERNANCE_ETAT_COMPLETE = 'complete';
    public final const ALTERNANCE_ETAT_VALIDE = 'valide';
    public final const ALTERNANCE_ETAT_SANS = 'sans';

    #[Groups(groups: ['alternance_administration'])]
    #[ORM\OneToOne(targetEntity: Entreprise::class, cascade: ['persist', 'remove'])]
    private ?Entreprise $entreprise = null;

    #[MaxDepth(2)]
    #[Groups(groups: ['alternance_administration'])]
    #[ORM\OneToOne(targetEntity: Contact::class, cascade: ['persist', 'remove'])]
    private ?Contact $tuteur = null;

    #[MaxDepth(2)]
    #[Groups(groups: ['alternance_administration'])]
    #[ORM\ManyToOne(targetEntity: Etudiant::class, inversedBy: 'alternances')]
    private ?Etudiant $etudiant = null;

    #[MaxDepth(2)]
    #[Groups(groups: ['alternance_administration'])]
    #[ORM\ManyToOne(targetEntity: Personnel::class, inversedBy: 'alternances')]
    private ?Personnel $tuteurUniversitaire = null;

    #[Groups(groups: ['alternance_administration'])]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 20)]
    private ?string $typeContrat;

    #[ORM\ManyToOne(targetEntity: Annee::class, inversedBy: 'alternances')]
    private ?Annee $annee = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 10)]
    private ?string $etat = null;

    #[Groups(groups: ['alternance_administration'])]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATE_MUTABLE, nullable: true)]
    private ?CarbonInterface $dateDebut = null;

    #[Groups(groups: ['alternance_administration'])]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATE_MUTABLE, nullable: true)]
    private ?CarbonInterface $dateFin = null;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\AlternanceFicheSuivi>|\App\Entity\AlternanceFicheSuivi[]
     */
    #[ORM\OneToMany(mappedBy: 'alternance', targetEntity: AlternanceFicheSuivi::class)]
    private Collection $alternanceFicheSuivis;

    #[ORM\ManyToOne(targetEntity: AnneeUniversitaire::class)]
    private ?AnneeUniversitaire $anneeUniversitaire = null;

    #[ORM\OneToOne(inversedBy: 'alternance', targetEntity: Adresse::class, cascade: ['persist', 'remove'])]
    private ?Adresse $adresseAlternance = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    private ?string $sujet = null;

    public function __construct()
    {
        $this->typeContrat = self::ALTERNANCE_PROFESSIONALISATION;
        $this->alternanceFicheSuivis = new ArrayCollection();
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getTuteur(): ?Contact
    {
        return $this->tuteur;
    }

    public function setTuteur(?Contact $tuteur): self
    {
        $this->tuteur = $tuteur;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getTuteurUniversitaire(): ?Personnel
    {
        return $this->tuteurUniversitaire;
    }

    public function setTuteurUniversitaire(?Personnel $tuteurUniversitaire): self
    {
        $this->tuteurUniversitaire = $tuteurUniversitaire;

        return $this;
    }

    public function getTypeContrat(): ?string
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(string $typeContrat): self
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }

    public function getAnnee(): ?Annee
    {
        return $this->annee;
    }

    public function setAnnee(?Annee $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateDebut(): ?CarbonInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(CarbonInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?CarbonInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(CarbonInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getAlternanceFicheSuivis(): Collection
    {
        return $this->alternanceFicheSuivis;
    }

    public function addAlternanceFicheSuivi(AlternanceFicheSuivi $alternanceFicheSuivi): self
    {
        if (!$this->alternanceFicheSuivis->contains($alternanceFicheSuivi)) {
            $this->alternanceFicheSuivis[] = $alternanceFicheSuivi;
            $alternanceFicheSuivi->setAlternance($this);
        }

        return $this;
    }

    public function removeAlternanceFicheSuivi(AlternanceFicheSuivi $alternanceFicheSuivi): self
    {
        if ($this->alternanceFicheSuivis->contains($alternanceFicheSuivi)) {
            $this->alternanceFicheSuivis->removeElement($alternanceFicheSuivi);
            // set the owning side to null (unless already changed)
            if ($alternanceFicheSuivi->getAlternance() === $this) {
                $alternanceFicheSuivi->setAlternance(null);
            }
        }

        return $this;
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

    public function getAdresseAlternance(): ?Adresse
    {
        return $this->adresseAlternance;
    }

    public function setAdresseAlternance(?Adresse $adresseAlternance): self
    {
        $this->adresseAlternance = $adresseAlternance;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }
}
