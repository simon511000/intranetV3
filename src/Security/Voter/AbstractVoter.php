<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Security/Voter/AbstractVoter.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 01/11/2021 18:14
 */

namespace App\Security\Voter;

use App\Entity\Departement;
use App\Entity\Personnel;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class AbstractVoter
{
    public final const MINIMAL_ROLE_ABS = 'MINIMAL_ROLE_ABS';
    public final const MINIMAL_ROLE_NOTE = 'MINIMAL_ROLE_NOTE';
    public final const MINIMAL_ROLE_ASS = 'MINIMAL_ROLE_ASS';
    public final const MINIMAL_ROLE_RP = 'MINIMAL_ROLE_RP';
    public final const MINIMAL_ROLE_DDE = 'MINIMAL_ROLE_DDE';
    public final const MINIMAL_ROLE_STAGE = 'MINIMAL_ROLE_STAGE';
    public final const MINIMAL_ROLE_PROJET = 'MINIMAL_ROLE_PROJET';
    public final const MINIMAL_ROLE_CDD = 'MINIMAL_ROLE_CDD';
    public final const MINIMAL_ROLE_EDT = 'MINIMAL_ROLE_EDT';
    public final const MINIMAL_ROLE_SCOL = 'MINIMAL_ROLE_SCOL';

    public final const LIST_OF_MINIMAL_ROLES = [
        self::MINIMAL_ROLE_ABS,
        self::MINIMAL_ROLE_NOTE,
        self::MINIMAL_ROLE_ASS,
        self::MINIMAL_ROLE_RP,
        self::MINIMAL_ROLE_DDE,
        self::MINIMAL_ROLE_STAGE,
        self::MINIMAL_ROLE_PROJET,
        self::MINIMAL_ROLE_EDT,
        self::MINIMAL_ROLE_CDD,
        self::MINIMAL_ROLE_SCOL,
    ];

    public final const HIERARCHICAL_ACCESS_FROM_ROLE = [
        self::MINIMAL_ROLE_ABS => ['ROLE_ABS', 'ROLE_ASS', 'ROLE_DDE', 'ROLE_RP', 'ROLE_CDD'],
        self::MINIMAL_ROLE_SCOL => ['ROLE_NOTES', 'ROLE_ABS', 'ROLE_ASS', 'ROLE_DDE', 'ROLE_RP', 'ROLE_CDD'],
        self::MINIMAL_ROLE_NOTE => ['ROLE_NOTES', 'ROLE_ASS', 'ROLE_DDE', 'ROLE_RP', 'ROLE_CDD'],
        self::MINIMAL_ROLE_ASS => ['ROLE_ASS', 'ROLE_DDE', 'ROLE_RP', 'ROLE_CDD'],
        self::MINIMAL_ROLE_RP => ['ROLE_RP', 'ROLE_CDD'],
        self::MINIMAL_ROLE_DDE => ['ROLE_DDE', 'ROLE_CDD'],
        self::MINIMAL_ROLE_STAGE => ['ROLE_STA', 'ROLE_ASS', 'ROLE_DDE', 'ROLE_RP', 'ROLE_CDD'],
        self::MINIMAL_ROLE_PROJET => ['ROLE_PRJ', 'ROLE_ASS', 'ROLE_DDE', 'ROLE_RP', 'ROLE_CDD'],
        self::MINIMAL_ROLE_EDT => ['ROLE_EDT', 'ROLE_ASS', 'ROLE_DDE', 'ROLE_RP', 'ROLE_CDD'],
        self::MINIMAL_ROLE_CDD => ['ROLE_CDD'],
    ];
    protected UserInterface | string $user;
    protected array $departementRoles;

    public function __construct(protected RequestStack $session, TokenStorageInterface $tokenStorage)
    {
        if (null !== $tokenStorage->getToken()) {
            $this->user = $tokenStorage->getToken()->getUser();
            if (!is_string($this->user) && $this->user instanceof Personnel) {
                $this->departementRoles = [];
                foreach ($this->user->getPersonnelDepartements() as $rf) {
                    if (null !== $rf->getDepartement() && !array_key_exists($rf->getDepartement()->getId(), $this->departementRoles)) {
                        $this->departementRoles[$rf->getDepartement()->getId()] = [];
                    }
                    if (null !== $rf->getDepartement()) {
                        $this->departementRoles[$rf->getDepartement()->getId()] = $rf->getRoles();
                    }
                }
            }
        }
    }

    public function userHasMinimalRoleInDepartement(string $attribute, ?Departement $departement): bool
    {
        if (null !== $departement && $this->userInGoodDepartement($departement)) {
            if (!array_key_exists($departement->getId(), $this->departementRoles)) {
                throw new AccessDeniedException('Vous n\'avez pas accès à ce département');
            }

            return count(array_intersect(self::HIERARCHICAL_ACCESS_FROM_ROLE[$attribute],
                    $this->departementRoles[$departement->getId()])) > 0;
        }

        throw new AccessDeniedException('Vous n\'êtes pas dans le bon département');
    }

    public function userInGoodDepartement(?Departement $departement): bool
    {
        if (null === $departement) {
            return false;
        }

        if (null === $this->session->getSession()->get('departement')) {
            return false;
        }

        if (is_string($this->session->getSession()->get('departement'))) {
            return $this->session->getSession()->get('departement') === $departement->getUuidString();
        }

        return $this->session->getSession()->get('departement')->equals($departement->getUuid());
    }

    public function isResponsableDepartement(Departement $departement): bool
    {
        if (!array_key_exists($departement->getId(), $this->departementRoles)) {
            throw new AccessDeniedException('Vous n\'avez pas accès à ce département');
        }

        return in_array('ROLE_CDD', $this->departementRoles[$departement->getId()], true) || in_array('ROLE_ASS',
                $this->departementRoles[$departement->getId()], true) || in_array('ROLE_DDE',
                $this->departementRoles[$departement->getId()], true);
    }
}
