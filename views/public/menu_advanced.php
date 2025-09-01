<?php 
$content = ob_start(); 
?>

<!-- Advanced Menu Layout -->
<style>
:root {
    --theme-color: <?= $restaurant['theme_color'] ?>;
    --theme-rgb: <?php 
        $hex = str_replace('#', '', $restaurant['theme_color']);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        echo "$r, $g, $b";
    ?>;
}

.theme-gradient {
    background: linear-gradient(135deg, var(--theme-color) 0%, rgba(var(--theme-rgb), 0.8) 100%);
}

.theme-gradient-light {
    background: linear-gradient(135deg, rgba(var(--theme-rgb), 0.1) 0%, rgba(var(--theme-rgb), 0.05) 100%);
}

.theme-accent {
    background: rgba(var(--theme-rgb), 0.1);
    border-color: rgba(var(--theme-rgb), 0.2);
}

.glass-effect {
    backdrop-filter: blur(20px);
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
</style>

<!-- Order Summary Floating Button -->
<button id="order-summary-btn" 
        class="fixed bottom-6 right-6 z-50 bg-white shadow-2xl rounded-full p-4 hover:scale-110 transition-all duration-300 border-2 hidden"
        style="border-color: var(--theme-color);"
        onclick="openOrderModal()">
    <div class="flex items-center gap-3">
        <div class="relative">
            <i class="fas fa-shopping-cart text-2xl" style="color: var(--theme-color);"></i>
            <span id="order-count-badge" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold hidden">0</span>
        </div>
        <span class="font-semibold hidden sm:inline" style="color: var(--theme-color);">Ordine</span>
    </div>
</button>

<!-- Restaurant Hero Section with Advanced Layout -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Dynamic Background -->
    <div class="absolute inset-0 theme-gradient">
        <?php if ($restaurant['cover_image_url']): ?>
        <img src="<?= BASE_URL ?>/uploads/<?= $restaurant['cover_image_url'] ?>" 
             alt="<?= htmlspecialchars($restaurant['name']) ?>"
             class="w-full h-full object-cover opacity-20">
        <?php endif; ?>
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-48 h-48 bg-white/5 rounded-full blur-2xl animate-bounce" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 right-1/3 w-32 h-32 bg-white/15 rounded-full blur-xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-black/30 via-transparent to-black/40"></div>
    </div>
    
    <!-- Hero Content -->
    <div class="relative z-10 container mx-auto px-6 lg:px-8 text-center">
        <!-- Restaurant Logo -->
        <?php if ($restaurant['logo_url']): ?>
        <div class="mb-8 flex justify-center">
            <div class="w-32 h-32 lg:w-48 lg:h-48 rounded-3xl overflow-hidden shadow-2xl bg-white/20 p-2 backdrop-blur-sm">
                <img src="<?= BASE_URL ?>/uploads/<?= $restaurant['logo_url'] ?>" 
                     alt="<?= htmlspecialchars($restaurant['name']) ?>"
                     class="w-full h-full object-cover rounded-2xl">
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Restaurant Name -->
        <h1 class="text-5xl lg:text-8xl font-black text-white leading-none mb-6 tracking-tight">
            <?= htmlspecialchars($restaurant['name']) ?>
        </h1>
        
        <!-- Description -->
        <?php if ($restaurant['description']): ?>
        <p class="text-xl lg:text-3xl text-white/90 font-light mb-12 max-w-4xl mx-auto leading-relaxed">
            <?= htmlspecialchars($restaurant['description']) ?>
        </p>
        <?php endif; ?>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-6 justify-center">
            <button onclick="scrollToMenu()" 
                    class="group px-8 py-4 bg-white/20 backdrop-blur-sm text-white rounded-2xl font-bold text-lg hover:bg-white/30 transition-all duration-300 border border-white/20 hover:scale-105">
                <i class="fas fa-utensils mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                Esplora il Menu
            </button>
            
            <?php if ($restaurant['phone']): ?>
            <a href="tel:<?= $restaurant['phone'] ?>" 
               class="group px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-2xl font-bold text-lg hover:bg-white/20 transition-all duration-300 border border-white/20 hover:scale-105">
                <i class="fas fa-phone mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                <?= htmlspecialchars($restaurant['phone']) ?>
            </a>
            <?php endif; ?>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-white/60 text-2xl"></i>
        </div>
    </div>
</section>

<!-- Restaurant Info Bar -->
<section class="sticky top-0 z-40 glass-effect border-b">
    <div class="container mx-auto px-6 lg:px-8 py-4">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
            <!-- Quick Info -->
            <div class="flex flex-wrap items-center gap-6 text-gray-700">
                <?php if ($restaurant['address']): ?>
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt" style="color: var(--theme-color);"></i>
                    <span><?= htmlspecialchars($restaurant['address']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($restaurant['phone']): ?>
                <div class="flex items-center gap-2">
                    <i class="fas fa-phone" style="color: var(--theme-color);"></i>
                    <a href="tel:<?= $restaurant['phone'] ?>" class="hover:opacity-70 transition-opacity">
                        <?= htmlspecialchars($restaurant['phone']) ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button onclick="toggleView()" 
                        class="px-4 py-2 theme-accent text-gray-700 rounded-lg hover:scale-105 transition-all duration-300">
                    <i id="view-icon" class="fas fa-th-large mr-2"></i>
                    <span id="view-text">Lista</span>
                </button>
                
                <?php if ($restaurant['qr_enabled']): ?>
                <button onclick="openQRModal()" 
                        class="px-4 py-2 bg-white border-2 text-gray-700 rounded-lg hover:scale-105 transition-all duration-300"
                        style="border-color: var(--theme-color);">
                    <i class="fas fa-qrcode mr-2"></i>
                    Condividi
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Category Navigation -->
<section class="sticky top-20 z-30 bg-white/95 backdrop-blur-sm border-b border-gray-200">
    <div class="container mx-auto px-6 lg:px-8 py-4">
        <div class="flex gap-2 overflow-x-auto scrollbar-hide">
            <?php foreach ($categories as $category): ?>
            <a href="#category-<?= $category['id'] ?>" 
               class="category-nav-item flex-shrink-0 px-6 py-3 rounded-full font-medium transition-all duration-300 hover:scale-105 whitespace-nowrap"
               data-category="<?= $category['id'] ?>"
               style="background: rgba(var(--theme-rgb), 0.1); color: var(--theme-color); border: 1px solid rgba(var(--theme-rgb), 0.2);">
                <?= htmlspecialchars($category['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Menu Content -->
<main id="menu-content" class="py-12 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-8">
        <?php foreach ($categories as $category): ?>
        <?php if (isset($menuItems[$category['id']]) && !empty($menuItems[$category['id']])): ?>
        
        <!-- Category Header -->
        <section id="category-<?= $category['id'] ?>" class="mb-16">
            <div class="text-center mb-12">
                <?php if ($category['image_url']): ?>
                <div class="w-24 h-24 mx-auto mb-6 rounded-2xl overflow-hidden shadow-lg theme-gradient p-1">
                    <img src="<?= BASE_URL ?>/uploads/<?= $category['image_url'] ?>" 
                         alt="<?= htmlspecialchars($category['name']) ?>"
                         class="w-full h-full object-cover rounded-xl">
                </div>
                <?php endif; ?>
                
                <h2 class="text-4xl lg:text-5xl font-bold mb-4" style="color: var(--theme-color);">
                    <?= htmlspecialchars($category['name']) ?>
                </h2>
                
                <?php if ($category['description']): ?>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    <?= htmlspecialchars($category['description']) ?>
                </p>
                <?php endif; ?>
            </div>
            
            <!-- Menu Items Grid -->
            <div id="grid-view-<?= $category['id'] ?>" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                <?php foreach ($menuItems[$category['id']] as $item): ?>
                <div class="menu-item-card bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden group hover:scale-105 <?= !$item['is_available'] ? 'opacity-60' : '' ?>">
                    <!-- Item Image -->
                    <div class="relative h-64 overflow-hidden">
                        <?php if ($item['image_url']): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= $item['image_url'] ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <?php else: ?>
                        <div class="w-full h-full theme-gradient-light flex items-center justify-center">
                            <i class="fas fa-utensils text-6xl opacity-30" style="color: var(--theme-color);"></i>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Featured Badge -->
                        <?php if ($item['is_featured']): ?>
                        <div class="absolute top-4 right-4 bg-yellow-400 text-black px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                            <i class="fas fa-star mr-1"></i>
                            Speciale
                        </div>
                        <?php endif; ?>
                        
                        <!-- Availability Overlay -->
                        <?php if (!$item['is_available']): ?>
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                            <span class="bg-red-500 text-white px-4 py-2 rounded-full font-bold">
                                Non Disponibile
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Price Tag -->
                        <div class="absolute bottom-4 left-4">
                            <div class="bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg">
                                <span class="text-2xl font-black" style="color: var(--theme-color);">
                                    €<?= number_format($item['price'], 2) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Item Content -->
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            <?= htmlspecialchars($item['name']) ?>
                        </h3>
                        
                        <?php if ($item['description']): ?>
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            <?= htmlspecialchars($item['description']) ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php if ($item['ingredients']): ?>
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 mb-2">Ingredienti:</h4>
                            <p class="text-sm text-gray-600">
                                <?= htmlspecialchars($item['ingredients']) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($item['allergens']): ?>
                        <div class="mb-4 p-3 bg-amber-50 border-l-4 border-amber-400 rounded-r-lg">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle text-amber-600"></i>
                                <span class="font-semibold text-amber-800">Allergeni:</span>
                            </div>
                            <p class="text-sm text-amber-700 mt-1">
                                <?= htmlspecialchars($item['allergens']) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Add to Order Button -->
                        <?php if ($item['is_available']): ?>
                        <button onclick="tempOrder.addItem(<?= $item['id'] ?>, '<?= addslashes($item['name']) ?>', <?= $item['price'] ?>, '<?= $item['image_url'] ?>')"
                                class="w-full py-3 bg-white border-2 rounded-xl font-bold text-lg hover:scale-105 transition-all duration-300 hover:shadow-lg"
                                style="color: var(--theme-color); border-color: var(--theme-color);">
                            <i class="fas fa-plus mr-2"></i>
                            Aggiungi all'Ordine
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- List View (Hidden by default) -->
            <div id="list-view-<?= $category['id'] ?>" class="hidden space-y-6">
                <?php foreach ($menuItems[$category['id']] as $item): ?>
                <div class="menu-item-list bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden <?= !$item['is_available'] ? 'opacity-60' : '' ?>">
                    <div class="flex flex-col lg:flex-row">
                        <!-- Item Image -->
                        <div class="lg:w-64 h-48 lg:h-auto relative flex-shrink-0">
                            <?php if ($item['image_url']): ?>
                            <img src="<?= BASE_URL ?>/uploads/<?= $item['image_url'] ?>" 
                                 alt="<?= htmlspecialchars($item['name']) ?>"
                                 class="w-full h-full object-cover">
                            <?php else: ?>
                            <div class="w-full h-full theme-gradient-light flex items-center justify-center">
                                <i class="fas fa-utensils text-4xl opacity-30" style="color: var(--theme-color);"></i>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($item['is_featured']): ?>
                            <div class="absolute top-2 right-2 bg-yellow-400 text-black px-2 py-1 rounded-full text-xs font-bold">
                                <i class="fas fa-star"></i>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!$item['is_available']): ?>
                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full font-bold text-sm">
                                    Non Disponibile
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Item Content -->
                        <div class="flex-1 p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-xl font-bold text-gray-900">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </h3>
                                    <span class="text-2xl font-black ml-4" style="color: var(--theme-color);">
                                        €<?= number_format($item['price'], 2) ?>
                                    </span>
                                </div>
                                
                                <?php if ($item['description']): ?>
                                <p class="text-gray-600 mb-3">
                                    <?= htmlspecialchars($item['description']) ?>
                                </p>
                                <?php endif; ?>
                                
                                <?php if ($item['ingredients']): ?>
                                <p class="text-sm text-gray-500 mb-3">
                                    <strong>Ingredienti:</strong> <?= htmlspecialchars($item['ingredients']) ?>
                                </p>
                                <?php endif; ?>
                                
                                <?php if ($item['allergens']): ?>
                                <div class="mb-3 p-2 bg-amber-50 border border-amber-200 rounded-lg">
                                    <p class="text-xs text-amber-700">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        <strong>Allergeni:</strong> <?= htmlspecialchars($item['allergens']) ?>
                                    </p>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Add to Order Button -->
                            <?php if ($item['is_available']): ?>
                            <button onclick="tempOrder.addItem(<?= $item['id'] ?>, '<?= addslashes($item['name']) ?>', <?= $item['price'] ?>, '<?= $item['image_url'] ?>')"
                                    class="self-start px-6 py-2 bg-white border-2 rounded-lg font-semibold hover:scale-105 transition-all duration-300"
                                    style="color: var(--theme-color); border-color: var(--theme-color);">
                                <i class="fas fa-plus mr-2"></i>
                                Aggiungi
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</main>

<!-- Order Summary Modal -->
<div id="orderModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
        <!-- Modal Header -->
        <div class="theme-gradient p-6 text-white">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Il Tuo Ordine</h2>
                <button onclick="closeOrderModal()" class="text-white/80 hover:text-white text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6 max-h-96 overflow-y-auto">
            <div id="order-items">
                <p class="text-gray-500 text-center py-8">Nessun piatto selezionato</p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="border-t border-gray-200 p-6 bg-gray-50">
            <!-- Total -->
            <div class="flex justify-between items-center mb-4 text-xl font-bold">
                <span>Totale:</span>
                <span id="order-total" style="color: var(--theme-color);">€0.00</span>
            </div>
            
            <!-- Split Bill Calculator -->
            <div class="bg-white rounded-lg p-4 mb-4 border border-gray-200">
                <h4 class="font-semibold mb-3 flex items-center gap-2">
                    <i class="fas fa-calculator" style="color: var(--theme-color);"></i>
                    Conto alla Romana
                </h4>
                <div class="flex items-center gap-4">
                    <label class="text-sm font-medium">Persone:</label>
                    <input type="number" id="split-count" value="1" min="1" max="20" 
                           class="w-16 px-2 py-1 border border-gray-300 rounded-lg text-center">
                    <span class="text-sm">→</span>
                    <div id="split-total" class="font-bold" style="color: var(--theme-color);">€0.00 a persona</div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex gap-3">
                <button onclick="tempOrder.clear(); closeOrderModal();" 
                        class="flex-1 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Svuota Ordine
                </button>
                <button onclick="shareOrder()" 
                        class="flex-1 py-3 bg-white border-2 rounded-xl font-bold hover:scale-105 transition-all duration-300"
                        style="color: var(--theme-color); border-color: var(--theme-color);">
                    <i class="fas fa-share mr-2"></i>
                    Condividi Ordine
                </button>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<?php if ($restaurant['qr_enabled']): ?>
<div id="qrModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl">
        <div class="p-6 text-center">
            <h3 class="text-2xl font-bold mb-4" style="color: var(--theme-color);">
                Condividi il Menu
            </h3>
            <div class="mb-4">
                <div class="w-48 h-48 mx-auto bg-gray-100 rounded-lg flex items-center justify-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode(BASE_URL . '/restaurant/' . $restaurant['slug']) ?>" 
                         alt="QR Code Menu" class="w-full h-full rounded-lg">
                </div>
            </div>
            <p class="text-gray-600 mb-6">
                Scannerizza il QR Code per condividere questo menu
            </p>
            <div class="flex gap-3">
                <button onclick="closeQRModal()" 
                        class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                    Chiudi
                </button>
                <button onclick="shareMenu()" 
                        class="flex-1 py-2 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                        style="background: var(--theme-color);">
                    Condividi Link
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Temporary Order JavaScript -->
<script>
// Temporary Order Management
class TemporaryOrder {
    constructor() {
        this.items = JSON.parse(localStorage.getItem('menooelo_temp_order') || '[]');
        this.updateUI();
        this.bindEvents();
    }

    addItem(id, name, price, image = null) {
        const existingIndex = this.items.findIndex(item => item.id === id);
        
        if (existingIndex >= 0) {
            this.items[existingIndex].quantity += 1;
        } else {
            this.items.push({
                id: id,
                name: name,
                price: parseFloat(price),
                image: image,
                quantity: 1
            });
        }
        
        this.saveOrder();
        this.updateUI();
        this.showAddedToast(name);
    }

    removeItem(id) {
        this.items = this.items.filter(item => item.id !== id);
        this.saveOrder();
        this.updateUI();
    }

    updateQuantity(id, quantity) {
        const item = this.items.find(item => item.id === id);
        if (item) {
            if (quantity <= 0) {
                this.removeItem(id);
            } else {
                item.quantity = parseInt(quantity);
                this.saveOrder();
                this.updateUI();
            }
        }
    }

    getTotal() {
        return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
    }

    getItemCount() {
        return this.items.reduce((count, item) => count + item.quantity, 0);
    }

    clear() {
        this.items = [];
        this.saveOrder();
        this.updateUI();
    }

    saveOrder() {
        localStorage.setItem('menooelo_temp_order', JSON.stringify(this.items));
    }

    updateUI() {
        this.updateOrderButton();
        this.updateOrderSummary();
    }

    updateOrderButton() {
        const button = document.getElementById('order-summary-btn');
        const badge = document.getElementById('order-count-badge');
        const count = this.getItemCount();
        
        if (count > 0) {
            button.classList.remove('hidden');
            badge.textContent = count;
            badge.classList.remove('hidden');
        } else {
            button.classList.add('hidden');
            badge.classList.add('hidden');
        }
    }

    updateOrderSummary() {
        const container = document.getElementById('order-items');
        const totalElement = document.getElementById('order-total');
        
        if (!container) return;

        container.innerHTML = '';
        
        if (this.items.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-8">Nessun piatto selezionato</p>';
        } else {
            this.items.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'flex items-center justify-between py-4 border-b border-gray-200 last:border-b-0';
                itemElement.innerHTML = `
                    <div class="flex items-center gap-4 flex-1">
                        <div class="w-16 h-16 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0">
                            ${item.image ? `<img src="<?= BASE_URL ?>/uploads/${item.image}" alt="${item.name}" class="w-full h-full object-cover">` : '<i class="fas fa-utensils text-gray-400 flex items-center justify-center w-full h-full text-2xl"></i>'}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-lg">${item.name}</h4>
                            <p class="text-gray-600">€${item.price.toFixed(2)} cad.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="tempOrder.updateQuantity(${item.id}, ${item.quantity - 1})" class="w-10 h-10 bg-gray-200 text-gray-600 rounded-full hover:bg-gray-300 transition-colors flex items-center justify-center font-bold">−</button>
                        <span class="w-8 text-center font-bold text-lg">${item.quantity}</span>
                        <button onclick="tempOrder.updateQuantity(${item.id}, ${item.quantity + 1})" class="w-10 h-10 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors flex items-center justify-center font-bold">+</button>
                        <button onclick="tempOrder.removeItem(${item.id})" class="ml-3 w-10 h-10 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors flex items-center justify-center"><i class="fas fa-trash text-sm"></i></button>
                    </div>
                `;
                container.appendChild(itemElement);
            });
        }

        const total = this.getTotal();
        if (totalElement) {
            totalElement.textContent = `€${total.toFixed(2)}`;
        }
        
        this.updateSplitTotal();
    }

    updateSplitTotal() {
        const splitCountElement = document.getElementById('split-count');
        const splitTotalElement = document.getElementById('split-total');
        
        if (splitCountElement && splitTotalElement) {
            const splitCount = parseInt(splitCountElement.value) || 1;
            const total = this.getTotal();
            const splitAmount = total / splitCount;
            splitTotalElement.textContent = `€${splitAmount.toFixed(2)} a persona`;
        }
    }

    showAddedToast(itemName) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-6 right-6 bg-green-500 text-white px-6 py-4 rounded-2xl shadow-2xl z-50 transform translate-x-full transition-transform duration-500';
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-2xl"></i>
                <div>
                    <div class="font-bold">${itemName}</div>
                    <div class="text-sm opacity-90">aggiunto all'ordine!</div>
                </div>
            </div>
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => document.body.removeChild(toast), 500);
        }, 4000);
    }

    bindEvents() {
        document.addEventListener('input', (e) => {
            if (e.target.id === 'split-count') {
                this.updateSplitTotal();
            }
        });
    }
}

