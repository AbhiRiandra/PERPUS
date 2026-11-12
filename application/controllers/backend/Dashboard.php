<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(['Paket_model', 'Peminjaman_model']);
    }

    public function index()
    {
        $data['title']      = 'Dashboard';
        $data['titlePage']  = 'Dashboard';
        $data['stat']       = $this->Paket_model->statistik();
        $data['paket']      = $this->Paket_model->get_all();
        $data['terlambat']  = $this->Peminjaman_model->get_all(['status' => 'terlambat']);
        $this->render('backend/dashboard/index', $data, 'dashboard');
    }
}
