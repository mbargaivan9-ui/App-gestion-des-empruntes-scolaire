/**
 * Main JavaScript - Biblio App
 */

document.addEventListener('DOMContentLoaded', function() {
    // Validation des formulaires
    initFormValidation();
    
    // Initialiser les tooltips Bootstrap
    initTooltips();
    
    // Gestion des messages d'alerte
    initAlerts();
    
    // Recherche en temps réel
    initSearch();
});

/**
 * Validation des formulaires
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate="true"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
                showAlert('error', 'Veuillez remplir tous les champs correctement');
            }
            form.classList.add('was-validated');
        });
    });
}

/**
 * Initialiser les tooltips Bootstrap
 */
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
}

/**
 * Gestion des alertes
 */
function initAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const closeBtn = alert.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                alert.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            });
        }
        
        // Auto-dismiss après 5 secondes
        if (alert.classList.contains('alert-success') || 
            alert.classList.contains('alert-warning')) {
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.style.animation = 'slideOut 0.3s ease forwards';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }
            }, 5000);
        }
    });
}

/**
 * Afficher une alerte
 */
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.setAttribute('role', 'alert');
    
    const icon = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    
    alertDiv.innerHTML = `
        <i class="fas ${icon[type] || 'fa-info-circle'}"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('main');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // Initialiser la fermeture
        setTimeout(() => {
            initAlerts();
        }, 100);
    }
}

/**
 * Recherche en temps réel
 */
function initSearch() {
    const searchInputs = document.querySelectorAll('[data-search="true"]');
    
    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableId = this.getAttribute('data-target');
            const table = document.getElementById(tableId);
            
            if (!table) return;
            
            const rows = table.querySelectorAll('tbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Afficher un message si aucun résultat
            if (visibleCount === 0) {
                const emptyRow = table.querySelector('tr[data-empty]');
                if (emptyRow) {
                    emptyRow.style.display = '';
                } else {
                    const tbody = table.querySelector('tbody');
                    const newRow = document.createElement('tr');
                    newRow.setAttribute('data-empty', 'true');
                    newRow.innerHTML = `<td colspan="100%" class="text-center py-4 text-muted">Aucun résultat trouvé</td>`;
                    tbody.appendChild(newRow);
                }
            } else {
                const emptyRow = table.querySelector('tr[data-empty]');
                if (emptyRow) {
                    emptyRow.remove();
                }
            }
        });
    });
}

/**
 * Confirmation avant suppression
 */
function confirmDelete(id, name = 'cet élément') {
    return confirm(`Êtes-vous sûr de vouloir supprimer ${name}?\nCette action ne peut pas être annulée.`);
}

/**
 * Copier dans le presse-papiers
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('success', 'Copié dans le presse-papiers');
    }).catch(() => {
        showAlert('error', 'Erreur lors de la copie');
    });
}

/**
 * Formater une date
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}

/**
 * Valider un email
 */
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Valider un téléphone
 */
function isValidPhone(phone) {
    const re = /^(?:\+33|0)[1-9](?:[0-9]{8})$/;
    return re.test(phone.replace(/[\s.-]/g, ''));
}

/**
 * Animer les compteurs statistiques
 */
function animateCounter(element, target, duration = 1000) {
    const currentValue = parseInt(element.textContent);
    const increment = (target - currentValue) / (duration / 16);
    
    let currentCount = currentValue;
    const timer = setInterval(() => {
        currentCount += increment;
        
        if (increment > 0 && currentCount >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else if (increment < 0 && currentCount <= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(currentCount);
        }
    }, 16);
}

/**
 * Exporter en CSV
 */
function exportTableToCSV(tableId, filename = 'export.csv') {
    const table = document.getElementById(tableId);
    let csv = [];
    
    // En-têtes
    const headers = [];
    table.querySelectorAll('thead th').forEach(th => {
        headers.push(th.textContent.trim());
    });
    csv.push(headers.join(','));
    
    // Données
    table.querySelectorAll('tbody tr').forEach(tr => {
        if (tr.style.display === 'none') return;
        
        const row = [];
        tr.querySelectorAll('td').forEach(td => {
            row.push('"' + td.textContent.trim().replace(/"/g, '""') + '"');
        });
        csv.push(row.join(','));
    });
    
    // Créer et télécharger le fichier
    const csvContent = 'data:text/csv;charset=utf-8,' + csv.join('\n');
    const link = document.createElement('a');
    link.setAttribute('href', encodeURI(csvContent));
    link.setAttribute('download', filename);
    link.click();
    
    showAlert('success', 'Données exportées avec succès');
}

/**
 * Gérer les thèmes (clair/sombre)
 */
function toggleTheme() {
    const htmlElement = document.documentElement;
    const currentTheme = htmlElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    htmlElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

/**
 * Charger le thème sauvegardé
 */
function loadSavedTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
}

// Charger le thème au démarrage
loadSavedTheme();

/**
 * Débounce pour les fonctions
 */
function debounce(func, delay) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, delay);
    };
}

/**
 * Animation CSS pour le slide out
 */
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        to {
            opacity: 0;
            transform: translateX(20px);
        }
    }
`;
document.head.appendChild(style);
