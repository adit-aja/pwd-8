<?php

// Mendefinisikan konstanta untuk konfigurasi koneksi database.
// define() digunakan untuk membuat konstanta yang nilainya tidak berubah.
define('DB_HOST', 'localhost'); // Alamat server database.
define('DB_USER', 'root');      // Username database.
define('DB_PASS', '');          // Password database, kosong.
define('DB_NAME', 'labtif_movie'); // Nama database yang digunakan.


// Membuat class Database untuk mengelola koneksi dan query database.
class Database
{
    // Properti untuk menyimpan koneksi MySQL dan query SQL.
    // protected berarti dapat diakses dari class ini dan class turunannya.
    protected $mysqli;
    protected $query;

    // Constructor dijalankan otomatis ketika objek Database dibuat.
    function __construct()
    {
        // Membuat koneksi ke database menggunakan class mysqli.
        // Parameter diambil dari konstanta yang telah didefinisikan sebelumnya.
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Memeriksa apakah koneksi database mengalami kesalahan.
        if ($this->mysqli->connect_errno) {

            // Menampilkan pesan jika koneksi database gagal.
            // connect_error berisi informasi penyebab kegagalan koneksi.
            echo "Koneksi Gagal: " . $this->mysqli->connect_error;
        }
    }

    // Method untuk menentukan tabel yang akan digunakan dalam query.
    function table($table)
    {
        // Membuat query SELECT untuk mengambil seluruh data dari tabel.
        // Nama tabel diambil dari parameter $table.
        $this->query = "SELECT * FROM $table";

        // Mengembalikan objek Database itu sendiri,
        // agar method lain dapat dipanggil secara berantai.
        return $this;
    }

    // Method untuk menambahkan kondisi WHERE pada query.
    // Parameter $arr berupa array asosiatif yang berisi kondisi pencarian.
    // Jika tidak diberikan nilai, maka array kosong digunakan.
    public function where($arr = array())
    {
        // Menyiapkan query WHERE untuk menambahkan kondisi pencarian.
        $sql = ' WHERE ';

        // Memeriksa apakah array hanya memiliki satu kondisi.
        if (count($arr) == 1) {

            // Mengambil key dan value dari array.
            // Key berisi nama kolom, sedangkan value berisi nilai yang dicari.
            foreach ($arr as $key => $value) {

                // Menambahkan kondisi ke query SQL.
                // Contoh: WHERE id = 1
                $sql .= $key . ' = ' . $value;
            }
        } else {

            // Jika kondisi lebih dari satu, maka setiap kondisi
            // akan ditambahkan menggunakan AND.
            foreach ($arr as $key => $value) {

                // Menambahkan kondisi ke query SQL.
                // Contoh: id = '1' AND title = 'Jumbo'
                $sql .= $key . " = '" . $value . "' AND ";
            }

            // Menghapus 5 karakter terakhir dari query,
            // yaitu " AND " agar query tidak diakhiri AND.
            $sql = substr($sql, 0, -5);
        }

        // Menambahkan query WHERE ke query sebelumnya.
        // Misalnya, query SELECT * FROM movies ditambah WHERE id = 1.
        $this->query .= $sql;

        // Mengembalikan objek Database agar method dapat dirangkai.
        return $this;
    }

    // Method untuk menjalankan query SELECT dan mengambil hasilnya.
    function get()
{
    // Menjalankan query SQL yang telah disimpan di properti $query.
    // Hasil query disimpan ke variabel $result.
    $result = $this->mysqli->query($this->query);

    // Memeriksa apakah query gagal dijalankan.
    if (!$result) {

        // Menghentikan program dan menampilkan pesan kesalahan query.
        // mysqli->error berisi informasi kesalahan dari database.
        die("Query gagal: " . $this->mysqli->error);
    }

    // Mengambil seluruh hasil query dalam bentuk array asosiatif.
    // MYSQLI_ASSOC membuat data menggunakan nama kolom sebagai key.
    return $result->fetch_all(MYSQLI_ASSOC);
}

    // Contoh query SELECT:
    // SELECT * FROM movies

    // Contoh query INSERT:
    // INSERT INTO movies (title, genre, description, cover)
    // VALUES ('Burung hantu', 'horror', 'asdhfuahds', 'foto.jpg')

