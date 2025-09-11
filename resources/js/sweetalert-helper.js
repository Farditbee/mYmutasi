/**
 * SweetAlert Helper Functions
 * Utility functions for common SweetAlert operations
 */

class SweetAlertHelper {
    /**
     * Show confirmation dialog for delete action
     * @param {Object} options - Configuration options
     * @param {string} options.title - Dialog title
     * @param {string} options.text - Dialog text
     * @param {string} options.itemName - Name of item being deleted
     * @param {boolean} options.hasRelatedData - Whether item has related data
     * @param {Function} options.onConfirm - Callback function when confirmed
     */
    static confirmDelete(options = {}) {
        const {
            title = 'Konfirmasi Hapus',
            text = 'Apakah Anda yakin ingin menghapus item ini?',
            itemName = 'item',
            hasRelatedData = false,
            onConfirm = () => {}
        } = options;

        let confirmationConfig = {
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        };

        if (hasRelatedData) {
            confirmationConfig.title = 'Perhatian!';
            confirmationConfig.text = `${itemName} ini memiliki data terkait. Menghapus ${itemName} akan menghapus semua data terkait. Lanjutkan?`;
            confirmationConfig.confirmButtonText = 'Ya, tetap hapus!';
        }

        return Swal.fire(confirmationConfig).then((result) => {
            if (result.isConfirmed) {
                this.showLoading('Menghapus...', `Sedang menghapus ${itemName}`);
                onConfirm();
            }
        });
    }

