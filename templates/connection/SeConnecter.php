<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Max It SA - Se Connecter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 h-screen flex flex-col lg:flex-row overflow-hidden">
    <!-- Section gauche - Orange -->
    <div class="bg-orange-500 w-full lg:w-1/2 flex flex-col items-center justify-center text-white relative overflow-hidden h-1/2 lg:h-full">
        <!-- Cercle décoratif en bas -->
        <div class="absolute bottom-0 left-0 w-32 h-16 lg:w-64 lg:h-32 bg-white opacity-20 rounded-full transform translate-y-8 lg:translate-y-16 -translate-x-16 lg:-translate-x-32"></div>
        
        <div class="text-center z-10 py-4 lg:py-0">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 lg:mb-8">Bienvenue Sur</h1>
            
            <!-- Logo -->
            <div class="bg-white text-orange-500 px-6 py-4 lg:px-8 lg:py-6 rounded-3xl shadow-lg">
                <div class="text-2xl lg:text-3xl font-bold">Max It</div>
                <div class="text-xl lg:text-2xl font-bold">SA</div>
            </div>
        </div>
    </div>

    <!-- Section droite - Formulaire -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-8 h-1/2 lg:h-full">
        <div class="w-full max-w-md">
            <h2 class="text-2xl lg:text-3xl font-bold text-orange-500 mb-8 lg:mb-12 text-center">Se Connecter</h2>
            
            <form class="space-y-6 lg:space-y-8">
                <!-- Numéro Tel -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2 text-sm lg:text-base">
                        Num Tel <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" class="w-full px-4 py-3 lg:px-5 lg:py-4 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 text-sm lg:text-base" required>
                </div>

                <!-- Mot de passe -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2 text-sm lg:text-base">
                        Mot de passe <span class="text-red-500">*</span>
                    </label>
                    <input type="password" class="w-full px-4 py-3 lg:px-5 lg:py-4 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 text-sm lg:text-base" required>
                </div>

                <!-- Bouton Se connecter -->
                <button type="submit" class="w-full bg-orange-500 text-white py-3 lg:py-4 rounded-lg font-semibold hover:bg-orange-600 transition duration-200 text-sm lg:text-base">
                    Se connecter
                </button>

                <!-- Lien de création de compte -->
                <p class="text-center text-gray-600 mt-6 lg:mt-8 text-sm lg:text-base">
                    Si vous n'avez pas un compte? 
                    <a href="#" class="text-orange-500 hover:text-orange-600 font-semibold">Cliquer ici Créer un Compte</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>