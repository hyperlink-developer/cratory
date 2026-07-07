import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import Swal from 'sweetalert2';

window.Swal = Swal;

const swalTheme = {
    background: '#151c2c',
    color: '#f8fafc',
    confirmButtonColor: '#4f46e5',
    cancelButtonColor: '#374151',
    customClass: {
        popup: 'rounded-xl border border-white/10 shadow-2xl',
        confirmButton: 'rounded-lg font-medium px-4 py-2',
        cancelButton: 'rounded-lg font-medium px-4 py-2',
    }
};

const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    background: '#151c2c',
    color: '#f8fafc',
    customClass: {
        popup: 'rounded-xl border border-white/10 shadow-xl'
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});
window.Toast = Toast;

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

document.addEventListener('livewire:init', () => {
    // Custom global confirm directive
    Livewire.directive('confirm', ({ el, directive, component, cleanup }) => {
        let content = directive.expression;

        let onClick = e => {
            e.preventDefault();
            e.stopImmediatePropagation();

            Swal.fire({
                ...swalTheme,
                text: content,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    el.removeAttribute('wire:confirm');
                    el.click();
                }
            });
        };

        el.addEventListener('click', onClick, { capture: true });

        cleanup(() => {
            el.removeEventListener('click', onClick);
        });
    });

    // Listen to generic Livewire notify events for toasts
    Livewire.on('notify', (data) => {
        const message = data[0]?.message || data?.message;
        const type = data[0]?.type || data?.type || 'success';
        
        Toast.fire({
            icon: type,
            title: message
        });
    });
});
