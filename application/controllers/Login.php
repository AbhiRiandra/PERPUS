<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_model');
    }

    public function index()
    {
        if ($this->session->userdata('id')) {
            redirect('backend/dashboard');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['titlePage'] = 'Login Sistem Peminjaman Buku';
            $this->loadPartials('login', $data);
        } else {
            $this->proses_login();
        }
    }

    private function proses_login()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');
        $user     = $this->Login_model->getUser($username);

        // pesan sengaja sama untuk username/password salah (tidak membocorkan akun mana yang ada)
        if ($user && password_verify($password, $user['password'])) {
            $this->session->sess_regenerate(TRUE);
            $this->session->set_userdata([
                'id'       => $user['id'],
                'nama'     => $user['nama'],
                'username' => $user['username'],
                'role'     => $user['role'],
            ]);
            redirect('backend/dashboard');
        }

        $this->session->set_flashdata('message', '<b>Username atau password salah!</b>');
        redirect('login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
