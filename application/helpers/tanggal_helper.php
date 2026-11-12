<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('tgl_indo')) {
    /** 2026-11-08 -> 8 Nov 2026 ; kosong -> - */
    function tgl_indo($tanggal)
    {
        if (empty($tanggal) || $tanggal === '0000-00-00') { return '-'; }
        $bln = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $t = strtotime($tanggal);
        return date('j', $t) . ' ' . $bln[(int) date('n', $t)] . ' ' . date('Y', $t);
    }
}

if (!function_exists('sisa_hari')) {
    /** Selisih hari dari hari ini ke jatuh tempo (negatif = sudah lewat) */
    function sisa_hari($jatuh_tempo)
    {
        $a = new DateTime(date('Y-m-d'));
        $b = new DateTime($jatuh_tempo);
        return (int) $a->diff($b)->format('%r%a');
    }
}

if (!function_exists('badge_jatuh_tempo')) {
    /** Badge status untuk peminjaman yang masih berjalan */
    function badge_jatuh_tempo($jatuh_tempo)
    {
        $s = sisa_hari($jatuh_tempo);
        if ($s < 0) {
            return '<span class="badge badge-soft-danger"><i class="bi bi-exclamation-circle-fill me-1"></i>Terlambat ' . abs($s) . ' hari</span>';
        }
        if ($s <= 7) {
            return '<span class="badge badge-soft-warning"><i class="bi bi-clock-fill me-1"></i>Sisa ' . $s . ' hari</span>';
        }
        return '<span class="badge badge-soft-success"><i class="bi bi-check-circle-fill me-1"></i>Sisa ' . $s . ' hari</span>';
    }
}
