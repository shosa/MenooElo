// MenooElo JavaScript Application
class MenuApp {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.setupNotifications();
        this.setupImageUpload();
        this.setupFormValidation();
    }
    
    setupEventListeners() {
        // Mobile menu toggle
        const burger = document.querySelector('.navbar-burger');
        const menu = document.querySelector('.navbar-menu');
        
        if (burger) {
            burger.addEventListener('click', () => {
                burger.classList.toggle('is-active');
                menu.classList.toggle('is-active');
            });
        }
        
        // Modals
        document.querySelectorAll('.modal-trigger').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const target = trigger.getAttribute('data-target');
                const modal = document.getElementById(target);
                if (modal) {
                    modal.classList.add('is-active');
                }
            });
        });
        
        document.querySelectorAll('.modal-close, .modal-background').forEach(close => {
            close.addEventListener('click', () => {
                close.closest('.modal').classList.remove('is-active');
            });
        });
        
        // Confirm delete
        document.querySelectorAll('.delete-confirm').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!confirm('Sei sicuro di voler eliminare questo elemento?')) {
                    e.preventDefault();
                }
            });
        });
        
        // Auto-hide notifications
        document.querySelectorAll('.notification .delete').forEach(deleteBtn => {
            deleteBtn.addEventListener('click', () => {
                deleteBtn.parentElement.remove();
            });
        });
    }
    
    setupNotifications() {
        // Auto-hide notifications after 5 seconds
        document.querySelectorAll('.notification:not(.is-persistent)').forEach(notification => {
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        });
    }
    
    setupImageUpload() {
        document.querySelectorAll('.upload-area').forEach(area => {
            const input = area.querySelector('input[type="file"]');
            const preview = area.querySelector('.image-preview');
            
            if (!input) return;
            
            // Drag and drop
            area.addEventListener('dragover', (e) => {
                e.preventDefault();
                area.classList.add('dragover');
            });
            
            area.addEventListener('dragleave', () => {
                area.classList.remove('dragover');
            });
            
            area.addEventListener('drop', (e) => {
                e.preventDefault();
                area.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    input.files = files;
                    this.previewImage(files[0], preview);
                }
            });
            
            // Click to select
            area.addEventListener('click', () => {
                input.click();
            });
            
            // File change
            input.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    this.previewImage(e.target.files[0], preview);
                }
            });
        });
    }
    
    previewImage(file, preview) {
        if (!file.type.startsWith('image/')) return;
        
        const reader = new FileReader();
        reader.onload = (e) => {
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    }
    
    setupFormValidation() {
        document.querySelectorAll('form[data-validate]').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
    }
    
    validateForm(form) {
        let isValid = true;
        
        // Required fields
        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'Campo obbligatorio');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });
        
        // Email validation
        form.querySelectorAll('input[type="email"]').forEach(field => {
            if (field.value && !this.isValidEmail(field.value)) {
                this.showFieldError(field, 'Email non valida');
                isValid = false;
            }
        });
        
        // Price validation
        form.querySelectorAll('input[data-type="price"]').forEach(field => {
            if (field.value && !this.isValidPrice(field.value)) {
                this.showFieldError(field, 'Prezzo non valido');
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('is-danger');
        const help = document.createElement('p');
        help.className = 'help is-danger field-error';
        help.textContent = message;
        field.parentNode.appendChild(help);
    }
    
    clearFieldError(field) {
        field.classList.remove('is-danger');
        const error = field.parentNode.querySelector('.field-error');
        if (error) error.remove();
    }
    
    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    isValidPrice(price) {
        const re = /^\d+(\.\d{1,2})?$/;
        return re.test(price) && parseFloat(price) >= 0;
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification is-${type} is-toast`;
        notification.innerHTML = `
            <button class="delete"></button>
            ${message}
        `;
        
        document.body.appendChild(notification);
        
        notification.querySelector('.delete').addEventListener('click', () => {
            notification.remove();
        });
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    // AJAX helpers
    async fetchJSON(url, options = {}) {
        const response = await fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            ...options
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    }
    
    async postJSON(url, data) {
        return await this.fetchJSON(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }
}

// Menu specific functions
class MenuManager {
    constructor() {
        this.setupSortable();
        this.setupQuickEdit();
    }
    
    setupSortable() {
        document.querySelectorAll('.sortable').forEach(list => {
            // Simple drag and drop implementation
            let draggedElement = null;
            
            list.querySelectorAll('.sortable-item').forEach(item => {
                item.draggable = true;
                
                item.addEventListener('dragstart', (e) => {
                    draggedElement = item;
                    e.dataTransfer.effectAllowed = 'move';
                });
                
                item.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                });
                
                item.addEventListener('drop', (e) => {
                    e.preventDefault();
                    if (draggedElement && draggedElement !== item) {
                        const rect = item.getBoundingClientRect();
                        const midpoint = rect.top + rect.height / 2;
                        
                        if (e.clientY < midpoint) {
                            item.parentNode.insertBefore(draggedElement, item);
                        } else {
                            item.parentNode.insertBefore(draggedElement, item.nextSibling);
                        }
                        
                        this.updateSortOrder();
                    }
                });
            });
        });
    }
    
    updateSortOrder() {
        const items = document.querySelectorAll('.sortable-item');
        const order = [];
        
        items.forEach((item, index) => {
            const id = item.dataset.id;
            if (id) {
                order.push({ id: id, order: index });
            }
        });
        
        // Send to server
        fetch('/admin/update-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ order: order })
        }).then(response => {
            if (!response.ok) {
                console.error('Failed to update order');
            }
        });
    }
    
    setupQuickEdit() {
        document.querySelectorAll('.quick-edit').forEach(element => {
            element.addEventListener('dblclick', (e) => {
                this.enableQuickEdit(element);
            });
        });
    }
    
    enableQuickEdit(element) {
        const originalValue = element.textContent;
        const input = document.createElement('input');
        input.value = originalValue;
        input.className = 'input is-small';
        
        element.replaceWith(input);
        input.focus();
        input.select();
        
        const saveEdit = () => {
            const newValue = input.value.trim();
            element.textContent = newValue || originalValue;
            input.replaceWith(element);
            
            if (newValue && newValue !== originalValue) {
                this.saveQuickEdit(element.dataset.field, element.dataset.id, newValue);
            }
        };
        
        input.addEventListener('blur', saveEdit);
        input.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') saveEdit();
            if (e.key === 'Escape') {
                element.textContent = originalValue;
                input.replaceWith(element);
            }
        });
    }
    
    saveQuickEdit(field, id, value) {
        fetch('/admin/quick-edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                field: field,
                id: id,
                value: value
            })
        }).then(response => {
            if (response.ok) {
                window.app.showNotification('Modificato con successo', 'success');
            } else {
                window.app.showNotification('Errore durante la modifica', 'danger');
            }
        });
    }
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.app = new MenuApp();
    window.menuManager = new MenuManager();
});