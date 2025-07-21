<?php
namespace App\Controller\Astreignable;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/astreignable", name="astreignable_dashboard")
     */
    public function index(): Response
    {
        $url = $this->get(AdminUrlGenerator::class)
                    ->setController(ServiceFaitCrudController::class)
                    ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Espace Astreignable');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil Astreignable', 'fa fa-home');
        // Ne lui donner que ses propres CRUD, par exemple :
        yield MenuItem::linkToCrud('Mes services',  'fa fa-briefcase', \App\Entity\ServiceFait::class);
        yield MenuItem::linkToCrud('Mes plannings', 'fa fa-calendar-alt', \App\Entity\PlanningAstreinte::class);
        yield MenuItem::linkToCrud('Mes logs',      'fa fa-book',       \App\Entity\MainCourante::class);
    }
}
