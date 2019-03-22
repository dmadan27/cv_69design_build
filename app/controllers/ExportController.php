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

    // ======================= BANK ============================

    public function bank() {}

    // ===================== END BANK ==========================

    // ===================== PROYEK ============================

    public function proyek() {}

    public function proyek_detail() {}    

    // ==================== END PROYEK =========================

    // ============== SUB KAS KECIL ============================

    /**
     * Export Seluruh Data Sub Kas Kecil
     */
    public function sub_kas_kecil() {
        $this->excel->setProperty('Data Sub Kas Kecil','Data Sub Kas Kecil','Data Sub Kas Kecil '.date('d/m/Y'));

        $row = $this->Sub_kas_kecilModel->export();
        $header = array_keys($row[0] ?? []); 
        $this->excel->setData($header, $row);
        $this->excel->getData('DATA SUB KAS KECIL', 'DATA SUB KAS KECIL', 4, 5);

        $this->excel->getExcel('DATA_SUB_KAS_KECIL');
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

            $this->excel->getExcel("DATA_DETAIL_SKK_".$id_skk."_".$nama."_".$bulan.$tahun);

        } else $this->redirect(BASE_URL."sub-kas-kecil");	
    }

    // =========== END SUB KAS KECIL ============================

    // ============== PENGAJUAN SUB KAS KECIL ===================

    /**
     * Export Data Pengajuan Sub Kas Kecil
     */
    public function pengajuan_sub_kas_kecil(){
        if ($_SERVER['REQUEST_METHOD'] != "POST") $this->redirect(BASE_URL."pengajuan-sub-kas-kecil");

        $tahun = $_POST['tahun'] ?? false;
        $bulan = $_POST['bulan'] ?? false;

        if ($tahun) {

            $data_pengajuan = $this->Pengajuan_sub_kas_kecilModel->getByTglExport($bulan."/".$tahun);

            $this->excel->setProperty('Data Pengajuan Sub Kas Kecil '.$bulan."/".$tahun, 'Export Data Pengajuan SKK', 'Dokumen di Ekspor Tanggal '.Date('d/m/Y'));
            $this->excel->setData(array_keys($data_pengajuan[0] ?? []), $data_pengajuan);
            $this->excel->getData('DATA PENGAJUAN SUB KAS KECIL '.$bulan.$tahun, 'DATA PENGAJUAN SUB KAS KECIL '.$bulan.$tahun, 4, 5, 0, true);
            $this->excel->getExcel('DATA_PENGAJUAN_SUB_KAS_KECIL_'.$bulan.$tahun);

        } else $this->redirect(BASE_URL."pengajuan-sub-kas-kecil");
    }

    // =========== END PENGAJUAN SUB KAS KECIL ==================
}
