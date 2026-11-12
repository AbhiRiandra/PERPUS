<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(['Paket_model', 'Buku_model', 'Peminjaman_model']);
    }

    public function index()
    {
        $status = $this->input->get('status', TRUE);
        $data['title']     = 'Data Paket Buku';
        $data['titlePage'] = 'Data Paket Buku';
        $data['status']    = in_array($status, ['tersedia', 'dipinjam', 'terlambat'], TRUE) ? $status : '';
        $data['paket']     = $this->Paket_model->get_all($data['status']);
        $this->render('backend/paket/index', $data, 'paket');
    }

    /** Detail paket: posisi sekarang, aksi, riwayat, dan daftar judul */
    public function detail($id = 0)
    {
        $paket = $this->Paket_model->get_by_id($id);
        if (!$paket) {
            $this->session->set_flashdata('error', 'Paket tidak ditemukan!');
            redirect('backend/paket');
        }
        $data['title']     = 'Paket ' . $paket['kode'];
        $data['titlePage'] = $data['title'];
        $data['paket']     = $paket;
        $data['aktif']     = $this->Paket_model->get_aktif($id);
        $data['riwayat']   = $this->Peminjaman_model->get_riwayat_paket($id);
        $data['buku']      = $this->Buku_model->get_by_paket($id);
        $this->render('backend/paket/detail', $data, 'paket');
    }

    public function tambah()
    {
        $this->require_login(['admin']);
        $data['title']     = 'Tambah Paket';
        $data['titlePage'] = $data['title'];
        $data['paket']     = NULL;
        $this->render('backend/paket/form', $data, 'paket');
    }

    public function simpan()
    {
        $this->require_login(['admin']);
        $this->require_post();
        if (!$this->_validasi()) {
            return $this->tambah();
        }
        $kode = strtoupper(trim($this->input->post('kode', TRUE)));
        if ($this->Paket_model->kode_exists($kode)) {
            $this->session->set_flashdata('error', 'Kode paket <strong>' . html_escape($kode) . '</strong> sudah dipakai!');
            redirect('backend/paket/tambah');
        }
        $this->Paket_model->insert([
            'kode'      => $kode,
            'nama'      => $this->input->post('nama', TRUE),
            'deskripsi' => $this->input->post('deskripsi', TRUE),
        ]);
        $id = $this->db->insert_id();
        $this->session->set_flashdata('success', 'Paket berhasil dibuat. Sekarang isi daftar bukunya (tambah satu per satu atau impor CSV).');
        redirect('backend/paket/detail/' . $id);
    }

    public function edit($id = 0)
    {
        $this->require_login(['admin']);
        $paket = $this->Paket_model->get_by_id($id);
        if (!$paket) {
            $this->session->set_flashdata('error', 'Paket tidak ditemukan!');
            redirect('backend/paket');
        }
        $data['title']     = 'Edit Paket';
        $data['titlePage'] = $data['title'];
        $data['paket']     = $paket;
        $this->render('backend/paket/form', $data, 'paket');
    }

    public function update($id = 0)
    {
        $this->require_login(['admin']);
        $this->require_post();
        if (!$this->Paket_model->get_by_id($id)) {
            $this->session->set_flashdata('error', 'Paket tidak ditemukan!');
            redirect('backend/paket');
        }
        if (!$this->_validasi()) {
            return $this->edit($id);
        }
        $kode = strtoupper(trim($this->input->post('kode', TRUE)));
        if ($this->Paket_model->kode_exists($kode, $id)) {
            $this->session->set_flashdata('error', 'Kode paket sudah dipakai paket lain!');
            redirect('backend/paket/edit/' . (int) $id);
        }
        $this->Paket_model->update($id, [
            'kode'      => $kode,
            'nama'      => $this->input->post('nama', TRUE),
            'deskripsi' => $this->input->post('deskripsi', TRUE),
        ]);
        $this->session->set_flashdata('success', 'Paket berhasil diperbarui!');
        redirect('backend/paket/detail/' . (int) $id);
    }

    public function delete($id = 0)
    {
        $this->require_login(['admin']);
        $this->require_post();
        $paket = $this->Paket_model->get_by_id($id);
        if (!$paket) {
            $this->session->set_flashdata('error', 'Paket tidak ditemukan!');
        } elseif ($paket['status'] === 'dipinjam') {
            $this->session->set_flashdata('error', 'Paket sedang dipinjam. Tarik dulu sebelum dihapus.');
        } elseif ($this->Paket_model->punya_riwayat($id)) {
            $this->session->set_flashdata('error', 'Paket punya riwayat peminjaman sehingga tidak bisa dihapus (agar laporan tetap utuh).');
        } else {
            $this->Paket_model->delete($id);
            $this->session->set_flashdata('success', 'Paket beserta daftar bukunya berhasil dihapus!');
        }
        redirect('backend/paket');
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('kode', 'Kode Paket', 'required|trim|alpha_numeric|max_length[10]', [
            'required'      => '%s tidak boleh kosong.',
            'alpha_numeric' => '%s hanya boleh huruf/angka (contoh: A, B, C1).',
        ]);
        $this->form_validation->set_rules('nama', 'Nama Paket', 'required|trim|max_length[150]', ['required' => '%s tidak boleh kosong.']);
        return $this->form_validation->run();
    }
}
