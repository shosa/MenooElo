<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/admin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-0">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">QR Code</h1>
                    <p class="text-gray-600 mt-1">Genera e personalizza il QR Code per il tuo menu</p>
                </div>
                <div>
                    <button onclick="window.print()" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-print"></i>
                        <span>Stampa QR Code</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- QR Code Display -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-qrcode text-blue-600"></i>
                        Il tuo QR Code
                    </h2>
                    
                    <div class="qr-container text-center" id="qrContainer">
                        <div class="inline-block p-8 bg-white border-2 border-gray-200 rounded-xl">
                            <!-- QR Code generated via API -->
                            <div class="w-64 h-64 mb-4 mx-auto">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=<?= urlencode($menu_url) ?>" 
                                     alt="Menu QR Code"
                                     class="w-full h-full rounded-lg border border-gray-200"
                                     id="qrCodeImage">
                            </div>
                            
                            <!-- Restaurant Info -->
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-gray-900">
                                    <?= htmlspecialchars($restaurant['name']) ?>
                                </h3>
                                <p class="text-gray-600 text-sm mt-1">Scansiona per visualizzare il menu</p>
                                <div class="mt-3 text-xs text-gray-500 break-all">
                                    <?= htmlspecialchars($menu_url) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- QR Info -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-blue-800">Come usare il QR Code:</h4>
                                <ul class="text-sm text-blue-700 mt-2 space-y-1">
                                    <li>• Stampa il QR Code e posizionalo sui tavoli</li>
                                    <li>• I clienti possono scansionarlo con la fotocamera del telefono</li>
                                    <li>• Verranno reindirizzati direttamente al tuo menu online</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Customization Options -->
                <div class="space-y-6">
                    <!-- Size Options -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-expand-arrows-alt text-blue-600"></i>
                            Dimensioni
                        </h3>
                        
                        <div class="grid grid-cols-3 gap-3">
                            <button onclick="changeSize('small')" 
                                    class="p-3 text-center border-2 border-gray-200 rounded-lg hover:border-blue-600 hover:bg-blue-50 transition-colors" 
                                    data-size="small">
                                <div class="w-12 h-12 bg-gray-100 rounded mx-auto mb-2"></div>
                                <span class="text-sm font-medium">Piccolo</span>
                                <div class="text-xs text-gray-500">200x200px</div>
                            </button>
                            <button onclick="changeSize('medium')" 
                                    class="p-3 text-center border-2 border-blue-600 bg-blue-50 rounded-lg hover:border-blue-600 hover:bg-blue-50 transition-colors" 
                                    data-size="medium">
                                <div class="w-16 h-16 bg-gray-100 rounded mx-auto mb-2"></div>
                                <span class="text-sm font-medium">Medio</span>
                                <div class="text-xs text-gray-500">300x300px</div>
                            </button>
                            <button onclick="changeSize('large')" 
                                    class="p-3 text-center border-2 border-gray-200 rounded-lg hover:border-blue-600 hover:bg-blue-50 transition-colors" 
                                    data-size="large">
                                <div class="w-20 h-20 bg-gray-100 rounded mx-auto mb-2"></div>
                                <span class="text-sm font-medium">Grande</span>
                                <div class="text-xs text-gray-500">400x400px</div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Download Options -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-download text-blue-600"></i>
                            Download
                        </h3>
                        
                        <div class="space-y-3">
                            <button onclick="downloadQR('png')" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-file-image"></i>
                                <span>Scarica PNG</span>
                            </button>
                            <button onclick="downloadQR('pdf')" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-file-pdf"></i>
                                <span>Scarica PDF</span>
                            </button>
                            <button onclick="downloadQR('svg')" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-vector-square"></i>
                                <span>Scarica SVG</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- URL Preview -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-link text-blue-600"></i>
                            URL Menu
                        </h3>
                        
                        <div class="flex items-center gap-2">
                            <input type="text" 
                                   value="<?= htmlspecialchars($menu_url) ?>" 
                                   readonly
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm">
                            <button onclick="copyToClipboard()" 
                                    class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                    title="Copia URL">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">URL che verrà aperto quando i clienti scansioneranno il QR Code</p>
                    </div>
                    
                    <!-- Menu Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            Informazioni Menu
                        </h3>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Ristorante</span>
                                <span class="font-medium text-gray-900"><?= htmlspecialchars($restaurant['name']) ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Stato</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $restaurant['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $restaurant['is_active'] ? 'Attivo' : 'Inattivo' ?>
                                </span>
                            </div>
                            <?php if ($restaurant['address']): ?>
                            <div class="flex items-start justify-between">
                                <span class="text-gray-600">Indirizzo</span>
                                <span class="font-medium text-gray-900 text-right"><?= nl2br(htmlspecialchars($restaurant['address'])) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-4">
                            <a href="<?= BASE_URL ?>/admin/analytics" 
                               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm">
                                <span>Vedi statistiche dettagliate</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentSize = 'medium';

function changeSize(size) {
    // Remove active class from all buttons
    document.querySelectorAll('[data-size]').forEach(btn => {
        btn.classList.remove('border-blue-600', 'bg-blue-50');
        btn.classList.add('border-gray-200');
    });
    
    // Add active class to selected button
    const selectedBtn = document.querySelector(`[data-size="${size}"]`);
    selectedBtn.classList.add('border-blue-600', 'bg-blue-50');
    selectedBtn.classList.remove('border-gray-200');
    
    currentSize = size;
    
    // TODO: Update QR code size
    console.log(`Changing QR code size to: ${size}`);
}

function downloadQR(format) {
    // TODO: Implement download functionality
    console.log(`Downloading QR code as: ${format}`);
    
    // Show success message
    const message = document.createElement('div');
    message.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg shadow-lg z-50';
    message.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>Download avviato (${format.toUpperCase()})</span>
        </div>
    `;
    document.body.appendChild(message);
    
    setTimeout(() => {
        document.body.removeChild(message);
    }, 3000);
}

function copyToClipboard() {
    const urlInput = document.querySelector('input[readonly]');
    urlInput.select();
    document.execCommand('copy');
    
    // Show success message
    const message = document.createElement('div');
    message.className = 'fixed top-4 right-4 bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-lg shadow-lg z-50';
    message.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>URL copiato negli appunti!</span>
        </div>
    `;
    document.body.appendChild(message);
    
    setTimeout(() => {
        document.body.removeChild(message);
    }, 3000);
}

// Print styles
const style = document.createElement('style');
style.textContent = `
    @media print {
        .flex, .grid { display: block !important; }
        .hidden { display: none !important; }
        .print\\:block { display: block !important; }
        body * { visibility: hidden; }
        #qrContainer, #qrContainer * { visibility: visible; }
        #qrContainer { 
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    }
`;
document.head.appendChild(style);
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>