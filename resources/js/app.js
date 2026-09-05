import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

function initRangePickers(root = document) {
    root.querySelectorAll('[data-flatpickr-range]').forEach((el) => {
        if (el._flatpickr) {
            return;
        }

        flatpickr(el, {
            mode: 'range',
            dateFormat: 'Y-m-d',
            defaultDate: el.dataset.from && el.dataset.to ? [el.dataset.from, el.dataset.to] : [],
            onChange(dates) {
                if (dates.length !== 2) {
                    return;
                }

                const form = el.closest('form');
                form.querySelector('[name="from"]').value = flatpickr.formatDate(dates[0], 'Y-m-d');
                form.querySelector('[name="to"]').value = flatpickr.formatDate(dates[1], 'Y-m-d');
            },
        });
    });
}

window.addEventListener('livewire:navigated', () => initRangePickers());
document.addEventListener('DOMContentLoaded', () => initRangePickers());
