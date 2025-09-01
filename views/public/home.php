<?php 
$content = ob_start(); 
?>

<!-- Hero Section -->
<section class="relative min-h-screen bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="7" cy="7" r="7"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <!-- Content -->
    <div class="relative z-10 flex items-center min-h-screen">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-5xl lg:text-7xl font-bold text-white leading-tight mb-6">
                        Menoo<span class="text-yellow-400">Elo</span>
                    </h1>
                    <h2 class="text-2xl lg:text-3xl text-blue-100 font-light mb-8">
                        Il futuro dei menu digitali
                    </h2>
                    <p class="text-xl text-blue-50 mb-12 max-w-2xl">
                        Crea menu digitali professionali per il tuo ristorante. 
                        Facile da gestire, bellissimo da vedere, perfetto per i tuoi clienti.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#featured" class="group inline-flex items-center justify-center gap-3 px-8 py-4 bg-white text-blue-700 rounded-xl font-semibold text-lg hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 shadow-xl">
                            <i class="fas fa-utensils text-xl group-hover:rotate-12 transition-transform duration-300"></i>
                            <span>Scopri i Menu</span>
                        </a>
                        <a href="<?= BASE_URL ?>/admin/login" class="group inline-flex items-center justify-center gap-3 px-8 py-4 border-2 border-white text-white rounded-xl font-semibold text-lg hover:bg-white hover:text-blue-700 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-user-shield text-xl group-hover:rotate-12 transition-transform duration-300"></i>
                            <span>Accedi</span>
                        </a>
                    </div>
                </div>
                
                <!-- Hero Image -->
                <div class="relative">
                    <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                             alt="Restaurant Menu" 
                             class="w-full h-[500px] object-cover">
                    </div>
                    <!-- Floating Elements -->
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-yellow-400 rounded-full animate-bounce delay-1000"></div>
                    <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-white bg-opacity-20 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
            <div class="w-1 h-3 bg-white rounded-full mt-2"></div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                I numeri parlano chiaro
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Ristoranti di ogni dimensione si fidano di MenooElo per la loro presenza digitale
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div class="group text-center p-8 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl border border-blue-200 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <div class="text-5xl lg:text-6xl font-bold text-blue-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                    <?= $stats['total_restaurants'] ?>
                </div>
                <div class="text-xl font-semibold text-gray-800">Ristoranti Attivi</div>
                <div class="text-gray-600 mt-2">e in costante crescita</div>
            </div>
            
            <div class="group text-center p-8 bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl border border-green-200 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <div class="text-5xl lg:text-6xl font-bold text-green-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                    <?= $stats['total_menus'] ?>
                </div>
                <div class="text-xl font-semibold text-gray-800">Piatti Disponibili</div>
                <div class="text-gray-600 mt-2">sempre aggiornati in tempo reale</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-gray-50" id="why">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                Perché Scegliere MenooElo?
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Tutto quello che serve per un menu digitale perfetto, progettato per ristoranti moderni
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Mobile First -->
            <div class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-6 transition-transform duration-300">
                    <i class="fas fa-mobile-alt text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Mobile First</h3>
                <p class="text-gray-600 leading-relaxed">
                    Ottimizzato per smartphone e tablet. I tuoi clienti avranno sempre la migliore esperienza di navigazione.
                </p>
            </div>
            
            <!-- QR Code -->
            <div class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-6 transition-transform duration-300">
                    <i class="fas fa-qrcode text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">QR Code</h3>
                <p class="text-gray-600 leading-relaxed">
                    Genera codici QR personalizzati per condividere facilmente il tuo menu con i clienti.
                </p>
            </div>
            
            <!-- Easy Management -->
            <div class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-6 transition-transform duration-300">
                    <i class="fas fa-cog text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Facile Gestione</h3>
                <p class="text-gray-600 leading-relaxed">
                    Pannello admin intuitivo per gestire menu, categorie e prezzi senza complessità.
                </p>
            </div>
            
            <!-- Customizable -->
            <div class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-6 transition-transform duration-300">
                    <i class="fas fa-palette text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Personalizzabile</h3>
                <p class="text-gray-600 leading-relaxed">
                    Colori, logo e immagini per rispecchiare perfettamente la tua identità visiva.
                </p>
            </div>
            
            <!-- Analytics -->
            <div class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-6 transition-transform duration-300">
                    <i class="fas fa-chart-line text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Analytics</h3>
                <p class="text-gray-600 leading-relaxed">
                    Statistiche dettagliate sui piatti più popolari per ottimizzare la tua offerta.
                </p>
            </div>
            
            <!-- Security -->
            <div class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-6 transition-transform duration-300">
                    <i class="fas fa-shield-alt text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Sicuro</h3>
                <p class="text-gray-600 leading-relaxed">
                    Dati protetti con backup automatici per garantire sempre la sicurezza delle informazioni.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Restaurants -->
