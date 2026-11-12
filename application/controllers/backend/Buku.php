<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Isi paket (daftar judul). Hanya admin yang boleh mengubah. */
class Buku extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login(['admin']);
        $this->load->model(['Paket_model', 'Buku_model']);
    }

    public function simpan($id_paket = 0)
    {
        $this->require_post();
        $paket = $this->_paket($id_paket);

        $this->form_validation->set_rules('judul', 'Judul', 'required|trim|max_length[255]', ['required' => '%s tidak boleh kosong.']);
        $this->form_validation->set_rules('tahun', 'Tahun', 'trim|exact_length[4]|numeric');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('backend/paket/detail/' . $paket['id']);
        }

        $this->Buku_model->insert([
            'id_paket' => $paket['id'],
            'judul'    => $this->input->post('judul', TRUE),
            'penulis'  => $this->input->post('penulis', TRUE),
            'penerbit' => $this->input->post('penerbit', TRUE),
            'tahun'    => $this->input->post('tahun', TRUE) ?: NULL,
            'kategori' => $this->input->post('kategori', TRUE),
        ]);
        $this->session->set_flashdata('success', 'Buku ditambahkan ke Paket ' . html_escape($paket['kode']) . '.');
        redirect('backend/paket/detail/' . $paket['id']);
    }

    public function delete($id = 0)
    {
        $this->require_post();
        $buku = $this->Buku_model->get_by_id($id);
        if (!$buku) {
            $this->session->set_flashdata('error', 'Buku tidak ditemukan!');
            redirect('backend/paket');
        }
        $this->Buku_model->delete($id);
        $this->session->set_flashdata('success', 'Buku dihapus dari paket.');
        redirect('backend/paket/detail/' . $buku['id_paket']);
    }

    /** Impor ratusan judul sekaligus dari file CSV */
    public function import($id_paket = 0)
    {
        $this->require_post();
        $paket = $this->_paket($id_paket);
        $back  = 'backend/paket/detail/' . $paket['id'];

        if (empty($_FILES['file_csv']['tmp_name']) || $_FILES['file_csv']['error'] !== UPLOAD_ERR_OK) {
            $this->session->set_flashdata('error', 'Pilih file CSV terlebih dahulu.');
            redirect($back);
        }
        $ext = strtolower(pathinfo($_FILES['file_csv']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv' || $_FILES['file_csv']['size'] > 2 * 1024 * 1024) {
            $this->session->set_flashdata('error', 'File harus berformat .csv dan maksimal 2 MB.');
            redirect($back);
        }

        $fh = fopen($_FILES['file_csv']['tmp_name'], 'r');
        $first = fgets($fh);
        rewind($fh);
        if (substr($first, 0, 3) === "\xEF\xBB\xBF") { fseek($fh, 3); }       // buang BOM Excel
        $delim = (substr_count($first, ';') > substr_count($first, ',')) ? ';' : ','; // Excel Indonesia memakai ;

        $rows = []; $skip = 0; $baris = 0;
        $mode = $this->input->post('mode'); // 'tambah' | 'ganti'
        while (($c = fgetcsv($fh, 2000, $delim)) !== FALSE) {
            $baris++;
            $judul = isset($c[0]) ? trim($c[0]) : '';
            if ($judul === '' || ($baris === 1 && strtolower($judul) === 'judul')) { // lewati header & baris kosong
                if ($baris !== 1 && $judul === '') { $skip++; }
                continue;
            }
            $tahun = isset($c[3]) ? trim($c[3]) : '';
            $rows[] = [
                'id_paket' => $paket['id'],
                'judul'    => mb_substr($judul, 0, 255),
                'penulis'  => isset($c[1]) ? mb_substr(trim($c[1]), 0, 150) : NULL,
                'penerbit' => isset($c[2]) ? mb_substr(trim($c[2]), 0, 150) : NULL,
                'tahun'    => preg_match('/^\d{4}$/', $tahun) ? $tahun : NULL,
                'kategori' => isset($c[4]) ? mb_substr(trim($c[4]), 0, 100) : NULL,
            ];
        }
        fclose($fh);

        if (empty($rows)) {
            $this->session->set_flashdata('error', 'Tidak ada data buku yang terbaca. Pastikan kolom pertama berisi judul.');
            redirect($back);
        }

        $this->db->trans_start();
        if ($mode === 'ganti') {
            $this->Buku_model->hapus_semua_paket($paket['id']);
        }
        $n = $this->Buku_model->insert_batch($rows);
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('success', $n . ' judul berhasil diimpor ke Paket ' . html_escape($paket['kode']) . ($skip ? " ($skip baris kosong dilewati)" : '') . '.');
        } else {
            $this->session->set_flashdata('error', 'Impor gagal, tidak ada data yang diubah.');
        }
        redirect($back);
    }

    /** Unduh contoh format CSV */
    public function template()
    {
        $this->load->helper('download');
        $csv  = "judul,penulis,penerbit,tahun,kategori\n";
        $csv .= "Contoh Judul Buku 1,Nama Penulis,Nama Penerbit,2020,Umum\n";
        $csv .= "Contoh Judul Buku 2,Nama Penulis,Nama Penerbit,2019,Sains\n";
        force_download('contoh_impor_buku.csv', $csv);
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
}
