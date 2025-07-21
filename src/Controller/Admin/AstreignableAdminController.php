<?php
// src/Controller/Admin/AstreignableAdminController.php
namespace App\Controller\Admin;

use App\Entity\Astreignable;
use App\Form\AstreignableType;
use App\Repository\AstreignableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AstreignableAdminController extends BaseAdminController
{
    /** @Route("/admin/astreignables", name="admin_astreignable_index", methods={"GET"}) */
    public function index(AstreignableRepository $repo): Response
    {
        return $this->render('admin/astreignable/index.html.twig', [
            'items' => $repo->findAll(),
        ]);
    }

    /** @Route("/admin/astreignables/new", name="admin_astreignable_new", methods={"GET","POST"}) */
    public function new(Request $req, EntityManagerInterface $em): Response
    {
        $entity = new Astreignable();
        $form   = $this->createForm(AstreignableType::class, $entity);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirectToRoute('admin_astreignable_index');
        }
        return $this->render('admin/astreignable/new.html.twig',['form'=>$form->createView()]);
    }

    /** @Route("/admin/astreignables/{id}", name="admin_astreignable_show", methods={"GET"}) */
    public function show(Astreignable $item): Response
    {
        return $this->render('admin/astreignable/show.html.twig',['item'=>$item]);
    }

    /** @Route("/admin/astreignables/{id}/edit", name="admin_astreignable_edit", methods={"GET","POST"}) */
    public function edit(Request $req, Astreignable $item, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AstreignableType::class, $item);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_astreignable_index');
        }
        return $this->render('admin/astreignable/edit.html.twig',['form'=>$form->createView(),'item'=>$item]);
    }

    /** @Route("/admin/astreignables/{id}/delete", name="admin_astreignable_delete", methods={"POST"}) */
    public function delete(Request $req, Astreignable $item, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $req->request->get('_token'))) {
            $em->remove($item);
            $em->flush();
        }
        return $this->redirectToRoute('admin_astreignable_index');
    }
}
