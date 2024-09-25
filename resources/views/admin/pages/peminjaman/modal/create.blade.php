<div class="modal fade" id="daftarkanPeminjamanModal" tabindex="-1" aria-labelledby="daftarkanPeminjamanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="daftarkanPeminjamanModalLabel">Daftarkan Peminjaman Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPeminjaman">
                    <div class="mb-3">
                        <label for="id_mahasiswa" class="form-label">Nama Mahasiswa</label>
                        <select class="form-select" id="id_mahasiswa" required>
                            <option value="">Pilih Mahasiswa</option>
                            <!-- List mahasiswa akan di-load via Ajax -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_buku" class="form-label">Judul Buku</label>
                        <select class="form-select" id="id_buku" required>
                            <option value="">Pilih Buku (Tersedia)</option>
                            <!-- List buku akan di-load via Ajax -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="durasi_peminjaman" class="form-label">Durasi Peminjaman (Hari)</label>
                        <input type="number" class="form-control" id="durasi_peminjaman" min="3" max="30" value="7" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="simpanPeminjaman()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#daftarkanPeminjamanModal').on('show.bs.modal', function() {
        loadMahasiswa();
        loadBuku();
    });

    function loadMahasiswa() {
        const token = localStorage.getItem("access_token");
        $.ajax({
            url: "/api/mahasiswa/dashboard/all",
            type: "GET",
            headers: {
                Authorization: "Bearer " + token,
            },
            success: function(response) {
                let options = '<option value="">Pilih Mahasiswa</option>';
                response.data.forEach(function(mahasiswa) {
                    options += `<option value="${mahasiswa.id}">${mahasiswa.nama_lengkap}</option>`;
                });
                $('#id_mahasiswa').html(options);
            },
            error: function() {
                Swal.fire("Gagal memuat daftar mahasiswa.");
            }
        });
    }

    function loadBuku() {
        const token = localStorage.getItem("access_token");
        $.ajax({
            url: "/api/buku/status/tersedia",
            type: "GET",
            headers: {
                Authorization: "Bearer " + token,
            },
            success: function(response) {
                let options = '<option value="">Pilih Buku</option>';
                response.data.forEach(function(buku) {
                    options += `<option value="${buku.id}">${buku.judul}</option>`;
                });
                $('#id_buku').html(options);
            },
            error: function() {
                Swal.fire("Gagal memuat daftar buku.");
            }
        });
    }

    function simpanPeminjaman() {
        const token = localStorage.getItem("access_token");
        const id_mahasiswa = $("#id_mahasiswa").val();
        const id_buku = $("#id_buku").val();
        const durasi_peminjaman = $("#durasi_peminjaman").val();

        $.ajax({
            url: "/api/peminjaman",
            type: "POST",
            headers: {
                Authorization: "Bearer " + token,
            },
            data: {
                id_mahasiswa: id_mahasiswa,
                id_buku: id_buku,
                durasi_peminjaman: durasi_peminjaman,
            },
            success: function(response) {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "Peminjaman berhasil didaftarkan",
                }).then(() => {
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("daftarkanPeminjamanModal")
                    );
                    modal.hide();
                    $("#peminjamanTable").DataTable().ajax.reload();
                });
            },
            error: function(error) {
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Gagal mendaftarkan peminjaman",
                });
            },
        });
    }
</script>