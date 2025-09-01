<?php 
$content = ob_start(); 
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        <!-- Icon -->
        <div class="mb-8">
            <div class="w-24 h-24 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-search text-4xl text-red-500"></i>
            </div>
        </div>
        
        <!-- Error Message -->
        <div class="mb-8">
            <h1 class="text-6xl font-bold text-gray-800 mb-2">404</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Ristorante non trovato</h2>
            <p class="text-gray-600 text-lg mb-6">
                Il ristorante che stai cercando non esiste o non è più attivo.
            </p>
            
            <!-- Reasons -->
            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 mb-8">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Possibili motivi:</h3>
                <ul class="text-left text-gray-600 space-y-2">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-circle text-xs text-red-400 mt-2"></i>
                        <span>L'URL è stato digitato incorrettamente</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-circle text-xs text-red-400 mt-2"></i>
                        <span>Il ristorante ha cambiato il proprio slug</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-circle text-xs text-red-400 mt-2"></i>
                        <span>Il ristorante è stato temporaneamente disattivato</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= BASE_URL ?>" 
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-home"></i>
                <span>Torna alla Home</span>
            </a>
            <a href="mailto:support@menooelo.com" 
               class="inline-flex items-center justify-center gap-2 px-6 py-3 text-blue-600 bg-white border-2 border-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-colors duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-envelope"></i>
                <span>Contatta il Supporto</span>
            </a>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>