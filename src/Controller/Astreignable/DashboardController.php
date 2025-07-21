<?php
// src/Controller/Astreignable/DashboardController.php
namespace App\Controller\Astreignable;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/astreignable/dashboard", name="astreignable_dashboard")
     */
    public function index(): Response
    {
        return $this->render('astreignable/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
