import './bootstrap';
import { initPasswordToggle } from './password-toggle';
import { initEventsModals } from './events-modal';

document.addEventListener('DOMContentLoaded', () => {
    initPasswordToggle('toggle-password', 'password');
    initPasswordToggle('toggle-password-confirm', 'password_confirmation');
    initPasswordToggle('toggle-register-password', 'password');
    initPasswordToggle('toggle-register-confirm', 'password_confirmation');
    initPasswordToggle('toggle-admin-event-password', 'admin-event-password');
    initPasswordToggle('toggle-user-event-password', 'user-event-password');
    
    initEventsModals();
});
