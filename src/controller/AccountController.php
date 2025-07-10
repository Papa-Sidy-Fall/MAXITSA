<?php

namespace App\Controller;

use App\core\abstract\AbstractController;
use App\src\service\CompteService;

class AccountController extends AbstractController
{
    private CompteService $compteService;

    public function __construct()
    {
        $this->compteService = new CompteService(new \App\src\repository\CompteRepository());
    }

    public function index()
    {
        if (!isset($_SESSION['client_id'])) {
            $this->redirect('/login');
        }

        $compte = $this->compteService->getPrincipalAccount($_SESSION['client_id']);
        
        if (!$compte) {
            $this->redirect('/create-account');
        }

        $this->render('account/index', ['compte' => $compte]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $compte = new \App\src\entity\Compte();
            $compte->setNumTel($_POST['numTel'])
                  ->setPhotoCniRecto($this->uploadFile('photoCniRecto'))
                  ->setPhotoCniVerso($this->uploadFile('photoCniVerso'))
                  ->setClientId($_SESSION['client_id'])
                  ->setTypeDeCompte('principal');

            if ($this->compteService->createPrincipalAccount($compte)) {
                $this->redirect('/account');
            } else {
                $this->render('account/create', ['error' => 'Erreur lors de la création du compte']);
            }
        } else {
            $this->render('account/create');
        }
    }

    private function uploadFile(string $fieldName): ?string
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/images/uploads/';
        $fileName = uniqid() . '_' . basename($_FILES[$fieldName]['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadFile)) {
            return '/images/uploads/' . $fileName;
        }

        return null;
    }
}