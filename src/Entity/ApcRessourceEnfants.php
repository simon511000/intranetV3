<?php

namespace App\Entity;

use App\Repository\ApcRessourceEnfantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApcRessourceEnfantsRepository::class)]
class ApcRessourceEnfants extends BaseEntity
{
    #[ORM\ManyToOne(targetEntity: ApcRessource::class, inversedBy: 'apcRessourceParentEnfants')]
    private ?ApcRessource $apcRessourceParent = null;

    #[ORM\ManyToOne(targetEntity: ApcRessource::class, inversedBy: 'apcRessourceEnfantEnfants')]
    private ?ApcRessource $apcRessourceEnfant = null;

    /**
     * @var \Doctrine\Common\Collections\Collection<\App\Entity\Groupe>
     */
    #[ORM\ManyToMany(targetEntity: Groupe::class, inversedBy: 'apcRessourceEnfants')]
    private \Doctrine\Common\Collections\Collection $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }



    public function getApcRessourceParent(): ?ApcRessource
    {
        return $this->apcRessourceParent;
    }

    public function setApcRessourceParent(?ApcRessource $apcRessourceParent): self
    {
        $this->apcRessourceParent = $apcRessourceParent;

        return $this;
    }

    public function getApcRessourceEnfant(): ?ApcRessource
    {
        return $this->apcRessourceEnfant;
    }

    public function setApcRessourceEnfant(?ApcRessource $apcRessourceEnfant): self
    {
        $this->apcRessourceEnfant = $apcRessourceEnfant;

        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        $this->groupes->removeElement($groupe);

        return $this;
    }

    public function containsGroupe(Groupe $groupe): bool
    {
        return $this->groupes->contains($groupe);
    }
}
