<?php
namespace App\Controller;

use App\Entity\Astreignable;
use App\Repository\AstreignableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/api/astreignables")
 */
class AstreignableController extends AbstractController
{
    /**
     * @Route("", name="app_astreignable_index", methods={"GET"})
     */
    public function index(AstreignableRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findAll(), 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_astreignable_show", methods={"GET"})
     */
    public function show(Astreignable $astreignable, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($astreignable, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("", name="app_astreignable_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $astreignable = new Astreignable();
        $astreignable->setNom($data['nom']);
        $astreignable->setPrenom($data['prenom']);
        $astreignable->setEmail($data['email']);
        $astreignable->setTelephone($data['telephone']);
        $astreignable->setSeniorite($data['seniorite']);
        $astreignable->setDirection($data['direction']);
        $astreignable->setDisponible($data['disponible']);

        $em->persist($astreignable);
        $em->flush();

        $json = $serializer->serialize($astreignable, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);
        return JsonResponse::fromJsonString($json, 201);
    }

    /**
     * @Route("/{id}", name="app_astreignable_update", methods={"PUT"})
     */
    public function update(Request $request, Astreignable $astreignable, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $astreignable->setNom($data['nom']);
        $astreignable->setPrenom($data['prenom']);
        $astreignable->setEmail($data['email']);
        $astreignable->setTelephone($data['telephone']);
        $astreignable->setSeniorite($data['seniorite']);
        $astreignable->setDirection($data['direction']);
        $astreignable->setDisponible($data['disponible']);

        $em->flush();

        $json = $serializer->serialize($astreignable, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_astreignable_delete", methods={"DELETE"})
     */
    public function delete(Astreignable $astreignable, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($astreignable);
        $em->flush();
        return new JsonResponse(null, 204);
    }

    /**
     * @Route("/disponibles", name="app_astreignable_disponibles", methods={"GET"})
     */
    public function disponibles(AstreignableRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findAvailable(), 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/direction/{direction}", name="app_astreignable_bydirection", methods={"GET"})
     */
    public function byDirection(string $direction, AstreignableRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findByDirection($direction), 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }
}
