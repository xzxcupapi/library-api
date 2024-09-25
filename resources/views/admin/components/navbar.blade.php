<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/dashboard">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/mahasiswa">Mahasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/buku">Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/peminjaman">Peminjaman</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/kunjungan">Kunjungan</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                        <li>
                            <a id="logoutLink" class="dropdown-item text-secondary" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#logoutLink').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: '/api/logout',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(response) {
                    localStorage.removeItem('access_token');

                    // Tampilkan pesan sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Logout Berhasil',
                        text: response.message || 'Anda telah keluar.'
                    }).then(() => {
                        window.location.href = '/login';
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Logout Gagal',
                        text: xhr.responseJSON.message || 'Terjadi kesalahan saat logout.'
                    });
                }
            });
        });
    });
</script>