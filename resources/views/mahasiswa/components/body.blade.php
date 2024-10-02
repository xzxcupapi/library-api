<div class="container mt-5 mb-5">
    <div class="row"></div>

    <!-- Pagination Section -->
    <nav aria-label="Page navigation example" class="mt-3">
        <ul class="pagination justify-content-center">
            <li class="page-item" id="prevPageItem">
                <a class="page-link" href="#" id="prevPage">&laquo;</a>
            </li>
            <li class="page-item disabled">
                <span class="page-link fw-bold" id="currentPageInfo">1</span>
            </li>
            <li class="page-item" id="nextPageItem">
                <a class="page-link" href="#" id="nextPage">&raquo;</a>
            </li>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        const itemsPerPage = 9;
        let bukuList = [];

        axios.get('http://127.0.0.1:8000/api/buku/dashboard/all')
            .then(function(response) {
                bukuList = response.data.data;

                bukuList.sort((a, b) => {
                    if (a.status === 'tersedia' && b.status !== 'tersedia') {
                        return -1;
                    }
                    if (a.status !== 'tersedia' && b.status === 'tersedia') {
                        return 1;
                    }
                    return 0; // if both are same, no change
                });

                renderPage(currentPage);
            })
            .catch(function(error) {
                console.error('Error fetching data:', error);
            });

        function renderPage(page) {
            const container = document.querySelector('.container .row');
            container.innerHTML = '';

            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedItems = bukuList.slice(start, end);

            paginatedItems.forEach(buku => {
                const buttonClass = buku.status === 'tersedia' ? 'btn-success' : 'btn-danger';
                const buttonText = buku.status === 'tersedia' ? 'Tersedia' : 'Dipinjam';

                const card = `
                    <div class="col-md-4 mb-3">
                        <div class="card mb-4 shadow-sm bg-body rounded" style="height: 100%;">
                            <div class="card-body d-flex flex-column">
                                <p class="card-title mb-2 fs-4 fw-semibold">${buku.judul}</p>
                                <p class="card-subtitle mb-2 text-muted fs-5">${buku.pengarang}</p>
                                <p class="card-subtitle mb-2 text-muted fs-5">${buku.penerbit}</p>
                                <div class="row mt-auto">
                                    <div class="col-md-8">
                                        <p class="card-subtitle text-muted">${buku.tahun_terbit}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn ${buttonClass} fw-semibold shadow-sm" style="pointer-events: none;">${buttonText}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', card);
            });

            document.getElementById('currentPageInfo').textContent = page;

            updatePagination(page);
        }

        function updatePagination(page) {
            const totalPages = Math.ceil(bukuList.length / itemsPerPage);

            if (page === 1) {
                document.getElementById('prevPageItem').classList.add('disabled');
            } else {
                document.getElementById('prevPageItem').classList.remove('disabled');
            }

            if (page === totalPages) {
                document.getElementById('nextPageItem').classList.add('disabled');
            } else {
                document.getElementById('nextPageItem').classList.remove('disabled');
            }
        }

        document.getElementById('prevPage').addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        document.getElementById('nextPage').addEventListener('click', function(e) {
            e.preventDefault();
            const totalPages = Math.ceil(bukuList.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);
            }
        });
    });
</script>
