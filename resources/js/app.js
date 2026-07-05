import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

document.addEventListener('alpine:init', () => {
    Alpine.data('datepicker', () => ({
        init() {
            flatpickr(this.$el, {
                dateFormat: 'Y-m-d',
                disableMobile: true,
                onChange: (selectedDates, dateStr, instance) => {
                    this.$el.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        }
    }));
});
