<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fs-3" href="{{ route('home') }}"><i class="bi bi-book"></i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <form id="searchForm" class="d-flex" style="width: 50%;">
                <input id="searchInput" class="form-control me-2" type="search" placeholder="Masukan Judul Buku" aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalLabel">Hasil Pencarian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Konten akan diisi dengan JavaScript -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const judul = document.getElementById('searchInput').value;

        if (judul) {
            axios.get(`http://127.0.0.1:8000/api/buku/search/all?judul=${judul}`)
                .then(function(response) {
                    const bukuList = response.data.data;
                    const modalBody = document.getElementById('modalBody');
                    modalBody.innerHTML = '';

                    if (bukuList.length > 0) {
                        bukuList.forEach(buku => {
                            const statusText = buku.status === 'tersedia' ? 'Tersedia' :
                                buku.status === 'dipinjam' ? 'Dipinjam' :
                                buku.status === 'hilang' ? 'Hilang' : 'Tidak Diketahui';

                            const card = `
                                <div class="card mb-3 shadow-sm">
                                    <div class="row g-0">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-book" style="font-size: 3rem;"></i>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title text-secondary">${buku.judul}</h5>
                                                <p class="card-text"><strong>Pengarang:</strong> ${buku.pengarang}</p>
                                                <p class="card-text"><strong>Penerbit:</strong> ${buku.penerbit}</p>
                                                <p class="card-text"><strong>Tahun Terbit:</strong> ${buku.tahun_terbit}</p>
                                                <p class="card-text"><strong>Status:</strong> ${statusText}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            modalBody.insertAdjacentHTML('beforeend', card);
                        });
                        const modal = new bootstrap.Modal(document.getElementById('bookModal'));
                        modal.show();
                    } else {
                        alert('Buku tidak ditemukan.');
                    }
                })
                .catch(function(error) {
                    console.error('Error fetching data:', error);
                    alert('Terjadi kesalahan saat mengambil data.');
                });
        } else {
            alert('Silakan masukkan judul buku.');
        }
    });
</script>
</script>