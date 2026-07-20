<?php

namespace App\Controllers;

use App\Models\ClientsModel;
use App\Models\PrefixesModel;

class AuthController extends BaseController
{
    protected $clientsModel;
    protected $prefixesModel;

    public function __construct()
    {
        $this->clientsModel   = new ClientsModel();
        $this->prefixesModel  = new PrefixesModel();
    }

    public function login()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('/prefixes');
        }

        $data = [
            'title' => 'Connexion',
        ];
        return $this->render('auth/login', $data);
    }

    public function loginPost()
    {
        $telephone = trim($this->request->getPost('telephone'));

        if (empty($telephone)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez entrer un numéro de téléphone.');
        }

        // Extraire le préfixe (3 premiers chiffres)
        if (strlen($telephone) < 3) {
            return redirect()->back()->withInput()->with('error', 'Numéro de téléphone trop court.');
        }

        $prefixe = substr($telephone, 0, 3);

        // Vérifier que le préfixe existe dans la base
        $prefixeExist = $this->prefixesModel->where('code', $prefixe)->first();

        if (!$prefixeExist) {
            return redirect()->back()->withInput()->with('error', 'Le préfixe "' . esc($prefixe) . '" n\'est pas un préfixe valide de l\'opérateur.');
        }

        // Chercher le client par numéro de téléphone
        $client = $this->clientsModel->findByTelephone($telephone);

        // Si le client n'existe pas, créer un compte automatiquement
        if (!$client) {
            $clientId = $this->clientsModel->save([
                'nom'       => 'Client',
                'prenom'    => 'Nouveau',
                'telephone' => $telephone,
                'solde'     => 0,
            ]);

            $client = $this->clientsModel->find($this->clientsModel->getInsertID());
        }

        // Mettre en session
        $this->session->set('user', [
            'id'        => $client->id,
            'nom'       => $client->nom,
            'prenom'    => $client->prenom,
            'telephone' => $client->telephone,
            'solde'     => $client->solde,
        ]);

        return redirect()->to('/prefixes')->with('success', 'Bienvenue, ' . esc($client->prenom) . ' ' . esc($client->nom) . ' !');
    }

    public function logout()
    {
        $this->session->destroy();

        return redirect()->to('/login')->with('success', 'Déconnexion réussie.');
    }
}
