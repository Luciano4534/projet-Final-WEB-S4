<?php
namespace App\Controllers;

class Produits extends BaseController
{
    public function index()
    {
        return "Liste des produits";
    }

    public function show($id)
    {
        return "Détails du produit avec l'ID: " . $id;
    }
}

?>