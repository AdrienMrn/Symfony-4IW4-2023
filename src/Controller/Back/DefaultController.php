<?php

namespace App\Controller\Back;

use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(Mailer $mailer): Response
    {
        $mailer->sendTemplate(1, [['email' => 'amorin@suchweb.fr', 'name' => 'test']], [
            'name' => 'test',
            'email' => 'test email',
            'password' => 'pwd'
        ]);

        return $this->render('back/default/index.html.twig', [
            'controller_name' => 'accueil',
        ]);
    }
}
