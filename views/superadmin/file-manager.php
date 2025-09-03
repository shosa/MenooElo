<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-x-hidden" x-data="fileManager()">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm sticky top-0 z-30">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                            File Manager
                        </h1>
                        <p class="text-slate-600 mt-2 flex items-center gap-2">
                            <i class="fas fa-cloud text-blue-500"></i>
                            Gestione avanzata dei file del sistema
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="refreshData()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-refresh" :class="{ 'animate-spin': loading }"></i>
                            <span>Aggiorna</span>
                        </button>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-tools"></i>
                                <span>Strumenti</span>
                                <i class="fas fa-chevron-down text-sm" :class="{ 'rotate-180': open }"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-white/20 backdrop-blur-xl z-50"
                                 x-cloak>
                                <div class="py-2">
                                    <button @click="scanOrphaned(); open = false" 
                                            class="flex items-center gap-3 w-full px-4 py-3 text-left hover:bg-orange-50 transition-colors duration-200">
                                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-search text-orange-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900">Scansiona Orfani</div>
                                            <div class="text-xs text-slate-500">Trova file non utilizzati</div>
                                        </div>
                                    </button>
                                    <button @click="scanDuplicates(); open = false" 
                                            class="flex items-center gap-3 w-full px-4 py-3 text-left hover:bg-purple-50 transition-colors duration-200">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-copy text-purple-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900">Trova Duplicati</div>
                                            <div class="text-xs text-slate-500">Identifica file identici</div>
                                        </div>
                                    </button>
                                    <button @click="exportReport(); open = false" 
                                            class="flex items-center gap-3 w-full px-4 py-3 text-left hover:bg-green-50 transition-colors duration-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-download text-green-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900">Esporta Report</div>
                                            <div class="text-xs text-slate-500">Scarica statistiche</div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-6">
            <!-- Enhanced Stats Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <div class="group relative bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-white/20 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-file text-white text-lg"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-slate-900" x-text="stats.total_files">-</div>
                                <div class="text-sm text-slate-500">File</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Totali</span>
                            <div class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">
                                Sistema
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="group relative bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-white/20 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-hdd text-white text-lg"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-slate-900" x-text="formatBytes(stats.total_size)">-</div>
                                <div class="text-sm text-slate-500">Storage</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Utilizzato</span>
                            <div class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-medium">
                                Attivo
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="group relative bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-white/20 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-red-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-slate-900" x-text="stats.orphaned_files">-</div>
                                <div class="text-sm text-slate-500">Orfani</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">File</span>
                            <div class="px-2 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-medium" 
                                 x-show="stats.orphaned_files > 0">
                                Attenzione
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="group relative bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-white/20 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-folder text-white text-lg"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-slate-900" x-text="stats.directories">-</div>
                                <div class="text-sm text-slate-500">Directory</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Totali</span>
                            <div class="px-2 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium">
                                Struttura
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced File Explorer -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 overflow-hidden">
                <!-- Explorer Header -->
                <div class="p-6 border-b border-slate-100">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-folder-open text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-slate-900">File Explorer</h2>
                                <div class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                                    <i class="fas fa-location-arrow text-xs"></i>
                                    <span x-text="currentPath || '/uploads'">/uploads</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- View Toggle -->
                            <div class="flex items-center bg-slate-100 rounded-lg p-1">
                                <button @click="currentView = 'grid'" 
                                        :class="currentView === 'grid' ? 'bg-white shadow-sm' : 'hover:bg-slate-200'"
                                        class="p-2 rounded-md transition-all duration-200">
                                    <i class="fas fa-th text-slate-600"></i>
                                </button>
                                <button @click="currentView = 'list'" 
                                        :class="currentView === 'list' ? 'bg-white shadow-sm' : 'hover:bg-slate-200'"
                                        class="p-2 rounded-md transition-all duration-200">
                                    <i class="fas fa-list text-slate-600"></i>
                                </button>
                            </div>
                            
                            <!-- Navigation Buttons -->
                            <button @click="goBack()" 
                                    :disabled="!currentDirectory"
                                    :class="!currentDirectory ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-100'"
                                    class="inline-flex items-center gap-2 px-3 py-2 text-slate-600 rounded-lg transition-all duration-200">
                                <i class="fas fa-arrow-left"></i>
                                <span class="hidden sm:inline">Indietro</span>
                            </button>
                            
                            <button @click="cleanupOrphaned()" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl font-medium hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-trash-alt"></i>
                                <span>Pulizia</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- File Content Area -->
                <div class="min-h-96" x-show="!loading" x-transition>
                    <!-- Grid View -->
                    <div x-show="currentView === 'grid'" class="p-6">
                        <div x-show="files.length === 0" class="text-center py-16">
                            <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-folder-open text-3xl text-slate-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-700 mb-2">Directory vuota</h3>
                            <p class="text-slate-500">Non ci sono file in questa directory.</p>
                        </div>
                        
                        <div x-show="files.length > 0" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                            <template x-for="file in files" :key="file.path">
                                <div class="group relative bg-white rounded-xl p-4 hover:shadow-lg transition-all duration-300 border border-slate-100 hover:border-slate-200 cursor-pointer"
                                     @click="file.type === 'directory' ? loadDirectory(file.path) : previewFile(file.path)">
                                    
                                    <!-- File Icon -->
                                    <div class="flex justify-center mb-3">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm"
                                             :class="file.type === 'directory' ? 'bg-gradient-to-br from-blue-500 to-indigo-500' : getFileIconBg(file.extension)">
                                            <i :class="file.type === 'directory' ? 'fas fa-folder text-white' : getFileIcon(file.extension)" class="text-lg"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- File Info -->
                                    <div class="text-center">
                                        <div class="font-medium text-slate-900 text-sm truncate mb-1" x-text="file.name" :title="file.name"></div>
                                        <div class="text-xs text-slate-500" x-text="formatBytes(file.size)"></div>
                                        <div class="text-xs text-slate-400 mt-1" x-text="file.modified"></div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div x-show="file.type !== 'directory'" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <button @click.stop="deleteFile(file.path)" 
                                                class="w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-md flex items-center justify-center transition-colors duration-200">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <!-- List View -->
                    <div x-show="currentView === 'list'" class="p-6">
                        <div x-show="files.length === 0" class="text-center py-16">
                            <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-folder-open text-3xl text-slate-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-700 mb-2">Directory vuota</h3>
                            <p class="text-slate-500">Non ci sono file in questa directory.</p>
                        </div>
                        
                        <div x-show="files.length > 0" class="space-y-2">
                            <template x-for="file in files" :key="file.path">
                                <div class="group flex items-center justify-between p-4 bg-white rounded-xl hover:shadow-md transition-all duration-200 border border-slate-100 hover:border-slate-200 cursor-pointer"
                                     @click="file.type === 'directory' ? loadDirectory(file.path) : previewFile(file.path)">
                                    
                                    <div class="flex items-center gap-4 flex-1 min-w-0">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0"
                                             :class="file.type === 'directory' ? 'bg-gradient-to-br from-blue-500 to-indigo-500' : getFileIconBg(file.extension)">
                                            <i :class="file.type === 'directory' ? 'fas fa-folder text-white' : getFileIcon(file.extension)"></i>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-slate-900 truncate" x-text="file.name"></div>
                                            <div class="text-sm text-slate-500" 
                                                 x-text="file.type === 'directory' ? (file.file_count || 0) + ' elementi' : (file.extension?.toUpperCase() || 'FILE')"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-4 flex-shrink-0">
                                        <div class="text-sm text-slate-500" x-text="formatBytes(file.size)"></div>
                                        <div class="text-sm text-slate-400 hidden lg:block" x-text="file.modified"></div>
                                        <div x-show="file.type !== 'directory'" class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <button @click.stop="deleteFile(file.path)" 
                                                    class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg flex items-center justify-center transition-colors duration-200">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                
                <!-- Loading State -->
                <div x-show="loading" class="p-6">
                    <div class="text-center py-16">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-spinner fa-spin text-2xl text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-700 mb-2">Caricamento...</h3>
                        <p class="text-slate-500">Sto recuperando i file...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Preview Modal -->
    <div x-show="showPreview" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closePreview()"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         x-cloak>
        <div @click.stop class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-auto shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h3 class="text-xl font-semibold text-slate-900" x-text="previewFile?.name">File Preview</h3>
                <button @click="closePreview()" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-times text-slate-600"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <div x-show="previewFile" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="font-semibold text-slate-900 mb-3">Informazioni File</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Nome:</span>
                                <span class="font-medium" x-text="previewFile?.name"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Dimensione:</span>
                                <span x-text="formatBytes(previewFile?.size || 0)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Tipo:</span>
                                <span x-text="previewFile?.extension?.toUpperCase() || 'N/A'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Modificato:</span>
                                <span x-text="previewFile?.modified"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview Content -->
                    <div>
                        <h4 class="font-semibold text-slate-900 mb-3">Anteprima</h4>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <div x-show="previewFile?.preview_type === 'image'">
                                <img :src="previewFile?.preview_url" :alt="previewFile?.name" class="max-w-full h-auto rounded-lg shadow-sm">
                            </div>
                            <div x-show="previewFile?.preview_type === 'text'" class="bg-slate-800 text-green-400 p-4 rounded-lg text-sm font-mono max-h-64 overflow-auto">
                                <pre x-text="previewFile?.content"></pre>
                            </div>
                            <div x-show="!previewFile?.preview_type || previewFile?.preview_type === 'none'" class="text-center py-8">
                                <div class="w-16 h-16 bg-slate-200 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-file text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500">Anteprima non disponibile per questo tipo di file</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-100">
                <button @click="deleteCurrentFile()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl font-medium hover:from-red-600 hover:to-pink-600 transition-all duration-200">
                    <i class="fas fa-trash"></i>
                    <span>Elimina</span>
                </button>
                <button @click="closePreview()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl font-medium hover:bg-slate-300 transition-all duration-200">
                    <span>Chiudi</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Orphaned Files Modal -->
    <div x-show="showOrphanedModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeOrphanedModal()"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         x-cloak>
        <div @click.stop class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-auto shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">File Orfani Trovati</h3>
                        <p class="text-sm text-slate-500">File non referenziati nel database</p>
                    </div>
                </div>
                <button @click="closeOrphanedModal()" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-times text-slate-600"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <div x-show="orphanedFiles.length === 0" class="text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">Nessun file orfano!</h3>
                    <p class="text-slate-500">Tutti i file sono correttamente referenziati nel database.</p>
                </div>
                
                <div x-show="orphanedFiles.length > 0">
                    <!-- Summary -->
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-orange-800" x-text="`${orphanedFiles.length} file orfani trovati`"></div>
                                <div class="text-sm text-orange-600" x-text="`Spazio sprecato: ${formatBytes(orphanedFiles.reduce((sum, f) => sum + f.size, 0))}`"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- File List -->
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <template x-for="(file, index) in orphanedFiles" :key="index">
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" :id="`orphan-${index}`" class="orphaned-checkbox rounded border-slate-300" @change="updateOrphanedSelection()">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" :class="getFileIconBg(file.extension)">
                                        <i :class="getFileIcon(file.extension)"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900" x-text="file.filename"></div>
                                        <div class="text-sm text-slate-500" x-text="`${file.directory} • ${formatBytes(file.size)} • ${file.modified}`"></div>
                                    </div>
                                </div>
                                <button @click="previewOrphanedFile(file.filepath)" class="text-blue-600 hover:text-blue-800 p-2">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div x-show="orphanedFiles.length > 0" class="flex items-center justify-between p-6 border-t border-slate-100">
                <div class="text-sm text-slate-600">
                    <span x-text="orphanedSummary">Nessun file selezionato</span>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="selectAllOrphaned()" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-xl font-medium hover:bg-slate-300 transition-all duration-200">
                        Seleziona Tutti
                    </button>
                    <button @click="deleteSelectedOrphaned()" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl font-medium hover:from-red-600 hover:to-pink-600 transition-all duration-200">
                        <i class="fas fa-trash"></i>
                        <span>Elimina Selezionati</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Duplicates Modal -->
    <div x-show="showDuplicatesModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeDuplicatesModal()"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         x-cloak>
        <div @click.stop class="bg-white rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-auto shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-copy text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">File Duplicati</h3>
                        <p class="text-sm text-slate-500">File identici nel sistema</p>
                    </div>
                </div>
                <button @click="closeDuplicatesModal()" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-times text-slate-600"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <div x-show="duplicates.length === 0" class="text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">Nessun duplicato trovato!</h3>
                    <p class="text-slate-500">Tutti i file sono unici nel sistema.</p>
                </div>
                
                <div x-show="duplicates.length > 0" class="space-y-6 max-h-96 overflow-y-auto">
                    <template x-for="(group, index) in duplicates" :key="index">
                        <div class="border border-slate-200 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-slate-900" x-text="`Gruppo ${index + 1}`"></h4>
                                <span class="text-sm text-red-600 font-medium" x-text="`${formatBytes(group.wasted_space)} sprecati`"></span>
                            </div>
                            <div class="space-y-3">
                                <template x-for="(file, fileIndex) in group.files" :key="fileIndex">
                                    <div class="flex items-center justify-between p-3 rounded-lg" :class="fileIndex === 0 ? 'bg-green-50' : 'bg-red-50'">
                                        <div class="flex items-center gap-3">
                                            <span :class="fileIndex === 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                                                  class="px-2 py-1 rounded text-xs font-medium" 
                                                  x-text="fileIndex === 0 ? 'MANTIENI' : 'DUPLICATO'"></span>
                                            <span class="font-mono text-sm" x-text="file.filename"></span>
                                            <span class="text-xs text-slate-500" x-text="file.directory"></span>
                                        </div>
                                        <div x-show="fileIndex > 0">
                                            <button @click="deleteDuplicateFile(file.filepath)" class="text-red-600 hover:text-red-800 p-2">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div x-show="globalLoading" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 flex items-center justify-center" 
         x-cloak>
        <div class="bg-white rounded-2xl p-8 shadow-2xl">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-spinner fa-spin text-2xl text-white"></i>
                </div>
                <p class="text-lg font-semibold text-slate-900" x-text="loadingMessage">Caricamento...</p>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
