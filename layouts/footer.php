<?php
/**
 * =====================================================
 * MenuKH
 * Footer Layout
 * Version : 1.0.0
 * =====================================================
 */
?>

</div><!-- /.app-wrapper -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Main JS -->
<script src="<?= asset('js/app.js'); ?>"></script>

<?php if ($flash = getFlash()): ?>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const toast = document.createElement('div');

    toast.className =
        'position-fixed top-0 end-0 p-3';

    toast.style.zIndex = '1080';

    toast.innerHTML = `
        <div class="toast show text-bg-<?= e($flash['type']) ?>" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <?= e($flash['message']) ?>
                </div>
                <button
                    type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast">
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {

        toast.remove();

    }, 4000);

});

</script>

<?php endif; ?>

</body>
</html>