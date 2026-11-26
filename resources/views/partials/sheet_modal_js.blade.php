{{-- Reusable behaviour for phone bottom sheets: any ".modal.sheet-modal" on the page gets
     swipe-down-to-close via its drag handle (CSS turns the same markup into a sheet on phones;
     tapping the dark overlay already closes it, that's standard Bootstrap backdrop behaviour). --}}
<script>
    (function () {
        document.querySelectorAll('.sheet-modal').forEach(function (modalEl) {
            const handle = modalEl.querySelector('.sheet-handle');
            const dialog = modalEl.querySelector('.modal-dialog');
            if (! handle || ! dialog) { return; }

            let startY = null;

            handle.addEventListener('touchstart', function (event) {
                startY = event.touches[0].clientY;
                dialog.style.transition = 'none';
            }, { passive: true });

            handle.addEventListener('touchmove', function (event) {
                if (startY === null) { return; }
                const delta = Math.max(0, event.touches[0].clientY - startY);
                dialog.style.transform = 'translateY(' + delta + 'px)';
            }, { passive: true });

            handle.addEventListener('touchend', function (event) {
                if (startY === null) { return; }
                const delta = event.changedTouches[0].clientY - startY;
                dialog.style.transition = '';
                dialog.style.transform = '';
                startY = null;

                if (delta > 80) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                }
            });
        });
    })();
</script>
