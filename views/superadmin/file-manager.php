<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">File Manager</h1>
                    <p class="text-gray-600 mt-1 flex items-center gap-2">
                        <i class="fas fa-cloud text-blue-500"></i>
                        Gestione avanzata dei file del sistema
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="refreshData()" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-all duration-200">
                        <i class="fas fa-refresh" id="refresh-icon"></i>
                        <span>Aggiorna</span>
                    </button>
                    <div class="relative">
                        <button onclick="toggleToolsDropdown()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200">
                            <i class="fas fa-tools"></i>
                            <span>Strumenti</span>
                            <i class="fas fa-chevron-down text-sm" id="tools-chevron"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="tools-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="py-2">
                                <button onclick="scanOrphaned(); toggleToolsDropdown()" 
                                        class="flex items-center gap-3 w-full px-4 py-3 text-left hover:bg-orange-50 transition-colors duration-200">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-search text-orange-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Scansiona Orfani</div>
                                        <div class="text-xs text-gray-500">Trova file non utilizzati</div>
                                    </div>
                                </button>
                                <button onclick="scanDuplicates(); toggleToolsDropdown()" 
                                        class="flex items-center gap-3 w-full px-4 py-3 text-left hover:bg-purple-50 transition-colors duration-200">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-copy text-purple-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Trova Duplicati</div>
                                        <div class="text-xs text-gray-500">Identifica file identici</div>
                                    </div>
                                </button>
                                <button onclick="exportReport(); toggleToolsDropdown()" 
                                        class="flex items-center gap-3 w-full px-4 py-3 text-left hover:bg-green-50 transition-colors duration-200">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-download text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Esporta Report</div>
                                        <div class="text-xs text-gray-500">Scarica statistiche</div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-6">
            <!-- Stats Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file text-blue-600 text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900" id="total-files">-</div>
                            <div class="text-sm text-gray-500">File</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 font-medium">Totali</span>
                        <div class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                            Sistema
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hdd text-green-600 text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900" id="total-size">-</div>
                            <div class="text-sm text-gray-500">Storage</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 font-medium">Utilizzato</span>
                        <div class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                            Attivo
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-orange-600 text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900" id="orphaned-files">-</div>
                            <div class="text-sm text-gray-500">Orfani</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 font-medium">File</span>
                        <div class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-medium hidden" id="orphaned-warning">
                            Attenzione
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder text-purple-600 text-lg"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900" id="total-directories">-</div>
                            <div class="text-sm text-gray-500">Directory</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 font-medium">Totali</span>
                        <div class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">
                            Struttura
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Explorer -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Explorer Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder-open text-blue-600"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">File Explorer</h2>
                                <div class="flex items-center gap-2 text-sm text-gray-500 mt-1">
                                    <i class="fas fa-location-arrow text-xs"></i>
                                    <span id="current-path">/uploads</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- View Toggle -->
                            <div class="flex items-center bg-gray-100 rounded-lg p-1">
                                <button onclick="setView('grid')" id="grid-view-btn"
                                        class="p-2 rounded-md bg-white shadow-sm transition-all duration-200">
                                    <i class="fas fa-th text-gray-600"></i>
                                </button>
                                <button onclick="setView('list')" id="list-view-btn"
                                        class="p-2 rounded-md hover:bg-gray-200 transition-all duration-200">
                                    <i class="fas fa-list text-gray-600"></i>
                                </button>
                            </div>
                            
                            <!-- Navigation Buttons -->
                            <button onclick="goBack()" id="back-btn"
                                    class="inline-flex items-center gap-2 px-3 py-2 text-gray-600 rounded-lg hover:bg-gray-100 transition-all duration-200 opacity-50 cursor-not-allowed">
                                <i class="fas fa-arrow-left"></i>
                                <span class="hidden sm:inline">Indietro</span>
                            </button>
                            
                            <button onclick="cleanupOrphaned()" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all duration-200">
                                <i class="fas fa-trash-alt"></i>
                                <span>Pulizia</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- File Content Area -->
                <div id="file-content" class="min-h-96">
                    <!-- Grid View -->
                    <div id="grid-view" class="p-6">
                        <div id="empty-folder" class="text-center py-16 hidden">
                            <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-folder-open text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Directory vuota</h3>
                            <p class="text-gray-500">Non ci sono file in questa directory.</p>
                        </div>
                        
                        <div id="files-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                            <!-- Files will be populated here -->
                        </div>
                    </div>
                    
                    <!-- List View -->
                    <div id="list-view" class="p-6 hidden">
                        <div id="empty-folder-list" class="text-center py-16 hidden">
                            <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-folder-open text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Directory vuota</h3>
                            <p class="text-gray-500">Non ci sono file in questa directory.</p>
                        </div>
                        
                        <div id="files-list" class="space-y-2">
                            <!-- Files will be populated here -->
                        </div>
                    </div>
                    
                    <!-- Loading State -->
                    <div id="loading-state" class="p-6">
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Caricamento...</h3>
                            <p class="text-gray-500">Sto recuperando i file...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="preview-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-auto shadow-xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900" id="preview-title">File Preview</h3>
                <button onclick="closePreview()" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Informazioni File</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nome:</span>
                                <span class="font-medium" id="preview-name">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dimensione:</span>
                                <span id="preview-size">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tipo:</span>
                                <span id="preview-type">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Modificato:</span>
                                <span id="preview-modified">-</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview Content -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Anteprima</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div id="preview-image" class="hidden">
                                <img id="preview-img" src="" alt="" class="max-w-full h-auto rounded-lg shadow-sm">
                            </div>
                            <div id="preview-text" class="hidden bg-gray-800 text-green-400 p-4 rounded-lg text-sm font-mono max-h-64 overflow-auto">
                                <pre id="preview-content"></pre>
                            </div>
                            <div id="preview-none" class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-file text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500">Anteprima non disponibile per questo tipo di file</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200">
                <button onclick="deleteCurrentFile()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all duration-200">
                    <i class="fas fa-trash"></i>
                    <span>Elimina</span>
                </button>
                <button onclick="closePreview()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-all duration-200">
                    <span>Chiudi</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Orphaned Files Modal -->
    <div id="orphaned-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-auto shadow-xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">File Orfani Trovati</h3>
                        <p class="text-sm text-gray-500">File non referenziati nel database</p>
                    </div>
                </div>
                <button onclick="closeOrphanedModal()" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <div id="orphaned-empty" class="text-center py-12 hidden">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Nessun file orfano!</h3>
                    <p class="text-gray-500">Tutti i file sono correttamente referenziati nel database.</p>
                </div>
                
                <div id="orphaned-content">
                    <!-- Summary -->
                    <div id="orphaned-summary" class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-orange-800" id="orphaned-count">0 file orfani trovati</div>
                                <div class="text-sm text-orange-600" id="orphaned-size">Spazio sprecato: 0 B</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- File List -->
                    <div id="orphaned-files" class="space-y-3 max-h-96 overflow-y-auto">
                        <!-- Files will be populated here -->
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div id="orphaned-footer" class="flex items-center justify-between p-6 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    <span id="selection-summary">Nessun file selezionato</span>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="selectAllOrphaned()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-all duration-200">
                        Seleziona Tutti
                    </button>
                    <button onclick="deleteSelectedOrphaned()" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all duration-200">
                        <i class="fas fa-trash"></i>
                        <span>Elimina Selezionati</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Duplicates Modal -->
    <div id="duplicates-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-6xl w-full max-h-[90vh] overflow-auto shadow-xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-copy text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">File Duplicati</h3>
                        <p class="text-sm text-gray-500">File identici nel sistema</p>
                    </div>
                </div>
                <button onclick="closeDuplicatesModal()" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <div id="duplicates-empty" class="text-center py-12 hidden">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Nessun duplicato trovato!</h3>
                    <p class="text-gray-500">Tutti i file sono unici nel sistema.</p>
                </div>
                
                <div id="duplicates-content" class="space-y-6 max-h-96 overflow-y-auto">
                    <!-- Duplicates will be populated here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Global Loading Overlay -->
    <div id="global-loading" class="hidden fixed inset-0 bg-black bg-opacity-30 z-40 flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 shadow-xl">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
                </div>
                <p class="text-lg font-semibold text-gray-900" id="loading-message">Caricamento...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Global state
