<?php
use App\Core\Helpers\FlashHelper;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Service Commercial' ?> - Max It SA</title>
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
                    <h1 class="text-2xl font-bold">Interface Service Commercial</h1>
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

        <!-- Recherche de compte -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Rechercher un compte client</h2>
                    <p class="text-gray-600 mt-2">Saisissez le numéro de téléphone du client pour consulter son compte</p>
                </div>

                <form method="POST" action="/commercial/search" class="space-y-6">
                    
                    <!-- Numéro de téléphone -->
                    <div>
                        <label for="numtel" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de téléphone du client *
                        </label>
                        <input type="tel" 
                               id="numtel" 
                               name="numtel" 
                               value="<?= htmlspecialchars($search_numtel ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-lg"
                               placeholder="Ex: 77 123 45 67"
                               required>
                        <?php if (isset($error)): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($error) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Bouton recherche -->
                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium text-lg">
                        Rechercher le compte
                    </button>

                </form>

            </div>

            <!-- Résultats de la recherche -->
            <?php if (isset($compte)): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    
                    <!-- En-tête du compte -->
                    <div class="bg-orange-50 border-l-4 border-orange-500 p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">
                                    Compte trouvé : <?= htmlspecialchars($compte['numtel']) ?>
                                </h3>
                                <p class="text-gray-600 mt-1">
                                    Client : <?= htmlspecialchars($compte['prenom']) ?> <?= htmlspecialchars($compte['nom']) ?>
                                </p>
                                <p class="text-gray-600">
                                    Email : <?= htmlspecialchars($compte['email']) ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 text-sm rounded-full <?= $compte['typedecompte'] === 'principal' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800' ?>">
                                    Compte <?= ucfirst($compte['typedecompte']) ?>
                                </span>
                                <br>
                                <span class="inline-block px-2 py-1 text-xs rounded-full mt-2 <?= $compte['status'] === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($compte['status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Solde -->
                    <div class="p-6 border-b">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Solde disponible</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">
                                <?= number_format($compte['solde'], 0, ',', ' ') ?> FCFA
                            </p>
                        </div>
                    </div>

                    <!-- Transactions récentes -->
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-lg font-bold text-gray-800">10 dernières transactions</h4>
                            <a href="/commercial/compte/<?= $compte['id'] ?>/transactions?numtel=<?= urlencode($compte['numtel']) ?>" 
                               class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                                Voir tout l'historique
                            </a>
                        </div>

                        <!-- Liste des transactions -->
                        <?php if (!empty($compte['transactions_recentes'])): ?>
                            <div class="space-y-4">
                                <?php foreach ($compte['transactions_recentes'] as $transaction): ?>
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        
                                        <!-- Info transaction -->
                                        <div class="flex items-center space-x-4">
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
                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    <?php if ($transaction['expediteur_id'] == $compte['id']): ?>
                                                        Envoi vers <?= htmlspecialchars($transaction['destinataire_numtel'] ?? 'N/A') ?>
                                                    <?php else: ?>
                                                        Reçu de <?= htmlspecialchars($transaction['expediteur_numtel'] ?? 'N/A') ?>
                                                    <?php endif; ?>
                                                </p>
                                                
                                                <div class="flex items-center space-x-3 text-sm text-gray-500">
                                                    <span><?= ucfirst($transaction['type_transaction']) ?></span>
                                                    <span><?= date('d/m/Y à H:i', strtotime($transaction['created_at'])) ?></span>
                                                    <span class="inline-block px-2 py-1 text-xs rounded-full <?= $transaction['statut'] === 'reussi' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                        <?= ucfirst($transaction['statut']) ?>
                                                    </span>
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
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune transaction</h3>
                                <p class="mt-1 text-sm text-gray-500">Ce compte n'a pas encore d'historique de transactions.</p>
                            </div>
                        <?php endif; ?>

                    </div>

                    <!-- Actions -->
                    <div class="bg-gray-50 px-6 py-4">
                        <div class="flex justify-between items-center text-sm text-gray-600">
                            <span>Compte créé le : <?= date('d/m/Y', strtotime($compte['created_at'])) ?></span>
                            <span>Dernière mise à jour : <?= date('d/m/Y', strtotime($compte['updated_at'])) ?></span>
                        </div>
                    </div>

                </div>
            <?php endif; ?>

        </div>

    </div>

</body>
</html>
