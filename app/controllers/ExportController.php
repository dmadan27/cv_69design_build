<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

// TODO: All method need for check to have session validation and safer validation
class Export extends Controller {

    function __construct() {
        $this->auth();
        $this->auth->cekAuth();
        $this->helper();
        $this->excel();
        $this->excel_v2();
    }

    public function index() {
        $this->redirect();
    }

    // ======================= BANK ============================

    /**
     * 
     */
    public function bank() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $config = array();
            $this->model('BankModel');

            $row = $this->BankModel->export();
            $column = array_keys($row[0]);
            
            $config['data']['main']['row'] = $row;
            $config['data']['main']['column'] = $column;
            $config['data']['main']['sheet'] = 'Data Bank';
            $config['data']['detail'] = NULL;
            $config['property']['title'] = 'Data Bank';
            $config['property']['subject'] = 'Data Bank CV. 69 Design Build';
            $config['property']['description'] = 'List Semua Data Bank CV. 69 Design Build';

            $this->excel_v2->setProperty($config['property']);
            $this->excel_v2->setData($config['data']['main'], $config['data']['detail']);
            $this->excel_v2->getExcel(1, 2);
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * 
     */
    public function bank_detail_mutasi($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $config = array();
            
            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;

            $this->model('BankModel');

            $row = $this->BankModel->export_mutasi($id, $tgl_awal, $tgl_akhir);
            $column = array_keys($row[0]);
            $column[0] = 'ID BANK';

            $property = 'Data Mutasi Bank '.$row[0]['BANK'].' Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
            $config['data']['main']['row'] = $row;
            $config['data']['main']['column'] = $column;
            $config['data']['main']['sheet'] = 'Data Mutasi Bank '.$row[0]['BANK'];
            $config['data']['detail'] = NULL;
            $config['property']['title'] = $property;
            $config['property']['subject'] = $property;
            $config['property']['description'] = $property;

            $this->excel_v2->setProperty($config['property']);
            $this->excel_v2->setData($config['data']['main'], $config['data']['detail']);
            $this->excel_v2->getExcel(1, 2);
        }
        else { die(ACCESS_DENIED); }
    }

    // ===================== END BANK ==========================

    // ===================== PROYEK ============================

    /**
     * Method proyek
     * Export proyek di list, mengexport data utama, detail pembayaran, dan detail skk
     * Berdasarkan tanggal proyek
     */
    public function proyek() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $config = $detail = array();

            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;
            
            $this->model('ProyekModel');

            $row = $this->ProyekModel->export($tgl_awal, $tgl_akhir);
            $column = array_keys($row[0]);

            $detailRow_pembayaran = $this->ProyekModel->export_detail_pembayaran($tgl_awal, $tgl_akhir);
            $detailColumn_pembayaran = array_keys($detailRow_pembayaran[0]);
            
            $detailRow_skk = $this->ProyekModel->export_detail_skk($tgl_awal, $tgl_akhir);
            $detailColumn_skk = array_keys($detailRow_skk[0]);

            $detail[0]['row'] = $detailRow_pembayaran;
            $detail[0]['column'] = $detailColumn_pembayaran;
            $detail[0]['sheet'] = 'Data Detail Pembayaran Proyek';

            $detail[1]['row'] = $detailRow_skk;
            $detail[1]['column'] = $detailColumn_skk;
            $detail[1]['sheet'] = 'Data Detail SKK Proyek';

            $property = 'Data Proyek Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
            $config['data']['main']['row'] = $row;
            $config['data']['main']['column'] = $column;
            $config['data']['main']['sheet'] = 'Data Proyek';
            $config['data']['detail'] = $detail;
            $config['property']['title'] = $config['property']['subject'] = $property;
            $config['property']['description'] = 'List Data Proyek Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

            $this->excel_v2->setProperty($config['property']);
            $this->excel_v2->setData($config['data']['main'], $config['data']['detail']);
            $this->excel_v2->getExcel(1, 2);
        }
        else { die(ACCESS_DENIED); }
    }
    
    /**
     * 
     */
    public function proyek_detail_pembayaran($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $config = array();

            $this->model('ProyekModel');

            $row = $this->ProyekModel->export_detail_pembayaran(false, false, $id);
            $column = array_keys($row[0]);
            $column[0] = 'ID PROYEK';

            $property = 'Data Detail Pembayaran Proyek '.$row[0]['PROYEK'];
            $config['data']['main']['row'] = $row;
            $config['data']['main']['column'] = $column;
            $config['data']['main']['sheet'] = $property;
            $config['data']['detail'] = NULL;
            $config['property']['title'] = $property;
            $config['property']['subject'] = $property;
            $config['property']['description'] = $property;

            $this->excel_v2->setProperty($config['property']);
            $this->excel_v2->setData($config['data']['main'], NULL);
            $this->excel_v2->getExcel(1, 2);
        }
        else { die(ACCESS_DENIED); }
    }
    
    /**
     * 
     */
    public function proyek_detail_pengajuan_skk($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $config = $detail = array();

            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;
            
            $this->model('Pengajuan_sub_kas_kecilModel');

            $row = $this->Pengajuan_sub_kas_kecilModel->export($tgl_awal, $tgl_akhir, $id);
            $column = array_keys($row[0]);

            $detailRow = $this->Pengajuan_sub_kas_kecilModel->export_detail($tgl_awal, $tgl_akhir, $id);
            $detailColumn = array_keys($detailRow[0]);

            $detail[0]['row'] = $detailRow;
            $detail[0]['column'] = $detailColumn;
            $detail[0]['sheet'] = 'Data Detail Pengajuan SKK';

            $property = 'Data Pengajuan SKK di Proyek '.strtoupper($id).' Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
            $config['data']['main']['row'] = $row;
            $config['data']['main']['column'] = $column;
            $config['data']['main']['sheet'] = 'Data Pengajuan SKK';
            $config['data']['detail'] = $detail;
            $config['property']['title'] = $config['property']['subject'] = $property;
            $config['property']['description'] = 'List Data Pengajuan SKK di Proyek '.strtoupper($id).' Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

            $this->excel_v2->setProperty($config['property']);
            $this->excel_v2->setData($config['data']['main'], $config['data']['detail']);
            $this->excel_v2->getExcel(1, 2);
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * 
     */
    public function proyek_detail_operasional_proyek($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $config = $detail = array();

            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;
            
            $this->model('Operasional_proyekModel');

            $row = $this->Operasional_proyekModel->export($tgl_awal, $tgl_akhir, $id);
            $column = array_keys($row[0]);

            $detailRow = $this->Operasional_proyekModel->export_detail($tgl_awal, $tgl_akhir, $id);
            $detailColumn = array_keys($detailRow[0]);

            $detail[0]['row'] = $detailRow;
            $detail[0]['column'] = $detailColumn;
            $detail[0]['sheet'] = 'Data Detail Operasional Proyek';

            $property = 'Data Operasional Proyek di Proyek '.strtoupper($id).' Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
            $config['data']['main']['row'] = $row;
            $config['data']['main']['column'] = $column;
            $config['data']['main']['sheet'] = 'Data Operasional Proyek';
            $config['data']['detail'] = $detail;
            $config['property']['title'] = $config['property']['subject'] = $property;
            $config['property']['description'] = 'List Data Operasional Proyek di Proyek '.strtoupper($id).' Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

            $this->excel_v2->setProperty($config['property']);
            $this->excel_v2->setData($config['data']['main'], $config['data']['detail']);
            $this->excel_v2->getExcel(1, 2);
        }
        else { die(ACCESS_DENIED); }
    }

    // ==================== END PROYEK =========================

    // ===================== DISTRIBUTOR =======================
    // ===================== END DISTRIBUTOR ===================

    // ===================== OPERASIONAL PROYEK ================
    // ===================== END OPERASIONAL PROYEK ============

    // ===================== OPERASIONAL =======================
    // ===================== END OPERASIONAL ===================

    // ===================== PENGAJUAN KAS KECIL ===============
    // ===================== END PENGAJUAN KAS KECIL ===========
    

    // ============== SUB KAS KECIL ============================

    /**
     * Export Seluruh Data Sub Kas Kecil
     */
    public function sub_kas_kecil() {
        $this->model('Sub_kas_kecilModel');
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

        $this->model('Mutasi_saldo_sub_kas_kecilModel');

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

        $this->model('Pengajuan_sub_kas_kecilModel');

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
