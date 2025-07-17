<?php
namespace App\Controller;

use App\Entity\ServiceFait;
use App\Repository\ServiceFaitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/api/services")
 */
class ServiceFaitController extends AbstractController
{
    /**
     * @Route("", name="app_servicefait_index", methods={"GET"})
     */
    public function index(ServiceFaitRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findAll(), 'json', [
            AbstractNormalizer::GROUPS => ['servicefait:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_servicefait_show", methods={"GET"})
     */
    public function show(ServiceFait $service, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($service, 'json', [
            AbstractNormalizer::GROUPS => ['servicefait:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("", name="app_servicefait_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $service = new ServiceFait();
        $service->setDate(new \DateTime($data['date']));
        $service->setHeuresEffectuees($data['heuresEffectuees']);
        $service->setValide($data['valide']);

        $em->persist($service);
        $em->flush();

        $json = $serializer->serialize($service, 'json', [
            AbstractNormalizer::GROUPS => ['servicefait:read']
        ]);
        return JsonResponse::fromJsonString($json, 201);
    }

    /**
     * @Route("/{id}", name="app_servicefait_update", methods={"PUT"})
     */
    public function update(Request $request, ServiceFait $service, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $service->setDate(new \DateTime($data['date']));
        $service->setHeuresEffectuees($data['heuresEffectuees']);
        $service->setValide($data['valide']);

        $em->flush();

        $json = $serializer->serialize($service, 'json', [
            AbstractNormalizer::GROUPS => ['servicefait:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_servicefait_delete", methods={"DELETE"})
     */
    public function delete(ServiceFait $service, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($service);
        $em->flush();
        return new JsonResponse(null, 204);
    }

    /**
     * @Route("/valide", name="app_servicefait_valides", methods={"GET"})
     */
    public function valides(ServiceFaitRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findValidated(), 'json', [
            AbstractNormalizer::GROUPS => ['servicefait:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/astreignable/{id}", name="app_servicefait_byastreignable", methods={"GET"})
     */
    public function byAstreignable(int $id, ServiceFaitRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findByAstreignableId($id), 'json', [
            AbstractNormalizer::GROUPS => ['servicefait:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }
}