    /**
     * Show confirmation dialog for status toggle
     * @param {Object} options - Configuration options
     * @param {string} options.currentStatus - Current status (active/inactive)
     * @param {string} options.itemName - Name of item being toggled
     * @param {Function} options.onConfirm - Callback function when confirmed
     */
    static confirmToggleStatus(options = {}) {
        const {
            currentStatus = 'active',
            itemName = 'item',
            onConfirm = () => {}
        } = options;

        const newStatus = currentStatus === 'active' ? 'nonaktif' : 'aktif';
        const action = currentStatus === 'active' ? 'menonaktifkan' : 'mengaktifkan';

        return Swal.fire({
            title: 'Konfirmasi',
            text: `Apakah Anda yakin ingin ${action} ${itemName} ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Ya, ${action}!`,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.showLoading('Memproses...', `Sedang ${action} ${itemName}`);
                onConfirm();
            }
        });
    }

    /**
     * Show confirmation dialog for form submission
     * @param {Object} options - Configuration options
     * @param {string} options.title - Dialog title
     * @param {string} options.text - Dialog text
     * @param {string} options.action - Action being performed
     * @param {boolean} options.hasWarning - Whether to show warning
     * @param {Function} options.onConfirm - Callback function when confirmed
     */
    static confirmSubmit(options = {}) {
        const {
            title = 'Konfirmasi',
            text = 'Apakah Anda yakin ingin menyimpan data ini?',
            action = 'menyimpan',
            hasWarning = false,
            onConfirm = () => {}
        } = options;

        let confirmationConfig = {
            title: title,
            text: text,
            icon: hasWarning ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Ya, ${action}!`,
            cancelButtonText: 'Batal'
        };

        return Swal.fire(confirmationConfig).then((result) => {
            if (result.isConfirmed) {
                this.showLoading('Memproses...', `Sedang ${action} data`);
                onConfirm();
            }
        });
    }

    /**
     * Show loading dialog
     * @param {string} title - Loading title
     * @param {string} text - Loading text
     */
    static showLoading(title = 'Memproses...', text = 'Mohon tunggu sebentar') {
        Swal.fire({
            title: title,
            text: text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    /**
     * Show success message
     * @param {string} title - Success title
     * @param {string} text - Success text
     * @param {Function} callback - Optional callback function
     */
    static showSuccess(title = 'Berhasil!', text = 'Operasi berhasil dilakukan', callback = null) {
        return Swal.fire({
            icon: 'success',
            title: title,
            text: text,
            confirmButtonColor: '#3085d6'
        }).then(() => {
            if (callback) callback();
        });
    }

    /**
     * Show error message
     * @param {string} title - Error title
     * @param {string} text - Error text
     * @param {Function} callback - Optional callback function
     */
    static showError(title = 'Error!', text = 'Terjadi kesalahan', callback = null) {
        return Swal.fire({
            icon: 'error',
            title: title,
            text: text,
            confirmButtonColor: '#3085d6'
        }).then(() => {
            if (callback) callback();
        });
    }

    /**
     * Show validation error for form fields
     * @param {string} message - Validation message
     */
    static showValidationError(message) {
        return Swal.fire({
            icon: 'error',
            title: 'Error Validasi!',
            text: message,
            confirmButtonColor: '#3085d6'
        });
    }

    /**
     * Validate form fields and show error if validation fails
     * @param {Array} fields - Array of field objects {element, name, required}
     * @returns {boolean} - True if validation passes
     */
    static validateForm(fields) {
        for (let field of fields) {
            const { element, name, required = true } = field;
            
            if (required && (!element.value || element.value.trim() === '')) {
                this.showValidationError(`${name} harus diisi!`);
                element.focus();
                return false;
            }
        }
        return true;
    }

    /**
     * Setup form submission with validation and confirmation
     * @param {Object} options - Configuration options
     * @param {HTMLFormElement} options.form - Form element
     * @param {HTMLButtonElement} options.submitBtn - Submit button element
     * @param {Array} options.fields - Array of field validation rules
     * @param {Object} options.confirmation - Confirmation dialog options
     */
    static setupFormSubmission(options = {}) {
        const {
            form,
            submitBtn,
            fields = [],
            confirmation = {}
        } = options;

        if (!form || !submitBtn) {
            console.error('Form and submit button are required');
            return;
        }

        submitBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Validate form
            if (!this.validateForm(fields)) {
                return;
            }
            
            // Show confirmation
            this.confirmSubmit({
                ...confirmation,
                onConfirm: () => {
                    form.submit();
                }
            });
        });

        // Handle Enter key submission
        form.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && e.target.type !== 'textarea') {
                e.preventDefault();
                submitBtn.click();
            }
        });
    }

    /**
     * Setup delete button with confirmation
     * @param {string} selector - CSS selector for delete buttons
     * @param {Object} options - Configuration options
     */
    static setupDeleteButtons(selector = '.delete-btn', options = {}) {
        document.addEventListener('click', (e) => {
            if (e.target.matches(selector)) {
                e.preventDefault();
                
                const button = e.target;
                const form = button.closest('form');
                const hasRelatedData = button.dataset.hasRelated === 'true';
                const itemName = button.dataset.itemName || 'item';
                
                this.confirmDelete({
                    ...options,
                    itemName: itemName,
                    hasRelatedData: hasRelatedData,
                    onConfirm: () => {
                        if (form) form.submit();
                    }
                });
            }
        });
    }

    /**
     * Setup toggle status buttons with confirmation
     * @param {string} selector - CSS selector for toggle buttons
     * @param {Object} options - Configuration options
     */
    static setupToggleButtons(selector = '.toggle-btn', options = {}) {
        document.addEventListener('click', (e) => {
            if (e.target.matches(selector)) {
                e.preventDefault();
                
                const button = e.target;
                const form = button.closest('form');
                const currentStatus = button.dataset.status === 'aktifkan' ? 'inactive' : 'active';
                const itemName = button.dataset.itemName || 'item';
                
                this.confirmToggleStatus({
                    ...options,
                    currentStatus: currentStatus,
                    itemName: itemName,
                    onConfirm: () => {
                        if (form) form.submit();
                    }
                });
            }
        });
    }

    /**
     * Initialize common SweetAlert functionality
     * @param {Object} options - Configuration options
     */
    static init(options = {}) {
        const {
            deleteSelector = '.delete-btn',
            toggleSelector = '.toggle-btn',
            enableDeleteButtons = true,
            enableToggleButtons = true
        } = options;

        document.addEventListener('DOMContentLoaded', () => {
            if (enableDeleteButtons) {
                this.setupDeleteButtons(deleteSelector);
            }
            
            if (enableToggleButtons) {
                this.setupToggleButtons(toggleSelector);
            }
        });
    }
}

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SweetAlertHelper;
}

// Make available globally
window.SweetAlertHelper = SweetAlertHelper;