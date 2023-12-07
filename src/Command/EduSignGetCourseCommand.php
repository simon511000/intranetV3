<?php

namespace App\Command;

use App\Classes\EduSign\GetCourses;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'edusign:get-course',
    description: 'Création des absences',
)]
class EduSignGetCourseCommand extends Command
{
    public function __construct(
        private GetCourses $getCourses,
    )
    {
        parent::__construct();
    }


    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->getCourses->getCourse();

        $io->success('Absences créées.');

        return Command::SUCCESS;
    }
}