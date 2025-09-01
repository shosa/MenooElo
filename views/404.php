<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        <!-- Icon -->
        <div class="mb-8">
            <div class="w-24 h-24 mx-auto bg-yellow-100 rounded-full flex items-center justify-center animate-pulse">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500"></i>
            </div>
        </div>
        
        <!-- Error Message -->
        <div class="mb-8">
            <h1 class="text-6xl font-bold text-gray-800 mb-2">404</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Pagina non trovata</h2>
            <p class="text-gray-600 text-lg mb-6">
                La pagina che stai cercando non esiste o non è stata ancora implementata.
            </p>
            
            <!-- Suggestion Box -->
            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 mb-8">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Cosa puoi fare:</h3>
                <div class="text-gray-600 space-y-2">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Controlla l'URL per errori di digitazione</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Torna alla homepage</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Usa il pulsante indietro del browser</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= BASE_URL ?>" 
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                <i class="fas fa-home"></i>
                <span>Torna alla Home</span>
            </a>
            <button onclick="window.history.back()" 
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 text-blue-600 bg-white border-2 border-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Indietro</span>
            </button>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>