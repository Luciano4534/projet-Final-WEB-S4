<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Tests fonctionnels pour l'authentification
 */
final class AuthTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $seed    = ['PrefixesSeed', 'TypesOperationsSeed', 'BaremesSeed', 'AdminSeed'];

    public function testLoginPageLoads()
    {
        $result = $this->get('/login');
        $result->assertOK();
        $result->assertSee('Mobile Money');
        $result->assertSee('Connexion');
    }

    public function testLoginWithValidPhone()
    {
        $result = $this->post('/login', [
            'telephone' => '0331234567',
        ]);
        $result->assertRedirect();
    }

    public function testLoginWithInvalidPrefix()
    {
        $result = $this->post('/login', [
            'telephone' => '9991234567',
        ]);
        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    public function testLoginWithEmptyPhone()
    {
        $result = $this->post('/login', [
            'telephone' => '',
        ]);
        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    public function testAutoCreateClient()
    {
        $this->post('/login', [
            'telephone' => '0339998877',
        ]);

        $db = \Config\Database::connect();
        $client = $db->table('clients')->where('telephone', '0339998877')->get()->getRow();
        $this->assertNotNull($client);
        $this->assertEquals('Client', $client->nom);
        $this->assertEquals('Nouveau', $client->prenom);
        $this->assertEquals(0, $client->solde);
    }

    public function testAdminLoginRedirectsToDashboard()
    {
        $result = $this->post('/login', [
            'telephone' => '0330000000',
        ]);
        $result->assertRedirect();
    }

    public function testLogout()
    {
        $this->post('/login', [
            'telephone' => '0331234567',
        ]);

        $result = $this->get('/logout');
        $result->assertRedirect();
        $result->assertSessionHas('success');
    }
}
