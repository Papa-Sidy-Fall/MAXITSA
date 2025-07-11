<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard - Max It SA' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="bg-orange-500 text-white px-4 py-2 rounded-xl shadow-lg">
                        <div class="text-lg font-bold">Max It</div>
                        <div class="text-sm font-bold">SA</div>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($user_name) ?></div>
                        <div class="text-xs text-gray-500"><?= htmlspecialchars($user_phone) ?></div>
                    </div>
                    <div class="relative">
                        <button class="bg-orange-100 p-2 rounded-full text-orange-600 hover:bg-orange-200 transition-colors">
                            <i class="fas fa-user w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Bienvenue, <?= htmlspecialchars(explode(' ', $user_name)[0]) ?> !</h1>
            <p class="text-gray-600">Voici un aperçu de votre compte Max It SA</p>
        </div>

        <!-- Balance Card -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-6 mb-8 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm mb-1">Solde du compte principal</p>
                    <p class="text-3xl font-bold"><?= number_format($solde, 0, ',', ' ') ?> FCFA</p>
                    <p class="text-orange-100 text-sm mt-2">
                        <i class="fas fa-phone mr-1"></i><?= htmlspecialchars($user_phone) ?>
                    </p>
                </div>
                <div class="text-right">
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="/transfert" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-paper-plane text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-900">Transférer</h3>
                        <p class="text-gray-600 text-sm">Envoyer de l'argent</p>
                    </div>
                </div>
            </a>
            
            <a href="/depot" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-plus text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-900">Dépôt</h3>
                        <p class="text-gray-600 text-sm">Alimenter le compte</p>
                    </div>
                </div>
            </a>
            
            <a href="/historique" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-history text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-900">Historique</h3>
                        <p class="text-gray-600 text-sm">Voir toutes les transactions</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Transactions récentes</h2>
                    <span class="text-sm text-gray-500"><?= $total_transactions ?> transaction(s) au total</span>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200">
                <?php if (empty($transactions)): ?>
                    <div class="p-8 text-center">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-receipt text-4xl"></i>
                        </div>
                        <p class="text-gray-500">Aucune transaction pour le moment</p>
                        <p class="text-gray-400 text-sm mt-1">Vos transactions apparaîtront ici</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <?php
                                        $bgColor = $transaction['color'] === 'green' ? 'bg-green-100' : 
                                                  ($transaction['color'] === 'red' ? 'bg-red-100' : 'bg-orange-100');
                                        $textColor = $transaction['color'] === 'green' ? 'text-green-600' : 
                                                    ($transaction['color'] === 'red' ? 'text-red-600' : 'text-orange-600');
                                        ?>
                                        <div class="<?= $bgColor ?> p-3 rounded-full">
                                            <?php if ($transaction['type'] === 'depot'): ?>
                                                <i class="fas fa-arrow-down <?= $textColor ?> text-lg"></i>
                                            <?php elseif ($transaction['type'] === 'retrait'): ?>
                                                <i class="fas fa-arrow-up <?= $textColor ?> text-lg"></i>
                                            <?php else: ?>
                                                <i class="fas fa-credit-card <?= $textColor ?> text-lg"></i>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <?= htmlspecialchars($transaction['description']) ?>
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            <?= $transaction['date'] ?>
                                            <?php if ($transaction['type'] === 'paiement'): ?>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    Paiement
                                                </span>
                                            <?php elseif ($transaction['type'] === 'depot'): ?>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Dépôt
                                                </span>
                                            <?php else: ?>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Retrait
                                                </span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold <?= $textColor ?>">
                                        <?= $transaction['montant'] ?> FCFA
                                    </p>
                                    <p class="text-xs text-gray-500 capitalize">
                                        <?= $transaction['statut'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Page <?= $current_page ?> sur <?= $total_pages ?>
                        </div>
                        <div class="flex space-x-2">
                            <?php if ($current_page > 1): ?>
                                <a href="?page=<?= $current_page - 1 ?>" 
                                   class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-chevron-left mr-1"></i>Précédent
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <a href="?page=<?= $current_page + 1 ?>" 
                                   class="px-3 py-2 text-sm font-medium text-white bg-orange-500 border border-orange-500 rounded-md hover:bg-orange-600 transition-colors">
                                    Suivant<i class="fas fa-chevron-right ml-1"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-orange-500 text-white px-3 py-1 rounded-lg">
                        <div class="text-sm font-bold">Max It SA</div>
                    </div>
                    <p class="text-sm text-gray-600">Votre partenaire financier de confiance</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/profile" class="text-sm text-gray-600 hover:text-orange-600 transition-colors">
                        <i class="fas fa-user-cog mr-1"></i>Profil
                    </a>
                    <a href="/logout" class="text-sm text-red-600 hover:text-red-700 transition-colors">
                        <i class="fas fa-sign-out-alt mr-1"></i>Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Animation d'entrée pour les cartes
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.bg-white');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Actualisation automatique du solde toutes les 30 secondes
        setInterval(function() {
            // Ici vous pouvez ajouter une requête AJAX pour actualiser le solde
            console.log('Actualisation du solde...');
        }, 30000);
    </script>
</body>
</html>