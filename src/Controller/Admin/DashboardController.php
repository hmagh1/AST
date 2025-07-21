<?php
// src/Controller/Admin/DashboardController.php
namespace App\Controller\Admin;

use App\Entity\AdministrateurUCAC;
use App\Entity\Astreignable;
use App\Entity\DRH;
use App\Entity\MainCourante;
use App\Entity\PlanningAstreinte;
use App\Entity\ServiceFait;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * Cette route correspond à la racine du back‑office.
     * Elle redirige automatiquement vers la liste des administrateurs.
     *
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(): Response
    {
        // on génère l'URL vers le CRUD d'AdministrateurUCAC
        $adminUrlGenerator = $this->get(AdminUrlGenerator::class);

        return $this->redirect(
            $adminUrlGenerator
                ->setController(AdministrateurUCACCrudController::class)
                ->setAction('index')
                ->generateUrl()
        );
    }

    /**
     * Configuration du titre du Dashboard (en haut à gauche)
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Astreinte API – Back‑Office');
    }

    /**
     * Construction du menu latéral
     */
    public function configureMenuItems(): iterable
    {
        // lien vers la home du front si besoin
        yield MenuItem::linkToRoute('← Retour au Front', 'fa fa-home', 'app_home');

        // section Administrateurs
        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud('Administrateurs', 'fas fa-user-shield', AdministrateurUCAC::class);
        yield MenuItem::linkToCrud('Astreignables', 'fas fa-user-clock', Astreignable::class);
        yield MenuItem::linkToCrud('DRH', 'fas fa-users-cog', DRH::class);

        // section Opérations
        yield MenuItem::section('Opérations');
        yield MenuItem::linkToCrud('Main Courantes', 'fas fa-book', MainCourante::class);
        yield MenuItem::linkToCrud('Planning', 'fas fa-calendar-alt', PlanningAstreinte::class);
        yield MenuItem::linkToCrud('Services Faits', 'fas fa-clipboard-check', ServiceFait::class);
    }
}
