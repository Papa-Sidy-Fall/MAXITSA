<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Max It SA - Création de Compte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .upload-area {
            border: 2px dashed #f97316;
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            border-color: #ea580c;
            background-color: #fff7ed;
        }
        .upload-area.dragover {
            border-color: #ea580c;
            background-color: #fff7ed;
        }
        .image-preview {
            max-width: 100%;
            max-height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
        
        @media (min-width: 1024px) {
            .image-preview {
                max-height: 90px;
            }
        }
    </style>
</head>
<body class="bg-gray-50 h-screen flex flex-col lg:flex-row overflow-hidden">
    <!-- Section gauche - Orange -->
    <div class="bg-orange-500 w-full lg:w-1/2 flex flex-col items-center justify-center text-white relative overflow-hidden h-1/3 lg:h-full">
        <!-- Cercle décoratif en bas -->
        <div class="absolute bottom-0 left-0 w-24 h-12 lg:w-48 lg:h-24 bg-white opacity-20 rounded-full transform translate-y-6 lg:translate-y-12 -translate-x-12 lg:-translate-x-24"></div>
        
        <div class="text-center z-10 py-2 lg:py-0">
            <h1 class="text-lg sm:text-xl lg:text-3xl font-bold mb-2 lg:mb-6">Bienvenue Sur</h1>
            
            <!-- Logo -->
            <div class="bg-white text-orange-500 px-3 py-2 lg:px-6 lg:py-4 rounded-2xl shadow-lg">
                <div class="text-lg lg:text-2xl font-bold">Max It</div>
                <div class="text-base lg:text-xl font-bold">SA</div>
            </div>
        </div>
    </div>

    <!-- Section droite - Formulaire -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-3 lg:p-6 h-2/3 lg:h-full overflow-y-auto">
        <div class="w-full max-w-sm">
            <h2 class="text-xl lg:text-2xl font-bold text-orange-500 mb-3 lg:mb-4 text-center">Création du Compte</h2>
            
            <form class="space-y-2 lg:space-y-3">
                <!-- Prénom -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-xs lg:text-sm">
                        Prénom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full px-2 py-1.5 lg:px-3 lg:py-2 border border-gray-300 rounded-md focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-200 text-xs lg:text-sm" required>
                </div>

                <!-- Nom -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-xs lg:text-sm">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full px-2 py-1.5 lg:px-3 lg:py-2 border border-gray-300 rounded-md focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-200 text-xs lg:text-sm" required>
                </div>

                <!-- CNI -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-xs lg:text-sm">
                        CNI <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full px-2 py-1.5 lg:px-3 lg:py-2 border border-gray-300 rounded-md focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-200 text-xs lg:text-sm">
                </div>

                <!-- Numéro Tel -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-xs lg:text-sm">
                        Numéro Tel <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" class="w-full px-2 py-1.5 lg:px-3 lg:py-2 border border-gray-300 rounded-md focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-200 text-xs lg:text-sm">
                </div>

                <!-- Mot de passe -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-xs lg:text-sm">
                        Mot de passe <span class="text-red-500">*</span>
                    </label>
                    <input type="password" class="w-full px-2 py-1.5 lg:px-3 lg:py-2 border border-gray-300 rounded-md focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-200 text-xs lg:text-sm">
                </div>

                <!-- Section Upload Photos -->
                <div class="grid grid-cols-2 gap-2">
                    <!-- CNI RECTO -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-center text-xs">CNI RECTO</label>
                        <div class="upload-area rounded-md p-2 text-center cursor-pointer" onclick="document.getElementById('cni-recto').click()">
                            <div id="recto-preview" class="hidden">
                                <img id="recto-image" class="image-preview mx-auto mb-1" alt="CNI Recto">
                                <p class="text-xs text-gray-600">Cliquez pour changer</p>
                            </div>
                            <div id="recto-placeholder" class="text-orange-500">
                                <svg class="w-6 h-6 lg:w-8 lg:h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-xs">Ajouter</p>
                            </div>
                        </div>
                        <input type="file" id="cni-recto" accept="image/*" class="hidden" onchange="previewImage(this, 'recto')">
                    </div>

                    <!-- CNI VERSO -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-center text-xs">CNI VERSO</label>
                        <div class="upload-area rounded-md p-2 text-center cursor-pointer" onclick="document.getElementById('cni-verso').click()">
                            <div id="verso-preview" class="hidden">
                                <img id="verso-image" class="image-preview mx-auto mb-1" alt="CNI Verso">
                                <p class="text-xs text-gray-600">Cliquez pour changer</p>
                            </div>
                            <div id="verso-placeholder" class="text-orange-500">
                                <svg class="w-6 h-6 lg:w-8 lg:h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-xs">Ajouter</p>
                            </div>
                        </div>
                        <input type="file" id="cni-verso" accept="image/*" class="hidden" onchange="previewImage(this, 'verso')">
                    </div>
                </div>

                <!-- Bouton Créer -->
                <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-md font-semibold hover:bg-orange-600 transition duration-200 text-sm">
                    Créer
                </button>

                <!-- Lien de connexion -->
                <p class="text-center text-gray-600 mt-2 text-xs">
                    Si vous avez déjà un compte? 
                    <a href="#" class="text-orange-500 hover:text-orange-600 font-semibold">Cliquez ici Se Connecter</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input, side) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(side + '-preview');
                    const placeholder = document.getElementById(side + '-placeholder');
                    const image = document.getElementById(side + '-image');
                    
                    image.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Drag and drop functionality
        document.querySelectorAll('.upload-area').forEach(area => {
            area.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            area.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            area.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const input = this.nextElementSibling;
                    input.files = files;
                    
                    const side = input.id.includes('recto') ? 'recto' : 'verso';
                    previewImage(input, side);
                }
            });
        });
    </script>
</body>
</html>