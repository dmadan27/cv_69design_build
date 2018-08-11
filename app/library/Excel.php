<?php
    Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

    /**
     * 
     */
    class Excel {
        protected $excel;
        protected $data = array();
        
        /**
         * 
         */
        public function __construct() {
            require_once ROOT.DS.'app'.DS.'library'.DS.'PHPExcel'.DS.'PHPExcel.php';
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
        public function setTitle($title) {
            
        }

        /**
         * 
         */
        public function getData($title, $title_header, $start_column_header, $start_row_data) {
            // set title file
            $this->excel->getActiveSheet(0)->setTitle($title);

            // set title header
            $this->excel->setActiveSheetIndex(0)->setCellValue('A1', $judul);
			$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
			$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
            $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
            
            $column = 'A';
            $start_column_header;
            $no = 1;
            $numRow = $start_row_data;

            // set header data
            foreach ($this->data as $key => $value) {               
                if($key == "header") {
                    // set header
                    foreach($value['header'] as $header) {
                        $this->excel->setActiveSheetIndex(0)->setCellValue($column.$start_column_header, $header);
                        $column++;
              
                    }
                    $column = 'A';
                }
                // set data row
                else if($key == "row") {
                    // set data
                    foreach($value['row'] as $row) {
                    	foreach($row as $valueRow){
                    		$this->excel->setActiveSheetIndex(0)->setCellValue($column.$numRow, $tempRow);
                    		$column++;	
                    	}
                    	$numRow++;
	                    $column = 'A';
                    }
                    $column = 'A';
                }
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
			$write->save('php://output');
        }
    }