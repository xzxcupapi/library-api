<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    @include('admin.components.navbar')
    <div class="container-fluid">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center my-4">
                <h1 class="text-left">Daftar Buku</h1>
                <button class="btn btn-dark fw-bold fs-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;" onclick="daftarkanBuku()">
                    <i class="bi bi-plus-square"></i>
                </button>
            </div>
            <div class="table-responsive">
                <table id="bukuTable" class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun Terbit</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('admin.pages.buku.modal.create')
    @include('admin.pages.buku.modal.edit')
    @include('admin.components.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#bukuTable").DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: {
                    url: "/api/buku",
                    type: "GET",
                    data: function(d) {
                        d.limit = d.length;
                        d.offset = d.start;
                    },
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: "judul",
                    },
                    {
                        data: "pengarang",
                    },
                    {
                        data: "penerbit",
                    },
                    {
                        data: "tahun_terbit",
                    },
                    {
                        data: "status",
                        render: function(data, type, row) {
                            let badgeClass = "";
                            let badgeText = "";

                            if (row.status === "tersedia") {
                                badgeClass = "bg-success";
                                badgeText = "Tersedia";
                            } else if (row.status === "dipinjam") {
                                badgeClass = "bg-warning";
                                badgeText = "Dipinjam";
                            } else if (row.status === "hilang") {
                                badgeClass = "bg-danger";
                                badgeText = "Hilang";
                            }

                            return `<span class="badge fw-semibold ${badgeClass}">${badgeText}</span>`;
                        },
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-secondary btn-sm me-2 rounded" onclick="editBuku(${row.id})"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-danger btn-sm rounded" onclick="deleteBuku(${row.id})"><i class="bi bi-trash3-fill"></i></button>
                            </div>
                        `;
                        },
                    },
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya",
                    },
                },
            });
        });

        function editBuku(id) {
            const token = localStorage.getItem("access_token");

            $.ajax({
                url: `/api/buku/${id}`,
                type: "GET",
                headers: {
                    Authorization: "Bearer " + token,
                },
                success: function(data) {
                    $("#edit_id").val(data.id);
                    $("#edit_judul").val(data.judul);
                    $("#edit_pengarang").val(data.pengarang);
                    $("#edit_penerbit").val(data.penerbit);
                    $("#edit_tahun_terbit").val(data.tahun_terbit);
                    $("#edit_status").val(data.status);
                    const modal = new bootstrap.Modal(document.getElementById("editBukuModal"));
                    modal.show();
                },
                error: function(error) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "Gagal memuat data buku.",
                    });
                },
            });
        }

        function deleteBuku(id) {
            Swal.fire({
                title: "Konfirmasi Hapus",
                text: "Apakah Anda yakin ingin menghapus buku ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = localStorage.getItem("access_token");

                    $.ajax({
                        url: `/api/buku/${id}`,
                        type: "DELETE",
                        headers: {
                            Authorization: "Bearer " + token,
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: "success",
                                title: "Dihapus!",
                                text: "Buku berhasil dihapus.",
                            }).then(() => {
                                $("#bukuTable").DataTable().ajax.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal",
                                text: "Gagal menghapus buku.",
                            });
                        },
                    });
                }
            });
        }

        function daftarkanBuku() {
            const modal = new bootstrap.Modal(
                document.getElementById("daftarkanBukuModal")
            );
            modal.show();
        }
    </script>

</body>

</html>