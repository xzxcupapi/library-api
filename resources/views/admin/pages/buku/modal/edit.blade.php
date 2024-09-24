<div class="modal fade" id="editBukuModal" tabindex="-1" aria-labelledby="editBukuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBukuModalLabel">Edit Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditBuku">
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="edit_judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_pengarang" class="form-label">Pengarang</label>
                        <input type="text" class="form-control" id="edit_pengarang" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="edit_penerbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="text" class="form-control" id="edit_tahun_terbit">
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status">
                            <option value="tersedia">Tersedia</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="updateBuku()">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    function updateBuku() {
        const token = localStorage.getItem("access_token");
        const id = $("#edit_id").val();
        const judul = $("#edit_judul").val();
        const pengarang = $("#edit_pengarang").val();
        const penerbit = $("#edit_penerbit").val();
        const tahun_terbit = $("#edit_tahun_terbit").val();
        const status = $("#edit_status").val();

        $.ajax({
            url: `/api/buku/${id}`,
            type: "PUT",
            headers: {
                Authorization: "Bearer " + token,
            },
            data: {
                judul: judul,
                pengarang: pengarang,
                penerbit: penerbit,
                tahun_terbit: tahun_terbit,
                status: status,
            },
            success: function(response) {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "Buku berhasil diperbarui.",
                }).then(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById("editBukuModal"));
                    modal.hide();
                    $("#bukuTable").DataTable().ajax.reload();
                });
            },
            error: function(error) {
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Gagal memperbarui data buku.",
                });
            },
        });
    }
</script>