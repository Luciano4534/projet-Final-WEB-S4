<?php

namespace App\Controllers;

class WelcomeController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Bienvenue',
        ];
        return $this->render('welcome', $data);
    }
}
