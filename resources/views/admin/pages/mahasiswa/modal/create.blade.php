<div class="modal fade" id="daftarkanMahasiswaModal" tabindex="-1" aria-labelledby="daftarkanMahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="daftarkanMahasiswaModalLabel">Daftarkan Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formDaftarMahasiswa">
                    <div class="mb-3">
                        <label for="npm" class="form-label">NPM</label>
                        <input type="text" class="form-control" id="npm" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="fakultas" class="form-label">Fakultas</label>
                        <input type="text" class="form-control" id="fakultas" required>
                    </div>
                    <div class="mb-3">
                        <label for="sidik_jari" class="form-label">Sidik Jari</label>
                        <input type="text" class="form-control" id="sidik_jari" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="simpanMahasiswa()">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    function simpanMahasiswa() {
        const token = localStorage.getItem("access_token");
        const npm = $("#npm").val();
        const nama_lengkap = $("#nama_lengkap").val();
        const fakultas = $("#fakultas").val();
        const sidik_jari = $("#sidik_jari").val();

        $.ajax({
            url: "/api/mahasiswa",
            type: "POST",
            headers: {
                Authorization: "Bearer " + token,
            },
            data: {
                npm: npm,
                nama_lengkap: nama_lengkap,
                fakultas: fakultas,
                sidik_jari: sidik_jari,
            },
            success: function(response) {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "Mahasiswa berhasil didaftarkan",
                }).then(() => {
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("daftarkanMahasiswaModal")
                    );
                    modal.hide();
                    $("#mahasiswaTable").DataTable().ajax.reload();
                });
            },
            error: function(error) {
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Gagal mendaftarkan mahasiswa",
                });
            },
        });
    }
</script>