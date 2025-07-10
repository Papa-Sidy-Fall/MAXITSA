<?php

namespace App\Service;

use App\Repository\ClientRepository;
use App\Repository\CompteRepository;
use App\Entity\Client;
use App\Entity\Compte;

class AuthService
{
    private ClientRepository $clientRepo;
    private CompteRepository $compteRepo;
    
    public function __construct(ClientRepository $clientRepo, CompteRepository $compteRepo)
    {
        $this->clientRepo = $clientRepo;
        $this->compteRepo = $compteRepo;
    }
    
    public function register(array $data, array $files = []): array
    {
        try {
            // Validation
            $errors = $this->validateRegistration($data);
            
            if (!empty($errors)) {
                return ['success' => false, 'errors' => $errors];
            }
            
            // Créer le client
            $client = new Client();
            $client->setPrenom($data['prenom'])
                   ->setNom($data['nom'])
                   ->setEmail($data['email'])
                   ->setCni($data['cni'])
                   ->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
            
            $clientId = $this->clientRepo->create($client);
            
            if (!$clientId) {
                return ['success' => false, 'errors' => ['general' => 'Erreur lors de la création du compte']];
            }
            
            // Gérer l'upload des photos
            $photoCniRecto = $this->handleFileUpload($files['cni-recto'] ?? null, 'recto');
            $photoCniVerso = $this->handleFileUpload($files['cni-verso'] ?? null, 'verso');
            
            // Créer le compte principal
            $compte = new Compte();
            $compte->setNumTel($data['telephone'])
                   ->setClientId($clientId)
                   ->setPhotoCniRecto($photoCniRecto)
                   ->setPhotoCniVerso($photoCniVerso)
                   ->setTypeDeCompte('principal')
                   ->setSolde(0.00)
                   ->setStatus('actif');
            
            $compteId = $this->compteRepo->create($compte);
            
            if (!$compteId) {
                return ['success' => false, 'errors' => ['general' => 'Erreur lors de la création du compte principal']];
            }
            
            return ['success' => true, 'message' => 'Inscription réussie'];
            
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => ['general' => 'Erreur système: ' . $e->getMessage()]];
        }
    }
    
    public function login(string $login, string $password): array
    {
        try {
            $user = $this->clientRepo->findByEmailOrPhone($login);
            
            if (!$user || !password_verify($password, $user['password'])) {
                return ['success' => false, 'error' => 'Identifiants incorrects'];
            }
            
            return [
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'prenom' => $user['prenom'],
                    'nom' => $user['nom'],
                    'email' => $user['email'],
                    'phone' => $user['num_tel'],
                    'compte_id' => $user['compte_id']
                ]
            ];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Erreur système'];
        }
    }
    
    private function validateRegistration(array $data): array
    {
        $errors = [];
        
        // Validation prénom
        if (empty($data['prenom'])) {
            $errors['prenom'] = 'Le prénom est requis';
        }
        
        // Validation nom
        if (empty($data['nom'])) {
            $errors['nom'] = 'Le nom est requis';
        }
        
        // Validation CNI
        if (empty($data['cni'])) {
            $errors['cni'] = 'Le CNI est requis';
        } elseif ($this->clientRepo->cniExists($data['cni'])) {
            $errors['cni'] = 'Ce CNI est déjà utilisé';
        }
        
        // Validation email
        if (empty($data['email'])) {
            $errors['email'] = 'L\'email est requis';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format d\'email invalide';
        } elseif ($this->clientRepo->emailExists($data['email'])) {
            $errors['email'] = 'Cet email est déjà utilisé';
        }
        
        // Validation téléphone
        if (empty($data['telephone'])) {
            $errors['telephone'] = 'Le téléphone est requis';
        } elseif (!preg_match('/^[+]?[0-9]{8,15}$/', $data['telephone'])) {
            $errors['telephone'] = 'Format de téléphone invalide';
        } elseif ($this->clientRepo->telephoneExists($data['telephone'])) {
            $errors['telephone'] = 'Ce numéro de téléphone est déjà utilisé';
        }
        
        // Validation mot de passe
        if (empty($data['password'])) {
            $errors['password'] = 'Le mot de passe est requis';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 6 caractères';
        }
        
        // Validation confirmation mot de passe
        if (empty($data['confirm_password'])) {
            $errors['confirm_password'] = 'La confirmation du mot de passe est requise';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Les mots de passe ne correspondent pas';
        }
        
        return $errors;
    }
    
    private function handleFileUpload(?array $file, string $type): ?string
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $uploadDir = __DIR__ . '/../../public/uploads/cni/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid($type . '_') . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'uploads/cni/' . $filename;
        }
        
        return null;
    }
}