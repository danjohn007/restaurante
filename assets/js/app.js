/**
 * Restaurant Management System - Main JavaScript File
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initializeApp();
});

function initializeApp() {
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize form validation
    initializeFormValidation();
    
    // Initialize table interactions
    initializeTableInteractions();
    
    // Initialize real-time updates
    initializeRealTimeUpdates();
    
    // Initialize keyboard shortcuts
    initializeKeyboardShortcuts();
}

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    // Bootstrap form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Custom validation for specific fields
    validateEmailFields();
    validatePhoneFields();
    validateNumberFields();
}

/**
 * Email field validation
 */
function validateEmailFields() {
    const emailFields = document.querySelectorAll('input[type="email"]');
    emailFields.forEach(function(field) {
        field.addEventListener('blur', function() {
            if (this.value && !isValidEmail(this.value)) {
                this.setCustomValidity('Por favor ingrese un email válido');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
    });
}

/**
 * Phone field validation
 */
function validatePhoneFields() {
    const phoneFields = document.querySelectorAll('input[type="tel"]');
    phoneFields.forEach(function(field) {
        field.addEventListener('input', function() {
            // Remove non-numeric characters except + and -
            this.value = this.value.replace(/[^0-9+\-\s]/g, '');
        });
    });
}

/**
 * Number field validation
 */
function validateNumberFields() {
    const numberFields = document.querySelectorAll('input[type="number"]');
    numberFields.forEach(function(field) {
        field.addEventListener('input', function() {
            if (this.value < 0) {
                this.value = 0;
            }
        });
    });
}

/**
 * Table interactions
 */
function initializeTableInteractions() {
    // Table selection
    const tableItems = document.querySelectorAll('.table-item');
    tableItems.forEach(function(table) {
        table.addEventListener('click', function() {
            handleTableClick(this);
        });
    });

    // Sortable tables
    initializeSortableTables();
}

/**
 * Handle table click events
 */
function handleTableClick(tableElement) {
    const tableId = tableElement.dataset.tableId;
    const status = tableElement.dataset.status;
    
    // Show table options modal or redirect based on user role
    if (typeof showTableModal === 'function') {
        showTableModal(tableId, status);
    }
}

/**
 * Initialize sortable tables
 */
function initializeSortableTables() {
    const sortableHeaders = document.querySelectorAll('.sortable');
    sortableHeaders.forEach(function(header) {
        header.addEventListener('click', function() {
            sortTable(this);
        });
    });
}

/**
 * Sort table by column
 */
function sortTable(header) {
    const table = header.closest('table');
    const column = Array.from(header.parentNode.children).indexOf(header);
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    const isAscending = header.classList.contains('sort-asc');
    
    // Remove all sort classes
    header.parentNode.querySelectorAll('th').forEach(th => {
        th.classList.remove('sort-asc', 'sort-desc');
    });
    
    // Add appropriate sort class
    header.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
    
    // Sort rows
    rows.sort((a, b) => {
        const aValue = a.children[column].textContent.trim();
        const bValue = b.children[column].textContent.trim();
        
        if (isNumeric(aValue) && isNumeric(bValue)) {
            return isAscending ? bValue - aValue : aValue - bValue;
        }
        
        return isAscending ? 
            bValue.localeCompare(aValue) : 
            aValue.localeCompare(bValue);
    });
    
    // Reappend sorted rows
    rows.forEach(row => tbody.appendChild(row));
}

/**
 * Real-time updates
 */
function initializeRealTimeUpdates() {
    // Update order status
    const orderStatusElements = document.querySelectorAll('.order-status');
    if (orderStatusElements.length > 0) {
        setInterval(updateOrderStatuses, 30000); // Update every 30 seconds
    }
    
    // Update table status
    const tableElements = document.querySelectorAll('.table-item');
    if (tableElements.length > 0) {
        setInterval(updateTableStatuses, 15000); // Update every 15 seconds
    }
}

/**
 * Update order statuses
 */
function updateOrderStatuses() {
    fetch(BASE_URL + 'api/orders/status')
        .then(response => response.json())
        .then(data => {
            data.forEach(order => {
                const statusElement = document.querySelector(`#order-${order.id} .order-status`);
                if (statusElement) {
                    updateOrderStatusElement(statusElement, order.status);
                }
            });
        })
        .catch(error => console.error('Error updating order statuses:', error));
}

/**
 * Update table statuses
 */
function updateTableStatuses() {
    fetch(BASE_URL + 'api/tables/status')
        .then(response => response.json())
        .then(data => {
            data.forEach(table => {
                const tableElement = document.querySelector(`[data-table-id="${table.id}"]`);
                if (tableElement) {
                    updateTableStatusElement(tableElement, table.status);
                }
            });
        })
        .catch(error => console.error('Error updating table statuses:', error));
}

/**
 * Keyboard shortcuts
 */
function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + shortcuts
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'n':
                    e.preventDefault();
                    if (typeof createNewOrder === 'function') {
                        createNewOrder();
                    }
                    break;
                case 's':
                    e.preventDefault();
                    saveCurrentForm();
                    break;
                case 'f':
                    e.preventDefault();
                    focusSearchField();
                    break;
            }
        }
        
        // ESC key
        if (e.key === 'Escape') {
            closeModals();
        }
    });
}

