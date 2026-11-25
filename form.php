<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Film - Labtif Movie</title>

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
            max-width: 650px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
        }

        h1 {
            margin-top: 0;
            font-size: 26px;
        }

        .subtitle {
            color: #777;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #888;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button,
        .kembali {
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }

        .simpan {
            background-color: #333;
            color: white;
        }

        .simpan:hover {
            background-color: #555;
        }

        .kembali {
            background-color: #eee;
            color: #333;
        }

        .kembali:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Tambah Film</h1>
        <p class="subtitle">Masukkan informasi film baru untuk menambahkan film ke koleksi Labtif Movie.</p>

        <form method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="title">Judul Film</label>
                <input type="text" id="title" name="title" placeholder="Masukkan judul film" required>
            </div>

            <div class="form-group">
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" placeholder="Masukkan genre film" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" placeholder="Masukkan deskripsi film" required></textarea>
            </div>

            <div class="form-group">
                <label for="cover">Poster Film</label>
                <input type="file" id="cover" name="cover" accept="image/*" required>
            </div>

            <div class="button-group">
                <button type="submit" name="submit" class="simpan">Simpan Film</button>
                <a href="index.php" class="kembali">Kembali</a>
            </div>

        </form>

        <?php

        // Memanggil file movie.php yang berada di folder controller.
        // __DIR__ mengambil lokasi direktori file PHP saat ini.
        // ../ digunakan untuk naik satu tingkat folder.
        require_once __DIR__ . "/../controller/movie.php";

        // Membuat objek Movie agar dapat menggunakan method
        // yang tersedia di dalam class Movie.
        $movie = new Movie();

        // Memeriksa apakah tombol submit pada form telah dikirim.
        // isset() digunakan untuk mengecek apakah variabel tersebut tersedia.
        if (isset($_POST['submit'])) {

            // Mengambil nilai judul film dari form menggunakan metode POST.
            $title = $_POST['title'];

            // Mengambil nilai genre film dari form menggunakan metode POST.
            $genre = $_POST['genre'];

            // Mengambil nilai deskripsi film dari form menggunakan metode POST.
            $description = $_POST['description'];

            // Mengambil nama file cover yang diunggah melalui form.
            // $_FILES digunakan untuk mengakses informasi file yang diupload.
            // ['name'] berisi nama asli file.
            $cover = $_FILES['cover']['name'];

            // Menentukan lokasi penyimpanan file cover.
            // ../ digunakan untuk naik satu folder.
            // images/ merupakan folder tempat gambar akan disimpan.
            // basename() mengambil nama file dari suatu path.
            $path = "../images/" . basename($cover);

            // Memindahkan file yang diupload dari lokasi sementara
            // ke folder images yang telah ditentukan pada variabel $path.
            // tmp_name berisi lokasi sementara file yang diupload.
            move_uploaded_file($_FILES['cover']['tmp_name'], $path);

            // Membuat array asosiatif yang berisi data film.
            // Data ini nantinya akan dikirim ke method addMovie()
            // untuk disimpan ke database.
            $data = [
                // Menyimpan judul film ke dalam array dengan key title.
                'title' => $title,

                // Menyimpan genre film ke dalam array dengan key genre.
                'genre' => $genre,

                // Menyimpan deskripsi film ke dalam array dengan key description.
                'description' => $description,

                // Menyimpan nama file cover ke dalam array dengan key cover.
                'cover' => $cover
            ];

            // Memanggil method addMovie() dari objek $movie
            // untuk menyimpan data film ke database.
            // Jika proses penyimpanan berhasil dan mengembalikan true,
            // maka kode di dalam if akan dijalankan.
            if ($movie->addMovie($data)) {

                // Menampilkan alert (popup) bahwa data berhasil disimpan.
                // window.location.href digunakan untuk mengarahkan
                // pengguna kembali ke halaman index.php.
                echo "<script>alert('Data berhasil disimpan'); window.location.href='../index.php';</script>";
            }
        }
        ?>
    </div>

</body>

</html>