let fileManager = {
    loading: false,
    currentDirectory: '',
    currentPath: '/uploads',
    currentView: 'grid',
    files: [],
    stats: { total_files: 0, total_size: 0, orphaned_files: 0, directories: 0 },
    previewFileData: null,
    orphanedFiles: [],
    duplicates: [],
    selectedOrphaned: [],
    csrfToken: '<?= $csrf_token ?>'
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadDirectory('');
});

// Dropdown toggle
function toggleToolsDropdown() {
    const dropdown = document.getElementById('tools-dropdown');
    const chevron = document.getElementById('tools-chevron');
    dropdown.classList.toggle('hidden');
    chevron.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#tools-dropdown') && !e.target.closest('button[onclick="toggleToolsDropdown()"]')) {
        document.getElementById('tools-dropdown').classList.add('hidden');
        document.getElementById('tools-chevron').style.transform = 'rotate(0deg)';
    }
});

// View management
function setView(view) {
    fileManager.currentView = view;
    
    // Update buttons
    document.getElementById('grid-view-btn').className = view === 'grid' 
        ? 'p-2 rounded-md bg-white shadow-sm transition-all duration-200'
        : 'p-2 rounded-md hover:bg-gray-200 transition-all duration-200';
    document.getElementById('list-view-btn').className = view === 'list' 
        ? 'p-2 rounded-md bg-white shadow-sm transition-all duration-200'
        : 'p-2 rounded-md hover:bg-gray-200 transition-all duration-200';
    
    // Show/hide views
    document.getElementById('grid-view').style.display = view === 'grid' ? 'block' : 'none';
    document.getElementById('list-view').style.display = view === 'list' ? 'block' : 'none';
}

