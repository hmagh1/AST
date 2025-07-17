<?php
namespace App\Controller;

use App\Entity\DRH;
use App\Repository\DRHRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/api/drhs")
 */
class DRHController extends AbstractController
{
    /**
     * @Route("", name="app_drh_index", methods={"GET"})
     */
    public function index(DRHRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findAll(), 'json', [
            AbstractNormalizer::GROUPS => ['drh:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_drh_show", methods={"GET"})
     */
    public function show(DRH $drh, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($drh, 'json', [
            AbstractNormalizer::GROUPS => ['drh:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("", name="app_drh_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $drh = new DRH();
        $drh->setNom($data['nom']);

        $em->persist($drh);
        $em->flush();

        $json = $serializer->serialize($drh, 'json', [
            AbstractNormalizer::GROUPS => ['drh:read']
        ]);
        return JsonResponse::fromJsonString($json, 201);
    }

    /**
     * @Route("/{id}", name="app_drh_update", methods={"PUT"})
     */
    public function update(Request $request, DRH $drh, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $drh->setNom($data['nom']);

        $em->flush();

        $json = $serializer->serialize($drh, 'json', [
            AbstractNormalizer::GROUPS => ['drh:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_drh_delete", methods={"DELETE"})
     */
    public function delete(DRH $drh, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($drh);
        $em->flush();
        return new JsonResponse(null, 204);
    }

    /**
     * @Route("/nom/{nom}", name="app_drh_bynom", methods={"GET"})
     */
    public function byNom(string $nom, DRHRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $drh = $repo->findOneByNom($nom);
        $json = $serializer->serialize($drh, 'json', [
            AbstractNormalizer::GROUPS => ['drh:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }
}
