<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi extends MY_Controller {

    private $jenis = [
        'kelurahan'  => 'Kelurahan',
        'desa'       => 'Desa',
        'sekolah'    => 'Sekolah',
        'taman_baca' => 'Taman Baca',
        'lainnya'    => 'Lainnya',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('Lokasi_model');
    }

    public function index()
    {
        $data['title']     = 'Data Lokasi';
        $data['titlePage'] = 'Data Lokasi Peminjaman';
        $data['lokasi']    = $this->Lokasi_model->get_all();
        $data['jenis']     = $this->jenis;
        $this->render('backend/lokasi/index', $data, 'lokasi');
    }

    public function tambah()
    {
        $this->require_login(['admin']);
        $data['title']     = 'Tambah Lokasi';
        $data['titlePage'] = $data['title'];
        $data['lokasi']    = NULL;
        $data['jenis']     = $this->jenis;
        $this->render('backend/lokasi/form', $data, 'lokasi');
    }

    public function simpan()
    {
        $this->require_login(['admin']);
        $this->require_post();
        if (!$this->_validasi()) {
            return $this->tambah();
        }
        $nama = trim($this->input->post('nama_lokasi', TRUE));
        if ($this->Lokasi_model->nama_exists($nama)) {
            $this->session->set_flashdata('error', 'Lokasi <strong>' . html_escape($nama) . '</strong> sudah terdaftar!');
            redirect('backend/lokasi/tambah');
        }
        $ok = $this->Lokasi_model->insert($this->_data($nama));
        $this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Lokasi berhasil ditambahkan!' : 'Gagal menambahkan lokasi.');
        redirect('backend/lokasi');
    }

    public function edit($id = 0)
    {
        $this->require_login(['admin']);
        $lokasi = $this->Lokasi_model->get_by_id($id);
        if (!$lokasi) {
            $this->session->set_flashdata('error', 'Lokasi tidak ditemukan!');
            redirect('backend/lokasi');
        }
        $data['title']     = 'Edit Lokasi';
        $data['titlePage'] = $data['title'];
        $data['lokasi']    = $lokasi;
        $data['jenis']     = $this->jenis;
        $this->render('backend/lokasi/form', $data, 'lokasi');
    }

    public function update($id = 0)
    {
        $this->require_login(['admin']);
        $this->require_post();
        if (!$this->Lokasi_model->get_by_id($id)) {
            $this->session->set_flashdata('error', 'Lokasi tidak ditemukan!');
            redirect('backend/lokasi');
        }
        if (!$this->_validasi()) {
            return $this->edit($id);
        }
        $nama = trim($this->input->post('nama_lokasi', TRUE));
        if ($this->Lokasi_model->nama_exists($nama, $id)) {
            $this->session->set_flashdata('error', 'Nama lokasi sudah dipakai lokasi lain!');
            redirect('backend/lokasi/edit/' . (int) $id);
        }
        $ok = $this->Lokasi_model->update($id, $this->_data($nama));
        $this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Lokasi berhasil diperbarui!' : 'Gagal memperbarui lokasi.');
        redirect('backend/lokasi');
    }

    public function delete($id = 0)
    {
        $this->require_login(['admin']);
        $this->require_post();
        if (!$this->Lokasi_model->get_by_id($id)) {
            $this->session->set_flashdata('error', 'Lokasi tidak ditemukan!');
        } elseif ($this->Lokasi_model->punya_riwayat($id)) {
            $this->session->set_flashdata('error', 'Lokasi tidak bisa dihapus karena sudah punya riwayat peminjaman.');
        } else {
            $this->Lokasi_model->delete($id);
            $this->session->set_flashdata('success', 'Lokasi berhasil dihapus!');
        }
        redirect('backend/lokasi');
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('nama_lokasi', 'Nama Lokasi', 'required|trim|max_length[150]', ['required' => '%s tidak boleh kosong.']);
        $this->form_validation->set_rules('jenis', 'Jenis Lokasi', 'required|in_list[' . implode(',', array_keys($this->jenis)) . ']');
        $this->form_validation->set_rules('no_hp', 'No. HP', 'trim|max_length[20]');
        return $this->form_validation->run();
    }

    private function _data($nama)
    {
        return [
            'nama_lokasi'      => $nama,
            'jenis'            => $this->input->post('jenis', TRUE),
            'alamat'           => $this->input->post('alamat', TRUE),
            'penanggung_jawab' => $this->input->post('penanggung_jawab', TRUE),
            'no_hp'            => $this->input->post('no_hp', TRUE),
        ];
    }
}
