<?php
// src/Controller/AstreignableController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Astreignable;
use App\Repository\AstreignableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

 /**
  * @Route("/api/astreignables", name="app_astreignable_")
  */
class AstreignableController extends AbstractController
{
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->em              = $em;
        $this->serializer      = $serializer;
        $this->passwordHasher  = $passwordHasher;
    }

    /** @Route("",        name="index",       methods={"GET"}) */
    public function index(AstreignableRepository $repo): JsonResponse
    {
        $entities = $repo->findAll();
        $json = $this->serializer->serialize($entities, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read'],
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("/{id}",    name="show",        methods={"GET"}) */
    public function show(Astreignable $astreignable): JsonResponse
    {
        $json = $this->serializer->serialize($astreignable, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read'],
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("",        name="create",      methods={"POST"}) */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // --- 1) Créer l’Astreignable ---
        $astreignable = new Astreignable();
        $astreignable->setNom($data['nom']);
        $astreignable->setPrenom($data['prenom']);
        $astreignable->setEmail($data['email']);
        $astreignable->setTelephone($data['telephone']);
        $astreignable->setSeniorite($data['seniorite']);
        $astreignable->setDirection($data['direction']);
        $astreignable->setDisponible($data['disponible']);

        // --- 2) Créer le User associé ---
        $user = new User();
        $user->setEmail($data['email']);
        $plainPwd = $data['password'] ?? bin2hex(random_bytes(4));
        $hashed   = $this->passwordHasher->hashPassword($user, $plainPwd);
        $user->setPassword($hashed);
        $user->setRoles(['ROLE_USER']);
        // bi‑directionnel
        $astreignable->setUser($user);
        $user->setAstreignableProfile($astreignable);

        // --- 3) Persister le tout ---
        $this->em->persist($astreignable);
        $this->em->persist($user);
        $this->em->flush();

        // --- 4) Retourner l’Astreignable en JSON ---
        $json = $this->serializer->serialize($astreignable, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read'],
        ]);

        return JsonResponse::fromJsonString($json, 201);
    }

    /** @Route("/{id}",    name="update",      methods={"PUT"}) */
    public function update(Request $request, Astreignable $astreignable): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        foreach (['nom','prenom','email','telephone','seniorite','direction','disponible'] as $field) {
            if (array_key_exists($field, $data)) {
                $setter = 'set'.ucfirst($field);
                $astreignable->$setter($data[$field]);
            }
        }

        $this->em->flush();

        $json = $this->serializer->serialize($astreignable, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read'],
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("/{id}",    name="delete",      methods={"DELETE"}) */
    public function delete(Astreignable $astreignable): JsonResponse
    {
        $this->em->remove($astreignable);
        $this->em->flush();

        return new JsonResponse(null, 204);
    }

    /** @Route("/disponibles",       name="disponibles",    methods={"GET"}) */
    public function disponibles(AstreignableRepository $repo): JsonResponse
    {
        $list = $repo->findAvailable();
        $json = $this->serializer->serialize($list, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read'],
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /** @Route("/direction/{dir}",   name="by_direction",   methods={"GET"}) */
    public function byDirection(string $dir, AstreignableRepository $repo): JsonResponse
    {
        $list = $repo->findByDirection($dir);
        $json = $this->serializer->serialize($list, 'json', [
            AbstractNormalizer::GROUPS => ['astreignable:read'],
        ]);

        return JsonResponse::fromJsonString($json);
    }
}
