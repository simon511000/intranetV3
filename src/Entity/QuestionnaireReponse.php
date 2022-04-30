<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Entity/QuestionnaireReponse.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 06/06/2021 08:58
 */

namespace App\Entity;

use App\Entity\Traits\LifeCycleTrait;
use App\Repository\QuestionnaireReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionnaireReponseRepository::class)]
#[ORM\HasLifecycleCallbacks]
class QuestionnaireReponse extends BaseEntity
{
    use LifeCycleTrait;

    #[ORM\ManyToOne(targetEntity: QuestionnaireQuestion::class, inversedBy: 'quizzReponses')]
    private ?QuestionnaireQuestion $question = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $valeur = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $ordre = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 30)]
    private string $alignement = 'HORIZONTAL_CENTER';

    public function getQuestion(): ?QuestionnaireQuestion
    {
        return $this->question;
    }

    public function setQuestion(?QuestionnaireQuestion $question): self
    {
        $this->question = $question;

        return $this;
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

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getAlignement(): ?string
    {
        return '' === $this->alignement ? 'HORIZONTAL_CENTER' : $this->alignement;
    }

    public function setAlignement(string $alignement): self
    {
        $this->alignement = $alignement;

        return $this;
    }
}
