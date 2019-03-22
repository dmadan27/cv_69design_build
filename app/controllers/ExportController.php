<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

// TODO: All method need for check to have session validation and safer validation
class Export extends Controller {

    function __construct() {
        $this->auth();
        $this->auth->cekAuth();
        $this->helper();
        $this->excel();

        $this->model('Sub_kas_kecilModel');
        $this->model('Mutasi_saldo_sub_kas_kecilModel');
        $this->model('Pengajuan_sub_kas_kecilModel');
    }

    public function index() {
        $this->redirect();
    }

    // ============== SUB KAS KECIL ===================

    /**
     * Export Seluruh Data Sub Kas Kecil
     */
    public function sub_kas_kecil() {
        $this->excel->setProperty('Data Sub Kas Kecil','Data Sub Kas Kecil','Data Sub Kas Kecil '.date('d/m/Y'));

        $row = $this->Sub_kas_kecilModel->export();
        $header = array_keys($row[0] ?? []); 
        $this->excel->setData($header, $row);
        $this->excel->getData('DATA SUB KAS KECIL', 'DATA SUB KAS KECIL', 4, 5);

        $this->excel->getExcel('SUB-KAS-KECIL');
    }

    /**
     * Export Detail Data Sub Kas Kecil
     */
    public function sub_kas_kecil_detail() {
        if ($_SERVER['REQUEST_METHOD'] != "POST") $this->redirect(BASE_URL."sub-kas-kecil");

        $id_skk = $_POST['id'] ?? false;
        $nama = $_POST['nama'] ?? false;
        $tahun = $_POST['tahun'] ?? false;
        $bulan = $_POST['bulan'] ?? false;

        if ($id_skk && $nama && $tahun) {
            
            $data_skk = $this->Sub_kas_kecilModel->getByIdExport($id_skk);
            $data_mutasi = $this->Mutasi_saldo_sub_kas_kecilModel->getByIdSKKTglExport($id_skk, $bulan."/".$tahun);
            $data_pengajuan = $this->Pengajuan_sub_kas_kecilModel->getByIdSKKTglExport($id_skk, $bulan."/".$tahun);

            $this->excel->setProperty('info-detail-skk-'.$id_skk.'-'.$nama, 'Info Detail SKK '.$nama.' '.date('d/m/Y'), 'Detail Data SKK '.$nama);

            $this->excel->setContent([
                [
                    'sheet_name' => 'DATA SKK '.$nama." (".$id_skk.")",
                    'title' => 'DATA SKK '.$nama." (".$id_skk.")",
                    'table_header' => array_keys($data_skk[0] ?? []),
                    'table_content' => $data_skk,
                    'table_row_start' => 4,
                    'enable_numbering' => false,
                ],[
                    'sheet_name' => 'DATA MUTASI SKK '.$nama." (".$id_skk.") ".$bulan.$tahun,
                    'title' => 'DATA MUTASI SKK '.$nama." (".$id_skk.") ".$bulan.$tahun,
                    'table_header' => array_keys($data_mutasi[0] ?? []),
                    'table_content' => $data_mutasi,
                    'table_row_start' => 4,
                    'enable_numbering' => true,
                ],[
                    'sheet_name' => "DATA HISTORI PENGAJUAN SKK ".$nama." (".$id_skk.") ".$bulan.$tahun,
                    'title' => "DATA HISTORI PENGAJUAN SKK ".$nama." (".$id_skk.") ".$bulan.$tahun,
                    'table_header' => array_keys($data_pengajuan[0] ?? []),
                    'table_content' => $data_pengajuan,
                    'table_row_start' => 4,
                    'enable_numbering' => true,
                ],
            ]);

            $this->excel->getExcel("INFO-DETAIL-SKK-".$id_skk."-".$nama."-".$bulan.$tahun);

        } else $this->redirect(BASE_URL."sub-kas-kecil");	
    }

    // =========== END SUB KAS KECIL ==================
}