function fileManager() {
    return {
        // State
        loading: false,
        globalLoading: false,
        loadingMessage: 'Caricamento...',
        currentDirectory: '',
        currentPath: '',
        currentView: 'grid',
        files: [],
        stats: {
            total_files: 0,
            total_size: 0,
            orphaned_files: 0,
            directories: 0
        },
        
        // Modals
        showPreview: false,
        showOrphanedModal: false,
        showDuplicatesModal: false,
        previewFile: null,
        orphanedFiles: [],
        duplicates: [],
        selectedOrphaned: [],
        orphanedSummary: 'Nessun file selezionato',
        
        csrfToken: '<?= $csrf_token ?>',
        
        init() {
            this.loadStats();
            this.loadDirectory('');
        },
        
        async loadStats() {
            try {
                const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'get_stats', csrf_token: this.csrfToken })
                });
                const data = await response.json();
                if (data.success) {
                    this.stats = data.stats;
                }
            } catch (error) {
                console.error('Stats error:', error);
            }
        },
        
        async loadDirectory(path) {
            this.loading = true;
            this.currentDirectory = path;
            this.currentPath = '/uploads' + (path ? '/' + path : '');
            
            try {
                const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'browse_files', directory: path, csrf_token: this.csrfToken })
                });
                const data = await response.json();
                if (data.success) {
                    this.files = data.files;
                } else {
                    this.showNotification('Errore: ' + data.error, 'error');
                    this.files = [];
                }
            } catch (error) {
                console.error('Directory load error:', error);
                this.showNotification('Errore di connessione', 'error');
                this.files = [];
            } finally {
                this.loading = false;
            }
        },
        
        goBack() {
            if (!this.currentDirectory) return;
            const parts = this.currentDirectory.split('/');
            parts.pop();
            const parentPath = parts.join('/');
            this.loadDirectory(parentPath);
        },
        
        async previewFile(filePath) {
            try {
                const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'preview_file', filepath: filePath, csrf_token: this.csrfToken })
                });
                const data = await response.json();
                if (data.success) {
                    this.previewFile = data.file_info;
                    this.showPreview = true;
                } else {
                    this.showNotification('Errore anteprima: ' + data.error, 'error');
                }
            } catch (error) {
                console.error('Preview error:', error);
                this.showNotification('Errore di connessione', 'error');
            }
        },
        
        closePreview() {
            this.showPreview = false;
            this.previewFile = null;
        },
        
        async deleteFile(filePath) {
            if (!confirm('Sei sicuro di voler eliminare questo file?\\n\\n' + filePath)) return;
            
            try {
                const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_file', filepath: filePath, csrf_token: this.csrfToken })
                });
                const data = await response.json();
                if (data.success) {
                    this.showNotification('File eliminato con successo', 'success');
                    this.loadDirectory(this.currentDirectory);
                    this.loadStats();
                } else {
                    this.showNotification('Errore eliminazione: ' + data.error, 'error');
                }
            } catch (error) {
                console.error('Delete error:', error);
                this.showNotification('Errore di connessione', 'error');
            }
        },
        
        deleteCurrentFile() {
            if (this.previewFile) {
                this.closePreview();
                this.deleteFile(this.previewFile.path);
            }
        },
        
        async scanOrphaned() {
            this.globalLoading = true;
            this.loadingMessage = 'Scansione file orfani in corso...';
            
            try {
                const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'scan_orphaned', csrf_token: this.csrfToken })
                });
                const data = await response.json();
                if (data.success) {
                    this.orphanedFiles = data.orphaned_files;
                    this.showOrphanedModal = true;
                    this.updateOrphanedSelection();
                } else {
                    this.showNotification('Errore scansione: ' + data.error, 'error');
                }
            } catch (error) {
                console.error('Scan error:', error);
                this.showNotification('Errore di connessione', 'error');
            } finally {
                this.globalLoading = false;
            }
        },
        
        async scanDuplicates() {
            this.globalLoading = true;
            this.loadingMessage = 'Scansione duplicati in corso...';
            
            try {
                const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'scan_duplicates', csrf_token: this.csrfToken })
                });
                const data = await response.json();
                if (data.success) {
                    this.duplicates = data.duplicates;
                    this.showDuplicatesModal = true;
                } else {
                    this.showNotification('Errore scansione: ' + data.error, 'error');
                }
            } catch (error) {
                console.error('Scan error:', error);
                this.showNotification('Errore di connessione', 'error');
            } finally {
                this.globalLoading = false;
            }
        },
        
        updateOrphanedSelection() {
            const checkboxes = document.querySelectorAll('.orphaned-checkbox');
            this.selectedOrphaned = [];
            let totalSize = 0;
            
            checkboxes.forEach((cb, index) => {
                if (cb.checked && this.orphanedFiles[index]) {
                    this.selectedOrphaned.push(this.orphanedFiles[index]);
                    totalSize += this.orphanedFiles[index].size;
                }
            });
            
            if (this.selectedOrphaned.length === 0) {
                this.orphanedSummary = 'Nessun file selezionato';
            } else {
                this.orphanedSummary = `${this.selectedOrphaned.length} file selezionati (${this.formatBytes(totalSize)})`;
            }
        },
        
        selectAllOrphaned() {
            const checkboxes = document.querySelectorAll('.orphaned-checkbox');
            const allSelected = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => cb.checked = !allSelected);
            this.updateOrphanedSelection();
        },
        
        async deleteSelectedOrphaned() {
            if (this.selectedOrphaned.length === 0) {
                this.showNotification('Nessun file selezionato', 'error');
                return;
            }
            
            const totalSize = this.selectedOrphaned.reduce((sum, file) => sum + file.size, 0);
            if (!confirm(`Sei sicuro di voler eliminare ${this.selectedOrphaned.length} file orfani (${this.formatBytes(totalSize)})?\\n\\nQuesta operazione non può essere annullata.`)) {
                return;
            }
            
            const filePaths = this.selectedOrphaned.map(file => file.relative_path);
            
            try {
                const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        action: 'bulk_delete', 
                        filepaths: filePaths, 
                        csrf_token: this.csrfToken 
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.showNotification(`${data.deleted_count} file eliminati con successo`, 'success');
                    this.closeOrphanedModal();
                    this.loadStats();
                    this.loadDirectory(this.currentDirectory);
                } else {
                    this.showNotification('Errore eliminazione: ' + data.error, 'error');
                }
            } catch (error) {
                console.error('Bulk delete error:', error);
                this.showNotification('Errore di connessione', 'error');
            }
        },
        
        async cleanupOrphaned() {
            if (!confirm('Sei sicuro di voler eliminare tutti i file orfani?\\n\\nQuesta operazione non può essere annullata.')) return;
            
            try {
                const response = await fetch('<?= BASE_URL ?>/superadmin/file-manager-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'cleanup_orphaned', csrf_token: this.csrfToken })
                });
                const data = await response.json();
                if (data.success) {
                    this.showNotification(`Pulizia completata: ${data.deleted_count} file eliminati`, 'success');
                    this.loadDirectory(this.currentDirectory);
                    this.loadStats();
                } else {
                    this.showNotification('Errore pulizia: ' + data.error, 'error');
                }
            } catch (error) {
                console.error('Cleanup error:', error);
                this.showNotification('Errore di connessione', 'error');
            }
        },
        
        closeOrphanedModal() {
            this.showOrphanedModal = false;
            this.selectedOrphaned = [];
            this.orphanedFiles = [];
        },
        
        closeDuplicatesModal() {
            this.showDuplicatesModal = false;
            this.duplicates = [];
        },
        
        async deleteDuplicateFile(filepath) {
            this.deleteFile(filepath);
        },
        
        exportReport() {
            this.showNotification('Funzionalità in sviluppo: Esportazione report', 'info');
        },
        
        refreshData() {
            this.loadStats();
            this.loadDirectory(this.currentDirectory);
            this.showNotification('Dati aggiornati', 'success');
        },
        
        // Utility functions
        formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        },
        
        getFileIcon(extension) {
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
        },
        
        getFileIconBg(extension) {
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
        },
        
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-xl text-white transform transition-all duration-300 ${
                type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 
                type === 'error' ? 'bg-gradient-to-r from-red-500 to-pink-500' : 
                'bg-gradient-to-r from-blue-500 to-indigo-500'
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
    }
}
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>