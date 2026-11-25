<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Film - Labtif Movie</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header {
            margin-bottom: 25px;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 28px;
        }

        .header p {
            color: #777;
            font-size: 14px;
            line-height: 1.6;
        }

        .tambah {
            display: inline-block;
            background-color: #333;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 10px;
        }

        .tambah:hover {
            background-color: #555;
        }

        .table-container {
            background-color: white;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        th {
            background-color: #f0f0f0;
            font-size: 14px;
            font-weight: 600;
            color: #444;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        td {
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        img {
            width: 65px;
            border-radius: 4px;
        }

        .deskripsi {
            text-align: left;
            line-height: 1.5;
            max-width: 300px;
        }

        .aksi {
            white-space: nowrap;
        }

        button {
            padding: 7px 11px;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
        }

        .edit {
            background-color: #eee;
            color: #333;
        }

        .edit:hover {
            background-color: #ddd;
        }

        .hapus {
            background-color: #fce8e8;
            color: #c0392b;
        }

        .hapus:hover {
            background-color: #f5d5d5;
        }

        form {
            display: inline;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <h1>Data Film Labtif Movie</h1>
            <p>Kelola koleksi film Labtif Movie di sini. Tambahkan film baru, ubah informasinya, atau hapus film yang sudah tidak ditayangkan.</p>

            <a href="tambah.html" class="tambah">+ Tambah Film</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Genre</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    // Memanggil file movie.php yang berisi class Movie.
                    // require_once digunakan agar file tidak dimuat berulang kali.
                    require_once "controller/movie.php";

                    // Membuat objek dari class Movie.
                    // Constructor Movie akan membuat koneksi ke database.
                    $movie = new Movie();

                    // Memanggil method getAllMovies() untuk mengambil seluruh data film
                    // dari database, kemudian menyimpannya ke variabel $datas.
                    $datas = $movie->getAllMovies();

                    // Membuat variabel nomor urut yang dimulai dari angka 1.
                    $i = 1;

                    // Melakukan perulangan untuk setiap data film yang ada di dalam $datas.
                    // Setiap data film disimpan sementara ke variabel $data.
                    foreach ($datas as $data) {
                    ?>

                        <tr>
                            <!-- Menampilkan nomor urut film, kemudian menaikkan nilainya 1 -->
                            <td><?= $i++ ?></td>

                            <td>
                                <!-- Menampilkan gambar poster film dari folder images -->
                                <img src="images/<?= $data['cover'] ?>" alt="Poster Film">
                            </td>

                            <!-- Menampilkan judul film dari database -->
                            <td><?= $data['title'] ?></td>

                            <!-- Menampilkan genre film dari database -->
                            <td><?= $data['genre'] ?></td>

                            <!-- Menampilkan deskripsi film dari database -->
                            <td class="deskripsi"><?= $data['description'] ?></td>

                            <td class="aksi">
                                <!-- Tombol Edit untuk menuju halaman edit film berdasarkan ID -->
                                <a href="edit.html?id=1">
                                    <button type="button" class="edit">Edit</button>
                                </a>

                                <!-- Form untuk mengirim permintaan penghapusan film -->
                                <form action="hapus.php" method="post">

                                    <!-- Input tersembunyi untuk mengirim ID film ke hapus.php -->
                                    <input type="hidden" name="id" value="1">

                                    <!-- Tombol untuk mengirim form penghapusan -->
                                    <button type="submit" class="hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>

                    <?php
                        // Mengakhiri perulangan foreach.
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>