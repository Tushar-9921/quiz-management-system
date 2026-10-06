    <!-- Main Footer -->
    <footer class="main-footer text-sm">
        <div class="float-right d-none d-sm-inline">
            <b>Quiz Management System</b> v2.0
        </div>
        <strong>Copyright &copy; 2026 Quiz Management System.</strong> All rights reserved. Built with <a
            href="#">AdminLTE
            3</a>.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="<?= ADMINLTE_PATH ?>plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= ADMINLTE_PATH ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= ADMINLTE_PATH ?>dist/js/adminlte.min.js"></script>

    <script>
$(document).ready(function() {
    // Auto-focus input in modals when opened
    $('#addQuizModal').on('shown.bs.modal', function() {
        $('#quizNameInput').trigger('focus');
    });

    // Smooth scroll for hash links in dashboard
    $('a[href^="#"]').on('click', function(e) {
        var target = this.hash;
        if (target && $(target).length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(target).offset().top - 70
            }, 500);
        }
    });
});
    </script>

    </body>

    </html>