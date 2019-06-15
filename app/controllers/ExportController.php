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
     * Method bank
     * Export semua data bank
     * Export khusus di list bank
     */
    public function bank() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();
            $this->model('BankModel');

            $row = empty($this->BankModel->export()) ? false : $this->BankModel->export();
            if($row) {
                $column = array_keys($row[0]);
            
                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Bank';
                
                $properties['title'] = 'Data Bank';
                $properties['subject'] = 'Data Bank CV. 69 Design Build';
                $properties['description'] = 'List Semua Data Bank CV. 69 Design Build';

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );

                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method bank_detail_mutasi
     * Export data mutasi bank sesuai tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di view bank
     * @param string $id id bank
     */
    public function bank_detail_mutasi($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();
            
            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;

            $this->model('BankModel');

            $row = empty($this->BankModel->export_mutasi($id, $tgl_awal, $tgl_akhir)) 
                ? false : $this->BankModel->export_mutasi($id, $tgl_awal, $tgl_akhir);
            if($row) {
                $column = array_keys($row[0]);
                $column[0] = 'ID BANK';

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Mutasi Bank '.$row[0]['BANK'];

                $property = 'Data Mutasi Bank '.$row[0]['BANK'].' Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $properties['description'] = $property;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    // ===================== END BANK ==========================

    // ===================== PROYEK ============================

    /**
     * Method proyek
     * Export data proyek beserta detailnya sesuai tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di list proyek
     * Hak Akses: Kas Besar dan Owner
     */
    public function proyek() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = $detail = array();

            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;
            
            $this->model('ProyekModel');

            $row = empty($this->ProyekModel->export($tgl_awal, $tgl_akhir)) ? false : $this->ProyekModel->export($tgl_awal, $tgl_akhir);

            if($row) {
                $column = array_keys($row[0]);

                $detailRow_pembayaran = empty($this->ProyekModel->export_detail_pembayaran($tgl_awal, $tgl_akhir)) 
                    ? false : $this->ProyekModel->export_detail_pembayaran($tgl_awal, $tgl_akhir);
                $detailColumn_pembayaran = $detailRow_pembayaran ? array_keys($detailRow_pembayaran[0]) : NULL;
                
                $detailRow_skk = empty($this->ProyekModel->export_detail_skk($tgl_awal, $tgl_akhir)) 
                    ? false : $this->ProyekModel->export_detail_skk($tgl_awal, $tgl_akhir);
                $detailColumn_skk = $detailRow_skk ? array_keys($detailRow_skk[0]) : NULL;

                $detail[0]['row'] = $detailRow_pembayaran;
                $detail[0]['column'] = $detailColumn_pembayaran;
                $detail[0]['sheet'] = 'Data Detail Pembayaran Proyek';

                $detail[1]['row'] = $detailRow_skk;
                $detail[1]['column'] = $detailColumn_skk;
                $detail[1]['sheet'] = 'Data Detail SKK Proyek';

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Proyek';

                $property = 'Data Proyek Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Data Proyek Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, $detail);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );

                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }
    
    /**
     * Method proyek_detail_pembayaran
     * Export data detail pembayaran proyek
     * Export khusus di view proyek
     * Hak Akses: Kas Besar dan Owner
     * @param string $id id proyek
     */
    public function proyek_detail_pembayaran($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();

            $this->model('ProyekModel');

            $row = empty($this->ProyekModel->export_detail_pembayaran(false, false, $id)) 
                ? false : $this->ProyekModel->export_detail_pembayaran(false, false, $id);
            if($row) {
                $column = array_keys($row[0]);

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Detail Pembayaran Proyek';

                $property = 'Data Detail Pembayaran Proyek '.$row[0]['ID PROYEK'];
                $properties['title'] = $properties['subject'] = $properties['description'] = $property;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );

                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }
    
    /**
     * Method proyek_detail_pengajuan_skk
     * Export data pengajuan skk di suatu proyek beserta detailnya sesuai tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di view proyek
     * Hak Akses: Kas Besar dan Owner
     * @param string $id id proyek
     */
    public function proyek_detail_pengajuan_skk($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = $detail = array();

            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;
            
            $this->model('Pengajuan_sub_kas_kecilModel');

            $row = empty($this->Pengajuan_sub_kas_kecilModel->export_by_proyek($tgl_awal, $tgl_akhir, $id)) 
                ? false : $this->Pengajuan_sub_kas_kecilModel->export_by_proyek($tgl_awal, $tgl_akhir, $id);
            if($row) {
                $column = array_keys($row[0]);

                $detailRow = $this->Pengajuan_sub_kas_kecilModel->export_detail_by_proyek($tgl_awal, $tgl_akhir, $id);
                $detailColumn = array_keys($detailRow[0]);

                $detail[0]['row'] = $detailRow;
                $detail[0]['column'] = $detailColumn;
                $detail[0]['sheet'] = 'Data Detail Pengajuan SKK';

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Pengajuan SKK';
                
                $property = 'Data Pengajuan SKK di Proyek '.strtoupper($id).' Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Data Pengajuan SKK di Proyek '.strtoupper($id).' Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, $detail);
                $this->excel_v2->getExcel(1, 2);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method proyek_detail_operasional_proyek
     * Export data operasional proyek di suatu proyek beserta detailnya sesuai tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di view proyek
     * Hak Akses: Kas Besar dan Owner
     * @param string $id id proyek
     */
    public function proyek_detail_operasional_proyek($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $detail = $properties = array();

            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;
            
            $this->model('Operasional_proyekModel');

            $row = empty($this->Operasional_proyekModel->export($tgl_awal, $tgl_akhir, $id)) 
                ? false : $this->Operasional_proyekModel->export($tgl_awal, $tgl_akhir, $id);
            if($row) {
                $column = array_keys($row[0]);

                $detailRow = $this->Operasional_proyekModel->export_detail($tgl_awal, $tgl_akhir, $id);
                $detailColumn = array_keys($detailRow[0]);

                $detail[0]['row'] = $detailRow;
                $detail[0]['column'] = $detailColumn;
                $detail[0]['sheet'] = 'Data Detail Operasional Proyek';

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Operasional Proyek';
                
                $property = 'Data Operasional Proyek di Proyek '.strtoupper($id).' Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Data Operasional Proyek di Proyek '.strtoupper($id).' Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, $detail);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    // ==================== END PROYEK =========================

    // ===================== DISTRIBUTOR =======================

    /**
     * Method distributor
     * Export semua data distributor
     * Export khusus di list distributor
     * Hak Akses: Kas Besar dan Owner
     */
    public function distributor() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();
            $this->model('DistributorModel');

            $row = empty($this->DistributorModel->export()) ? false : $this->DistributorModel->export();
            if($row) {
                $column = array_keys($row[0]);
            
                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Distributor';

                $properties['title'] = 'Data Distributor';
                $properties['subject'] = 'Data Distributor CV. 69 Design Build';
                $properties['description'] = 'List Semua Data Distributor CV. 69 Design Build';

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    // ===================== END DISTRIBUTOR ===================

    // ===================== OPERASIONAL PROYEK ================

    /**
     * Method operasional_proyek
     * Export data operasional_proyek beserta detailnya sesuai tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di list operasional proyek
     * Hak Akses: Kas Besar dan Owner
     */
    public function operasional_proyek() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $detail = $properties = array();

            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;
            
            $this->model('Operasional_proyekModel');

            $row = empty($this->Operasional_proyekModel->export($tgl_awal, $tgl_akhir)) 
                ? false : $this->Operasional_proyekModel->export($tgl_awal, $tgl_akhir);

            if($row) {
                $column = array_keys($row[0]);

                $detailRow = empty($this->Operasional_proyekModel->export_detail($tgl_awal, $tgl_akhir)) 
                    ? false : $this->Operasional_proyekModel->export_detail($tgl_awal, $tgl_akhir);
                $detailColumn = $detailRow ? array_keys($detailRow[0]) : NULL;

                $detail[0]['row'] = $detailRow;
                $detail[0]['column'] = $detailColumn;
                $detail[0]['sheet'] = 'Data Detail Operasional Proyek';

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Operasional Proyek';
                
                $property = 'Data Operasional Proyek Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Data Operasional Proyek Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, $detail);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method operasional_proyek_detail
     * Export data detail operasional proyek
     * Export khusus di view
     * Hak Akses: Kas Besar dan Owner
     * @param string $id id operasional proyek
     */
    public function operasional_proyek_detail($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();

            $this->model('Operasional_proyekModel');

            $row = empty($this->Operasional_proyekModel->export_detail(false, false, $id)) 
                ? false : $this->Operasional_proyekModel->export_detail(false, false, $id);
            if($row) {
                $column = array_keys($row[0]);

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Detail Operasional Proyek';

                $property = 'Data Detail Operasional Proyek '.$row[0]['PROYEK'];
                $properties['title'] = $properties['subject'] = $properties['description'] = $property;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    // ===================== END OPERASIONAL PROYEK ============

    // ===================== OPERASIONAL =======================

    /**
     * Method operasional
     * Export data operasional sesuai tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di list operasional
     * Hak Akses: Kas Besar dan Owner
     */
    public function operasional() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();

            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;
            
            $this->model('OperasionalModel');

            $row = empty($this->OperasionalModel->export($tgl_awal, $tgl_akhir)) 
                ? false : $this->OperasionalModel->export($tgl_awal, $tgl_akhir);

            if($row) {
                $column = array_keys($row[0]);

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Operasional';
                
                $property = 'Data Operasional Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Data Operasional Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    // ===================== END OPERASIONAL ===================

    // ===================== PENGAJUAN KAS KECIL ===============

    /**
     * Method pengajuan_kas_kecil
     * Export data pengajuan kas kecil sesuai tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di list pengajuan kas kecil
     * Hak Akses: Kas Besar, Kas Kecil, dan Owner
     */
    public function pengajuan_kas_kecil() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL' 
        || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();

            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;
            
            $this->model('Pengajuan_kasKecilModel');
            if($_SESSION['sess_level'] === 'KAS KECIL') {
                $row = empty($this->Pengajuan_kasKecilModel->export($tgl_awal, $tgl_akhir, $_SESSION['sess_id'])) 
                    ? false : $this->Pengajuan_kasKecilModel->export($tgl_awal, $tgl_akhir, $_SESSION['sess_id']);
            }
            else {
                $row = empty($this->Pengajuan_kasKecilModel->export($tgl_awal, $tgl_akhir)) 
                    ? false : $this->Pengajuan_kasKecilModel->export($tgl_awal, $tgl_akhir);
            }

            if($row) {
                $column = array_keys($row[0]);

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Pengajuan Kas Kecil';
                
                $property = 'Data Pengajuan Kas Kecil Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Data Pengajuan Kas Kecil Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    // ===================== END PENGAJUAN KAS KECIL ===========
    

    // ============== SUB KAS KECIL ============================

    /**
     * Method sub_kas_kecil
     * Export Seluruh Data Sub Kas Kecil
     * Export khusus di list sub kas kecil
     * Hak Akses: Kas Besar, Kas Kecil dan Owner
     */
    public function sub_kas_kecil() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL' 
        || $_SESSION['sess_level'] === 'OWNER')) {
            $this->model('Sub_kas_kecilModel');

            $mainData = $properties = array();

            $row = empty($this->Sub_kas_kecilModel->export()) ? false : $this->Sub_kas_kecilModel->export();

            if ($row) {
                $column = array_keys($row[0]);

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Sub Kas Kecil';
                
                $property = 'Data Sub Kas Kecil';
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Data Sub Kas Kecil';

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            } else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );

                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } else { die(ACCESS_DENIED); }
    }

    /**
     * Method sub_kas_kecil_detail
     * Export Semua Detail Data Sub Kas Kecil sesuai dengan bulan dan tahun
     * Bulan dan Tahun berupa POST
     * Export khusus di view sub kas kecil
     * Hak Akses: Kas Besar, Kas Kecil dan Owner
     * @param string $id id sub kas kecil
     */
    public function sub_kas_kecil_detail($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL' 
        || $_SESSION['sess_level'] === 'OWNER')) {
            $this->model('Sub_kas_kecilModel');
            $this->model('Mutasi_saldo_sub_kas_kecilModel');
            $this->model('Pengajuan_sub_kas_kecilModel');

            $mainData = $properties = $detail = array();

            $tahun = $_POST['tahun'] ?? false;
            $bulan = $_POST['bulan'] ?? false;

            $row = empty($this->Sub_kas_kecilModel->getByIdExport($id)) ? false : $this->Sub_kas_kecilModel->getByIdExport($id);

            if($row) {
                $skk = $this->Sub_kas_kecilModel->getById($id);

                $column = array_keys($row[0]);

                $detailRow_mutasi = empty($this->Mutasi_saldo_sub_kas_kecilModel->getByIdSKKTglExport($id, $tahun."-".$bulan."%")) 
                    ? false : $this->Mutasi_saldo_sub_kas_kecilModel->getByIdSKKTglExport($id, $tahun."-".$bulan."%");
                $detailColumn_mutasi = $detailRow_mutasi ? array_keys($detailRow_mutasi[0]) : NULL;
                
                $detailRow_pengajuan = empty($this->Pengajuan_sub_kas_kecilModel->getByIdSKKTglExport($id, $tahun."-".$bulan."%")) 
                    ? false : $this->Pengajuan_sub_kas_kecilModel->getByIdSKKTglExport($id, $tahun."-".$bulan."%");
                $detailColumn_pengajuan = $detailRow_pengajuan ? array_keys($detailRow_pengajuan[0]) : NULL;

                $detail[0]['row'] = $detailRow_mutasi;
                $detail[0]['column'] = $detailColumn_mutasi;
                $detail[0]['sheet'] = 'Data Mutasi SKK ('.$skk['nama'].')';

                $detail[1]['row'] = $detailRow_pengajuan;
                $detail[1]['column'] = $detailColumn_pengajuan;
                $detail[1]['sheet'] = 'Data Pengajuan SKK ('.$skk['nama'].')';

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data SKK ('.$skk['nama'].')';

                $property = 'Data SKK ('.$skk['nama'].') '.$bulan.$tahun;
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Data SKK ('.$skk['nama'].') '.$bulan.$tahun;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, $detail);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );

                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method skk_detail_pengajuan
     * Export data detail pengajuan sub kas kecil sesuai dengan tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di view sub kas kecil
     * Hak Akses: Kas Besar, Kas Kecil dan Owner
     * @param string $id id sub kas kecil
     */
    public function skk_detail_pengajuan($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL' 
        || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();
            
            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;

            $this->model('Sub_kas_kecilModel');

            $row = empty($this->Sub_kas_kecilModel->export_detail_pengajuan($id, $tgl_awal, $tgl_akhir)) 
                ? false : $this->Sub_kas_kecilModel->export_detail_pengajuan($id, $tgl_awal, $tgl_akhir);
            if($row) {
                $skk = $this->Sub_kas_kecilModel->getById($id);

                $column = array_keys($row[0]);

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Pengajuan Sub Kas Kecil';

                $property = 'Data Histori Pengajuan SKK ('.$skk["nama"].') Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $properties['description'] = $property;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method skk_detail_mutasi
     * Export data detail mutasi sub kas kecil sesuai dengan tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di view sub kas kecil
     * Hak Akses: Kas Besar, Kas Kecil, dan Owner
     * @param string $id id sub kas kecil
     */
    public function skk_detail_mutasi($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL' 
        || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();
            
            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;

            $this->model('Sub_kas_kecilModel');

            $row = empty($this->Sub_kas_kecilModel->export_detail_mutasi($id, $tgl_awal, $tgl_akhir)) 
                ? false : $this->Sub_kas_kecilModel->export_detail_mutasi($id, $tgl_awal, $tgl_akhir);
            if($row) {
                $skk = $this->Sub_kas_kecilModel->getById($id);

                $column = array_keys($row[0]);

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Mutasi Saldo Sub Kas Kecil';

                $property = 'Data Mutasi Saldo SKK ('.$skk["nama"].') Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $properties['description'] = $property;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    // =========== END SUB KAS KECIL ============================

    // ============== PENGAJUAN SUB KAS KECIL ===================

    /**
     * Method pengajuan_sub_kas_kecil
     * Export Data Pengajuan Sub Kas Kecil beserta detailnya sesuai dengan tanggal mulai dan akhir
     * Tanggal mulai dan akhir berupa POST
     * Export khusus di list pengajuan sub kas kecil
     * Hak Akses: Kas Besar, Kas Kecil, dan Owner
     */
    public function pengajuan_sub_kas_kecil(){
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL' 
        || $_SESSION['sess_level'] === 'OWNER')) {
            $this->model('Pengajuan_sub_kas_kecilModel');

            $mainData = $properties = $detail = array();

            $tgl_awal = $_POST['tgl_awal'] ?? false;
            $tgl_akhir = $_POST['tgl_akhir'] ?? false;

            $row = $this->Pengajuan_sub_kas_kecilModel->export($tgl_awal, $tgl_akhir) ?? false;

            if ($row) {
                $column = array_keys($row[0]);

                $detailRow_detail = $this->Pengajuan_sub_kas_kecilModel->export_detail($tgl_awal, $tgl_akhir) ?? false;
                $detailColumn_detail = $detailRow_detail ? array_keys($detailRow_detail[0]) : NULL;

                $detail[0]['row'] = $detailRow_detail;
                $detail[0]['column'] = $detailColumn_detail;
                $detail[0]['sheet'] = 'Data Detail Pengajuan SKK';

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Pengajuan SKK';

                $property = 'Data Pengajuan Sub Kas Kecil '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Data Pengajuan Sub Kas Kecil '.$tgl_awal.' s.d '.$tgl_akhir;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, $detail);
                $this->excel_v2->getExcel(1, 2, true);

            } else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        } else { die(ACCESS_DENIED); }
    }

    /**
     * Method pengajuan_sub_kas_kecil_detail
     * Export data detail pengajuan sub kas kecil
     * Export khusus di view pengajuan sub kas kecil
     * Hak Akses: Kas Besar, Kas Kecil, dan Owner
     * @param string $id id pengajuan sub kas kecil
     */
    public function pengajuan_sub_kas_kecil_detail($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL' 
        || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();

            $this->model('Pengajuan_sub_kas_kecilModel');

            $row = empty($this->Pengajuan_sub_kas_kecilModel->export_detail_by_pengajuan($id)) 
                ? false : $this->Pengajuan_sub_kas_kecilModel->export_detail_by_pengajuan($id);
            if($row) {
                $column = array_keys($row[0]);

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Detail Pengajuan Sub Kas Kecil';

                $property = 'Data Detail Pengajuan Sub Kas Kecil';
                $properties['title'] = $properties['subject'] = $properties['description'] = $property;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    // =========== END PENGAJUAN SUB KAS KECIL ==================

    // ============== LAPORAN PENGAJUAN SKK ===================

    /**
     * Method laporan_pengajuan_skk
     * Export Data Laporan pengajuan sub kas kecil beserta detailnya sesuai dengan tanggal awal dan akhir
     * Tanggal mulai dan akhir berupa POST
     * Export khusus di list laporan pengajuan sub kas kecil
     * Hak Akses: Kas Besar, Kas Kecil, dan Owner
     */
    public function laporan_pengajuan_skk() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL' 
        || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $detail = $properties = array();

            $tgl_awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : false;
            $tgl_akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : false;
            
            $this->model('Laporan_sub_kas_kecilModel');
            $row = empty($this->Laporan_sub_kas_kecilModel->export($tgl_awal, $tgl_akhir)) 
                ? false : $this->Laporan_sub_kas_kecilModel->export($tgl_awal, $tgl_akhir);

            if($row) {
                $column = array_keys($row[0]);

                $detailRow = empty($this->Laporan_sub_kas_kecilModel->export_detail($tgl_awal, $tgl_akhir)) 
                    ? false : $this->Laporan_sub_kas_kecilModel->export_detail($tgl_awal, $tgl_akhir);
                $detailColumn = $detailRow ? array_keys($detailRow[0]) : NULL;

                $detail[0]['row'] = $detailRow;
                $detail[0]['column'] = $detailColumn;
                $detail[0]['sheet'] = 'Data Detail Laporan Pengajuan SKK';

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Laporan Pengajuan SKK';
                
                $property = 'Data Laporan Pengajuan SKK Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Data Laporan Pengajuan SKK Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method laporan_pengajuan_skk_detail
     * Export data detail laporan pengajuan sub kas kecil
     * Export khusus di view laporan pengajuan sub kas kecil
     * Hak Akses: Kas Besar, Kas Kecil, dan Owner
     * @param string $id id pengajuan sub kas kecil
     */
    public function laporan_pengajuan_skk_detail($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL' 
        || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();

            $this->model('Laporan_sub_kas_kecilModel');

            $row = empty($this->Laporan_sub_kas_kecilModel->export_detail($id)) 
                ? false : $this->Laporan_sub_kas_kecilModel->export_detail($id);
            if($row) {
                $column = array_keys($row[0]);

                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Detail Laporan Pengajuan SKK';

                $property = 'Data Detail Laporan Pengajuan SKK';
                $properties['title'] = $properties['subject'] = $properties['description'] = $property;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    // ============== END LAPORAN PENGAJUAN SKK ===================

    /**
     * Method kas_besar
     * Export semua data kas besar
     * Export khusus di list kas besar
     * Hak Akses: Kas Besar, dan Owner
     */
    public function kas_besar() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();
            $this->model('Kas_besarModel');

            $row = empty($this->Kas_besarModel->export()) ? false : $this->Kas_besarModel->export();
            if($row) {
                $column = array_keys($row[0]);
            
                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Kas Besar';
                
                $properties['title'] = 'Data Kas Besar';
                $properties['subject'] = 'Data Kas Besar CV. 69 Design Build';
                $properties['description'] = 'List Semua Data Kas Besar CV. 69 Design Build';

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method kas_kecil
     * Export semua data kas kecil
     * Export khusus di list kas kecil
     * Hak Akses: Kas Besar, dan Owner
     */
    public function kas_kecil() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();
            $this->model('Kas_kecilModel');

            $row = empty($this->Kas_kecilModel->export()) ? false : $this->Kas_kecilModel->export();
            if($row) {
                $column = array_keys($row[0]);
            
                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Kas Kecil';
                
                $properties['title'] = 'Data Kas Kecil';
                $properties['subject'] = 'Data Kas Kecil CV. 69 Design Build';
                $properties['description'] = 'List Semua Data Kas Kecil CV. 69 Design Build';

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method kas_kecil_detail
     * Export Semua Detail Data Kas Kecil sesuai dengan bulan dan tahun
     * Bulan dan Tahun berupa POST
     * Export khusus di view kas kecil
     * Hak Akses: Kas Besar, dan Owner
     */
    public function kas_kecil_detail($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $this->model('Kas_kecilModel');
            $this->model('Mutasi_saldo_kas_kecilModel');
            $this->model('Pengajuan_kasKecilModel');

            $mainData = $properties = $detail = array();

            $tahun = $_POST['tahun'] ?? false;
            $bulan = $_POST['bulan'] ?? false;

            $row = $this->Kas_kecilModel->export_by_id($id) ?? false;

            if ($row) {
                $kas_kecil = $this->Kas_kecilModel->getById($id);

                $column = array_keys($row[0]);

                $detailRow_mutasi = empty($this->Mutasi_saldo_kas_kecilModel->export_by_id_bulan_tahun($id, $tahun."-".$bulan."%")) 
                    ? false : $this->Mutasi_saldo_kas_kecilModel->export_by_id_bulan_tahun($id, $tahun."-".$bulan."%");
                $detailColumn_mutasi = $detailRow_mutasi ? array_keys($detailRow_mutasi[0]) : NULL;

                $detailRow_pengajuan = empty($this->Pengajuan_kasKecilModel->export_by_id_bulan_tahun($id, $tahun."-".$bulan."%")) 
                    ? false : $this->Pengajuan_kasKecilModel->export_by_id_bulan_tahun($id, $tahun."-".$bulan."%");
                $detailColumn_pengajuan = $detailRow_pengajuan ? array_keys($detailRow_pengajuan[0]) : NULL;

                $detail[0]['row'] = $detailRow_mutasi;
                $detail[0]['column'] = $detailColumn_mutasi;
                $detail[0]['sheet'] = 'Data Mutasi Kas Kecil ('.$kas_kecil['nama'].')';

                $detail[1]['row'] = $detailRow_pengajuan;
                $detail[1]['column'] = $detailColumn_pengajuan;
                $detail[1]['sheet'] = 'Data Histori Pengajuan Kas Kecil ('.$kas_kecil['nama'].')';
                
                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Kas Kecil ('.$kas_kecil['nama'].')';

                $property = 'Data Detail Kas Kecil ('.$kas_kecil['nama'].') '.$bulan.$tahun;
                $properties['title'] = $properties['subject'] = $property;
                $properties['description'] = 'List Detail Kas Kecil ('.$kas_kecil['nama'].') '.$bulan.$tahun;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, $detail);
                $this->excel_v2->getExcel(1, 2, true);
            } else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
            
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method kas_kecil_detail_mutasi
     * Export data detail mutasi saldo kas kecil sesuai tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di view kas kecil
     * Hak Akses: Kas Besar, dan Owner
     * @param string $id id kas kecil
     */
    public function kas_kecil_detail_mutasi($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $this->model('Kas_kecilModel');

            $mainData = $properties = array();
            
            $tgl_awal = $_POST['tgl_awal'] ?? false;
            $tgl_akhir = $_POST['tgl_akhir'] ?? false;
            
            $row = $this->Kas_kecilModel->export_detail_mutasi($tgl_awal, $tgl_akhir, $id) ?? false;

            if ($row) {
                $kas_kecil = $this->Kas_kecilModel->getById($id);
                $column = array_keys($row[0]);
            
                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Mutasi Kas Kecil';
                
                $properties['title'] = 'Data Mutasi Kas Kecil ('.$kas_kecil['nama'].') Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['subject'] = 'Data Mutasi Kas Kecil CV. 69 Design Build';
                $properties['description'] = 'List Mutasi Kas Kecil ('.$kas_kecil['nama'].') Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            } else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method kas_kecil_detail_pengajuan
     * Export data detail pengajuan kas kecil sesuai tanggal awal dan akhir
     * Tanggal awal dan akhir berupa POST
     * Export khusus di view kas kecil
     * Hak Akses: Kas Besar, dan Owner
     * @param string $id id kas kecil
     */
    public function kas_kecil_detail_pengajuan($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $this->model('Kas_kecilModel');

            $mainData = $properties = array();
            
            $tgl_awal = $_POST['tgl_awal'] ?? false;
            $tgl_akhir = $_POST['tgl_akhir'] ?? false;
            
            $row = $this->Kas_kecilModel->export_detail_pengajuan($tgl_awal, $tgl_akhir, $id) ?? false;

            if ($row) {
                $kas_kecil = $this->Kas_kecilModel->getById($id);
                $column = array_keys($row[0]);
            
                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data Histori Pengajuan Kas Kecil';
                
                $properties['title'] = 'Data Histori Pengajuan Kas Kecil ('.$kas_kecil['nama'].') Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;
                $properties['subject'] = 'Data Histori Pengajuan Kas Kecil CV. 69 Design Build';
                $properties['description'] = 'List Histori Pengajuan Kas Kecil ('.$kas_kecil['nama'].') Tanggal '.$tgl_awal.' s.d '.$tgl_akhir;

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            } else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method saldo_kas_kecil
     * Export data mutasi saldo kas kecil
     * Export khusus di List saldo kas kecil
     * Hak Akses: Kas Kecil yang bersangkutan
     * @param string $id id kas kecil 
     */
    public function saldo_kas_kecil($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && ($_SESSION['sess_level'] === 'KAS KECIL')) {
            if(strtolower($_SESSION['sess_id']) !== strtolower($id)) {
                die(ACCESS_DENIED);
            }
            else {

            }
        }
        else { die(ACCESS_DENIED); }
    }

    /**
     * Method user
     * Export semua data user
     * Export khusus di List User
     * Hak Akses: Kas Besar dan Owner
     */
    public function user() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        ($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER')) {
            $mainData = $properties = array();
            $this->model('UserModel');

            $row = empty($this->UserModel->export()) ? false : $this->UserModel->export();
            if($row) {
                $column = array_keys($row[0]);
            
                $mainData['row'] = $row;
                $mainData['column'] = $column;
                $mainData['sheet'] = 'Data User';
                
                $properties['title'] = 'Data User';
                $properties['subject'] = 'Data User CV. 69 Design Build';
                $properties['description'] = 'List Semua Data User CV. 69 Design Build';

                $this->excel_v2->setProperty($properties);
                $this->excel_v2->setData($mainData, NULL);
                $this->excel_v2->getExcel(1, 2, true);
            }
            else {
                $response =  array(
                    'success' => false,
                    'message' => 'Tidak ada data yang bisa di export!'
                );
                echo json_encode($response);
            }
        }
        else { die(ACCESS_DENIED); }
    }
}