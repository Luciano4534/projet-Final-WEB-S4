<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Tests fonctionnels pour le CRUD Barèmes
 */
final class BaremesCrudTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $seed    = ['PrefixesSeed', 'TypesOperationsSeed', 'BaremesSeed', 'AdminSeed'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->post('/login', ['telephone' => '0330000000']);
    }

    public function testBaremesListPage()
    {
        $result = $this->get('/baremes');
        $result->assertOK();
        $result->assertSee('Barèmes');
    }

    public function testBaremesCreatePage()
    {
        $result = $this->get('/baremes/create');
        $result->assertOK();
        $result->assertSee('Ajouter');
    }

    public function testBaremesStore()
    {
        $result = $this->post('/baremes/store', [
            'type_operation_id' => '1',
            'montant_min'       => '2000000',
            'montant_max'       => '5000000',
            'frais'             => '5000',
        ]);
        $result->assertRedirect();
        $result->assertSessionHas('success');
    }

    public function testBaremesEditPage()
    {
        $result = $this->get('/baremes/edit/1');
        $result->assertOK();
        $result->assertSee('Modifier');
    }

    public function testBaremesUpdate()
    {
        $result = $this->post('/baremes/update/1', [
            'type_operation_id' => '1',
            'montant_min'       => '0',
            'montant_max'       => '10000',
            'frais'             => '150',
        ]);
        $result->assertRedirect();
        $result->assertSessionHas('success');
    }

    public function testBaremesDelete()
    {
        $result = $this->post('/baremes/delete/1');
        $result->assertRedirect();
        $result->assertSessionHas('success');
    }
}
