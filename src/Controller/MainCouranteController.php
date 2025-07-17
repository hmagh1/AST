<?php
namespace App\Controller;

use App\Entity\MainCourante;
use App\Repository\MainCouranteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/api/maincourantes")
 */
class MainCouranteController extends AbstractController
{
    /**
     * @Route("", name="app_maincourante_index", methods={"GET"})
     */
    public function index(MainCouranteRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findAll(), 'json', [
            AbstractNormalizer::GROUPS => ['maincourante:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_maincourante_show", methods={"GET"})
     */
    public function show(MainCourante $mainCourante, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($mainCourante, 'json', [
            AbstractNormalizer::GROUPS => ['maincourante:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("", name="app_maincourante_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $main = new MainCourante();
        $main->setDate(new \DateTime($data['date']));
        $main->setDetails($data['details']);

        $em->persist($main);
        $em->flush();

        $json = $serializer->serialize($main, 'json', [
            AbstractNormalizer::GROUPS => ['maincourante:read']
        ]);
        return JsonResponse::fromJsonString($json, 201);
    }

    /**
     * @Route("/{id}", name="app_maincourante_update", methods={"PUT"})
     */
    public function update(Request $request, MainCourante $main, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $main->setDate(new \DateTime($data['date']));
        $main->setDetails($data['details']);

        $em->flush();

        $json = $serializer->serialize($main, 'json', [
            AbstractNormalizer::GROUPS => ['maincourante:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_maincourante_delete", methods={"DELETE"})
     */
    public function delete(MainCourante $main, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($main);
        $em->flush();
        return new JsonResponse(null, 204);
    }

    /**
     * @Route("/astreignable/{id}", name="app_maincourante_byastreignable", methods={"GET"})
     */
    public function byAstreignable(int $id, MainCouranteRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findByAstreignableId($id), 'json', [
            AbstractNormalizer::GROUPS => ['maincourante:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }
}
