<?php
/**
 * ==========================================================
 * MenuKH
 * Dashboard Footer
 * ----------------------------------------------------------
 * File : layouts/dashboard/footer.php
 * Version : 1.0.0
 * ==========================================================
 */
?>

        <footer class="mk-footer mt-5">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    © <?= date('Y') ?>

                    <?= APP_NAME ?>

                </div>

                <div>

                    Version 1.0.0

                </div>

            </div>

        </footer>

    </div><!-- /.mk-main -->

</div><!-- /.mk-dashboard -->

<!-- Bootstrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Dashboard JS -->

<script src="<?= asset('js/dashboard.js') ?>"></script>

<script>
/*
|--------------------------------------------------------------------------
| Sidebar Toggle
|--------------------------------------------------------------------------
*/

const sidebarToggle = document.getElementById('sidebarToggle');

const sidebar = document.querySelector('.mk-sidebar');

if(sidebarToggle){

    sidebarToggle.addEventListener('click',()=>{

        sidebar.classList.toggle('show');

    });

}

</script>

</body>

</html>