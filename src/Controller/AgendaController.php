<?php
/**
 * Copyright (C) 2013 - 2019 | David annebicque | IUT de Troyes - All Rights Reserved
 *
 * @file /Users/davidannebicque/htdocs/intranetv3/src/Controller/AgendaController.php
 * @author David annebicque
 * @project intranetv3
 * @date  07/05/2019 10:42
 * @lastUpdate 07/05/2019 10:41
 *
 */

namespace App\Controller;

use App\MesClasses\Edt\MyEdt;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AgendaController
 * @package App\Controller
 * @Route("/agenda")
 */
class AgendaController extends BaseController
{
    /**
     * @Route("/{semaine}/{filtre}/{valeur}", name="agenda_index")
     * @param int    $semaine
     * @param string $filtre
     * @param string $valeur
     *
     * @return Response
     */
    public function index(
        $semaine = 0,
        $filtre = '',
        $valeur = ''
        ): Response
    {
        return $this->render('agenda/index.html.twig', [
            'filtre' => $filtre,
            'valeur' => $valeur,
            'semaine' => $semaine,
        ]);
    }
}
