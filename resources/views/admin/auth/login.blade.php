<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <section class="text-center">
        <div class="p-5 bg-secondary" style="height: 300px;"></div>
        <!-- Background image -->
        <div class="card mx-auto shadow-5-strong bg-body-tertiary" style="
        margin-top: -100px;
        backdrop-filter: blur(30px);
        max-width: 500px;
        ">
            <div class="card-body py-5 px-md-5">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-12">
                        <h2 class="fw-bold mb-5">Login</h2>
                        <form id="loginForm">
                            <!-- Email input -->
                            <div class="col-md-12 mb-4">
                                <div class="form-outline">
                                    <input type="email" id="emailInput" class="form-control" style="max-width: 350px; margin: 0 auto;" required />
                                    <label class="form-label" for="emailInput">Email</label>
                                </div>
                            </div>

                            <!-- Password input -->
                            <div class="col-md-12 mb-4">
                                <div class="form-outline">
                                    <input type="password" id="passwordInput" class="form-control" style="max-width: 350px; margin: 0 auto;" required />
                                    <label class="form-label" for="passwordInput">Password</label>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-secondary fw-bold btn-block mb-4" style="max-width: 350px; margin: 0 auto;">
                                Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- jQuery, Bootstrap JS, Popper.js, and SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                const email = $('#emailInput').val();
                const password = $('#passwordInput').val();

                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    data: {
                        email: email,
                        password: password,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.access_token) {
                            localStorage.setItem('access_token', response.access_token);

                            Swal.fire({
                                icon: 'success',
                                title: 'Login Berhasil',
                                text: response.message
                            }).then(() => {
                                console.log('Redirecting to /dashboard');
                                window.location.href = '/dashboard';
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        if (xhr.status === 404) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Gagal',
                                text: xhr.responseJSON.message || 'User belum terdaftar.'
                            });
                        } else if (xhr.status === 401) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Gagal',
                                text: xhr.responseJSON.message || 'Password Salah.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan pada server!'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>