// Load stats
async function loadStats() {
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get_stats', csrf_token: fileManager.csrfToken })
        });
        const data = await response.json();
        if (data.success) {
            fileManager.stats = data.stats;
            updateStatsDisplay();
        }
    } catch (error) {
        console.error('Stats error:', error);
    }
}

function updateStatsDisplay() {
    document.getElementById('total-files').textContent = fileManager.stats.total_files;
    document.getElementById('total-size').textContent = formatBytes(fileManager.stats.total_size);
    document.getElementById('orphaned-files').textContent = fileManager.stats.orphaned_files;
    document.getElementById('total-directories').textContent = fileManager.stats.directories;
    
    const warning = document.getElementById('orphaned-warning');
    if (fileManager.stats.orphaned_files > 0) {
        warning.classList.remove('hidden');
    } else {
        warning.classList.add('hidden');
    }
}

// Load directory
async function loadDirectory(path) {
    showLoading(true);
    fileManager.currentDirectory = path;
    fileManager.currentPath = '/uploads' + (path ? '/' + path : '');
    document.getElementById('current-path').textContent = fileManager.currentPath;
    
    // Update back button
    const backBtn = document.getElementById('back-btn');
    if (path) {
        backBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        backBtn.classList.add('hover:bg-gray-100');
    } else {
        backBtn.classList.add('opacity-50', 'cursor-not-allowed');
        backBtn.classList.remove('hover:bg-gray-100');
    }
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'browse_files', directory: path, csrf_token: fileManager.csrfToken })
        });
        const data = await response.json();
        if (data.success) {
            fileManager.files = data.files;
            renderFiles();
        } else {
            showNotification('Errore: ' + data.error, 'error');
            fileManager.files = [];
            renderFiles();
        }
    } catch (error) {
        console.error('Directory load error:', error);
        showNotification('Errore di connessione', 'error');
        fileManager.files = [];
        renderFiles();
    } finally {
        showLoading(false);
    }
}

function goBack() {
    if (!fileManager.currentDirectory) return;
    const parts = fileManager.currentDirectory.split('/');
    parts.pop();
    const parentPath = parts.join('/');
    loadDirectory(parentPath);
}

