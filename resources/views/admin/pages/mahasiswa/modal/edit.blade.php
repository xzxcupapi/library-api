<div class="modal fade" id="editMahasiswaModal" tabindex="-1" aria-labelledby="editMahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMahasiswaModalLabel">Edit Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditMahasiswa">
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_npm" class="form-label">NPM</label>
                        <input type="text" class="form-control" id="edit_npm" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_fakultas" class="form-label">Fakultas</label>
                        <input type="text" class="form-control" id="edit_fakultas" required>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="edit_sidik_jari" class="form-label">Sidik Jari</label>
                        <input type="text" class="form-control" id="edit_sidik_jari" readonly>
                    </div> -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="updateMahasiswa()">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    function updateMahasiswa() {
        const token = localStorage.getItem("access_token");
        const id = $("#edit_id").val();
        const npm = $("#edit_npm").val();
        const nama_lengkap = $("#edit_nama_lengkap").val();
        const fakultas = $("#edit_fakultas").val();
        // const sidik_jari = $("#edit_sidik_jari").val();

        $.ajax({
            url: `/api/mahasiswa/${id}`,
            type: "PUT",
            headers: {
                Authorization: "Bearer " + token,
            },
            data: {
                npm: npm,
                nama_lengkap: nama_lengkap,
                fakultas: fakultas,
                // sidik_jari: sidik_jari,
            },
            success: function(response) {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "Mahasiswa berhasil diperbarui.",
                }).then(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById("editMahasiswaModal"));
                    modal.hide();
                    $("#mahasiswaTable").DataTable().ajax.reload();
                });
            },
            error: function(error) {
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Gagal memperbarui mahasiswa.",
                });
            },
        });
    }
</script>