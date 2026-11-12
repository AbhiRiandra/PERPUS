<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Inti sistem: setiap perpindahan paket (pinjam / oper / tarik) dijalankan
 * dalam SATU transaksi database, sehingga tabel `peminjaman` (riwayat) dan
 * `paket` (posisi terkini) selalu sinkron -> "data otomatis tertarik".
 */
class Peminjaman_model extends CI_Model {

    /** Riwayat. $filter: ['status'=>'berjalan|selesai|terlambat', 'id_paket'=>, 'id_lokasi'=>] */
    public function get_all($filter = [])
    {
        $this->db->select('peminjaman.*, paket.kode AS kode_paket, paket.nama AS nama_paket,
            lokasi.nama_lokasi, user.nama AS nama_petugas');
        $this->db->from('peminjaman');
        $this->db->join('paket', 'paket.id = peminjaman.id_paket');
        $this->db->join('lokasi', 'lokasi.id = peminjaman.id_lokasi');
        $this->db->join('user', 'user.id = peminjaman.id_user', 'left');

        if (!empty($filter['status'])) {
            if ($filter['status'] === 'terlambat') {
                $this->db->where('peminjaman.status', 'berjalan');
                $this->db->where('peminjaman.tgl_jatuh_tempo <', date('Y-m-d'));
            } else {
                $this->db->where('peminjaman.status', $filter['status']);
            }
        }
        if (!empty($filter['id_paket']))  { $this->db->where('peminjaman.id_paket', (int) $filter['id_paket']); }
        if (!empty($filter['id_lokasi'])) { $this->db->where('peminjaman.id_lokasi', (int) $filter['id_lokasi']); }

        $this->db->order_by('peminjaman.tgl_pinjam', 'DESC');
        $this->db->order_by('peminjaman.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_riwayat_paket($id_paket)
    {
        return $this->get_all(['id_paket' => $id_paket]);
    }

    /** Paket keluar dari perpustakaan ke sebuah lokasi */
    public function pinjam($id_paket, $id_lokasi, $tgl_pinjam, $tgl_jatuh_tempo, $catatan, $id_user)
    {
        $this->db->trans_start();

        // kunci baris paket supaya tidak ada dua petugas yang memproses bersamaan
        $paket = $this->db->query('SELECT id, status FROM paket WHERE id = ? FOR UPDATE', [$id_paket])->row_array();
        if (!$paket || $paket['status'] !== 'tersedia') {
            $this->db->trans_complete();
            return 'Paket tidak tersedia (sedang dipinjam atau tidak ditemukan).';
        }

        $this->db->insert('peminjaman', [
            'id_paket'        => $id_paket,
            'id_lokasi'       => $id_lokasi,
            'jenis'           => 'pinjam',
            'tgl_pinjam'      => $tgl_pinjam,
            'tgl_jatuh_tempo' => $tgl_jatuh_tempo,
            'status'          => 'berjalan',
            'catatan'         => $catatan,
            'id_user'         => $id_user,
        ]);
        $this->db->update('paket', ['status' => 'dipinjam', 'id_lokasi' => $id_lokasi], ['id' => $id_paket]);

        $this->db->trans_complete();
        return $this->db->trans_status() ? TRUE : 'Gagal menyimpan peminjaman.';
    }

    /** Paket dipindah langsung dari lokasi A ke lokasi B (tanpa balik ke perpus) */
    public function oper($id_paket, $id_lokasi_baru, $tgl_oper, $tgl_jatuh_tempo, $catatan, $id_user)
    {
        $this->db->trans_start();

        $paket = $this->db->query('SELECT id, status, id_lokasi FROM paket WHERE id = ? FOR UPDATE', [$id_paket])->row_array();
        if (!$paket || $paket['status'] !== 'dipinjam') {
            $this->db->trans_complete();
            return 'Paket tidak sedang dipinjam, jadi tidak bisa dioper.';
        }
        if ((int) $paket['id_lokasi'] === (int) $id_lokasi_baru) {
            $this->db->trans_complete();
            return 'Lokasi tujuan sama dengan lokasi saat ini.';
        }

        $aktif = $this->db->get_where('peminjaman', ['id_paket' => $id_paket, 'status' => 'berjalan'])->row_array();
        if ($aktif && $tgl_oper < $aktif['tgl_pinjam']) {
            $this->db->trans_complete();
            return 'Tanggal oper tidak boleh sebelum tanggal pinjam di lokasi sebelumnya.';
        }

        // 1) tutup peminjaman di lokasi lama
        $this->db->update('peminjaman', [
            'tgl_kembali' => $tgl_oper,
            'status'      => 'selesai',
        ], ['id_paket' => $id_paket, 'status' => 'berjalan']);

        // 2) buka peminjaman baru di lokasi tujuan
        $this->db->insert('peminjaman', [
            'id_paket'        => $id_paket,
            'id_lokasi'       => $id_lokasi_baru,
            'jenis'           => 'oper',
            'tgl_pinjam'      => $tgl_oper,
            'tgl_jatuh_tempo' => $tgl_jatuh_tempo,
            'status'          => 'berjalan',
            'catatan'         => $catatan,
            'id_user'         => $id_user,
        ]);

        // 3) posisi terkini paket
        $this->db->update('paket', ['id_lokasi' => $id_lokasi_baru], ['id' => $id_paket]);

        $this->db->trans_complete();
        return $this->db->trans_status() ? TRUE : 'Gagal memproses oper paket.';
    }

    /** Paket ditarik kembali ke perpustakaan */
    public function tarik($id_paket, $tgl_kembali, $catatan, $id_user)
    {
        $this->db->trans_start();

        $paket = $this->db->query('SELECT id, status FROM paket WHERE id = ? FOR UPDATE', [$id_paket])->row_array();
        if (!$paket || $paket['status'] !== 'dipinjam') {
            $this->db->trans_complete();
            return 'Paket tidak sedang dipinjam.';
        }

        $aktif = $this->db->get_where('peminjaman', ['id_paket' => $id_paket, 'status' => 'berjalan'])->row_array();
        if ($aktif && $tgl_kembali < $aktif['tgl_pinjam']) {
            $this->db->trans_complete();
            return 'Tanggal tarik tidak boleh sebelum tanggal pinjam.';
        }

        $upd = ['tgl_kembali' => $tgl_kembali, 'status' => 'selesai'];
        if ($catatan !== '') {
            $upd['catatan'] = $catatan;
        }
        $this->db->update('peminjaman', $upd, ['id_paket' => $id_paket, 'status' => 'berjalan']);
        $this->db->update('paket', ['status' => 'tersedia', 'id_lokasi' => NULL], ['id' => $id_paket]);

        $this->db->trans_complete();
        return $this->db->trans_status() ? TRUE : 'Gagal memproses penarikan paket.';
    }

    /** Perpanjang jatuh tempo peminjaman yang sedang berjalan */
    public function perpanjang($id_paket, $tgl_jatuh_tempo_baru)
    {
        $aktif = $this->db->get_where('peminjaman', ['id_paket' => $id_paket, 'status' => 'berjalan'])->row_array();
        if (!$aktif) {
            return 'Paket tidak sedang dipinjam.';
        }
        if ($tgl_jatuh_tempo_baru <= $aktif['tgl_jatuh_tempo']) {
            return 'Jatuh tempo baru harus setelah jatuh tempo saat ini (' . date('d-m-Y', strtotime($aktif['tgl_jatuh_tempo'])) . ').';
        }
        $this->db->update('peminjaman', ['tgl_jatuh_tempo' => $tgl_jatuh_tempo_baru], ['id' => $aktif['id']]);
        return TRUE;
    }

    
/**
 * Menghapus riwayat peminjaman yang sudah selesai.
 * Riwayat yang masih berjalan tidak boleh dihapus.
 *
 * @param array $ids Daftar ID riwayat yang dipilih.
 * @return int|bool Jumlah data terhapus atau FALSE jika gagal.
 */
public function hapus_riwayat_selesai($ids)
{
    if (empty($ids) || !is_array($ids)) {
        return 0;
    }

    $ids = array_values(array_unique(array_filter(
        array_map('intval', $ids),
        function ($id) {
            return $id > 0;
        }
    )));

    if (empty($ids)) {
        return 0;
    }

    $this->db->trans_begin();

    // Hanya menghapus riwayat dengan status selesai.
    $this->db
        ->where_in('id', $ids)
        ->where('status', 'selesai')
        ->delete('peminjaman');

    $jumlah = $this->db->affected_rows();

    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        return FALSE;
    }

    $this->db->trans_commit();

    return $jumlah;
}

}
