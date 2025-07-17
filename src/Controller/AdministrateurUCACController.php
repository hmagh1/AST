<?php
namespace App\Controller;

use App\Entity\AdministrateurUCAC;
use App\Repository\AdministrateurUCACRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/api/admins")
 */
class AdministrateurUCACController extends AbstractController
{
    /**
     * @Route("", name="app_administrateurucac_index", methods={"GET"})
     */
    public function index(AdministrateurUCACRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findAll(), 'json', [
            AbstractNormalizer::GROUPS => ['admin:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_administrateurucac_show", methods={"GET"})
     */
    public function show(AdministrateurUCAC $admin, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($admin, 'json', [
            AbstractNormalizer::GROUPS => ['admin:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("", name="app_administrateurucac_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $admin = new AdministrateurUCAC();
        $admin->setNom($data['nom']);
        $admin->setEmail($data['email']);

        $em->persist($admin);
        $em->flush();

        $json = $serializer->serialize($admin, 'json', [
            AbstractNormalizer::GROUPS => ['admin:read']
        ]);
        return JsonResponse::fromJsonString($json, 201);
    }

    /**
     * @Route("/{id}", name="app_administrateurucac_update", methods={"PUT"})
     */
    public function update(Request $request, AdministrateurUCAC $admin, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $admin->setNom($data['nom']);
        $admin->setEmail($data['email']);

        $em->flush();

        $json = $serializer->serialize($admin, 'json', [
            AbstractNormalizer::GROUPS => ['admin:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", name="app_administrateurucac_delete", methods={"DELETE"})
     */
    public function delete(AdministrateurUCAC $admin, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($admin);
        $em->flush();
        return new JsonResponse(null, 204);
    }

    /**
     * @Route("/email/{email}", name="app_administrateurucac_byemail", methods={"GET"})
     */
    public function byEmail(string $email, AdministrateurUCACRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $admin = $repo->findOneByEmail($email);
        $json = $serializer->serialize($admin, 'json', [
            AbstractNormalizer::GROUPS => ['admin:read']
        ]);
        return JsonResponse::fromJsonString($json);
    }
}
