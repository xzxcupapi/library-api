<div class="modal fade" id="daftarkanBukuModal" tabindex="-1" aria-labelledby="daftarkanBukuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="daftarkanBukuModalLabel">Daftarkan Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formDaftarBuku">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="pengarang" class="form-label">Pengarang</label>
                        <input type="text" class="form-control" id="pengarang" required>
                    </div>
                    <div class="mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="penerbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="text" class="form-control" id="tahun_terbit" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="simpanBuku()">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    function simpanBuku() {
        const token = localStorage.getItem("access_token");
        const judul = $("#judul").val();
        const pengarang = $("#pengarang").val();
        const penerbit = $("#penerbit").val();
        const tahun_terbit = $("#tahun_terbit").val();

        $.ajax({
            url: "/api/buku",
            type: "POST",
            headers: {
                Authorization: "Bearer " + token,
            },
            data: {
                judul: judul,
                pengarang: pengarang,
                penerbit: penerbit,
                tahun_terbit: tahun_terbit,
            },
            success: function(response) {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "Buku berhasil didaftarkan",
                }).then(() => {
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("daftarkanBukuModal")
                    );
                    modal.hide();
                    $("#bukuTable").DataTable().ajax.reload();
                });
            },
            error: function(error) {
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Gagal mendaftarkan buku",
                });
            },
        });
    }
</script>