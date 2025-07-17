<?php
namespace App\Controller;

use App\Entity\PlanningAstreinte;
use App\Repository\PlanningAstreinteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/api/plannings")
 */
class PlanningAstreinteController extends AbstractController
{
    /**
     * @Route("", name="app_planningastreinte_index", methods={"GET"})
     */
    public function index(PlanningAstreinteRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findAll(), 'json', [
            AbstractNormalizer::GROUPS => ['planning:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_planningastreinte_show", methods={"GET"})
     */
    public function show(PlanningAstreinte $planning, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($planning, 'json', [
            AbstractNormalizer::GROUPS => ['planning:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("", name="app_planningastreinte_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $planning = new PlanningAstreinte();
        $planning->setDateDebut(new \DateTime($data['dateDebut']));
        $planning->setDateFin(new \DateTime($data['dateFin']));
        $planning->setTheme($data['theme']);
        $planning->setStatut($data['statut']);

        $em->persist($planning);
        $em->flush();

        $json = $serializer->serialize($planning, 'json', [
            AbstractNormalizer::GROUPS => ['planning:read']
        ]);
        return JsonResponse::fromJsonString($json, 201);
    }

    /**
     * @Route("/{id}", name="app_planningastreinte_update", methods={"PUT"})
     */
    public function update(Request $request, PlanningAstreinte $planning, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $planning->setDateDebut(new \DateTime($data['dateDebut']));
        $planning->setDateFin(new \DateTime($data['dateFin']));
        $planning->setTheme($data['theme']);
        $planning->setStatut($data['statut']);

        $em->flush();

        $json = $serializer->serialize($planning, 'json', [
            AbstractNormalizer::GROUPS => ['planning:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_planningastreinte_delete", methods={"DELETE"})
     */
    public function delete(PlanningAstreinte $planning, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($planning);
        $em->flush();
        return new JsonResponse(null, 204);
    }

    /**
     * @Route("/statut/{statut}", name="app_planningastreinte_bystatut", methods={"GET"})
     */
    public function byStatut(string $statut, PlanningAstreinteRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findByStatut($statut), 'json', [
            AbstractNormalizer::GROUPS => ['planning:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/actifs", name="app_planningastreinte_actifs", methods={"GET"})
     */
    public function actifs(PlanningAstreinteRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findActive(), 'json', [
            AbstractNormalizer::GROUPS => ['planning:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }
}
