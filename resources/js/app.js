import './bootstrap';
import './sweetalert-helper';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Initialize SweetAlert Helper for common functionality
SweetAlertHelper.init();
