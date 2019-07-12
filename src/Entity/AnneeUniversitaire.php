<?php
/**
 * Copyright (C) 7 / 2019 | David annebicque | IUT de Troyes - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetv3/src/Entity/AnneeUniversitaire.php
 * @author     David Annebicque
 * @project intranetv3
 * @date 7/12/19 11:23 AM
 * @lastUpdate 4/18/19 9:03 AM
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnneeUniversitaireRepository")
 */
class AnneeUniversitaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"annee_universitaire"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"annee_universitaire"})
     */
    private $annee;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"annee_universitaire"})
     */
    private $commentaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Departement", mappedBy="anneeUniversitairePrepare")
     */
    private $departements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Calendrier", mappedBy="anneeUniversitaire")
     */
    private $calendriers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CreneauCours", mappedBy="anneeUniversitaire")
     */
    private $creneauCours;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Scolarite", mappedBy="anneeUniversitaire")
     */
    private $scolarites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScolaritePromo", mappedBy="anneeUniversitaire")
     */
    private $scolaritePromos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Diplome", mappedBy="anneeUniversitaire")
     */
    private $diplomes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Disponibilite", mappedBy="anneeUniversitaire")
     */
    private $disponibilites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProgressionPedagogique", mappedBy="anneeUniversitaire")
     */
    private $progressionPedagogiques;

    public function __construct()
    {
        $this->setAnnee(date('Y'));
        $this->departements = new ArrayCollection();
        $this->calendriers = new ArrayCollection();
        $this->creneauCours = new ArrayCollection();
        $this->scolarites = new ArrayCollection();
        $this->scolaritePromos = new ArrayCollection();
        $this->diplomes = new ArrayCollection();
        $this->disponibilites = new ArrayCollection();
        $this->progressionPedagogiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Collection|Departement[]
     */
    public function getDepartements(): Collection
    {
        return $this->departements;
    }

    public function addDepartement(Departement $departement): self
    {
        if (!$this->departements->contains($departement)) {
            $this->departements[] = $departement;
            $departement->setAnneeUniversitairePrepare($this);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): self
    {
        if ($this->departements->contains($departement)) {
            $this->departements->removeElement($departement);
            // set the owning side to null (unless already changed)
            if ($departement->getAnneeUniversitairePrepare() === $this) {
                $departement->setAnneeUniversitairePrepare(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Calendrier[]
     */
    public function getCalendriers(): Collection
    {
        return $this->calendriers;
    }

    public function addCalendrier(Calendrier $calendrier): self
    {
        if (!$this->calendriers->contains($calendrier)) {
            $this->calendriers[] = $calendrier;
            $calendrier->setAnneeUniversitaire($this);
        }

        return $this;
    }

    public function removeCalendrier(Calendrier $calendrier): self
    {
        if ($this->calendriers->contains($calendrier)) {
            $this->calendriers->removeElement($calendrier);
            // set the owning side to null (unless already changed)
            if ($calendrier->getAnneeUniversitaire() === $this) {
                $calendrier->setAnneeUniversitaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CreneauCours[]
     */
    public function getCreneauCours(): Collection
    {
        return $this->creneauCours;
    }

    public function addCreneauCour(CreneauCours $creneauCour): self
    {
        if (!$this->creneauCours->contains($creneauCour)) {
            $this->creneauCours[] = $creneauCour;
            $creneauCour->setAnneeUniversitaire($this);
        }

        return $this;
    }

    public function removeCreneauCour(CreneauCours $creneauCour): self
    {
        if ($this->creneauCours->contains($creneauCour)) {
            $this->creneauCours->removeElement($creneauCour);
            // set the owning side to null (unless already changed)
            if ($creneauCour->getAnneeUniversitaire() === $this) {
                $creneauCour->setAnneeUniversitaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Scolarite[]
     */
    public function getScolarites(): Collection
    {
        return $this->scolarites;
    }

    public function addScolarite(Scolarite $scolarite): self
    {
        if (!$this->scolarites->contains($scolarite)) {
            $this->scolarites[] = $scolarite;
            $scolarite->setAnneeUniversitaire($this);
        }

        return $this;
    }

    public function removeScolarite(Scolarite $scolarite): self
    {
        if ($this->scolarites->contains($scolarite)) {
            $this->scolarites->removeElement($scolarite);
            // set the owning side to null (unless already changed)
            if ($scolarite->getAnneeUniversitaire() === $this) {
                $scolarite->setAnneeUniversitaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ScolaritePromo[]
     */
    public function getScolaritePromos(): Collection
    {
        return $this->scolaritePromos;
    }

    public function addScolaritePromo(ScolaritePromo $scolaritePromo): self
    {
        if (!$this->scolaritePromos->contains($scolaritePromo)) {
            $this->scolaritePromos[] = $scolaritePromo;
            $scolaritePromo->setAnneeUniversitaire($this);
        }

        return $this;
    }

    public function removeScolaritePromo(ScolaritePromo $scolaritePromo): self
    {
        if ($this->scolaritePromos->contains($scolaritePromo)) {
            $this->scolaritePromos->removeElement($scolaritePromo);
            // set the owning side to null (unless already changed)
            if ($scolaritePromo->getAnneeUniversitaire() === $this) {
                $scolaritePromo->setAnneeUniversitaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Diplome[]
     */
    public function getDiplomes(): Collection
    {
        return $this->diplomes;
    }

    public function addDiplome(Diplome $diplome): self
    {
        if (!$this->diplomes->contains($diplome)) {
            $this->diplomes[] = $diplome;
            $diplome->setAnneeUniversitaire($this);
        }

        return $this;
    }

    public function removeDiplome(Diplome $diplome): self
    {
        if ($this->diplomes->contains($diplome)) {
            $this->diplomes->removeElement($diplome);
            // set the owning side to null (unless already changed)
            if ($diplome->getAnneeUniversitaire() === $this) {
                $diplome->setAnneeUniversitaire(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function displayAnneeUniversitaire(): string
    {

        $s = $this->getAnnee() + 1;

        return $this->getAnnee() . ' | ' . $s;

    }

    /**
     * @return Collection|Disponibilite[]
     */
    public function getDisponibilites(): Collection
    {
        return $this->disponibilites;
    }

    public function addDisponibilite(Disponibilite $disponibilite): self
    {
        if (!$this->disponibilites->contains($disponibilite)) {
            $this->disponibilites[] = $disponibilite;
            $disponibilite->setAnneeUniversitaire($this);
        }

        return $this;
    }

    public function removeDisponibilite(Disponibilite $disponibilite): self
    {
        if ($this->disponibilites->contains($disponibilite)) {
            $this->disponibilites->removeElement($disponibilite);
            // set the owning side to null (unless already changed)
            if ($disponibilite->getAnneeUniversitaire() === $this) {
                $disponibilite->setAnneeUniversitaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProgressionPedagogique[]
     */
    public function getProgressionPedagogiques(): Collection
    {
        return $this->progressionPedagogiques;
    }

    public function addProgressionPedagogique(ProgressionPedagogique $progressionPedagogique): self
    {
        if (!$this->progressionPedagogiques->contains($progressionPedagogique)) {
            $this->progressionPedagogiques[] = $progressionPedagogique;
            $progressionPedagogique->setAnneeUniversitaire($this);
        }

        return $this;
    }

    public function removeProgressionPedagogique(ProgressionPedagogique $progressionPedagogique): self
    {
        if ($this->progressionPedagogiques->contains($progressionPedagogique)) {
            $this->progressionPedagogiques->removeElement($progressionPedagogique);
            // set the owning side to null (unless already changed)
            if ($progressionPedagogique->getAnneeUniversitaire() === $this) {
                $progressionPedagogique->setAnneeUniversitaire(null);
            }
        }

        return $this;
    }
}
