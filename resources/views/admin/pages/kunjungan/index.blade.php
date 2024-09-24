<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kunjungan</title>
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
                <h1 class="text-left">Daftar Kunjungan Mahasiswa</h1>
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <select id="monthDropdown" class="form-select" aria-label="Select Month">
                            <option value="">Pilih Bulan</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <button id="sortByMonth" class="btn btn-secondary">Pilih</button>
                </div>
            </div>
            <div class="table-responsive">
                <table id="kunjunganTable" class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NPM</th>
                            <th>Nama Lengkap</th>
                            <th>Fakultas</th>
                            <th>Tanggal Kunjungan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
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
                let selectedMonth = null;

                const table = $("#kunjunganTable").DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 10,
                    ajax: {
                        url: "/api/kunjungan",
                        type: "GET",
                        headers: {
                            Authorization: "Bearer " + token,
                        },
                        data: function(d) {
                            d.limit = d.length;
                            d.offset = d.start;
                            d.month = selectedMonth;
                        },
                    },
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            },
                        },
                        {
                            data: "mahasiswa.npm",
                        },
                        {
                            data: "mahasiswa.nama_lengkap",
                        },
                        {
                            data: "mahasiswa.fakultas",
                        },
                        {
                            data: "tanggal_kunjungan",
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

                $("#sortByMonth").on("click", function() {
                    selectedMonth = $("#monthDropdown").val();
                    table.ajax.reload();
                });
            }
        });
    </script>

</body>

</html>