function showLoading(show) {
    document.getElementById('loading-state').style.display = show ? 'block' : 'none';
    document.getElementById('grid-view').style.display = show ? 'none' : (fileManager.currentView === 'grid' ? 'block' : 'none');
    document.getElementById('list-view').style.display = show ? 'none' : (fileManager.currentView === 'list' ? 'block' : 'none');
}

function renderFiles() {
    const gridContainer = document.getElementById('files-grid');
    const listContainer = document.getElementById('files-list');
    const emptyGrid = document.getElementById('empty-folder');
    const emptyList = document.getElementById('empty-folder-list');
    
    if (fileManager.files.length === 0) {
        gridContainer.innerHTML = '';
        listContainer.innerHTML = '';
        emptyGrid.classList.remove('hidden');
        emptyList.classList.remove('hidden');
        return;
    }
    
    emptyGrid.classList.add('hidden');
    emptyList.classList.add('hidden');
    
    // Render grid view
    gridContainer.innerHTML = fileManager.files.map(file => `
        <div class="group relative bg-white rounded-lg p-4 hover:shadow-lg transition-all duration-300 border border-gray-200 hover:border-gray-300 cursor-pointer"
             onclick="${file.type === 'directory' ? `loadDirectory('${file.path}')` : `previewFile('${file.path}')`}">
            <div class="flex justify-center mb-3">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center shadow-sm ${file.type === 'directory' ? 'bg-blue-100' : getFileIconBg(file.extension)}">
                    <i class="${file.type === 'directory' ? 'fas fa-folder text-blue-600' : getFileIcon(file.extension)} text-lg"></i>
                </div>
            </div>
            <div class="text-center">
                <div class="font-medium text-gray-900 text-sm truncate mb-1" title="${file.name}">${file.name}</div>
                <div class="text-xs text-gray-500">${formatBytes(file.size)}</div>
                <div class="text-xs text-gray-400 mt-1">${file.modified}</div>
            </div>
            ${file.type !== 'directory' ? `
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <button onclick="event.stopPropagation(); deleteFile('${file.path}')" 
                            class="w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            ` : ''}
        </div>
    `).join('');
    
    // Render list view
    listContainer.innerHTML = fileManager.files.map(file => `
        <div class="group flex items-center justify-between p-4 bg-white rounded-lg hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-gray-300 cursor-pointer"
             onclick="${file.type === 'directory' ? `loadDirectory('${file.path}')` : `previewFile('${file.path}')`}">
            <div class="flex items-center gap-4 flex-1 min-w-0">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0 ${file.type === 'directory' ? 'bg-blue-100' : getFileIconBg(file.extension)}">
                    <i class="${file.type === 'directory' ? 'fas fa-folder text-blue-600' : getFileIcon(file.extension)}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-gray-900 truncate">${file.name}</div>
                    <div class="text-sm text-gray-500">${file.type === 'directory' ? (file.file_count || 0) + ' elementi' : (file.extension?.toUpperCase() || 'FILE')}</div>
                </div>
            </div>
            <div class="flex items-center gap-4 flex-shrink-0">
                <div class="text-sm text-gray-500">${formatBytes(file.size)}</div>
                <div class="text-sm text-gray-400 hidden lg:block">${file.modified}</div>
                ${file.type !== 'directory' ? `
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <button onclick="event.stopPropagation(); deleteFile('${file.path}')" 
                                class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg flex items-center justify-center transition-colors duration-200">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                ` : ''}
            </div>
        </div>
    `).join('');
}

// Preview file
async function previewFile(filePath) {
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'preview_file', filepath: filePath, csrf_token: fileManager.csrfToken })
        });
        const data = await response.json();
        if (data.success) {
            fileManager.previewFileData = data.file_info;
            showPreviewModal();
        } else {
            showNotification('Errore anteprima: ' + data.error, 'error');
        }
    } catch (error) {
        console.error('Preview error:', error);
        showNotification('Errore di connessione', 'error');
    }
}

function showPreviewModal() {
    const file = fileManager.previewFileData;
    document.getElementById('preview-title').textContent = file.name;
    document.getElementById('preview-name').textContent = file.name;
    document.getElementById('preview-size').textContent = formatBytes(file.size || 0);
    document.getElementById('preview-type').textContent = file.extension?.toUpperCase() || 'N/A';
    document.getElementById('preview-modified').textContent = file.modified;
    
    // Show appropriate preview
    document.getElementById('preview-image').classList.add('hidden');
    document.getElementById('preview-text').classList.add('hidden');
    document.getElementById('preview-none').classList.add('hidden');
    
    if (file.preview_type === 'image') {
        document.getElementById('preview-img').src = file.preview_url;
        document.getElementById('preview-img').alt = file.name;
        document.getElementById('preview-image').classList.remove('hidden');
    } else if (file.preview_type === 'text') {
        document.getElementById('preview-content').textContent = file.content;
        document.getElementById('preview-text').classList.remove('hidden');
    } else {
        document.getElementById('preview-none').classList.remove('hidden');
    }
    
    document.getElementById('preview-modal').classList.remove('hidden');
}

function closePreview() {
    document.getElementById('preview-modal').classList.add('hidden');
    fileManager.previewFileData = null;
}

function deleteCurrentFile() {
    if (fileManager.previewFileData) {
        closePreview();
        deleteFile(fileManager.previewFileData.path);
    }
}

// Delete file
async function deleteFile(filePath) {
    if (!confirm('Sei sicuro di voler eliminare questo file?\\n\\n' + filePath)) return;
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'delete_file', filepath: filePath, csrf_token: fileManager.csrfToken })
        });
        const data = await response.json();
        if (data.success) {
            showNotification('File eliminato con successo', 'success');
            loadDirectory(fileManager.currentDirectory);
            loadStats();
        } else {
            showNotification('Errore eliminazione: ' + data.error, 'error');
        }
    } catch (error) {
        console.error('Delete error:', error);
        showNotification('Errore di connessione', 'error');
    }
}

// Scan functions
async function scanOrphaned() {
    showGlobalLoading('Scansione file orfani in corso...');
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'scan_orphaned', csrf_token: fileManager.csrfToken })
        });
        const data = await response.json();
        if (data.success) {
            fileManager.orphanedFiles = data.orphaned_files;
            showOrphanedModal();
        } else {
            showNotification('Errore scansione: ' + data.error, 'error');
        }
    } catch (error) {
        console.error('Scan error:', error);
        showNotification('Errore di connessione', 'error');
    } finally {
        hideGlobalLoading();
    }
}

async function scanDuplicates() {
    showGlobalLoading('Scansione duplicati in corso...');
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'scan_duplicates', csrf_token: fileManager.csrfToken })
        });
        const data = await response.json();
        if (data.success) {
            fileManager.duplicates = data.duplicates;
            showDuplicatesModal();
        } else {
            showNotification('Errore scansione: ' + data.error, 'error');
        }
    } catch (error) {
        console.error('Scan error:', error);
        showNotification('Errore di connessione', 'error');
    } finally {
        hideGlobalLoading();
    }
}

function showOrphanedModal() {
    if (fileManager.orphanedFiles.length === 0) {
        document.getElementById('orphaned-empty').classList.remove('hidden');
        document.getElementById('orphaned-content').classList.add('hidden');
        document.getElementById('orphaned-footer').classList.add('hidden');
    } else {
        document.getElementById('orphaned-empty').classList.add('hidden');
        document.getElementById('orphaned-content').classList.remove('hidden');
        document.getElementById('orphaned-footer').classList.remove('hidden');
        
        // Update summary
        const totalSize = fileManager.orphanedFiles.reduce((sum, f) => sum + f.size, 0);
        document.getElementById('orphaned-count').textContent = `${fileManager.orphanedFiles.length} file orfani trovati`;
        document.getElementById('orphaned-size').textContent = `Spazio sprecato: ${formatBytes(totalSize)}`;
        
        // Render files list
        const container = document.getElementById('orphaned-files');
        container.innerHTML = fileManager.orphanedFiles.map((file, index) => `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <div class="flex items-center gap-4">
                    <input type="checkbox" id="orphan-${index}" class="orphaned-checkbox rounded border-gray-300" onchange="updateOrphanedSelection()">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center ${getFileIconBg(file.extension)}">
                        <i class="${getFileIcon(file.extension)}"></i>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">${file.filename}</div>
                        <div class="text-sm text-gray-500">${file.directory} • ${formatBytes(file.size)} • ${file.modified}</div>
                    </div>
                </div>
                <button onclick="previewFile('${file.filepath}')" class="text-blue-600 hover:text-blue-800 p-2">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        `).join('');
        
        updateOrphanedSelection();
    }
    
    document.getElementById('orphaned-modal').classList.remove('hidden');
}

function closeOrphanedModal() {
    document.getElementById('orphaned-modal').classList.add('hidden');
    fileManager.selectedOrphaned = [];
    fileManager.orphanedFiles = [];
}

function updateOrphanedSelection() {
    const checkboxes = document.querySelectorAll('.orphaned-checkbox');
    fileManager.selectedOrphaned = [];
    let totalSize = 0;
    
    checkboxes.forEach((cb, index) => {
        if (cb.checked && fileManager.orphanedFiles[index]) {
            fileManager.selectedOrphaned.push(fileManager.orphanedFiles[index]);
            totalSize += fileManager.orphanedFiles[index].size;
        }
    });
    
    const summary = document.getElementById('selection-summary');
    if (fileManager.selectedOrphaned.length === 0) {
        summary.textContent = 'Nessun file selezionato';
    } else {
        summary.textContent = `${fileManager.selectedOrphaned.length} file selezionati (${formatBytes(totalSize)})`;
    }
}

function selectAllOrphaned() {
    const checkboxes = document.querySelectorAll('.orphaned-checkbox');
    const allSelected = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => cb.checked = !allSelected);
    updateOrphanedSelection();
}

async function deleteSelectedOrphaned() {
    if (fileManager.selectedOrphaned.length === 0) {
        showNotification('Nessun file selezionato', 'error');
        return;
    }
    
    const totalSize = fileManager.selectedOrphaned.reduce((sum, file) => sum + file.size, 0);
    if (!confirm(`Sei sicuro di voler eliminare ${fileManager.selectedOrphaned.length} file orfani (${formatBytes(totalSize)})?\\n\\nQuesta operazione non può essere annullata.`)) {
        return;
    }
    
    const filePaths = fileManager.selectedOrphaned.map(file => file.relative_path);
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                action: 'bulk_delete', 
                filepaths: filePaths, 
                csrf_token: fileManager.csrfToken 
            })
        });
        const data = await response.json();
        if (data.success) {
            showNotification(`${data.deleted_count} file eliminati con successo`, 'success');
            closeOrphanedModal();
            loadStats();
            loadDirectory(fileManager.currentDirectory);
        } else {
            showNotification('Errore eliminazione: ' + data.error, 'error');
        }
    } catch (error) {
        console.error('Bulk delete error:', error);
        showNotification('Errore di connessione', 'error');
    }
}

function showDuplicatesModal() {
    if (fileManager.duplicates.length === 0) {
        document.getElementById('duplicates-empty').classList.remove('hidden');
        document.getElementById('duplicates-content').classList.add('hidden');
    } else {
        document.getElementById('duplicates-empty').classList.add('hidden');
        document.getElementById('duplicates-content').classList.remove('hidden');
        
        const container = document.getElementById('duplicates-content');
        container.innerHTML = fileManager.duplicates.map((group, index) => `
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-900">Gruppo ${index + 1}</h4>
                    <span class="text-sm text-red-600 font-medium">${formatBytes(group.wasted_space)} sprecati</span>
                </div>
                <div class="space-y-3">
                    ${group.files.map((file, fileIndex) => `
                        <div class="flex items-center justify-between p-3 rounded-lg ${fileIndex === 0 ? 'bg-green-50' : 'bg-red-50'}">
                            <div class="flex items-center gap-3">
                                <span class="${fileIndex === 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} px-2 py-1 rounded text-xs font-medium">
                                    ${fileIndex === 0 ? 'MANTIENI' : 'DUPLICATO'}
                                </span>
                                <span class="font-mono text-sm">${file.filename}</span>
                                <span class="text-xs text-gray-500">${file.directory}</span>
                            </div>
                            ${fileIndex > 0 ? `
                                <button onclick="deleteFile('${file.filepath}')" class="text-red-600 hover:text-red-800 p-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : ''}
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }
    
    document.getElementById('duplicates-modal').classList.remove('hidden');
}

function closeDuplicatesModal() {
    document.getElementById('duplicates-modal').classList.add('hidden');
    fileManager.duplicates = [];
}

// Cleanup orphaned
async function cleanupOrphaned() {
    if (!confirm('Sei sicuro di voler eliminare tutti i file orfani?\\n\\nQuesta operazione non può essere annullata.')) return;
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'cleanup_orphaned', csrf_token: fileManager.csrfToken })
        });
        const data = await response.json();
        if (data.success) {
            showNotification(`Pulizia completata: ${data.deleted_count} file eliminati`, 'success');
            loadDirectory(fileManager.currentDirectory);
            loadStats();
        } else {
            showNotification('Errore pulizia: ' + data.error, 'error');
        }
    } catch (error) {
        console.error('Cleanup error:', error);
        showNotification('Errore di connessione', 'error');
    }
}

