<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi_model extends CI_Model {

    public function get_all()
    {
        // sekaligus hitung berapa paket yang sedang ada di lokasi tsb
        $this->db->select('lokasi.*, (SELECT COUNT(*) FROM paket WHERE paket.id_lokasi = lokasi.id) AS jml_paket', FALSE);
        $this->db->order_by('nama_lokasi', 'ASC');
        return $this->db->get('lokasi')->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('lokasi', ['id' => $id])->row_array();
    }

    public function insert($data)       { return $this->db->insert('lokasi', $data); }

    public function update($id, $data)  { return $this->db->update('lokasi', $data, ['id' => $id]); }

    public function delete($id)         { return $this->db->delete('lokasi', ['id' => $id]); }

    public function nama_exists($nama, $exclude_id = null)
    {
        $this->db->where('nama_lokasi', $nama);
        if ($exclude_id !== null) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results('lokasi') > 0;
    }

    /** Lokasi dipakai riwayat peminjaman? Kalau ya, tidak boleh dihapus. */
    public function punya_riwayat($id)
    {
        return $this->db->where('id_lokasi', $id)->count_all_results('peminjaman') > 0;
    }
}
