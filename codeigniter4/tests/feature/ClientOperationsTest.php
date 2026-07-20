<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Tests fonctionnels pour les opérations client
 */
final class ClientOperationsTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $seed    = ['PrefixesSeed', 'TypesOperationsSeed', 'BaremesSeed', 'AdminSeed'];

    protected function setUp(): void
    {
        parent::setUp();
        // Créer un client de test et le connecter
        $this->post('/login', ['telephone' => '0331112233']);
    }

    public function testSoldePageLoads()
    {
        $result = $this->get('/client/solde');
        $result->assertOK();
        $result->assertSee('Mon Solde');
    }

    public function testDepotPageLoads()
    {
        $result = $this->get('/client/depot');
        $result->assertOK();
        $result->assertSee('Dépôt');
    }

    public function testDepotValid()
    {
        $result = $this->post('/client/depot', [
            'montant' => '5000',
        ]);
        $result->assertRedirect();
        $result->assertSessionHas('success');
    }

    public function testDepotInvalidMontant()
    {
        $result = $this->post('/client/depot', [
            'montant' => '-100',
        ]);
        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testRetraitPageLoads()
    {
        $result = $this->get('/client/retrait');
        $result->assertOK();
        $result->assertSee('Retrait');
    }

    public function testRetraitSoldeInsuffisant()
    {
        $result = $this->post('/client/retrait', [
            'montant' => '100000',
        ]);
        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    public function testTransfertPageLoads()
    {
        $result = $this->get('/client/transfert');
        $result->assertOK();
        $result->assertSee('Transfert');
    }

    public function testTransfertToSelf()
    {
        $result = $this->post('/client/transfert', [
            'montant'       => '1000',
            'telephone_dest' => '0331112233',
        ]);
        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    public function testTransfertToInexistantClient()
    {
        $result = $this->post('/client/transfert', [
            'montant'       => '1000',
            'telephone_dest' => '0330000000',
        ]);
        // Devrait échouer car le destinataire n'existe pas dans cette configuration
        $result->assertRedirect();
    }

    public function testHistoriquePageLoads()
    {
        $result = $this->get('/client/historique');
        $result->assertOK();
        $result->assertSee('Historique');
    }
}
