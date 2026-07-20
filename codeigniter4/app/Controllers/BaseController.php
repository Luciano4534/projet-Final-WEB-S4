<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class BaseController extends Controller
{
    protected $helpers = ['url','form'];
    protected $session;
    protected $request;
    protected $currentUser;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        // Initialisation de la session
        $this->session = \Config\Services::session();
        // Récupération de l'utilisateur connecté (si existe)
        $this->currentUser = $this->session->get('user');
    }
    /**
    * Vérifie si un utilisateur est connecté
    */
    protected function isLoggedIn()
    {
        return $this->currentUser !== null;
    }
    /**
    * Redirection si non connecté
    */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
        return redirect()->to('/login');
    }
    }
    /**
    * Méthode pour envoyer des données aux vues
    */
    protected function render($view, $data = [])
    {
        $data['currentUser'] = $this->currentUser;
        return view($view, $data);

    }
}

?>