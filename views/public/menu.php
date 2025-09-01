<?php 
$content = ob_start(); 
?>

<style>
:root {
    --theme-color: <?= $restaurant['theme_color'] ?? '#3b82f6' ?>;
    --theme-rgb: <?php 
        $themeColor = $restaurant['theme_color'] ?? '#3b82f6';
        $hex = str_replace('#', '', $themeColor);
        if(strlen($hex) == 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2)); 
            $b = hexdec(substr($hex, 4, 2));
            echo "$r, $g, $b";
        } else {
            echo "59, 130, 246"; // fallback blue
        }
    ?>;
}
.theme-gradient { background: linear-gradient(135deg, var(--theme-color), rgba(var(--theme-rgb), 0.8)); }
.theme-light { background: rgba(var(--theme-rgb), 0.1); }
.theme-border { border-color: rgba(var(--theme-rgb), 0.3); }
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

/* Animazioni personalizzate */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(50px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1); }
}

@keyframes bounceIn {
    0% { opacity: 0; transform: scale(0.3); }
    50% { opacity: 1; transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { opacity: 1; transform: scale(1); }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes pulse-glow {
    0%, 100% { 
        box-shadow: 0 0 0 0 rgba(var(--theme-rgb), 0.7);
        transform: scale(1);
    }
    50% { 
        box-shadow: 0 0 0 15px rgba(var(--theme-rgb), 0);
        transform: scale(1.05);
    }
}

.animate-fadeInUp { animation: fadeInUp 0.8s ease-out forwards; }
.animate-fadeInDown { animation: fadeInDown 0.6s ease-out forwards; }
.animate-scaleIn { animation: scaleIn 0.7s ease-out forwards; }
.animate-bounceIn { animation: bounceIn 1s ease-out forwards; }
.animate-float { animation: float 3s ease-in-out infinite; }
.animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }

/* Layout fixes per contenimento */
* {
    box-sizing: border-box;
}

body {
    overflow-x: hidden;
    width: 100%;
}

.container {
    max-width: 100%;
    overflow-x: hidden;
    padding-left: 1rem;
    padding-right: 1rem;
}

/* Fix per elementi che possono sforare */
img {
    max-width: 100%;
    height: auto;
}

.category-section button {
    max-width: 100%;
    word-wrap: break-word;
}

/* Responsive fixes */
@media (max-width: 768px) {
    .container {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .category-section button {
        padding: 1rem;
    }
    
    h1, h2, h3 {
        word-wrap: break-word;
        hyphens: auto;
    }
}

/* Categorie collassabili */
.category-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.5s ease-out;
}
.category-content.expanded {
    max-height: 5000px;
    transition: max-height 0.8s ease-in;
}
</style>

<!-- Order Floating Button -->
<button id="order-summary-btn" 
        class="fixed bottom-6 right-6 z-50 rounded-full p-4 hover:scale-110 transition-all duration-300 shadow-2xl animate-pulse-glow hidden border border-white"
        style="background: var(--theme-color);"
        onclick="openOrderModal()">
    <div class="flex items-center gap-3">
        <div class="relative">
            <i class="fas fa-shopping-cart text-2xl text-white"></i>
            <span id="order-count-badge" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold hidden">0</span>
        </div>
        <span class="font-semibold hidden sm:inline text-white">Ordine</span>
    </div>
</button>

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
        const splitTotalElement = document.getElementById('split-total');
        
        if (!container) return;

        container.innerHTML = '';
        
        if (this.items.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-8">Nessun piatto selezionato</p>';
        } else {
            this.items.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'flex items-center justify-between py-3 border-b border-gray-200';
                itemElement.innerHTML = `
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden">
                            ${item.image ? `<img src="<?= BASE_URL ?>/uploads/${item.image}" alt="${item.name}" class="w-full h-full object-cover">` : '<i class="fas fa-utensils text-gray-400 flex items-center justify-center w-full h-full"></i>'}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${item.name}</h4>
                            <p class="text-sm text-gray-600">€${item.price.toFixed(2)} cad.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="tempOrder.updateQuantity(${item.id}, ${item.quantity - 1})" class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full hover:bg-gray-300 transition-colors">-</button>
                        <span class="w-8 text-center font-medium">${item.quantity}</span>
                        <button onclick="tempOrder.updateQuantity(${item.id}, ${item.quantity + 1})" class="w-8 h-8 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors">+</button>
                        <button onclick="tempOrder.removeItem(${item.id})" class="ml-2 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors"><i class="fas fa-trash text-xs"></i></button>
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
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>${itemName} aggiunto all'ordine!</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        
        // Hide toast
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }

    bindEvents() {
        // Split count change event
        document.addEventListener('input', (e) => {
            if (e.target.id === 'split-count') {
                this.updateSplitTotal();
            }
        });
    }
}

// Initialize temporary order
let tempOrder;
document.addEventListener('DOMContentLoaded', function() {
    tempOrder = new TemporaryOrder();
});
</script>

<!-- Restaurant Header -->
<section class="relative bg-gray-900 overflow-hidden min-h-screen flex items-center justify-center">
    <?php if (isset($restaurant['cover_image_url']) && $restaurant['cover_image_url']): ?>
    <!-- Background Banner with Blur -->
    <div class="absolute inset-0">
        <img src="<?= BASE_URL ?>/uploads/<?= $restaurant['cover_image_url'] ?>" 
             alt="<?= htmlspecialchars($restaurant['name']) ?>"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="absolute inset-0 theme-gradient opacity-20"></div>
    </div>
    <?php else: ?>
    <!-- Gradient Background when no banner -->
    <div class="absolute inset-0 theme-gradient"></div>
    <?php endif; ?>
    
    <!-- Content -->
    <div class="relative z-10 py-16 lg:py-24">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="max-w-4xl">
                <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
                    <!-- Logo -->
                    <?php if (isset($restaurant['logo_url']) && $restaurant['logo_url']): ?>
                    <div class="flex-shrink-0 animate-fadeInUp" style="animation-delay: 0.3s;">
                        <div class="w-32 h-32 lg:w-40 lg:h-40 rounded-2xl overflow-hidden shadow-2xl bg-white p-2">
                            <img src="<?= BASE_URL ?>/uploads/<?= $restaurant['logo_url'] ?>" 
                                 alt="<?= htmlspecialchars($restaurant['name']) ?>"
                                 class="w-full h-full object-contain rounded-xl">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Restaurant Info -->
                    <div class="flex-1 text-center lg:text-left">
                        <h1 class="text-4xl lg:text-6xl font-bold text-white leading-tight mb-4 animate-fadeInDown">
                            <?= htmlspecialchars($restaurant['name']) ?>
                        </h1>
                        
                        <?php if ($restaurant['description']): ?>
                        <p class="text-xl lg:text-2xl text-white/90 font-light mb-8 max-w-2xl animate-fadeInUp" style="animation-delay: 0.2s;">
                            <?= htmlspecialchars($restaurant['description']) ?>
                        </p>
                        <?php endif; ?>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-4 justify-center lg:justify-start mb-8 animate-fadeInUp" style="animation-delay: 0.4s;">
                            <button onclick="scrollToMenu()" 
                                    class="group inline-flex items-center gap-3 px-8 py-4 bg-white text-black rounded-xl font-bold text-lg hover:scale-105 hover:shadow-2xl transition-all duration-300">
                                <i class="fas fa-utensils group-hover:rotate-12 transition-transform duration-300"></i>
                                <span>Vai al Menu</span>
                            </button>
                            
                            <?php if ($restaurant['phone']): ?>
                            <a href="tel:<?= $restaurant['phone'] ?>" 
                               class="group inline-flex items-center gap-3 px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-semibold hover:bg-white/30 hover:scale-105 transition-all duration-300 border border-white/20">
                                <i class="fas fa-phone group-hover:rotate-12 transition-transform duration-300"></i>
                                <span><?= htmlspecialchars($restaurant['phone']) ?></span>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($restaurant['website']): ?>
                            <a href="<?= $restaurant['website'] ?>" target="_blank"
                               class="group inline-flex items-center gap-3 px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-semibold hover:bg-white/30 hover:scale-105 transition-all duration-300 border border-white/20">
                                <i class="fas fa-globe group-hover:rotate-12 transition-transform duration-300"></i>
                                <span>Sito Web</span>
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Scroll Indicator -->
                        <div class="flex justify-center lg:justify-start animate-fadeInUp" style="animation-delay: 0.6s;">
                            <div class="animate-bounce">
                                <i class="fas fa-chevron-down text-white/60 text-2xl animate-pulse"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-10 right-10 w-20 h-20 bg-white/10 rounded-full animate-pulse hidden lg:block"></div>
    <div class="absolute bottom-10 left-10 w-16 h-16 bg-white/5 rounded-full animate-bounce hidden lg:block"></div>
</section>

<!-- Restaurant Details -->
<section class="py-8 bg-gray-50 border-b border-gray-200">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
            <!-- Contact Info -->
            <div class="flex flex-wrap items-center gap-6 text-gray-600">
                <?php if ($restaurant['address']): ?>
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-gray-500"></i>
                    <span><?= htmlspecialchars($restaurant['address']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($restaurant['email']): ?>
                <div class="flex items-center gap-2">
                    <i class="fas fa-envelope text-gray-500"></i>
                    <a href="mailto:<?= $restaurant['email'] ?>" class="hover:text-blue-600 transition-colors">
                        <?= htmlspecialchars($restaurant['email']) ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Social Links -->
            <div class="flex items-center gap-3">
                <?php if ($restaurant['social_facebook']): ?>
                <a href="<?= $restaurant['social_facebook'] ?>" target="_blank"
                   class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors duration-200">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <?php endif; ?>
                
                <?php if ($restaurant['social_instagram']): ?>
                <a href="<?= $restaurant['social_instagram'] ?>" target="_blank"
                   class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-600 text-white rounded-full flex items-center justify-center hover:from-purple-700 hover:to-pink-700 transition-colors duration-200">
                    <i class="fab fa-instagram"></i>
                </a>
                <?php endif; ?>
                
                <!-- QR Code Button -->
                <?php if ($restaurant['features']['qrcode'] ?? false): ?>
                <button onclick="openQRModal()" 
                        class="w-10 h-10 bg-gray-600 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors duration-200"
                        title="Condividi Menu">
                    <i class="fas fa-qrcode"></i>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Category Navigation -->
<?php if (!empty($categories)): ?>
<section class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-sm">
    <div class="container mx-auto px-6 lg:px-8 py-4">
        <div class="flex gap-2 overflow-x-auto scrollbar-hide">
            <?php foreach ($categories as $category): ?>
            <a href="#category-<?= $category['id'] ?>" 
               class="category-nav-btn flex-shrink-0 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all duration-200 border border-gray-200"
               data-target="category-<?= $category['id'] ?>">
                <?= htmlspecialchars($category['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Menu Content -->
<section id="menu-section" class="py-8 lg:py-12 bg-white">
    <div class="container mx-auto px-6 lg:px-8">
        <?php if (empty($categories)): ?>
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-32 h-32 mx-auto mb-8 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-utensils text-4xl text-gray-400"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-600 mb-4">Menu in Costruzione</h2>
            <p class="text-xl text-gray-500">Il nostro menu sarà presto disponibile!</p>
        </div>
        <?php else: ?>
        
        <!-- Menu Categories -->
        <?php foreach ($categories as $category): ?>
        <div id="category-<?= $category['id'] ?>" class="category-section mb-8 scroll-mt-24">
            <!-- Category Header (Clickable) -->
            <button onclick="toggleCategory('<?= $category['id'] ?>')" 
                    class="w-full text-left rounded-2xl shadow-lg border-2 theme-border hover:shadow-xl hover:scale-[1.02] transition-all duration-300 mb-6 group overflow-hidden relative h-32">
                
                <?php if ($category['image_url']): ?>
                <!-- Background Image with Strong Gradient -->
                <div class="absolute inset-0">
                    <img src="<?= BASE_URL ?>/uploads/<?= $category['image_url'] ?>" 
                         alt="<?= htmlspecialchars($category['name']) ?>"
                         class="w-full h-full object-cover object-left">
                    <!-- Strong Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-black/30 from-5% via-black/60 via-25% via-white/90 via-45% to-white to-55%"></div>
                </div>
                <?php endif; ?>
                
                <!-- Content -->
                <div class="relative z-10 h-full flex items-center p-4 <?= $category['image_url'] ? '' : 'bg-white' ?>">
                    <div class="flex items-center justify-between w-full">
                        <div class="flex-1 pr-6 min-w-0">
                            <h2 class="text-2xl lg:text-3xl font-bold leading-tight mb-1 <?= $category['image_url'] ? 'text-white drop-shadow-2xl' : '' ?>" 
                                style="<?= !$category['image_url'] ? 'color: var(--theme-color);' : '' ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </h2>
                            <?php if ($category['description']): ?>
                            <p class="text-xs leading-relaxed <?= $category['image_url'] ? 'text-white/95 drop-shadow-xl' : 'text-gray-600' ?> line-clamp-2">
                                <?= htmlspecialchars($category['description']) ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        <!-- Right area - always on white background -->
                        <div class="flex items-center gap-3 flex-shrink-0 relative z-20">
                            <div class="text-xs text-right">
                                <div class="font-bold" style="color: var(--theme-color);">
                                    <?= count($menuItems[$category['id']] ?? []) ?> piatti
                                </div>
                                <div class="text-xs text-gray-500">
                                    Espandi
                                </div>
                            </div>
                            <i id="icon-<?= $category['id'] ?>" class="fas fa-chevron-down text-xl transition-transform duration-300 group-hover:scale-110" 
                               style="color: var(--theme-color);"></i>
                        </div>
                    </div>
                </div>
            </button>
            
            <!-- Category Content (Collapsible) -->
            <div id="content-<?= $category['id'] ?>" class="category-content" style="max-height: 0; overflow: hidden; transition: max-height 0.5s ease-out;">
                <!-- Menu Items -->
            <?php if (empty($menuItems[$category['id']])): ?>
            <div class="text-center py-8 bg-gray-50 rounded-2xl">
                <i class="fas fa-plate-wheat text-3xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">Nessun piatto disponibile in questa categoria.</p>
            </div>
            <?php else: ?>
            <div class="grid sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php foreach ($menuItems[$category['id']] as $item): ?>
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden <?= !$item['is_available'] ? 'opacity-60' : '' ?>">
                    <!-- Item Image -->
                    <?php if ($item['image_url']): ?>
                    <div class="relative h-48 overflow-hidden">
                        <img src="<?= BASE_URL ?>/uploads/<?= $item['image_url'] ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        
                        <!-- Featured Badge -->
                        <?php if ($item['is_featured']): ?>
                        <div class="absolute top-4 left-4">
                            <div class="flex items-center gap-1 px-3 py-1 bg-yellow-500 text-white rounded-full text-sm font-medium shadow-lg">
                                <i class="fas fa-star text-xs"></i>
                                <span>Speciale</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Availability Overlay -->
                        <?php if (!$item['is_available']): ?>
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                            <span class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold">
                                Non Disponibile
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Item Content -->
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">
                                    <?= htmlspecialchars($item['name']) ?>
                                    <?php if ($item['is_featured'] && !$item['image_url']): ?>
                                    <i class="fas fa-star text-yellow-500 ml-2 text-sm"></i>
                                    <?php endif; ?>
                                </h3>
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <span class="text-2xl font-bold" style="color: <?= $restaurant['theme_color'] ?>;">
                                    <?php if (!empty($item['variants'])): ?>
                                    da €<?= number_format(min(array_column($item['variants'], 'price_modifier')) + $item['price'], 2) ?>
                                    <?php else: ?>
                                    €<?= number_format($item['price'], 2) ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <?php if ($item['description']): ?>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            <?= nl2br(htmlspecialchars($item['description'])) ?>
                        </p>
                        <?php endif; ?>
                        
                        <!-- Ingredients -->
                        <?php if ($item['ingredients']): ?>
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-800 mb-2">Ingredienti:</h4>
                            <p class="text-sm text-gray-600"><?= htmlspecialchars($item['ingredients']) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Variants -->
                        <?php if (!empty($item['variants'])): ?>
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Varianti:</h4>
                            <div class="space-y-2">
                                <?php foreach ($item['variants'] as $variant): ?>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-700"><?= htmlspecialchars($variant['name']) ?></span>
                                    <span class="font-semibold" style="color: <?= $restaurant['theme_color'] ?>;">
                                        €<?= number_format($item['price'] + $variant['price_modifier'], 2) ?>
                                    </span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Extras -->
                        <?php if (!empty($item['extras'])): ?>
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Extra disponibili:</h4>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($item['extras'] as $extra): ?>
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                    <span><?= htmlspecialchars($extra['name']) ?></span>
                                    <span class="font-semibold">+€<?= number_format($extra['price'], 2) ?></span>
                                </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Allergens -->
                        <?php if (!empty($item['allergens'])): ?>
                        <div class="flex items-start gap-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                            <div>
                                <h4 class="text-sm font-semibold text-yellow-800">Allergeni:</h4>
                                <p class="text-sm text-yellow-700">
                                    <?= implode(', ', $item['allergens']) ?>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Add to Order Button -->
                        <?php if ($item['is_available']): ?>
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <button onclick="tempOrder.addItem(<?= $item['id'] ?>, '<?= addslashes($item['name']) ?>', <?= $item['price'] ?>, '<?= $item['image_url'] ?>')"
                                    class="w-full px-6 py-3 text-white rounded-xl font-semibold hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 shadow-lg"
                                    style="background: var(--theme-color);">
                                <i class="fas fa-plus"></i>
                                <span>Aggiungi all'Ordine</span>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            </div> <!-- Close category-content collapsible div -->
        </div>
        <?php endforeach; ?>
        
        <?php endif; ?>
    </div>
</section>

<!-- QR Code Modal -->
<?php if ($restaurant['features']['qrcode'] ?? false): ?>
<div id="qr-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
        <div class="p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Condividi questo Menu</h3>
            
            <div class="mb-8">
                <div class="inline-block p-4 bg-gray-50 rounded-2xl">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode(BASE_URL . '/restaurant/' . $restaurant['slug']) ?>" 
                         alt="QR Code Menu"
                         class="w-48 h-48 mx-auto">
                </div>
                <p class="text-sm text-gray-600 mt-4">
                    Scansiona per condividere il menu
                </p>
            </div>
            
            <div class="flex gap-3">
                <button onclick="shareMenu()" 
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-share"></i>
                    <span>Condividi</span>
                </button>
                <button onclick="closeQRModal()" 
                        class="px-6 py-3 text-gray-600 border border-gray-300 rounded-xl font-semibold hover:bg-gray-50 transition-colors duration-200">
                    Chiudi
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

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

<!-- Floating QR Button -->
<?php if ($restaurant['features']['qrcode'] ?? false): ?>
<button onclick="openQRModal()" 
        class="fixed bottom-6 left-6 w-14 h-14 rounded-full shadow-xl z-40 flex items-center justify-center text-white font-semibold hover:scale-110 transition-all duration-300"
        style="background: var(--theme-color);">
    <i class="fas fa-qrcode text-xl"></i>
</button>
<?php endif; ?>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-6 lg:px-8 text-center">
        <h3 class="text-2xl font-bold mb-4">
            <?= htmlspecialchars($restaurant['name']) ?>
        </h3>
        <p class="text-gray-400 mb-6">
            Menu digitale powered by <strong class="text-white">MenooElo</strong>
        </p>
        
        <!-- Social Links -->
        <div class="flex justify-center gap-4 mb-6">
            <?php if ($restaurant['social_facebook']): ?>
            <a href="<?= $restaurant['social_facebook'] ?>" target="_blank"
               class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors duration-200">
                <i class="fab fa-facebook-f"></i>
            </a>
            <?php endif; ?>
            
            <?php if ($restaurant['social_instagram']): ?>
            <a href="<?= $restaurant['social_instagram'] ?>" target="_blank"
               class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors duration-200">
                <i class="fab fa-instagram"></i>
            </a>
            <?php endif; ?>
        </div>
        
        
    </div>
</footer>

<!-- Scripts -->
<script>
// Smooth scrolling for category navigation
document.querySelectorAll('.category-nav-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = btn.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Highlight active category in navigation
function highlightActiveCategory() {
    const categories = document.querySelectorAll('.category-section');
    const navButtons = document.querySelectorAll('.category-nav-btn');
    
    let activeIndex = 0;
    categories.forEach((category, index) => {
        const rect = category.getBoundingClientRect();
        if (rect.top <= 150 && rect.bottom >= 150) {
            activeIndex = index;
        }
    });
    
    navButtons.forEach((btn, index) => {
        if (index === activeIndex) {
            btn.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-200');
            btn.classList.add('text-white', 'border-transparent');
            btn.style.background = '<?= $restaurant['theme_color'] ?>';
        } else {
            btn.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
            btn.classList.remove('text-white', 'border-transparent');
            btn.style.background = '';
        }
    });
}

// QR Modal functions
function openQRModal() {
    document.getElementById('qr-modal').classList.remove('hidden');
}

function closeQRModal() {
    document.getElementById('qr-modal').classList.add('hidden');
}

// Share functionality
function shareMenu() {
    if (navigator.share) {
        navigator.share({
            title: '<?= addslashes($restaurant['name']) ?> - Menu',
            text: 'Guarda il menu di <?= addslashes($restaurant['name']) ?>',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            // Show success message
            const btn = document.querySelector('[onclick="shareMenu()"]');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> <span>Copiato!</span>';
            btn.classList.add('bg-green-600');
            btn.classList.remove('bg-blue-600');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-blue-600');
            }, 2000);
        });
    }
}

// Order Modal functions
function openOrderModal() {
    document.getElementById('orderModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeOrderModal() {
    document.getElementById('orderModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Share Order functionality
function shareOrder() {
    const items = tempOrder.items;
    if (items.length === 0) return;
    
    let orderText = `🍽️ Il mio ordine da <?= addslashes($restaurant['name']) ?>:\n\n`;
    items.forEach(item => {
        orderText += `• ${item.quantity}x ${item.name} - €${(item.price * item.quantity).toFixed(2)}\n`;
    });
    orderText += `\n💰 Totale: €${tempOrder.getTotal().toFixed(2)}`;
    orderText += `\n📍 <?= addslashes($restaurant['address'] ?? '') ?>`;
    
    if (navigator.share) {
        navigator.share({
            title: `Ordine da <?= addslashes($restaurant['name']) ?>`,
            text: orderText
        });
    } else {
        navigator.clipboard.writeText(orderText).then(() => {
            alert('Ordine copiato negli appunti!');
        });
    }
}

// Scroll to Menu function
function scrollToMenu() {
    const menuSection = document.getElementById('menu-section');
    if (menuSection) {
        menuSection.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start',
            inline: 'nearest'
        });
    }
}

// Toggle Category Accordion
function toggleCategory(categoryId) {
    const content = document.getElementById(`content-${categoryId}`);
    const icon = document.getElementById(`icon-${categoryId}`);
    
    if (!content || !icon) return;
    
    const isExpanded = content.style.maxHeight && content.style.maxHeight !== '0px';
    
    if (isExpanded) {
        // Collapse
        content.style.maxHeight = '0px';
        icon.style.transform = 'rotate(0deg)';
        content.style.paddingTop = '0px';
        content.style.paddingBottom = '0px';
    } else {
        // Expand
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
        content.style.paddingTop = '20px';
        content.style.paddingBottom = '20px';
        
        // Auto adjust if content grows
        setTimeout(() => {
            if (content.style.maxHeight !== '0px') {
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        }, 100);
    }
}

// Expand first category by default
document.addEventListener('DOMContentLoaded', function() {
    const firstCategory = document.querySelector('[id^="content-"]');
    if (firstCategory) {
        const categoryId = firstCategory.id.replace('content-', '');
        toggleCategory(categoryId);
    }
});

// Event listeners
window.addEventListener('scroll', highlightActiveCategory);
document.addEventListener('DOMContentLoaded', highlightActiveCategory);

// Close modal when clicking outside
document.getElementById('qr-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeQRModal();
    }
});

document.getElementById('orderModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeOrderModal();
    }
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>