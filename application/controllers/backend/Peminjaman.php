<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login(['admin', 'petugas']);
        $this->load->model(['Paket_model', 'Lokasi_model', 'Peminjaman_model']);
    }

    /** Riwayat semua peminjaman + filter */
    public function index()
    {
        $status    = $this->input->get('status', TRUE);
        $id_lokasi = (int) $this->input->get('lokasi');
        $filter = [];
        if (in_array($status, ['berjalan', 'selesai', 'terlambat'], TRUE)) { $filter['status'] = $status; }
        if ($id_lokasi > 0) { $filter['id_lokasi'] = $id_lokasi; }

        $data['title']     = 'Riwayat Peminjaman';
        $data['titlePage'] = $data['title'];
        $data['status']    = isset($filter['status']) ? $filter['status'] : '';
        $data['id_lokasi'] = $id_lokasi;
        $data['lokasi']    = $this->Lokasi_model->get_all();
        $data['riwayat']   = $this->Peminjaman_model->get_all($filter);
        $this->render('backend/peminjaman/index', $data, 'peminjaman');
    }


/**
 * Hapus beberapa riwayat peminjaman yang dipilih.
 * Hanya admin yang boleh menghapus.
 */
public function hapus_terpilih()
{
    $this->require_login(['admin']);
    $this->require_post();

    $ids = $this->input->post('riwayat_ids');

    if (!is_array($ids) || empty($ids)) {
        $this->session->set_flashdata(
            'error',
            'Pilih minimal satu riwayat yang ingin dihapus.'
        );
        redirect('backend/peminjaman');
        return;
    }

    // Validasi ID dan hilangkan duplikasi.
    $ids = array_values(array_unique(array_filter(
        array_map('intval', $ids),
        function ($id) {
            return $id > 0;
        }
    )));

    if (empty($ids)) {
        $this->session->set_flashdata(
            'error',
            'Data riwayat yang dipilih tidak valid.'
        );
        redirect('backend/peminjaman');
        return;
    }

    $jumlah = $this->Peminjaman_model->hapus_riwayat_selesai($ids);

    if ($jumlah === FALSE) {
        $this->session->set_flashdata(
            'error',
            'Gagal menghapus riwayat peminjaman.'
        );
    } elseif ($jumlah > 0) {
        $this->session->set_flashdata(
            'success',
            $jumlah . ' riwayat peminjaman berhasil dihapus.'
        );
    } else {
        $this->session->set_flashdata(
            'error',
            'Tidak ada riwayat selesai yang dapat dihapus dari pilihan tersebut.'
        );
    }

    redirect('backend/peminjaman');
}


    /** Form: pinjamkan paket yang tersedia ke sebuah lokasi */
    public function pinjam($id_paket = 0)
    {
        $paket = $this->_paket($id_paket);
        if ($paket['status'] !== 'tersedia') {
            $this->_gagal('Paket ' . $paket['kode'] . ' sedang dipinjam.', $id_paket);
        }
        $this->_form('pinjam', $paket, 'Pinjamkan Paket ' . $paket['kode']);
    }

    public function proses_pinjam($id_paket = 0)
    {
        $this->require_post();
        $paket = $this->_paket($id_paket);
        if (!$this->_validasi()) {
            return $this->pinjam($id_paket);
        }
        $r = $this->Peminjaman_model->pinjam(
            $paket['id'],
            (int) $this->input->post('id_lokasi'),
            $this->input->post('tanggal'),
            $this->_jatuh_tempo(),
            $this->input->post('catatan', TRUE),
            $this->session->userdata('id')
        );
        $this->_selesai($r, "Paket {$paket['kode']} berhasil dipinjamkan. Posisi paket otomatis diperbarui.", $paket['id']);
    }

    /** Form: oper paket dari lokasi sekarang ke lokasi lain */
    public function oper($id_paket = 0)
    {
        $paket = $this->_paket($id_paket);
        if ($paket['status'] !== 'dipinjam') {
            $this->_gagal('Paket ' . $paket['kode'] . ' tidak sedang dipinjam, tidak bisa dioper.', $id_paket);
        }
        $this->_form('oper', $paket, 'Oper Paket ' . $paket['kode'], $paket['id_lokasi']);
    }

    public function proses_oper($id_paket = 0)
    {
        $this->require_post();
        $paket = $this->_paket($id_paket);
        if (!$this->_validasi()) {
            return $this->oper($id_paket);
        }
        $r = $this->Peminjaman_model->oper(
            $paket['id'],
            (int) $this->input->post('id_lokasi'),
            $this->input->post('tanggal'),
            $this->_jatuh_tempo(),
            $this->input->post('catatan', TRUE),
            $this->session->userdata('id')
        );
        $this->_selesai($r, "Paket {$paket['kode']} berhasil dioper. Riwayat lama ditutup dan riwayat baru dibuat otomatis.", $paket['id']);
    }

    /** Tarik paket kembali ke perpustakaan */
    public function proses_tarik($id_paket = 0)
    {
        $this->require_post();
        $paket = $this->_paket($id_paket);
        $tgl = $this->input->post('tanggal');
        if (!$this->_tgl_valid($tgl)) { $tgl = date('Y-m-d'); }
        $r = $this->Peminjaman_model->tarik($paket['id'], $tgl, trim($this->input->post('catatan', TRUE)), $this->session->userdata('id'));
        $this->_selesai($r, "Paket {$paket['kode']} sudah ditarik kembali ke perpustakaan dan berstatus tersedia.", $paket['id']);
    }

    public function proses_perpanjang($id_paket = 0)
    {
        $this->require_post();
        $paket = $this->_paket($id_paket);
        $tgl = $this->input->post('tgl_jatuh_tempo');
        if (!$this->_tgl_valid($tgl)) {
            $this->_gagal('Tanggal jatuh tempo tidak valid.', $paket['id']);
        }
        $r = $this->Peminjaman_model->perpanjang($paket['id'], $tgl);
        $this->_selesai($r, 'Jatuh tempo paket ' . $paket['kode'] . ' diperpanjang.', $paket['id']);
    }

    // ---------------------------------------------------------------- helper

    private function _form($mode, $paket, $judul, $id_lokasi_sekarang = NULL)
    {
        $lokasi = $this->Lokasi_model->get_all();
        if ($id_lokasi_sekarang) { // saat oper, lokasi sekarang tidak boleh jadi tujuan
            $lokasi = array_values(array_filter($lokasi, function ($l) use ($id_lokasi_sekarang) {
                return (int) $l['id'] !== (int) $id_lokasi_sekarang;
            }));
        }
        $data['title']     = $judul;
        $data['titlePage'] = $judul;
        $data['mode']      = $mode;
        $data['paket']     = $paket;
        $data['lokasi']    = $lokasi;
        $this->render('backend/peminjaman/form', $data, 'paket');
    }

    private function _paket($id)
    {
        $paket = $this->Paket_model->get_by_id($id);
        if (!$paket) {
            $this->session->set_flashdata('error', 'Paket tidak ditemukan!');
            redirect('backend/paket');
        }
        return $paket;
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('id_lokasi', 'Lokasi tujuan', 'required|integer', ['required' => '%s harus dipilih.']);
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|callback__cek_tanggal');
        $this->form_validation->set_rules('durasi', 'Lama pinjam', 'required|integer|greater_than[0]|less_than[731]');
        return $this->form_validation->run();
    }

    public function _cek_tanggal($tgl)
    {
        if (!$this->_tgl_valid($tgl)) {
            $this->form_validation->set_message('_cek_tanggal', '{field} tidak valid.');
            return FALSE;
        }
        return TRUE;
    }

    private function _tgl_valid($tgl)
    {
        $d = DateTime::createFromFormat('Y-m-d', (string) $tgl);
        return $d && $d->format('Y-m-d') === $tgl;
    }

    private function _jatuh_tempo()
    {
        $hari = (int) $this->input->post('durasi');
        return date('Y-m-d', strtotime($this->input->post('tanggal') . " +{$hari} days"));
    }

    private function _selesai($hasil, $pesan_sukses, $id_paket)
    {
        if ($hasil === TRUE) {
            $this->session->set_flashdata('success', $pesan_sukses);
        } else {
            $this->session->set_flashdata('error', $hasil);
        }
        redirect('backend/paket/detail/' . (int) $id_paket);
    }

    private function _gagal($pesan, $id_paket)
    {
        $this->session->set_flashdata('error', $pesan);
        redirect('backend/paket/detail/' . (int) $id_paket);
    }
}
