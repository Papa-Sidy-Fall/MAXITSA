<?php
use App\Core\Helpers\FlashHelper;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mes Comptes' ?> - Max It SA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Header -->
    <header class="bg-orange-500 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <!-- Logo et titre -->
                <div class="flex items-center space-x-4">
                    <div class="bg-white text-orange-500 px-4 py-2 rounded-2xl shadow-lg">
                        <div class="text-lg font-bold">Max It</div>
                        <div class="text-sm font-bold">SA</div>
                    </div>
                    <h1 class="text-2xl font-bold">Mes Comptes</h1>
                </div>
                <div class="flex space-x-4">
                    <a href="/dashboard" class="bg-orange-400 hover:bg-orange-600 px-4 py-2 rounded">
                        Dashboard
                    </a>
                    <a href="/logout" class="bg-red-500 hover:bg-red-700 px-4 py-2 rounded">
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        
        <!-- Messages Flash -->
        <?php 
        App\Core\Session::start();
        if (App\Core\Session::hasFlash('success')): 
        ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars(App\Core\Session::getFlash('success')) ?>
            </div>
        <?php endif; ?>
        
        <?php if (App\Core\Session::hasFlash('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars(App\Core\Session::getFlash('error')) ?>
            </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="mb-6">
            <a href="/comptes/create" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Ajouter un compte secondaire
            </a>
        </div>

        <!-- Liste des comptes -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($comptes as $compte): ?>
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 <?= $compte['typedecompte'] === 'principal' ? 'border-orange-500' : 'border-gray-300' ?>">
                    
                    <!-- En-tête du compte -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                <?= htmlspecialchars($compte['numtel']) ?>
                            </h3>
                            <span class="inline-block px-2 py-1 text-xs rounded-full <?= $compte['typedecompte'] === 'principal' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800' ?>">
                                <?= ucfirst($compte['typedecompte']) ?>
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-1 text-xs rounded-full <?= $compte['status'] === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= ucfirst($compte['status']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Solde -->
                    <div class="mb-4">
                        <p class="text-2xl font-bold text-green-600">
                            <?= number_format($compte['solde'], 0, ',', ' ') ?> FCFA
                        </p>
                        <p class="text-sm text-gray-500">Solde disponible</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col space-y-2">
                        <a href="/comptes/<?= $compte['id'] ?>" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded text-center text-sm">
                            Voir détails
                        </a>
                        
                        <?php if ($compte['typedecompte'] === 'secondaire'): ?>
                            <form method="POST" action="/comptes/make-principal" class="w-full">
                                <input type="hidden" name="compte_id" value="<?= $compte['id'] ?>">
                                <button type="submit" 
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded text-sm"
                                        onclick="return confirm('Voulez-vous définir ce compte comme principal ?')">
                                    Définir comme principal
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($comptes)): ?>
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun compte</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier compte.</p>
                    <div class="mt-6">
                        <a href="/comptes/create" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                            Créer un compte
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
