# MenooElo - Sistema di Menu Digitali

Un sistema completo per la gestione di menu digitali per ristoranti, bar, pizzerie e locali di ristorazione.

##  Caratteristiche

### Architettura 3-Tier
- **Super Admin Panel**: Gestione completa del sistema e dei ristoranti
- **Restaurant Admin Panel**: Gestione del proprio menu e impostazioni
- **Frontend Pubblico**: Visualizzazione menu per i clienti

### Funzionalità Principali
- **Menu Digitali Responsive** - Ottimizzati per mobile e desktop
- **Gestione Categorie e Piatti** - Organizzazione completa del menu
- **Upload Immagini** - Foto per categorie e piatti
- **Varianti e Extra** - Gestione taglie e aggiunte
- **Informazioni Allergeni** - Sicurezza per i clienti
- **QR Code** - Condivisione facile del menu
- **Temi Personalizzati** - Colori e branding personalizzati
- **Multi-tenant** - Gestione multipli ristoranti
- **Sistema Permessi** - Ruoli e autorizzazioni
- **Activity Logs** - Tracciamento delle attività
- **Mobile-First Design** - Perfetto su smartphone

## Tecnologie Utilizzate

- **Backend**: PHP 7.4+ con architettura MVC
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **CSS Framework**: Tailwind CSS
- **Icons**: Font Awesome
- **Server**: Apache con mod_rewrite

## Requisiti di Sistema

- PHP 7.4 o superiore
- MySQL 5.7 o superiore
- Apache con mod_rewrite abilitato
- Estensioni PHP:
  - PDO
  - GD (per manipolazione immagini)
  - mbstring
  - JSON

## Installazione

### 1. Configurazione Database
1. Crea un database MySQL chiamato `menooelo`
2. Modifica `config/config.php` con le tue credenziali database:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'menooelo');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Installazione Automatica
1. Naviga su `http://localhost/menooelo/install.php`
2. Clicca su "Installa MenooElo"
3. Al termine, elimina il file `install.php` per sicurezza

### 4. Primo Accesso
**Super Admin:**
- URL: `http://localhost/menooelo/superadmin/login`
- Username: `superadmin`
- Email: `admin@menooelo.com`
- Password: `password` (da cambiare immediatamente)

## Guida Utilizzo

### Super Admin
1. **Gestione Ristoranti**: Crea e gestisce tutti i ristoranti del sistema
2. **Controllo Utenti**: Gestisce gli admin dei ristoranti
3. **Impostazioni Sistema**: Configurazione globale
4. **Analytics**: Statistiche generali del sistema

### Restaurant Admin
1. **Dashboard**: Panoramica del proprio ristorante
2. **Categorie Menu**: Crea e gestisce le categorie
3. **Piatti**: Aggiunge e modifica i piatti del menu
4. **Impostazioni**: Personalizza il ristorante (logo, colori, info)
5. **QR Code**: Genera codici QR per condividere il menu

### Cliente Finale
1. **Visualizzazione Menu**: Interfaccia mobile-friendly
2. **Navigazione Categorie**: Accesso rapido alle sezioni del menu
3. **Dettagli Piatti**: Descrizioni, ingredienti, allergeni
4. **Condivisione**: QR code e condivisione social

## Personalizzazione

### Colori e Branding
- Ogni ristorante può impostare il proprio colore tema
- Upload di logo personalizzato
- Immagine di copertina

### Layout Responsivo
- Design mobile-first
- Adattamento automatico a tutti i dispositivi
- Navigazione touch-friendly

## Sicurezza

- **Autenticazione**: Sistema di login sicuro con hash password
- **Autorizzazioni**: Controllo accessi basato sui ruoli
- **CSRF Protection**: Token di sicurezza per i form
- **Sanitizzazione Input**: Protezione contro XSS e SQL Injection
- **Activity Logging**: Tracciamento di tutte le attività


## Licenza

Questo progetto è rilasciato sotto licenza MIT.

---

