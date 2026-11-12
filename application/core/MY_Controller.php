<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar'); // WITA (Mataram)
    }

    /** Halaman publik (login): header + view + footer */
    public function loadPartials($view = '', $data = [])
    {
        $this->load->view('_partials/headers', $data);
        $this->load->view($view, $data);
        $this->load->view('_partials/footer');
    }

    /** Halaman backend: header + sidebar + flash + view + footer */
    public function render($view, $data = [], $active = 'dashboard')
    {
        $data['active'] = $active;
        $this->load->view('_partials/headers', $data);
        $this->load->view('_partials/layout_open', $data);
        $this->load->view($view, $data);
        $this->load->view('_partials/layout_close');
        $this->load->view('_partials/footer');
    }

    /** Wajib login. Opsional: batasi role. */
    protected function require_login($roles = [])
    {
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
        if (!empty($roles) && !in_array($this->session->userdata('role'), $roles, TRUE)) {
            $this->session->set_flashdata('error', 'Anda tidak punya akses ke halaman tersebut.');
            redirect('backend/dashboard');
        }
    }

    /** Hanya terima request POST (untuk aksi simpan/hapus/pinjam) */
    protected function require_post()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
    }
}
