<?php

// Memanggil file database.php dari folder model.
// __DIR__ menunjukkan lokasi folder file movie.php saat ini.
// ../ digunakan untuk naik satu tingkat folder.
require_once __DIR__ . "/../model/database.php";

// Membuat class Movie untuk mengelola data film.
class Movie
{
    // Menyimpan objek Database yang akan digunakan
    // untuk berinteraksi dengan database.
    protected $db;

    // Constructor dijalankan secara otomatis
    // ketika objek Movie dibuat.
    function __construct()
    {
        // Membuat objek baru dari class Database
        // dan menyimpannya ke dalam variabel $db.
        $this->db = new Database();
    }

    // Method untuk mengambil seluruh data film dari database.
    function getAllMovies()
    {
        // Memilih seluruh data dari tabel movies
        // kemudian mengambil hasil query.
        return $this->db->table('movies')->get();
    }

    // Method untuk mengambil data film berdasarkan ID.
    // Parameter $id berisi ID film yang ingin dicari.
    function getMovieById($id)
    {
        // Mengambil data dari tabel movies
        // dengan kondisi ID sesuai parameter yang diberikan.
        // Hasilnya dikembalikan dalam bentuk data.
        return $this->db->table('movies')->where(['id' => $id])->get();
    }

    // Method untuk menambahkan data film baru ke database.
    // Parameter $data berisi data film yang akan ditambahkan.
    function addMovie($data)
    {
        // Memilih tabel movies kemudian menjalankan insert
        // untuk memasukkan data baru ke dalam database.
        return $this->db->table('movies')->insert($data);
    }

    // Method untuk memperbarui data film berdasarkan ID.
    // $id berisi ID film yang akan diubah.
    // $data berisi data baru yang akan diperbarui.
    function updateMovie($id, $data)
    {
        // Memilih tabel movies, menentukan film berdasarkan ID,
        // kemudian menjalankan update dengan data yang diberikan.
        return $this->db->table('movies')->where(['id' => $id])->update($data);
    }

    // Method untuk menghapus data film berdasarkan ID.
    // Parameter $id berisi ID film yang akan dihapus.
    function deleteMovie($id) {
        // Memilih tabel movies, menentukan data film berdasarkan ID,
        // kemudian menjalankan proses penghapusan data.
        return $this->db->table('movies')->where(['id' => $id])->delete();
    }
}