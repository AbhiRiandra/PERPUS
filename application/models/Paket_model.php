<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket_model extends CI_Model {

    /**
     * Daftar paket + lokasi sekarang + info peminjaman yang sedang berjalan.
     * $status: '' | 'tersedia' | 'dipinjam' | 'terlambat'
     */
    public function get_all($status = '')
    {
        $this->db->select("paket.*,
            lokasi.nama_lokasi, lokasi.jenis AS jenis_lokasi,
            (SELECT COUNT(*) FROM buku WHERE buku.id_paket = paket.id) AS jml_buku,
            pm.tgl_pinjam, pm.tgl_jatuh_tempo", FALSE);
        $this->db->from('paket');
        $this->db->join('lokasi', 'lokasi.id = paket.id_lokasi', 'left');
        $this->db->join("peminjaman pm", "pm.id_paket = paket.id AND pm.status = 'berjalan'", 'left');

        if ($status === 'tersedia' || $status === 'dipinjam') {
            $this->db->where('paket.status', $status);
        } elseif ($status === 'terlambat') {
            $this->db->where('paket.status', 'dipinjam');
            $this->db->where('pm.tgl_jatuh_tempo <', date('Y-m-d'));
        }
        $this->db->order_by('paket.kode', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        $this->db->select('paket.*, lokasi.nama_lokasi, lokasi.penanggung_jawab, lokasi.no_hp AS no_hp_lokasi,
            (SELECT COUNT(*) FROM buku WHERE buku.id_paket = paket.id) AS jml_buku', FALSE);
        $this->db->from('paket');
        $this->db->join('lokasi', 'lokasi.id = paket.id_lokasi', 'left');
        $this->db->where('paket.id', $id);
        return $this->db->get()->row_array();
    }

    /** Peminjaman yang sedang berjalan untuk paket ini (atau NULL) */
    public function get_aktif($id_paket)
    {
        $this->db->select('peminjaman.*, lokasi.nama_lokasi');
        $this->db->from('peminjaman');
        $this->db->join('lokasi', 'lokasi.id = peminjaman.id_lokasi');
        $this->db->where(['peminjaman.id_paket' => $id_paket, 'peminjaman.status' => 'berjalan']);
        return $this->db->get()->row_array();
    }

    public function insert($data)       { return $this->db->insert('paket', $data); }

    public function update($id, $data)  { return $this->db->update('paket', $data, ['id' => $id]); }

    public function delete($id)         { return $this->db->delete('paket', ['id' => $id]); }

    public function kode_exists($kode, $exclude_id = null)
    {
        $this->db->where('kode', $kode);
        if ($exclude_id !== null) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results('paket') > 0;
    }

    public function punya_riwayat($id)
    {
        return $this->db->where('id_paket', $id)->count_all_results('peminjaman') > 0;
    }

    /** Statistik untuk dashboard */
    public function statistik()
    {
        $today = date('Y-m-d');
        return [
            'total_paket' => $this->db->count_all_results('paket'),
            'tersedia'    => $this->db->where('status', 'tersedia')->count_all_results('paket'),
            'dipinjam'    => $this->db->where('status', 'dipinjam')->count_all_results('paket'),
            'terlambat'   => $this->db->where(['status' => 'berjalan', 'tgl_jatuh_tempo <' => $today])->count_all_results('peminjaman'),
            'total_buku'  => $this->db->count_all_results('buku'),
            'total_lokasi'=> $this->db->count_all_results('lokasi'),
        ];
    }
}
