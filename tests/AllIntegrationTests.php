<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AllIntegrationTests extends WebTestCase
{
    private \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;

    protected function setUp(): void
    {
        // On force l'env "test"
        self::ensureKernelShutdown();
        $this->client = static::createClient([], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
    }

    /**
     * @dataProvider crudProvider
     */
    public function testCrudEndpoints(string $resource, array $payload): void
    {
        // INDEX
        $this->client->request('GET', "/api/{$resource}");
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // CREATE
        $this->client->request(
            'POST',
            "/api/{$resource}",
            [], [], 
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode($payload)
        );

        $status = $this->client->getResponse()->getStatusCode();
        $this->assertTrue(
            in_array($status, [201, 400, 422], true),
            "POST /api/{$resource} returned HTTP {$status}, expected 201, 400 or 422."
        );

        if ($status === 201) {
            // Si création OK, on vérifie qu'on a bien un id numérique dans la réponse JSON
            $data = json_decode($this->client->getResponse()->getContent(), true);
            $this->assertArrayHasKey('id', $data, 'Le JSON de création doit contenir un champ "id".');
            $this->assertIsInt($data['id'], '"id" doit être un entier.');
        }

        // SHOW non‑existant
        $this->client->request('GET', "/api/{$resource}/999999");
        $this->assertTrue(
            in_array($this->client->getResponse()->getStatusCode(), [404, 400], true),
            "GET /api/{$resource}/999999 returned unexpected HTTP code."
        );

        // UPDATE non‑existant
        $this->client->request(
            'PUT',
            "/api/{$resource}/999999",
            [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );
        $this->assertTrue(
            in_array($this->client->getResponse()->getStatusCode(), [404, 400], true),
            "PUT /api/{$resource}/999999 returned unexpected HTTP code."
        );

        // DELETE non‑existant
        $this->client->request('DELETE', "/api/{$resource}/999999");
        $this->assertTrue(
            in_array($this->client->getResponse()->getStatusCode(), [404, 400], true),
            "DELETE /api/{$resource}/999999 returned unexpected HTTP code."
        );
    }

    public function crudProvider(): array
    {
        return [
            'Admins' => ['admins', [
                'email' => 'admin@example.com',
                'nom'   => 'AdminName',
            ]],
            'Astreignables' => ['astreignables', [
                'nom'        => 'Dupont',
                'prenom'     => 'Jean',
                'email'      => 'jean.dupont@test.com',
                'telephone'  => '0102030405',
                'seniorite'  => 'Senior',
                'direction'  => 'IT',
                'disponible' => true,
            ]],
            'DRHs' => ['drhs', [
                'nom' => 'RHName',
            ]],
            'MainCourantes' => ['maincourantes', [
                'date'         => '2025-07-16',
                'details'      => 'Détail de test',
                'astreignable' => 1,
            ]],
            'Plannings' => ['plannings', [
                'dateDebut' => '2025-07-16',
                'dateFin'   => '2025-07-17',
                'theme'     => 'Urgence',
                'statut'    => 'Actif',
            ]],
            'Services' => ['services', [
                'date'             => '2025-07-16',
                'heuresEffectuees' => 4,
                'valide'           => true,
                'astreignable'     => 1,
            ]],
        ];
    }
}