/**
 * Utility functions
 */

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isNumeric(str) {
    return !isNaN(str) && !isNaN(parseFloat(str));
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN'
    }).format(amount);
}

function formatDate(date) {
    return new Intl.DateTimeFormat('es-MX', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(date));
}

function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alert-container') || document.body;
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.insertBefore(alertDiv, alertContainer.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 150);
    }, 5000);
}

function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

function saveCurrentForm() {
    const forms = document.querySelectorAll('form');
    if (forms.length > 0) {
        forms[0].submit();
    }
}

function focusSearchField() {
    const searchField = document.querySelector('input[type="search"], input[name="search"]');
    if (searchField) {
        searchField.focus();
    }
}

function closeModals() {
    const modals = document.querySelectorAll('.modal.show');
    modals.forEach(modal => {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    });
}

function updateOrderStatusElement(element, status) {
    const statusClasses = {
        'pending': 'bg-warning',
        'preparing': 'bg-info', 
        'ready': 'bg-success',
        'served': 'bg-primary',
        'paid': 'bg-dark',
        'cancelled': 'bg-danger'
    };
    
    const statusLabels = {
        'pending': 'Pendiente',
        'preparing': 'Preparando',
        'ready': 'Listo',
        'served': 'Servido', 
        'paid': 'Pagado',
        'cancelled': 'Cancelado'
    };
    
    // Remove old classes
    Object.values(statusClasses).forEach(cls => element.classList.remove(cls));
    
    // Add new class and update text
    element.classList.add(statusClasses[status] || 'bg-secondary');
    element.textContent = statusLabels[status] || status;
}

function updateTableStatusElement(element, status) {
    const statusClasses = ['available', 'occupied', 'reserved', 'cleaning'];
    statusClasses.forEach(cls => element.classList.remove(cls));
    element.classList.add(status);
    element.dataset.status = status;
}

/**
 * AJAX helper functions
 */
function makeRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    return fetch(url, { ...defaultOptions, ...options })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        });
}

function submitForm(form, callback) {
    const formData = new FormData(form);
    const button = form.querySelector('button[type="submit"]');
    
    if (button) {
        button.classList.add('loading');
        button.disabled = true;
    }
    
    fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (button) {
            button.classList.remove('loading');
            button.disabled = false;
        }
        callback(data);
    })
    .catch(error => {
        if (button) {
            button.classList.remove('loading');
            button.disabled = false;
        }
        console.error('Error:', error);
        showAlert('Error al procesar la solicitud', 'danger');
    });
}

// Global variables
const BASE_URL = document.querySelector('base')?.href || '/restaurante/';

// Export functions for global use
window.RestaurantSystem = {
    showAlert,
    confirmAction,
    makeRequest,
    submitForm,
    formatCurrency,
    formatDate,
    updateOrderStatusElement,
    updateTableStatusElement
};