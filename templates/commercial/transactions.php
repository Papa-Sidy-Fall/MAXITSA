<?php
use App\Core\Helpers\FlashHelper;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Historique des transactions' ?> - Max It SA</title>
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
                    <h1 class="text-2xl font-bold">Historique des transactions</h1>
                </div>
                <div class="flex space-x-4">
                    <a href="/commercial" class="bg-orange-400 hover:bg-orange-600 px-4 py-2 rounded">
                        Retour à la recherche
                    </a>
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

        <!-- Informations du compte -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        Compte : <?= htmlspecialchars($compte['numtel']) ?>
                    </h2>
                    <p class="text-gray-600">
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
                    <p class="text-2xl font-bold text-green-600 mt-2">
                        <?= number_format($compte['solde'], 0, ',', ' ') ?> FCFA
                    </p>
                </div>
            </div>
        </div>

        <!-- Filtres de recherche -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Filtrer les transactions</h3>
            
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Maintenir les paramètres d'URL existants -->
                <input type="hidden" name="numtel" value="<?= htmlspecialchars($compte['numtel']) ?>">
                
                <!-- Date de début -->
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de début
                    </label>
                    <input type="date" 
                           id="date_debut" 
                           name="date_debut" 
                           value="<?= htmlspecialchars($filters['date_debut'] ?? '') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <!-- Date de fin -->
                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de fin
                    </label>
                    <input type="date" 
                           id="date_fin" 
                           name="date_fin" 
                           value="<?= htmlspecialchars($filters['date_fin'] ?? '') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <!-- Type de transaction -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Type de transaction
                    </label>
                    <select id="type" 
                            name="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Tous les types</option>
                        <option value="transfert" <?= ($filters['type'] ?? '') === 'transfert' ? 'selected' : '' ?>>Transfert</option>
                        <option value="depot" <?= ($filters['type'] ?? '') === 'depot' ? 'selected' : '' ?>>Dépôt</option>
                        <option value="retrait" <?= ($filters['type'] ?? '') === 'retrait' ? 'selected' : '' ?>>Retrait</option>
                        <option value="paiement" <?= ($filters['type'] ?? '') === 'paiement' ? 'selected' : '' ?>>Paiement</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex space-x-2">
                    <button type="submit" 
                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium">
                        Filtrer
                    </button>
                    <a href="?numtel=<?= urlencode($compte['numtel']) ?>" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Liste des transactions -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">
                        Historique des transactions
                        <?php if (!empty($filters['date_debut']) || !empty($filters['date_fin']) || !empty($filters['type'])): ?>
                            (Filtré)
                        <?php endif; ?>
                    </h3>
                    <p class="text-sm text-gray-600">
                        Total : <?= count($transactions) ?> transaction(s)
                    </p>
                </div>
            </div>

            <?php if (!empty($transactions)): ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($transactions as $transaction): ?>
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                
                                <!-- Info transaction -->
                                <div class="flex items-center space-x-4">
                                    <!-- Icône -->
                                    <div class="flex-shrink-0">
                                        <?php if ($transaction['expediteur_id'] == $compte['id']): ?>
                                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                                </svg>
                                            </div>
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Détails -->
                                    <div>
                                        <p class="text-lg font-medium text-gray-900">
                                            <?php if ($transaction['expediteur_id'] == $compte['id']): ?>
                                                Envoi vers <?= htmlspecialchars($transaction['destinataire_numtel'] ?? 'N/A') ?>
                                            <?php else: ?>
                                                Reçu de <?= htmlspecialchars($transaction['expediteur_numtel'] ?? 'N/A') ?>
                                            <?php endif; ?>
                                        </p>
                                        
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                                            <span class="font-medium"><?= ucfirst($transaction['type_transaction']) ?></span>
                                            <span><?= date('d/m/Y à H:i:s', strtotime($transaction['created_at'])) ?></span>
                                            <span class="inline-block px-2 py-1 text-xs rounded-full <?= $transaction['statut'] === 'reussi' ? 'bg-green-100 text-green-800' : ($transaction['statut'] === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                                <?= ucfirst($transaction['statut']) ?>
                                            </span>
                                        </div>

                                        <?php if (!empty($transaction['description'])): ?>
                                            <p class="text-sm text-gray-600 mt-2">
                                                <?= htmlspecialchars($transaction['description']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Montant -->
                                <div class="text-right">
                                    <p class="text-xl font-bold <?= $transaction['expediteur_id'] == $compte['id'] ? 'text-red-600' : 'text-green-600' ?>">
                                        <?= $transaction['expediteur_id'] == $compte['id'] ? '-' : '+' ?>
                                        <?= number_format($transaction['montant'], 0, ',', ' ') ?> FCFA
                                    </p>
                                    <?php if (isset($transaction['frais']) && $transaction['frais'] > 0 && $transaction['expediteur_id'] == $compte['id']): ?>
                                        <p class="text-sm text-gray-500">
                                            Frais: <?= number_format($transaction['frais'], 0, ',', ' ') ?> FCFA
                                        </p>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="bg-gray-50 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Page <?= $pagination['current_page'] ?> sur <?= $pagination['total_pages'] ?>
                                (<?= $pagination['total_items'] ?> transaction(s) au total)
                            </div>
                            
                            <div class="flex space-x-2">
                                <!-- Page précédente -->
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <?php
                                    $prevParams = $_GET;
                                    $prevParams['page'] = $pagination['current_page'] - 1;
                                    ?>
                                    <a href="?<?= http_build_query($prevParams) ?>" 
                                       class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-50">
                                        Précédent
                                    </a>
                                <?php endif; ?>

                                <!-- Pages -->
                                <?php 
                                $start = max(1, $pagination['current_page'] - 2);
                                $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                                ?>
                                
                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                    <?php
                                    $pageParams = $_GET;
                                    $pageParams['page'] = $i;
                                    ?>
                                    <a href="?<?= http_build_query($pageParams) ?>" 
                                       class="px-3 py-2 rounded-lg <?= $i === $pagination['current_page'] ? 'bg-orange-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <!-- Page suivante -->
                                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                    <?php
                                    $nextParams = $_GET;
                                    $nextParams['page'] = $pagination['current_page'] + 1;
                                    ?>
                                    <a href="?<?= http_build_query($nextParams) ?>" 
                                       class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-50">
                                        Suivant
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune transaction trouvée</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        <?php if (!empty($filters['date_debut']) || !empty($filters['date_fin']) || !empty($filters['type'])): ?>
                            Aucune transaction ne correspond aux critères de recherche.
                        <?php else: ?>
                            Ce compte n'a pas encore d'historique de transactions.
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($filters['date_debut']) || !empty($filters['date_fin']) || !empty($filters['type'])): ?>
                        <div class="mt-4">
                            <a href="?numtel=<?= urlencode($compte['numtel']) ?>" 
                               class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium">
                                Voir toutes les transactions
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>

    </div>

</body>
</html>
