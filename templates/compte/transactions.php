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
                    <div>
                        <h1 class="text-2xl font-bold">Historique des transactions</h1>
                        <p class="text-orange-200">Compte: <?= htmlspecialchars($compte['numtel']) ?></p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="/comptes/<?= $compte['id'] ?>" class="bg-orange-400 hover:bg-orange-600 px-4 py-2 rounded">
                        Retour au compte
                    </a>
                    <a href="/comptes" class="bg-orange-400 hover:bg-orange-600 px-4 py-2 rounded">
                        Mes comptes
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        
        <!-- Messages Flash -->
        <?= FlashHelper::render() ?>

        <!-- Filtres de recherche -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Filtrer les transactions</h2>
            
            <form method="GET" class="grid md:grid-cols-4 gap-4">
                
                <!-- Date de début -->
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">
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
                    <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">
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
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                        Type de transaction
                    </label>
                    <select id="type" 
                            name="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Tous les types</option>
                        <option value="paiement" <?= ($filters['type'] ?? '') === 'paiement' ? 'selected' : '' ?>>Paiement</option>
                        <option value="transfert" <?= ($filters['type'] ?? '') === 'transfert' ? 'selected' : '' ?>>Transfert</option>
                        <option value="depot" <?= ($filters['type'] ?? '') === 'depot' ? 'selected' : '' ?>>Dépôt</option>
                        <option value="retrait" <?= ($filters['type'] ?? '') === 'retrait' ? 'selected' : '' ?>>Retrait</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex-1">
                        Filtrer
                    </button>
                    <a href="/comptes/<?= $compte['id'] ?>/transactions" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Effacer
                    </a>
                </div>

            </form>
        </div>

        <!-- Résultats -->
        <div class="bg-white rounded-lg shadow-md">
            
            <!-- En-tête -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        Transactions 
                        <?php if ($pagination['total'] > 0): ?>
                            (<?= number_format($pagination['total']) ?> résultats)
                        <?php endif; ?>
                    </h2>
                    <div class="text-sm text-gray-500">
                        Page <?= $pagination['current_page'] ?> sur <?= $pagination['last_page'] ?>
                    </div>
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
                                    <div class="flex items-center space-x-4">
                                        <!-- Icône et type -->
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
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <p class="font-medium text-gray-900">
                                                    <?php if ($transaction['expediteur_id'] == $compte['id']): ?>
                                                        Envoi vers <?= htmlspecialchars($transaction['destinataire_numtel'] ?? 'N/A') ?>
                                                    <?php else: ?>
                                                        Reçu de <?= htmlspecialchars($transaction['expediteur_numtel'] ?? 'N/A') ?>
                                                    <?php endif; ?>
                                                </p>
                                                
                                                <span class="inline-block px-3 py-1 text-xs rounded-full bg-orange-100 text-orange-800">
                                                    <?= ucfirst($transaction['type_transaction']) ?>
                                                </span>
                                                
                                                <span class="inline-block px-2 py-1 text-xs rounded-full <?= $transaction['statut'] === 'reussi' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                    <?= ucfirst($transaction['statut']) ?>
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                                                <span><?= date('d/m/Y à H:i:s', strtotime($transaction['created_at'])) ?></span>
                                                <?php if (!empty($transaction['description'])): ?>
                                                    <span>•</span>
                                                    <span><?= htmlspecialchars($transaction['description']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Montant -->
                                <div class="text-right">
                                    <p class="text-xl font-bold <?= $transaction['expediteur_id'] == $compte['id'] ? 'text-red-600' : 'text-green-600' ?>">
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune transaction trouvée</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            <?php if (!empty($filters['date_debut']) || !empty($filters['date_fin']) || !empty($filters['type'])): ?>
                                Aucune transaction ne correspond aux critères de recherche.
                            <?php else: ?>
                                Ce compte n'a pas encore d'historique de transactions.
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['last_page'] > 1): ?>
                <div class="p-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        
                        <!-- Informations -->
                        <div class="text-sm text-gray-700">
                            Affichage de <?= (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 ?> 
                            à <?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) ?> 
                            sur <?= number_format($pagination['total']) ?> résultats
                        </div>

                        <!-- Navigation -->
                        <div class="flex space-x-2">
                            <?php if ($pagination['has_prev']): ?>
                                <a href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['current_page'] - 1])) ?>" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                                    Précédent
                                </a>
                            <?php endif; ?>

                            <!-- Pages -->
                            <?php 
                            $start = max(1, $pagination['current_page'] - 2);
                            $end = min($pagination['last_page'], $pagination['current_page'] + 2);
                            ?>
                            
                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <?php if ($i == $pagination['current_page']): ?>
                                    <span class="px-3 py-2 bg-orange-500 text-white rounded-lg text-sm">
                                        <?= $i ?>
                                    </span>
                                <?php else: ?>
                                    <a href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>" 
                                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                                        <?= $i ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($pagination['has_next']): ?>
                                <a href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['current_page'] + 1])) ?>" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                                    Suivant
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>

</body>
</html>