    // Method untuk menambahkan data baru ke dalam tabel.
    // Parameter $arr berisi data yang ingin dimasukkan.
    public function insert($arr = array())
    {
        // Mengubah query SELECT menjadi INSERT.
        // Contoh: SELECT * FROM movies menjadi INSERT INTO movies.
        $this->query = str_replace('SELECT * FROM', 'INSERT INTO', $this->query);

        // Variabel untuk menampung nama-nama kolom yang akan diisi.
        $columns = '';

        // Variabel untuk menampung nilai data yang akan dimasukkan.
        $vals = '';

        // Melakukan perulangan pada setiap data di dalam array $arr.
        foreach ($arr as $key => $value) {

            // Menambahkan nama kolom ke variabel $columns.
            // Contoh: title, genre, description, cover
            $columns .= $key . ", ";

            // Menambahkan nilai data ke variabel $vals.
            // Setiap nilai dibungkus dengan tanda petik.
            $vals .= "'" . $value . "', ";
        }

        // Menggabungkan nama kolom dan nilai ke query INSERT.
        // substr() digunakan untuk menghilangkan koma dan spasi terakhir.
        // Hasil contoh:
        // INSERT INTO movies (title, genre) VALUES ('Jumbo', 'Drama')
        $this->query .= " (" . substr($columns, 0, -2) . ") VALUES (" . substr($vals, 0, -2) . ")";

        // Menampilkan query SQL untuk keperluan pengecekan/debugging.
        // echo $this->query;

        // Menyiapkan query menggunakan prepare().
        // Jika gagal, program akan berhenti dan menampilkan error.
        $q = $this->mysqli->prepare($this->query) or die($this->mysqli->error);

        // Komentar untuk pengecekan error prepare secara manual.
        // Bagian ini tidak dijalankan karena masih dalam komentar.
        // if (!$q) {
        //     die($this->mysqli->error);
        // }

        // Menjalankan query INSERT yang telah disiapkan.
        if ($q->execute()) {

            // Mengembalikan nilai true jika query berhasil dijalankan.
            return true;
        }

        // Alternatif menjalankan query secara langsung.
        // $q->execute();

        // Menampilkan query SQL untuk pengecekan.
        // echo $this->query;
    }

    // Contoh query UPDATE:
    // UPDATE table SET column1 = "value1", column2 = "value2" WHERE condition;

    // Method untuk memperbarui data pada tabel.
    // Parameter $arr berisi kolom dan nilai baru yang akan diubah.
    function update($arr = array()) {

        // Karena pemanggilan method menggunakan table()->where()->update()
        // maka query awal berbentuk SELECT * FROM ... WHERE ...
        // Di sini SELECT * FROM diubah menjadi UPDATE.
        $this->query = str_replace('SELECT * FROM', 'UPDATE', $this->query);

        // Memisahkan query menjadi dua bagian berdasarkan WHERE.
        // Bagian pertama berisi UPDATE nama_tabel.
        // Bagian kedua berisi kondisi WHERE.
        $part = explode(' WHERE ', $this->query); 

        // Variabel untuk menampung bagian SET pada query UPDATE.
        $val = '';

        // Melakukan perulangan terhadap data yang ingin diperbarui.
        foreach ($arr as $key => $value) {

            // Membentuk pasangan kolom dan nilai untuk SET.
            // Contoh: title = 'Jumbo', genre = 'Animasi'
            $val .= $key . " = '" . $value . "', "; 
        }

        // Merangkai kembali query UPDATE.
        // $part[0] berisi nama tabel.
        // substr($val, 0, -2) menghapus koma dan spasi terakhir.
        // $part[1] berisi kondisi WHERE.
        // Hasil contoh:
        // UPDATE movies SET title = 'Jumbo' WHERE id = 1
        $this->query = $part[0] . " SET " . substr($val, 0, -2) . " WHERE ". $part[1];

        // Menyiapkan query UPDATE menggunakan prepare().
        // Jika gagal, program berhenti dan menampilkan error database.
        $q = $this->mysqli->prepare($this->query) or die($this->mysqli->error);

        // Menjalankan query UPDATE untuk memperbarui data.
        $q->execute();

        // Menampilkan query SQL untuk pengecekan.
        // echo $this->query . "<br>";

        // Menampilkan bagian query sebelum WHERE.
        // echo $part[0] . "<br>";

        // Menampilkan kondisi WHERE.
        // echo $part[1] . "<br>";

        // Menampilkan isi bagian SET.
        // echo $val . "<br>";

    }

    // Contoh query DELETE:
    // DELETE FROM table WHERE ....

    // Method untuk menghapus data dari tabel.
    function delete() {

        // Mengubah query SELECT * menjadi DELETE.
        // Contoh: SELECT * FROM movies WHERE id = 1
        // menjadi: DELETE FROM movies WHERE id = 1
        $this->query = str_replace('SELECT *', 'DELETE', $this->query);

        // Menyiapkan query DELETE menggunakan prepare().
        // Jika gagal, program berhenti dan menampilkan error.
        $q = $this->mysqli->prepare($this->query) or die($this->mysqli->error);

        // Menjalankan query DELETE untuk menghapus data.
        $q->execute();
    }
}


// Contoh penggunaan class Database.
// Bagian ini tidak dijalankan karena masih dalam komentar.

// Membuat objek Database.
// $db = new Database();

// Mengambil seluruh data dari tabel movies.
// echo $db->table('movies')->get();

// Menambahkan data film baru ke tabel movies.
// $db->table('movies')->insert([
//     'title' => 'adsf',
//     'genre' => 'asdfdsf',
//     'description' => 'asdfsdf',
//     'cover' => 'adsfsfs'
// ]);

// Memperbarui data film dengan ID 1.
// Mengubah judul dan genre film.
// $db->table('movies')->where(['id' => '1'])->update(['title' => 'Contoh', 'genre' => 'Contoh Genre']);