<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Tests fonctionnels pour le tableau de bord admin
 */
final class DashboardTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $seed    = ['PrefixesSeed', 'TypesOperationsSeed', 'BaremesSeed', 'AdminSeed'];

    protected function setUp(): void
    {
        parent::setUp();
        // Connecter l'admin
        $this->post('/login', ['telephone' => '0330000000']);
    }

    public function testDashboardPageLoads()
    {
        $result = $this->get('/dashboard');
        $result->assertOK();
        $result->assertSee('Tableau de bord');
        $result->assertSee('Clients');
        $result->assertSee('Transactions');
    }

    public function testDashboardClientsPage()
    {
        $result = $this->get('/dashboard/clients');
        $result->assertOK();
        $result->assertSee('Situation des comptes clients');
    }

    public function testDashboardClientsSearch()
    {
        $result = $this->get('/dashboard/clients?search=Admin');
        $result->assertOK();
    }

    public function testDashboardGainsPage()
    {
        $result = $this->get('/dashboard/gains');
        $result->assertOK();
        $result->assertSee('Gains');
    }
}