function exportReport() {
    showNotification('Funzionalità in sviluppo: Esportazione report', 'info');
}

function refreshData() {
    const icon = document.getElementById('refresh-icon');
    icon.classList.add('animate-spin');
    
    loadStats();
    loadDirectory(fileManager.currentDirectory);
    showNotification('Dati aggiornati', 'success');
    
    setTimeout(() => icon.classList.remove('animate-spin'), 1000);
}

// Global loading
function showGlobalLoading(message) {
    document.getElementById('loading-message').textContent = message;
    document.getElementById('global-loading').classList.remove('hidden');
}

function hideGlobalLoading() {
    document.getElementById('global-loading').classList.add('hidden');
}

// Utility functions
function formatBytes(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

function getFileIcon(extension) {
    const iconMap = {
        'jpg': 'fas fa-image text-green-500',
        'jpeg': 'fas fa-image text-green-500',
        'png': 'fas fa-image text-green-500',
        'gif': 'fas fa-image text-green-500',
        'webp': 'fas fa-image text-green-500',
        'svg': 'fas fa-image text-green-500',
        'pdf': 'fas fa-file-pdf text-red-500',
        'doc': 'fas fa-file-word text-blue-500',
        'docx': 'fas fa-file-word text-blue-500',
        'txt': 'fas fa-file-alt text-gray-500',
        'md': 'fas fa-file-alt text-gray-500',
        'zip': 'fas fa-file-archive text-yellow-500',
        'rar': 'fas fa-file-archive text-yellow-500',
        'ttf': 'fas fa-font text-purple-500',
        'otf': 'fas fa-font text-purple-500',
        'woff': 'fas fa-font text-purple-500',
        'woff2': 'fas fa-font text-purple-500'
    };
    return iconMap[extension?.toLowerCase()] || 'fas fa-file text-gray-400';
}

function getFileIconBg(extension) {
    const bgMap = {
        'jpg': 'bg-green-100',
        'jpeg': 'bg-green-100',
        'png': 'bg-green-100',
        'gif': 'bg-green-100',
        'webp': 'bg-green-100',
        'svg': 'bg-green-100',
        'pdf': 'bg-red-100',
        'doc': 'bg-blue-100',
        'docx': 'bg-blue-100',
        'txt': 'bg-gray-100',
        'md': 'bg-gray-100',
        'zip': 'bg-yellow-100',
        'rar': 'bg-yellow-100',
        'ttf': 'bg-purple-100',
        'otf': 'bg-purple-100',
        'woff': 'bg-purple-100',
        'woff2': 'bg-purple-100'
    };
    return bgMap[extension?.toLowerCase()] || 'bg-gray-100';
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-xl text-white transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        'bg-blue-600'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => notification.style.transform = 'translateX(0)', 10);
    
    // Remove after delay
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>