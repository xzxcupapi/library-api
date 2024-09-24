<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
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
                <h1 class="text-left">Daftar Mahasiswa</h1>
                <button class="btn btn-dark fw-bold fs-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;" onclick="daftarkanMahasiswa()">
                    <i class="bi bi-plus-square"></i>
                </button>
            </div>
            <div class="table-responsive">
                <table id="mahasiswaTable" class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NPM</th>
                            <th>Nama Lengkap</th>
                            <th>Fakultas</th>
                            <th>Sidik Jari</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('admin.pages.mahasiswa.modal.create')
    @include('admin.pages.mahasiswa.modal.edit')
    @include('admin.components.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const token = localStorage.getItem("access_token");
            if (!token) {
                window.location.href = "/login";
            } else {
                $("#mahasiswaTable").DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 10,
                    ajax: {
                        url: "/api/mahasiswa",
                        type: "GET",
                        headers: {
                            Authorization: "Bearer " + token,
                        },
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
                            data: "npm",
                        },
                        {
                            data: "nama_lengkap",
                        },
                        {
                            data: "fakultas",
                        },
                        {
                            data: "sidik_jari",
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-secondary btn-sm me-2 rounded" onclick="editMahasiswa(${row.id})"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-danger btn-sm rounded" onclick="deleteMahasiswa(${row.id})"><i class="bi bi-trash3-fill"></i></button>
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
            }
        });

        function editMahasiswa(id) {
            const token = localStorage.getItem("access_token");

            $.ajax({
                url: `/api/mahasiswa/${id}`,
                type: "GET",
                headers: {
                    Authorization: "Bearer " + token,
                },
                success: function(data) {
                    $("#edit_id").val(data.id);
                    $("#edit_npm").val(data.npm);
                    $("#edit_nama_lengkap").val(data.nama_lengkap);
                    $("#edit_fakultas").val(data.fakultas);
                    $("#edit_sidik_jari").val(data.sidik_jari);

                    const modal = new bootstrap.Modal(document.getElementById("editMahasiswaModal"));
                    modal.show();
                },
                error: function(error) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "Gagal memuat data mahasiswa.",
                    });
                },
            });
        }

        function deleteMahasiswa(id) {
            Swal.fire({
                title: "Konfirmasi Hapus",
                text: "Apakah Anda yakin ingin menghapus mahasiswa ini?",
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
                        url: `/api/mahasiswa/${id}`,
                        type: "DELETE",
                        headers: {
                            Authorization: "Bearer " + token,
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: "success",
                                title: "Dihapus!",
                                text: "Mahasiswa berhasil dihapus.",
                            }).then(() => {
                                $("#mahasiswaTable").DataTable().ajax.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal",
                                text: "Gagal menghapus mahasiswa.",
                            });
                        },
                    });
                }
            });
        }

        function daftarkanMahasiswa() {
            const modal = new bootstrap.Modal(
                document.getElementById("daftarkanMahasiswaModal")
            );
            modal.show();
        }
    </script>

</body>

</html>