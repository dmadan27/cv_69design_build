<?php
    Defined("BASE_PATH") or die(ACCESS_DENIED);

    /**
     * Class Excel_v2
     * Masih tahap pengembangan
     */
    class Excel_v2 {
        
        private $excel;
        private $err = "Export Data Failed.</br>Please Contact Developer For Support.";
        public $property = array();
        public $data = array();
        public $json = true;
        
        /**
         * Method __construct
		 * Load library PHPExcel
         */
        public function __construct() {
            require_once ROOT.DS.'library'.DS.'PHPExcel'.DS.'PHPExcel.php';
            $this->excel = new PHPExcel(); 
        }

        /**
         * Method setData
         * Proses set header (column) dan data (baris) di excel
         * @param data {array} Main Data
         *      array(
         *          titleHeader => $data['title_header'],
         *          column => $data['column'],
         *          row => $data['row],
         *          sheet => $data['sheet]
         *      )
         * @param detail {array} Detail Data
         *      array(
         *          count => count($detail),
         *          data => array(
         *              array(
         *                  titleHeader => $data['title_header'],
         *                  column => $detail[0]['column'],
         *                  row => $detail[0]['row'],
         *                  sheet => $detail[0]['sheet']
         *              ),
         *              ....
         *          )
         *      )
         */
        public function setData($data, $detail = NULL) {
            $this->data = array(
                'main' => array(
                    'column' => $data['column'],
                    'row' => $data['row'],
                    'sheet' => $data['sheet']
                )
            );

            if($detail === NULL) { $this->data['detail'] = $detail; }
            else if(is_array($detail)){
                $this->data['detail'] = array(
                    'count' => count($detail),
                    'data' => $detail
                );
            }
        }

        /**
         * Method setProperty
         * @param property {array}
         *      array(
         *          title => {string}
         *          subject => {string}
         *          description => {string}
         *      )
         */
        public function setProperty($property) {
            $this->property = $property;
        }

        /**
         * 
         */
        private function getData($start_column_header, $start_row_data, $numbering) {
            $main_sheet = $this->data['main']['sheet'];

            // set property
            $this->excel->getProperties()
                ->setCreator('CV. 69 DESIGN BUILD')
                ->setTitle($this->property['title'])
                ->setSubject($this->property['subject'])
                ->setDescription($this->property['description']);
            
            // set title sheet awal
            $this->excel->getActiveSheet(0)->setTitle($main_sheet);
            
            $column = 'A';
            $no = 1;
            $numRow = $start_row_data;

            // set main data
            $main_data = (empty($this->data['main'])) ? false : $this->data['main'];
            if($main_data) {
                foreach ($main_data as $key => $value) {               
                    if($key == "column") {
                        if($numbering) {
                            $this->excel->getActiveSheet()->getStyle($column.$start_column_header)->getFont()->setBold(TRUE);
                            $this->excel->getActiveSheet()->setCellValue($column.$start_column_header, "NO");
                            $column = 'B';
                        }
    
                        // set header
                        foreach($value as $header) {
                            $this->excel->setActiveSheetIndex(0)->setCellValue($column.$start_column_header, $header);
                            $this->excel->setActiveSheetIndex(0)->getStyle($column.$start_column_header)->getFont()->setBold(TRUE);
                            $column++;
                        }
                        $column = 'A';
                    }
                    // set data row
                    else if($key == "row") {
                        // set data
                        $number = 1;
                        foreach($value as $row) {
                            if($numbering) {
                                $this->excel->getActiveSheet()->setCellValue($column.$numRow, $number);
                                $column = 'B';
                            }
                            foreach($row as $valueRow){
                                $this->excel->setActiveSheetIndex(0)->setCellValue($column.$numRow, $valueRow);
                                $this->excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
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

            // set detail data
            $detail_data = (empty($this->data['detail'])) ? false : $this->data['detail'];
            if($detail_data) {
                $i = 1;
                foreach ($detail_data['data'] as $item) {
                    $numRow = $start_row_data;
                    $sheetDetail = $this->excel->createSheet($i);
                    $this->excel->setActiveSheetIndex($i);

                    foreach($item as $key => $row) {
                        if($key == "column") {
                            // set header
                            if(!empty($row)) {
                                if($numbering) {
                                    $this->excel->getActiveSheet()->getStyle($column.$start_column_header)->getFont()->setBold(TRUE);
                                    $this->excel->getActiveSheet()->setCellValue($column.$start_column_header, "NO");
                                    $column = 'B';
                                }
                                foreach($row as $header) {
                                    $sheetDetail->setCellValue($column.$start_column_header, $header);
                                    $sheetDetail->getStyle($column.$start_column_header)->getFont()->setBold(TRUE);
                                    $column++;
                                }
                                $column = 'A';
                            }
                            else {
                                $this->excel->getActiveSheet()->setCellValue($column.$start_column_header, "TIDAK ADA DATA");
                            }
                        }
                        // set data row
                        else if($key == "row") {
                            // set data
                            if($row) {
                                $number = 1;
                                foreach($row as $rows) {
                                    if($numbering) {
                                        $this->excel->getActiveSheet()->setCellValue($column.$numRow, $number);
                                        $column = 'B';
                                    }
                                    foreach($rows as $valueRow){
                                        $sheetDetail->setCellValue($column.$numRow, $valueRow);
                                        $this->excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
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

                    $sheetDetail->setTitle($detail_data['data'][$i-1]['sheet']);
                    $i++;
                }   
            }
        }

        /**
         * Method getExcel
         * @param start_column_header {integer}
         * @param start_row_data {integer}
         */
        public function getExcel($start_column_header, $start_row_data, $numbering = false) {
            $filename = $this->property['title']."_(".date('d-m-Y').").xlsx";

            try {
                // render data ke excel
                $this->getData($start_column_header, $start_row_data, $numbering);
                if($this->json) {
                    header('Content-Type: application/json');
                }
                else {
                    // Proses pembentukan file excel
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment; filename="'.$filename.'"'); // Set nama file excel nya
                    header('Cache-Control: max-age=0');
                }
                
                ob_start();
                $write = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                $write->save('php://output');
                $xlsData = ob_get_contents();
                ob_end_clean();
                
                $success = true;
            } catch (Exception $e) {
                $success = false;
                $message = 'Error: '.$e->getMessage();
            }
            
            if($this->json) {
                if($success) {
                    $response =  array(
                        'success' => $success,
                        'filename' => $filename,
                        'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($xlsData)
                    );
                }
                else {
                    $response =  array(
                        'success' => $success,
                        'message' => $message
                    );
                }

                die(json_encode($response));
            }

            exit;
        }
    }