<?php
// src/Controller/AstreignableController.php

namespace App\Controller;

use App\Entity\Astreignable;
use App\Repository\AstreignableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/astreignables", name="app_astreignable_")
 */
class AstreignableController extends AbstractController
{
    /** @Route("",      name="index",       methods={"GET"})  */
    public function index(AstreignableRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $data = $repo->findAll();
        $json = $serializer->serialize($data, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("/{id}",  name="show",        methods={"GET"})  */
    public function show(Astreignable $astreignable, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($astreignable, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("",      name="create",      methods={"POST"}) */
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $astro = (new Astreignable())
            ->setNom($data['nom'] ?? null)
            ->setPrenom($data['prenom'] ?? null)
            ->setEmail($data['email'] ?? null)
            ->setTelephone($data['telephone'] ?? null)
            ->setSeniorite($data['seniorite'] ?? null)
            ->setDirection($data['direction'] ?? null)
            ->setDisponible($data['disponible'] ?? false)
        ;

        $em->persist($astro);
        $em->flush();

        $json = $serializer->serialize($astro, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);

        return JsonResponse::fromJsonString($json, 201);
    }

    /** @Route("/{id}",  name="update",      methods={"PUT"}) */
    public function update(Request $request, Astreignable $astreignable, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        foreach (['nom','prenom','email','telephone','seniorite','direction','disponible'] as $field) {
            if (isset($data[$field])) {
                $setter = 'set'.ucfirst($field);
                $astreignable->$setter($data[$field]);
            }
        }

        $em->flush();

        $json = $serializer->serialize($astreignable, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("/{id}",  name="delete",      methods={"DELETE"}) */
    public function delete(Astreignable $astreignable, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($astreignable);
        $em->flush();

        return new JsonResponse(null, 204);
    }

    /** @Route("/disponibles", name="disponibles", methods={"GET"}) */
    public function disponibles(AstreignableRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $data = $repo->findAvailable();
        $json = $serializer->serialize($data, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("/direction/{direction}", name="by_direction", methods={"GET"}) */
    public function byDirection(string $direction, AstreignableRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $data = $repo->findByDirection($direction);
        $json = $serializer->serialize($data, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read']
        ]);

        return JsonResponse::fromJsonString($json);
    }
}
