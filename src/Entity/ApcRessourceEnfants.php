<?php

namespace App\Entity;

use App\Repository\ApcRessourceEnfantsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApcRessourceEnfantsRepository::class)]
class ApcRessourceEnfants extends BaseEntity
{


    #[ORM\ManyToOne(targetEntity: ApcRessource::class, inversedBy: 'apcRessourceParentEnfants')]
    private ?ApcRessource $apcRessourceParent;

    #[ORM\ManyToOne(targetEntity: ApcRessource::class, inversedBy: 'apcRessourceEnfantEnfants')]
    private ?ApcRessource $apcRessourceEnfant;


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
}