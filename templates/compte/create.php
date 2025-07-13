<?php
use App\Core\Helpers\FlashHelper;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Nouveau compte' ?> - Max It SA</title>
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
                    <h1 class="text-2xl font-bold">Ajouter un compte secondaire</h1>
                </div>
                <a href="/comptes" class="bg-orange-400 hover:bg-orange-600 px-4 py-2 rounded">
                    Retour aux comptes
                </a>
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

        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-8">
                
                <form method="POST" action="/comptes/store" enctype="multipart/form-data" class="space-y-6">
                    
                    <!-- Numéro de téléphone -->
                    <div>
                        <label for="numtel" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de téléphone *
                        </label>
                        <input type="tel" 
                               id="numtel" 
                               name="numtel" 
                               value="<?= htmlspecialchars($old['numtel'] ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 <?= isset($errors['numtel']) ? 'border-red-500' : '' ?>"
                               placeholder="Ex: 77 123 45 67"
                               required>
                        <?php if (isset($errors['numtel'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($errors['numtel']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Photos CNI (optionnelles) -->
                    <div class="grid md:grid-cols-2 gap-6">
                        
                        <!-- CNI Recto -->
                        <div>
                            <label for="photocnirecto" class="block text-sm font-medium text-gray-700 mb-2">
                                CNI Recto (optionnel)
                            </label>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                <input type="file" 
                                       id="photocnirecto" 
                                       name="photocnirecto" 
                                       accept="image/*"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium text-orange-600">Cliquez pour choisir</span> ou glissez-déposez
                                </p>
                                <p class="text-xs text-gray-500">PNG, JPG jusqu'à 2MB</p>
                            </div>
                            <?php if (isset($errors['photocnirecto'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($errors['photocnirecto']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- CNI Verso -->
                        <div>
                            <label for="photocniverso" class="block text-sm font-medium text-gray-700 mb-2">
                                CNI Verso (optionnel)
                            </label>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                <input type="file" 
                                       id="photocniverso" 
                                       name="photocniverso" 
                                       accept="image/*"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium text-orange-600">Cliquez pour choisir</span> ou glissez-déposez
                                </p>
                                <p class="text-xs text-gray-500">PNG, JPG jusqu'à 2MB</p>
                            </div>
                            <?php if (isset($errors['photocniverso'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($errors['photocniverso']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Informations -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="flex-shrink-0 h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-orange-800">
                                    À propos des comptes secondaires
                                </h3>
                                <div class="mt-2 text-sm text-orange-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Un compte secondaire vous permet de recevoir des transferts sur un autre numéro</li>
                                        <li>Vous pouvez avoir plusieurs comptes secondaires</li>
                                        <li>Le solde de base est de 0 FCFA</li>
                                        <li>Vous pouvez changer un compte secondaire en compte principal à tout moment</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <a href="/comptes" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Annuler
                        </a>
                        <button type="submit" class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 focus:ring-2 focus:ring-orange-500">
                            Créer le compte
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

    <script>
        // Preview des images
        function setupFilePreview(inputId) {
            const input = document.getElementById(inputId);
            const container = input.parentElement;
            
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.createElement('img');
                        preview.src = e.target.result;
                        preview.className = 'mt-2 max-w-full h-32 object-cover rounded';
                        
                        // Supprimer l'ancienne preview s'il y en a une
                        const oldPreview = container.querySelector('img');
                        if (oldPreview) {
                            oldPreview.remove();
                        }
                        
                        container.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        setupFilePreview('photocnirecto');
        setupFilePreview('photocniverso');
    </script>

</body>
</html>
