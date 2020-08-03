<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Classes/ServiceRealise/ServiceRealiseCelcat.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 03/08/2020 16:52

namespace App\Classes\ServiceRealise;


use App\DTO\EvenementEdt;
use App\Entity\AnneeUniversitaire;
use App\Entity\CelcatEvent;
use App\Entity\Matiere;
use App\Entity\Personnel;
use App\Repository\CelcatEventsRepository;

class ServiceRealiseCelcat implements ServiceRealise
{

    /**
     * @var CelcatEventsRepository
     */
    private CelcatEventsRepository $celcatEventsRepository;

    public function __construct(CelcatEventsRepository $celcatEventsRepository)
    {
        $this->celcatEventsRepository = $celcatEventsRepository;
    }

    public function getServiceRealiseParMatiere(Matiere $matiere, AnneeUniversitaire $anneeUniversitaire): array
    {
        // TODO: Implement getServiceRealiseParMatiere() method.
    }

    public function getServiceRealiserParEnseignant(Personnel $personnel, AnneeUniversitaire $anneeUniversitaire): array
    {
        $events = $this->celcatEventsRepository->findBy(['codePersonnel' => $personnel->getNumeroHarpege()],
            ['matiere' => 'ASC', 'semaine' => 'ASC', 'jour' => 'ASC', 'debut' => 'ASC']);
        $tabEvent = [];
        foreach ($events as $event) {
            $tabEvent[] = $this->convertToEvenementEdt($event);
        }

        return $tabEvent;
    }

    /**
     * @param CelcatEvent $event
     *
     * @return EvenementEdt
     */
    public function convertToEvenementEdt($event): EvenementEdt
    {
        $ev = new EvenementEdt();
        $ev->groupe = $event->getLibGroupe();
        $ev->jour = $event->getJour();
        $ev->date = $event->getDate();

        $ev->heure = $event->getDebut();
        $ev->matiere = $event->getLibModule();
        $ev->type_cours = $event->getType();
        $ev->personnel = $event->getLibPersonnel();

        return $ev;
    }
}