// UI Functions
let currentView = 'grid';
let tempOrder;

function toggleView() {
    const viewIcon = document.getElementById('view-icon');
    const viewText = document.getElementById('view-text');
    
    if (currentView === 'grid') {
        currentView = 'list';
        viewIcon.className = 'fas fa-th mr-2';
        viewText.textContent = 'Griglia';
        
        // Show list views, hide grid views
        document.querySelectorAll('[id^="grid-view-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="list-view-"]').forEach(el => el.classList.remove('hidden'));
    } else {
        currentView = 'grid';
        viewIcon.className = 'fas fa-th-large mr-2';
        viewText.textContent = 'Lista';
        
        // Show grid views, hide list views
        document.querySelectorAll('[id^="list-view-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="grid-view-"]').forEach(el => el.classList.remove('hidden'));
    }
}

function scrollToMenu() {
    document.getElementById('menu-content').scrollIntoView({ behavior: 'smooth' });
}

function openOrderModal() {
    document.getElementById('orderModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeOrderModal() {
    document.getElementById('orderModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openQRModal() {
    document.getElementById('qrModal').classList.remove('hidden');
}

function closeQRModal() {
    document.getElementById('qrModal').classList.add('hidden');
}

function shareOrder() {
    const items = tempOrder.items;
    if (items.length === 0) return;
    
    let orderText = `🍽️ Il mio ordine da <?= htmlspecialchars($restaurant['name']) ?>:\n\n`;
    items.forEach(item => {
        orderText += `• ${item.quantity}x ${item.name} - €${(item.price * item.quantity).toFixed(2)}\n`;
    });
    orderText += `\n💰 Totale: €${tempOrder.getTotal().toFixed(2)}`;
    orderText += `\n📍 <?= htmlspecialchars($restaurant['address'] ?? '') ?>`;
    
    if (navigator.share) {
        navigator.share({
            title: `Ordine da <?= htmlspecialchars($restaurant['name']) ?>`,
            text: orderText
        });
    } else {
        navigator.clipboard.writeText(orderText);
        alert('Ordine copiato negli appunti!');
    }
}

function shareMenu() {
    const url = '<?= BASE_URL . '/restaurant/' . $restaurant['slug'] ?>';
    const text = `Guarda il menu di <?= htmlspecialchars($restaurant['name']) ?>! ${url}`;
    
    if (navigator.share) {
        navigator.share({
            title: `Menu - <?= htmlspecialchars($restaurant['name']) ?>`,
            text: text,
            url: url
        });
    } else {
        navigator.clipboard.writeText(text);
        alert('Link copiato negli appunti!');
    }
    closeQRModal();
}

// Smooth scrolling for navigation
document.addEventListener('DOMContentLoaded', function() {
    tempOrder = new TemporaryOrder();
    
    // Category navigation highlighting
    const categoryItems = document.querySelectorAll('.category-nav-item');
    const sections = document.querySelectorAll('[id^="category-"]');
    
    function highlightCurrentCategory() {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            if (sectionTop <= 200) {
                current = section.getAttribute('id');
            }
        });
        
        categoryItems.forEach(item => {
            const categoryId = 'category-' + item.getAttribute('data-category');
            if (categoryId === current) {
                item.style.background = 'var(--theme-color)';
                item.style.color = 'white';
            } else {
                item.style.background = 'rgba(var(--theme-rgb), 0.1)';
                item.style.color = 'var(--theme-color)';
            }
        });
    }
    
    window.addEventListener('scroll', highlightCurrentCategory);
    highlightCurrentCategory();
    
    // Smooth scrolling for category links
    categoryItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'orderModal') closeOrderModal();
    if (e.target.id === 'qrModal') closeQRModal();
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>