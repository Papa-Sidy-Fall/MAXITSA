<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Max It SA' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="bg-orange-500 text-white px-8 py-6 rounded-3xl shadow-lg inline-block mb-8">
                    <div class="text-3xl font-bold">Max It</div>
                    <div class="text-2xl font-bold">SA</div>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Bienvenue sur Max It SA
                </h1>
                <p class="text-gray-600 mb-8">
                    Votre plateforme de transfert d'argent sécurisée
                </p>
                
                <div class="space-y-4">
                    <a href="/login" 
                       class="w-full bg-orange-500 text-white py-3 px-6 rounded-lg font-semibold hover:bg-orange-600 transition duration-200 block text-center">
                        Se Connecter
                    </a>
                    
                    <a href="/register" 
                       class="w-full bg-white text-orange-500 py-3 px-6 rounded-lg font-semibold border-2 border-orange-500 hover:bg-orange-50 transition duration-200 block text-center">
                        Créer un Compte
                    </a>
                </div>
            </div>
            
            <div class="text-center text-sm text-gray-500">
                <!-- <p>✅ Base de données connectée</p>
                <p>🚀 Application opérationnelle</p> -->
            </div>
        </div>
    </div>
</body>
</html>