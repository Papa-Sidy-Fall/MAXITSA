<?php
use App\Core\Helpers\FlashHelper;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Détails du compte' ?> - Max It SA</title>
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
                    <h1 class="text-2xl font-bold">Détails du compte</h1>
                </div>
                <div class="flex space-x-4">
                    <a href="/comptes" class="bg-orange-400 hover:bg-orange-600 px-4 py-2 rounded">
                        Mes comptes
                    </a>
                    <a href="/dashboard" class="bg-orange-400 hover:bg-orange-600 px-4 py-2 rounded">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        
        <!-- Messages Flash -->
        <?= FlashHelper::render() ?>

        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Informations du compte -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 <?= $compte['typedecompte'] === 'principal' ? 'border-orange-500' : 'border-gray-300' ?>">
                    
                    <!-- En-tête -->
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-bold text-gray-800">
                            <?= htmlspecialchars($compte['numtel']) ?>
                        </h2>
                        <span class="inline-block px-3 py-1 text-sm rounded-full <?= $compte['typedecompte'] === 'principal' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800' ?>">
                            Compte <?= ucfirst($compte['typedecompte']) ?>
                        </span>
                    </div>

                    <!-- Solde -->
                    <div class="text-center mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-3xl font-bold text-green-600">
                            <?= number_format($compte['solde'], 0, ',', ' ') ?> FCFA
                        </p>
                        <p class="text-sm text-gray-500">Solde disponible</p>
                    </div>

                    <!-- Statut -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Statut :</span>
                            <span class="inline-block px-2 py-1 text-xs rounded-full <?= $compte['status'] === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= ucfirst($compte['status']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Créé le :</span>
                            <span class="font-medium"><?= date('d/m/Y', strtotime($compte['created_at'])) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Modifié le :</span>
                            <span class="font-medium"><?= date('d/m/Y', strtotime($compte['updated_at'])) ?></span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <?php if ($compte['typedecompte'] === 'secondaire'): ?>
                        <div class="mt-6 pt-6 border-t">
                            <form method="POST" action="/comptes/make-principal" class="w-full">
                                <input type="hidden" name="compte_id" value="<?= $compte['id'] ?>">
                                <button type="submit" 
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg font-medium"
                                        onclick="return confirm('Voulez-vous définir ce compte comme principal ?')">
                                    Définir comme compte principal
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Transactions récentes -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    
                    <!-- En-tête des transactions -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-bold text-gray-800">Transactions récentes</h2>
                            <a href="/comptes/<?= $compte['id'] ?>/transactions" 
                               class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                                Voir tout l'historique
                            </a>
                        </div>
                    </div>

                    <!-- Liste des transactions -->
                    <div class="divide-y divide-gray-200">
                        <?php if (!empty($transactions)): ?>
                            <?php foreach ($transactions as $transaction): ?>
                                <div class="p-6 hover:bg-gray-50">
                                    <div class="flex justify-between items-center">
                                        
                                        <!-- Informations transaction -->
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <!-- Icône -->
                                                <div class="flex-shrink-0">
                                                    <?php if ($transaction['expediteur_id'] == $compte['id']): ?>
                                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                                            </svg>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                            </svg>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Détails -->
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-900">
                                                        <?php if ($transaction['expediteur_id'] == $compte['id']): ?>
                                                            Envoi vers <?= htmlspecialchars($transaction['destinataire_numtel'] ?? 'N/A') ?>
                                                        <?php else: ?>
                                                            Reçu de <?= htmlspecialchars($transaction['expediteur_numtel'] ?? 'N/A') ?>
                                                        <?php endif; ?>
                                                    </p>
                                                    
                                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                        <span><?= ucfirst($transaction['type_transaction']) ?></span>
                                                        <span><?= date('d/m/Y à H:i', strtotime($transaction['created_at'])) ?></span>
                                                        <span class="inline-block px-2 py-1 text-xs rounded-full <?= $transaction['statut'] === 'reussi' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                            <?= ucfirst($transaction['statut']) ?>
                                                        </span>
                                                    </div>
                                                    
                                                    <?php if (!empty($transaction['description'])): ?>
                                                        <p class="text-sm text-gray-600 mt-1">
                                                            <?= htmlspecialchars($transaction['description']) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Montant -->
                                        <div class="text-right">
                                            <p class="font-bold <?= $transaction['expediteur_id'] == $compte['id'] ? 'text-red-600' : 'text-green-600' ?>">
                                                <?= $transaction['expediteur_id'] == $compte['id'] ? '-' : '+' ?>
                                                <?= number_format($transaction['montant'], 0, ',', ' ') ?> FCFA
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune transaction</h3>
                                <p class="mt-1 text-sm text-gray-500">Ce compte n'a pas encore d'historique de transactions.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        </div>

    </div>

</body>
</html>
