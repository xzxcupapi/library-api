# Fullstack Library Management System

## Features

### User Authentication

-   **Login**

    -   **Endpoint:** `POST /api/login`
    -   **Controller:** `AuthController::class`
    -   **Method:** `login`
    -   **Description:** Authenticates a user and provides a token.

-   **Logout**

    -   **Endpoint:** `POST /api/logout`
    -   **Controller:** `AuthController::class`
    -   **Method:** `logout`
    -   **Description:** Logs out the authenticated user and invalidates the token.

-   **Get User**
    -   **Endpoint:** `GET /api/user`
    -   **Middleware:** `auth:sanctum`
    -   **Description:** Retrieves the authenticated user's information.

### Mahasiswa Management

-   **Store Mahasiswa**

    -   **Endpoint:** `POST /api/mahasiswa`
    -   **Controller:** `MahasiswaController::class`
    -   **Method:** `store`
    -   **Middleware:** `auth:sanctum`
    -   **Description:** Stores a new mahasiswa record.

-   **Get All Mahasiswa Data**

    -   **Endpoint:** `GET /api/mahasiswa`
    -   **Controller:** `MahasiswaController::class`
    -   **Method:** `getAllData`
    -   **Middleware:** `auth:sanctum`
    -   **Description:** Retrieves all mahasiswa records.

-   **Search Mahasiswa by NPM**

    -   **Endpoint:** `GET /api/mahasiswa/search`
    -   **Controller:** `MahasiswaController::class`
    -   **Method:** `searchByNpm`
    -   **Middleware:** `auth:sanctum`
    -   **Description:** Searches for a mahasiswa record by NPM.
    -   **Example:** `http://127.0.0.1:8000/api/mahasiswa/search?npm=8499273695`

-   **Delete Mahasiswa**
    -   **Endpoint:** `DELETE /api/mahasiswa/{id}`
    -   **Controller:** `MahasiswaController::class`
    -   **Method:** `destroy`
    -   **Middleware:** `auth:sanctum`
    -   **Description:** Deletes a mahasiswa record by ID.

### Kunjungan Management

-   **Store Kunjungan**

    -   **Endpoint:** `POST /api/kunjungan`
    -   **Controller:** `KunjunganController::class`
    -   **Method:** `store`
    -   **Description:** Stores a new kunjungan record.

-   **Get All Kunjungan Data**
    -   **Endpoint:** `GET /api/kunjungan`
    -   **Controller:** `KunjunganController::class`
    -   **Method:** `getAll`
    -   **Middleware:** `auth:sanctum`
    -   **Description:** Retrieves all kunjungan records along with mahasiswa information.

### Buku Management

-   **Store Buku**

    -   **Endpoint:** `POST /api/buku`
    -   **Controller:** `BukuController::class`
    -   **Method:** `store`
    -   **Middleware:** `auth:sanctum`
    -   **Description:** Stores a new buku record.

-   **Get All Buku Data**

    -   **Endpoint:** `GET /api/buku`
    -   **Controller:** `BukuController::class`
    -   **Method:** `getAll`
    -   **Middleware:** `auth:sanctum`
    -   **Description:** Retrieves all buku records.

-   **Search Buku by Judul**

    -   **Endpoint:** `GET /api/buku/search`
    -   **Controller:** `BukuController::class`
    -   **Method:** `searchByJudul`
    -   **Middleware:** `auth:sanctum`
    -   **Description:** Searches for a buku record by Judul.
    -   **Example:** `http://127.0.0.1:8000/api/buku/search?judul=nis`
    -   **Example:** `Hanya menggunakan prefix 3 huruf saja`

-   **Delete Buku**
    -   **Endpoint:** `DELETE /api/buku/{id}`
    -   **Controller:** `BukuController::class`
    -   **Method:** `destroy`
    -   **Middleware:** `auth:sanctum`
    -   **Description:** Deletes a buku record by ID.

## Routes

### Authentication Routes

```php
Route::prefix('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});
```

### Mahasiswa Routes

```php
Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::prefix('mahasiswa')->group(function () {
        Route::post('/', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::get('/', [MahasiswaController::class, 'getAllData'])->name('mahasiswa.index');
        Route::get('/search', [MahasiswaController::class, 'searchByNpm'])->name('mahasiswa.search');
        Route::delete('/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
    });
});
```

### Kunjungan Routes

```php
without Login
Route::prefix('kunjungan')->group(function () {
    Route::post('/', [KunjunganController::class, 'store'])->name('kunjungan.store');
});
Login
Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::prefix('kunjungan')->group(function () {
        Route::get('/', [KunjunganController::class, 'getAllData'])->name('kunjungan.index');
    });
});
```

### Buku Routes

```php
Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::prefix('buku')->group(function () {
        Route::post('/', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/', [BukuController::class, 'getAllData'])->name('buku.index');
        Route::get('/search', [BukuController::class, 'searchByJudul'])->name('buku.search');
        Route::delete('/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });
});
```

## Installation and Setup

1. Clone the repository.
2. Install dependencies using `composer install`.
3. Set up your `.env` file.
4. Run migrations using `php artisan migrate`.
5. Start the server using `php artisan serve`.

## License

This project is licensed under the MIT License.
