<?php

namespace App\Service;

use App\src\entity\Compte;
use App\src\repository\CompteRepository;

class CompteService
{
    private CompteRepository $compteRepository;

    public function __construct(CompteRepository $compteRepository)
    {
        $this->compteRepository = $compteRepository;
    }

    public function createPrincipalAccount(Compte $compte): bool
    {
        return $this->compteRepository->save($compte);
    }

    public function getPrincipalAccount(int $clientId): ?Compte
    {
        return $this->compteRepository->findPrincipalByClientId($clientId);
    }
}