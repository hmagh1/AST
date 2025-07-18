<?php
// src/Controller/AdministrateurUCACController.php

namespace App\Controller;

use App\Entity\AdministrateurUCAC;
use App\Repository\AdministrateurUCACRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/admins", name="app_administrateurucac_")
 */
class AdministrateurUCACController extends AbstractController
{
    /** @Route("",      name="index",  methods={"GET"})  */
    public function index(AdministrateurUCACRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $data = $repo->findAll();
        $json = $serializer->serialize($data, 'json', [
            AbstractNormalizer::GROUPS => ['admin:read']
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("/{id}",  name="show",   methods={"GET"})  */
    public function show(AdministrateurUCAC $admin, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($admin, 'json', [
            AbstractNormalizer::GROUPS => ['admin:read']
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("",      name="create", methods={"POST"}) */
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data  = json_decode($request->getContent(), true);
        $admin = (new AdministrateurUCAC())
            ->setNom($data['nom'] ?? null)
            ->setEmail($data['email'] ?? null)
        ;

        $em->persist($admin);
        $em->flush();

        $json = $serializer->serialize($admin, 'json', [
            AbstractNormalizer::GROUPS => ['admin:read']
        ]);

        return JsonResponse::fromJsonString($json, 201);
    }

    /** @Route("/{id}",  name="update", methods={"PUT"}) */
    public function update(Request $request, AdministrateurUCAC $admin, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nom']))   $admin->setNom($data['nom']);
        if (isset($data['email'])) $admin->setEmail($data['email']);

        $em->flush();

        $json = $serializer->serialize($admin, 'json', [
            AbstractNormalizer::GROUPS => ['admin:read']
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("/{id}",  name="delete", methods={"DELETE"}) */
    public function delete(AdministrateurUCAC $admin, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($admin);
        $em->flush();

        return new JsonResponse(null, 204);
    }

    /** @Route("/email/{email}", name="by_email", methods={"GET"}) */
    public function byEmail(string $email, AdministrateurUCACRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $admin = $repo->findOneByEmail($email);

        $json = $serializer->serialize($admin, 'json', [
            AbstractNormalizer::GROUPS => ['admin:read']
        ]);

        return JsonResponse::fromJsonString($json);
    }
}