<?php if (!empty($featured_restaurants)): ?>
<section id="featured" class="py-20 bg-white">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                Ristoranti in Evidenza
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Scopri alcuni dei nostri menu digitali e lasciati ispirare dalle possibilità
            </p>
        </div>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach ($featured_restaurants as $restaurant): ?>
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:scale-105 overflow-hidden border border-gray-100">
                <!-- Cover Image -->
                <?php if ($restaurant['cover_image_url']): ?>
                <div class="relative h-48 overflow-hidden">
                    <img src="<?= BASE_URL ?>/uploads/<?= $restaurant['cover_image_url'] ?>" 
                         alt="<?= htmlspecialchars($restaurant['name']) ?>"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>
                <?php endif; ?>
                
                <div class="p-6">
                    <!-- Logo and Name -->
                    <div class="flex items-center gap-4 mb-4">
                        <?php if ($restaurant['logo_url']): ?>
                        <div class="flex-shrink-0 w-12 h-12 rounded-full overflow-hidden bg-gray-100">
                            <img src="<?= BASE_URL ?>/uploads/<?= $restaurant['logo_url'] ?>" 
                                 alt="<?= htmlspecialchars($restaurant['name']) ?>"
                                 class="w-full h-full object-cover">
                        </div>
                        <?php endif; ?>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">
                                <?= htmlspecialchars($restaurant['name']) ?>
                            </h3>
                            <?php if ($restaurant['description']): ?>
                            <p class="text-sm text-gray-600 line-clamp-2">
                                <?= htmlspecialchars(substr($restaurant['description'], 0, 60)) ?>...
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Stats and CTA -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 px-3 py-1 bg-gray-100 rounded-full">
                            <i class="fas fa-utensils text-gray-500 text-xs"></i>
                            <span class="text-sm font-medium text-gray-700">
                                <?= $restaurant['menu_items_count'] ?> piatti
                            </span>
                        </div>
                        <a href="<?= BASE_URL ?>/restaurant/<?= $restaurant['slug'] ?>" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200 text-sm">
                            <span>Visualizza</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- How it Works -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                Come Funziona
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                In pochi semplici passaggi il tuo ristorante avrà un menu digitale professionale
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-12 relative">
            <!-- Connection Line -->
            <div class="hidden md:block absolute top-1/2 left-1/4 right-1/4 h-1 bg-gradient-to-r from-blue-200 via-indigo-200 to-green-200 rounded-full -translate-y-1/2 z-0"></div>
            
            <!-- Step 1 -->
            <div class="relative z-10 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <span class="text-2xl font-bold text-white">1</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Registrazione</h3>
                <p class="text-gray-600 leading-relaxed">
                    Il super admin crea il tuo account ristorante con le credenziali di accesso personalizzate.
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="relative z-10 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <span class="text-2xl font-bold text-white">2</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Personalizza</h3>
                <p class="text-gray-600 leading-relaxed">
                    Configura il tuo ristorante: logo, colori, informazioni e crea le categorie del menu.
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="relative z-10 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <span class="text-2xl font-bold text-white">3</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Pubblica</h3>
                <p class="text-gray-600 leading-relaxed">
                    Aggiungi i tuoi piatti con foto e descrizioni, poi condividi il menu digitale con i clienti.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="7" cy="7" r="7"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="container mx-auto px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl lg:text-6xl font-bold text-white mb-8">
            Pronto per iniziare?
        </h2>
        <p class="text-xl lg:text-2xl text-blue-100 mb-12 max-w-4xl mx-auto leading-relaxed">
            Trasforma il tuo menu tradizionale in un'esperienza digitale moderna che i tuoi clienti ameranno
        </p>
        
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="mailto:admin@menooelo.com" 
               class="group inline-flex items-center justify-center gap-3 px-8 py-4 bg-white text-blue-700 rounded-xl font-semibold text-lg hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 shadow-xl">
                <i class="fas fa-envelope text-xl group-hover:bounce transition-transform duration-300"></i>
                <span>Contattaci Ora</span>
            </a>
            <a href="#featured" 
               class="group inline-flex items-center justify-center gap-3 px-8 py-4 border-2 border-white text-white rounded-xl font-semibold text-lg hover:bg-white hover:text-blue-700 transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-eye text-xl group-hover:bounce transition-transform duration-300"></i>
                <span>Scopri gli Esempi</span>
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Brand -->
            <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    Menoo<span class="text-yellow-400">Elo</span>
                </h3>
                <p class="text-gray-400 leading-relaxed mb-6">
                    Sistema di menu digitali per ristoranti moderni. Facile, veloce e professionale.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors duration-200">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-400 transition-colors duration-200">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
            
            <!-- Product -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-6">Prodotto</h4>
                <ul class="space-y-3">
                    <li><a href="#why" class="text-gray-400 hover:text-white transition-colors duration-200">Funzionalità</a></li>
                    <li><a href="#featured" class="text-gray-400 hover:text-white transition-colors duration-200">Demo</a></li>
                    <li><a href="<?= BASE_URL ?>/superadmin/login" class="text-gray-400 hover:text-white transition-colors duration-200">Amministrazione</a></li>
                </ul>
            </div>
            
            <!-- Support -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-6">Supporto</h4>
                <ul class="space-y-3">
                    <li><a href="mailto:support@menooelo.com" class="text-gray-400 hover:text-white transition-colors duration-200">Assistenza</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Documentazione</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">FAQ</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Tutorial</a></li>
                </ul>
            </div>
            
            <!-- Company -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-6">Azienda</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Chi siamo</a></li>
                    <li><a href="mailto:admin@menooelo.com" class="text-gray-400 hover:text-white transition-colors duration-200">Contatti</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Privacy Policy</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Termini di Servizio</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm mb-4 md:mb-0">
                © 2024 <strong class="text-white">MenooElo</strong> by OELO Solutions. Tutti i diritti riservati.
            </p>
            <p class="text-gray-400 text-sm">
                Made with ❤️ in Italy
            </p>
        </div>
    </div>
</footer>

<!-- Smooth scrolling -->
<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>