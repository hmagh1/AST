<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AllIntegrationTests extends WebTestCase
{
    /** @var \Symfony\Bundle\FrameworkBundle\KernelBrowser */
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    private function getPayload(string $endpoint): array
    {
        switch ($endpoint) {
            case 'administrateurucacs':
                return [
                    'email' => 'admin@test.com',
                    'nom'   => 'AdminNom'
                ];

            case 'astreignables':
                return [
                    'nom'       => 'Dupont',
                    'prenom'    => 'Jean',
                    'email'     => 'jean.dupont@test.com',
                    'telephone' => '0102030405',
                    'seniorite' => 'Junior',
                    'direction' => 'IT',
                    'disponible'=> true,
                ];

            case 'drhs':
                return [
                    'nom'   => 'RHName',
                    'email' => 'drh@example.com'
                ];

            case 'maincourantes':
                // on créera un astreignable au préalable si besoin
                return [
                    'date'         => '2025-07-16',
                    'details'      => 'Un détail de test',
                    'astreignable' => 1
                ];

            case 'planningastreintes':
                return [
                    'dateDebut' => '2025-07-16',
                    'dateFin'   => '2025-07-17',
                    'theme'     => 'Urgence',
                    'statut'    => 'Actif'
                ];

            case 'servicefaits':
                return [
                    'date'             => '2025-07-16',
                    'heuresEffectuees' => 3,
                    'valide'           => true,
                    'astreignable'     => 1
                ];

            default:
                return [];
        }
    }

    private function assertFullCrud(string $endpoint): void
    {
        // 1) INDEX
        $this->client->request('GET', "/api/$endpoint");
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // 2) CREATE
        $payload = $this->getPayload($endpoint);
        $this->client->request(
            'POST',
            "/api/$endpoint",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );
        $status = $this->client->getResponse()->getStatusCode();
        $this->assertSame(201, $status, "POST /api/$endpoint doit renvoyer 201, got $status.");

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data, 'La réponse doit contenir la clé "id".');
        $id = $data['id'];

        // 3) SHOW
        $this->client->request('GET', "/api/$endpoint/$id");
        $this->assertResponseIsSuccessful();
        $returned = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame($id, $returned['id']);

        // 4) UPDATE
        // On modifie un champ générique pour vérifier
        $updated = $payload;
        if (isset($updated['nom'])) {
            $updated['nom'] .= '_upd';
        } elseif (isset($updated['theme'])) {
            $updated['theme'] .= '_upd';
        }
        $this->client->request(
            'PUT',
            "/api/$endpoint/$id",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($updated)
        );
        $this->assertResponseIsSuccessful();
        $respUpd = json_decode($this->client->getResponse()->getContent(), true);
        // On compare juste un champ pour s'assurer que le PUT a marché
        if (isset($respUpd['nom'])) {
            $this->assertStringEndsWith('_upd', $respUpd['nom']);
        } elseif (isset($respUpd['theme'])) {
            $this->assertStringEndsWith('_upd', $respUpd['theme']);
        }

        // 5) DELETE
        $this->client->request('DELETE', "/api/$endpoint/$id");
        $this->assertSame(204, $this->client->getResponse()->getStatusCode(), "DELETE /api/$endpoint/$id doit renvoyer 204.");

        // 6) SHOW après suppression → 404
        $this->client->request('GET', "/api/$endpoint/$id");
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());

        // 7) opérations sur ID inexistant
        foreach (['GET', 'PUT', 'DELETE'] as $method) {
            $this->client->request(
                $method,
                "/api/$endpoint/999999",
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($payload)
            );
            $this->assertContains(
                $this->client->getResponse()->getStatusCode(),
                [404, 400],
                "$method /api/$endpoint/999999 devrait renvoyer 404 ou 400."
            );
        }
    }

    public function testAdministrateurUCACController(): void
    {
        $this->assertFullCrud('administrateurucacs');
    }

    public function testAstreignableController(): void
    {
        $this->assertFullCrud('astreignables');
    }

    public function testDRHController(): void
    {
        $this->assertFullCrud('drhs');
    }

    public function testMainCouranteController(): void
    {
        $this->assertFullCrud('maincourantes');
    }

    public function testPlanningAstreinteController(): void
    {
        $this->assertFullCrud('planningastreintes');
    }

    public function testServiceFaitController(): void
    {
        $this->assertFullCrud('servicefaits');
    }
}
