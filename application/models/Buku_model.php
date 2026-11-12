<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku_model extends CI_Model {

    public function get_by_paket($id_paket)
    {
        $this->db->order_by('judul', 'ASC');
        return $this->db->get_where('buku', ['id_paket' => $id_paket])->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('buku', ['id' => $id])->row_array();
    }

    public function insert($data)       { return $this->db->insert('buku', $data); }

    public function update($id, $data)  { return $this->db->update('buku', $data, ['id' => $id]); }

    public function delete($id)         { return $this->db->delete('buku', ['id' => $id]); }

    public function insert_batch($rows)
    {
        // pecah per 100 baris supaya aman untuk paket ratusan judul
        $total = 0;
        foreach (array_chunk($rows, 100) as $chunk) {
            $total += $this->db->insert_batch('buku', $chunk);
        }
        return $total;
    }

    public function hapus_semua_paket($id_paket)
    {
        return $this->db->delete('buku', ['id_paket' => $id_paket]);
    }

    public function judul_ada($id_paket, $judul)
    {
        return $this->db->where(['id_paket' => $id_paket, 'judul' => $judul])->count_all_results('buku') > 0;
    }
}
