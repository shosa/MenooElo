# Politica di Sicurezza

## Versioni Supportate

Attualmente supportiamo le seguenti versioni con aggiornamenti di sicurezza:

| Versione | Supportata |
| -------- | ---------- |
| 1.0.x    | ✅         |

## Segnalazione Vulnerabilità

Se scopri una vulnerabilità di sicurezza in MenuOELO, ti preghiamo di seguire questi passaggi:

### 1. NON creare una issue pubblica
Le vulnerabilità di sicurezza non devono essere segnalate pubblicamente fino a quando non sono state risolte.

### 2. Contatto Diretto
Invia un email a: **security@menooelo.com**

Include nella tua segnalazione:
- Descrizione dettagliata della vulnerabilità
- Passi per riprodurre il problema
- Potenziale impatto
- Qualsiasi prova di concetto (se appropriata)

### 3. Tempi di Risposta
- **Conferma ricezione**: entro 48 ore
- **Valutazione iniziale**: entro 7 giorni
- **Risoluzione**: secondo la gravità (critica: 3-7 giorni, alta: 14 giorni, media: 30 giorni)

## Misure di Sicurezza Implementate

### Autenticazione e Autorizzazione
- Hash password con bcrypt (cost 10)
- Session management sicuro
- Sistema di ruoli e permessi granulare
- Timeout automatico sessioni
- Protection contro attacchi brute force

### Protezione Input
- Sanitizzazione di tutti gli input utente
- Prepared statements per query database
- CSRF token protection su tutti i form
- Validazione lato server e client
- Upload file sicuro con validazione tipo/dimensione

### Database
- Connection sicura con PDO
- Prepared statements per prevenire SQL Injection
- Separazione privilegi database
- Activity logging per audit trail

### Session Management
- HttpOnly cookies
- Secure flag (quando HTTPS disponibile)
- Strict mode sessions
- Session regeneration dopo login

### File System
- Upload directory outside web root (quando possibile)
- Validazione estensioni file
- Controllo dimensione file
- Prevenzione path traversal

## Configurazione Sicura

### Hosting Requirements
```
- PHP 7.4+ con patch di sicurezza aggiornate
- MySQL 5.7+ con configurazione sicura
- HTTPS obbligatorio in produzione
- Backup automatici database
- Log monitoring
```

### Configurazioni Consigliate

#### PHP Configuration
```ini
; Nascondi versione PHP
expose_php = Off

; Limiti upload
upload_max_filesize = 5M
post_max_size = 10M

; Disabilita funzioni pericolose
disable_functions = exec,passthru,shell_exec,system,proc_open,popen

; Error reporting
display_errors = Off
log_errors = On
```

#### MySQL Configuration
```sql
-- Crea utente dedicato con privilegi limitati
CREATE USER 'menooelo_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON menooelo.* TO 'menooelo_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Apache Security Headers
```apache
# Security Headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Content-Security-Policy "default-src 'self'"

# HSTS (if HTTPS)
Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
```

## Checklist di Sicurezza Post-Installazione

### ✅ Configurazione Iniziale
- [ ] Cambiare password default super admin
- [ ] Eliminare file install.php
- [ ] Configurare HTTPS
- [ ] Impostare backup database
- [ ] Verificare permessi directory

### ✅ Hardening
- [ ] Configurare security headers Apache
- [ ] Limitare accesso directory uploads
- [ ] Impostare rate limiting
- [ ] Configurare log monitoring
- [ ] Testare configurazione sicurezza

### ✅ Monitoraggio
- [ ] Configurare alert per login falliti
- [ ] Monitorare activity logs
- [ ] Verificare backup automatici
- [ ] Aggiornamenti regolari sistema

## Vulnerabilità Conosciute

Attualmente non ci sono vulnerabilità conosciute nella versione 1.0.0.

## Risorse Aggiuntive

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Guide](https://www.php.net/manual/en/security.php)
- [MySQL Security Guide](https://dev.mysql.com/doc/refman/8.0/en/security.html)