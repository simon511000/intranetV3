<?php
/*
 * Copyright (c) 2022. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/Command/UpdateSemestreRessourceCommand.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 14/07/2022 11:52
 */

namespace App\Command;

use App\Repository\ApcRessourceRepository;
use App\Repository\ApcSaeRepository;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-semestre-ressource',
    description: 'Add a short description for your command',
)]
class UpdateSemestreRessourceCommand extends Command
{
    public function __construct(
        private readonly ApcSaeRepository $apcSaeRepository,
        private readonly ApcRessourceRepository $apcRessourceRepository,
        private readonly EvaluationRepository $evaluationRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);
        $ressources = $this->apcRessourceRepository->findAll();
        foreach ($ressources as $ressource) {
            $semestre = $ressource->getSemestre();
            $ressource->addSemestre($semestre);
            $semestre->addApcSemestresRessource($ressource);
            $ressource->setSemestre(null);
        }
        $this->entityManager->flush();
        $io->success('Ressources mises à jour');

        $saes = $this->apcSaeRepository->findAll();
        foreach ($saes as $sae) {
            $semestre = $sae->getSemestre();
            $sae->addSemestre($semestre);
            $semestre->addApcSemestresSae($sae);
            $sae->setSemestre(null);
        }
        $this->entityManager->flush();
        $io->success('SAES mises à jour');

        $evaluations = $this->evaluationRepository->findAll();
        foreach ($evaluations as $evaluation) {
            $semestre = $evaluation->getTypeGroupe()?->getSemestre();
            $evaluation->setSemestre($semestre);
        }
        $this->entityManager->flush();
        $io->success('Evaluations mises à jour');

        return Command::SUCCESS;
    }
}
