<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman</title>
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
                <h1 class="text-left">Daftar Peminjaman</h1>
                <button class="btn btn-dark fw-bold fs-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;" onclick="daftarkanPeminjaman()">
                    <i class="bi bi-plus-square"></i>
                </button>
            </div>
            <div class="table-responsive">
                <table id="peminjamanTable" class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NPM</th>
                            <th>Nama Lengkap</th>
                            <th>Judul Buku</th>
                            <th>Durasi</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('admin.components.footer')
    @include('admin.pages.peminjaman.modal.create')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const token = localStorage.getItem("access_token");
            if (!token) {
                window.location.href = "/login";
            } else {
                $("#peminjamanTable").DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 10,
                    ajax: {
                        url: "/api/peminjaman/all/data",
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
                            render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                        },
                        {
                            data: "npm"
                        },
                        {
                            data: "nama_lengkap"
                        },
                        {
                            data: "judul_buku"
                        },
                        {
                            data: "durasi_peminjaman",
                            render: function(data, type, row) {
                                return data + " Hari";
                            }
                        },
                        {
                            data: "tanggal_pengembalian"
                        },
                        {
                            data: "status",
                            render: function(data, type, row) {
                                if (data === "peminjaman") {
                                    return '<span class="badge bg-primary">Peminjaman</span>';
                                } else if (data === "selesai") {
                                    return '<span class="badge bg-success">Selesai</span>';
                                } else {
                                    return '<span class="badge bg-secondary">Status Tidak Diketahui</span>';
                                }
                            },
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-secondary btn-sm me-2 fw-semibold rounded" onclick="editPeminjaman(${row.id})"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-danger btn-sm fw-semibold rounded" onclick="deletePeminjaman(${row.id})"><i class="bi bi-trash3-fill"></i></button>
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

        function daftarkanPeminjaman() {
            const modal = new bootstrap.Modal(
                document.getElementById("daftarkanPeminjamanModal")
            );
            modal.show();
            loadMahasiswa();
            loadBuku();
        }

        function editPeminjaman(id) {
            Swal.fire({
                title: 'Konfirmasi Pengembalian',
                text: "Apakah Anda yakin ingin menyelesaikan peminjaman ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = localStorage.getItem("access_token");
                    $.ajax({
                        url: `/api/peminjaman/${id}`,
                        type: 'PUT',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                response.message,
                                'success'
                            ).then(() => {
                                $('#peminjamanTable').DataTable().ajax.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat memperbarui peminjaman.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function deletePeminjaman(id) {
            Swal.fire({
                title: "Konfirmasi Hapus",
                text: "Apakah Anda yakin ingin menghapus peminjaman ini?",
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
                        url: `/api/peminjaman/${id}`,
                        type: "DELETE",
                        headers: {
                            Authorization: "Bearer " + token,
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: "success",
                                title: "Dihapus!",
                                text: "Peminjaman berhasil dihapus.",
                            }).then(() => {
                                $("#peminjamanTable").DataTable().ajax.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal",
                                text: "Gagal menghapus peminjaman.",
                            });
                        },
                    });
                }
            });
        }
    </script>

</body>

</html>