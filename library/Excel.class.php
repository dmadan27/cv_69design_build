<?php
    Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

    /**
     * Masih tahap pengembangan
     */
    class Excel {
        
        private $err = "Export Data Failed.</br>Please Contact Developer For Support.";
        protected $excel;
        public $data = array();
        
        /**
         * 
         */
        public function __construct() {
            require_once ROOT.DS.'library'.DS.'PHPExcel'.DS.'PHPExcel.php';
            $this->excel = new PHPExcel(); 
        }

        /**
         * 
         */
        public function setData($dataHeader, $dataRow) {
            $this->data['header'] = $dataHeader;
            $this->data['row'] = $dataRow;
        }

        /**
         * 
         */
        public function setProperty($title, $subject, $description) {
            $this->excel->getProperties()
                ->setCreator('CV. 69 DESIGN BUILD')
                ->setTitle($title)
                ->setSubject($subject)
                ->setDescription($description);
        }

        /**
         * 
         */
        public function getData($title_sheet, $title_header, $start_column_header, $start_row_data, $sheet_index = 0, $numbering = false) {

            // validasi data
            if ($title_sheet == null) die($this->err);
            if ($title_header == null) die($this->err);
            if ($start_column_header == null) die($this->err);
            if ($start_row_data == null) die($this->err);
            
            // membuat sheet baru jika belum dibuat
            if ($sheet_index >= $this->excel->getSheetCount()) $this->excel->createSheet();
            
            // set active sheet
            $this->excel->setActiveSheetIndex($sheet_index);

            // set title sheet
            $this->excel->getActiveSheet()->setTitle($title_sheet);

            // set title header
            $this->excel->getActiveSheet()->setCellValue('A1', "Tanggal Ekspor: ".date("d/m/Y"));
            $this->excel->getActiveSheet()->setCellValue('A2', $title_header);
			$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A1
			$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
            $this->excel->getActiveSheet()->getStyle('A2')->getAlignment();
            // ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
            
            $column = 'A';
            $numRow = $start_row_data;

            // set header data
            foreach ($this->data as $key => $value) {               
                if($key == "header") {
                    // set header
                    if ($numbering) {
                        if (count($value) <= 0) $this->excel->getActiveSheet()->setCellValue($column.$start_column_header, "TIDAK ADA DATA");
                        else {
                            $this->excel->getActiveSheet()->setCellValue($column.$start_column_header, "NO");
                            $column = 'B';
                        }
                    }
                    foreach($value as $header) {
                        $this->excel->getActiveSheet()->setCellValue($column.$start_column_header, $header);
                        $column++;
                    }
                    $column = 'A';
                }
                // set data row
                else if($key == "row") {
                    // set data
                    $number = 1;
                    foreach($value as $row) {
                        if ($numbering) {
                            $this->excel->getActiveSheet()->setCellValue($column.$numRow, $number);
                            $column = 'B';
                        }
                    	foreach($row as $valueRow){
                    		$this->excel->getActiveSheet()->setCellValue($column.$numRow, $valueRow);
                            $column++;	
                    	}
                        $numRow++;
                        $number++;
	                    $column = 'A';
                    }
                    $column = 'A';
                }
            }
        }

        /**
         * 
         */
        public function setContent($data) {
            if ($data == null) die($this->err);

            foreach ($data as $key => $value) {

                // validasi data
                if (!array_key_exists('table_header', $value)) die($this->err);
                if (!array_key_exists('table_content', $value)) die($this->err);
                if (!array_key_exists('sheet_name', $value)) die($this->err);
                if (!array_key_exists('title', $value)) die($this->err);
                if (!array_key_exists('table_row_start', $value)) die($this->err);
                if (!array_key_exists('enable_numbering', $value)) die($this->err);

                $this->setData($value['table_header'], $value['table_content']);
                $this->getData($value['sheet_name'], $value['title'], $value['table_row_start'], $value['table_row_start']+1, $key, $value['enable_numbering']);
            }
        }

        /**
         * 
         */
        public function getExcel($title) {
            // Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'.$title.'".xlsx"'); // Set nama file excel nya
			header('Cache-Control: max-age=0');

            $write = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            ob_end_clean();
			$write->save('php://output');
			exit;
        }
    }