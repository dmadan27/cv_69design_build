<?php
    Defined("BASE_PATH") or die(ACCESS_DENIED);

    /**
     * Class Excel_v2
     * Masih tahap pengembangan
     */
    class Excel_v2 {
        
        private $excel;
        public $property = array();
        public $data = array();
        
        /**
         * Method __construct
		 * Load library PHPExcel
         */
        public function __construct() {
            require_once ROOT.DS.'app'.DS.'library'.DS.'PHPExcel'.DS.'PHPExcel.php';
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
        private function getData($start_column_header, $start_row_data) {
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
            $main_data = $this->data['main'];
            foreach ($main_data as $key => $value) {               
                if($key == "column") {
                    // set header
                    foreach($value as $header) {
                        $this->excel->setActiveSheetIndex(0)->setCellValue($column.$start_column_header, $header);
                        $column++;
                    }
                    $column = 'A';
                }
                // set data row
                else if($key == "row") {
                    // set data
                    foreach($value as $row) {
                    	foreach($row as $valueRow){
                            $this->excel->setActiveSheetIndex(0)->setCellValue($column.$numRow, $valueRow);
                            $this->excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
                    		$column++;	
                    	}
                    	$numRow++;
	                    $column = 'A';
                    }
                    $column = 'A';
                }
            }

            // set detail data
            $detail_data = (empty($this->data['detail']) || $this->data['detail'] === NULL) ? false : $this->data['detail'];
            if($detail_data) {
                $i = 1;
                $numRow = $start_row_data;
                foreach ($detail_data['data'] as $item) {
                    
                    $sheetDetail = $this->excel->createSheet($i);
                    $this->excel->setActiveSheetIndex($i);

                    foreach($item as $key => $row) {

                        // echo '<pre>';
                        // var_dump($key);
                        // echo '</pre>';

                        if($key == "column") {
                            // set header
                            foreach($row as $header) {
                                $sheetDetail->setCellValue($column.$start_column_header, $header);
                                $column++;
                            }
                            $column = 'A';
                        }
                        // set data row
                        else if($key == "row") {
                            // set data
                            foreach($row as $rows) {
                                foreach($rows as $valueRow){
                                    $sheetDetail->setCellValue($column.$numRow, $valueRow);
                                    $this->excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
                                    $column++;	
                                }
                                $numRow++;
                                $column = 'A';
                            }
                            $column = 'A';
                        }
                    }

                    $sheetDetail->setTitle($detail_data['data'][$i-1]['sheet']);
                    $i++;
                }   
            }

            // echo '<pre>';
            // var_dump($detail_data);
            // echo '</pre>';
        }

        /**
         * Method getExcel
         * @param start_column_header {integer}
         * @param start_row_data {integer}
         */
        public function getExcel($start_column_header, $start_row_data) {
            // render data ke excel
            $this->getData($start_column_header, $start_row_data);
            $filename = $this->property['title']."_".date('d-m-Y').".xlsx";
            
            // Proses pembentukan file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'.$filename.'"'); // Set nama file excel nya
			header('Cache-Control: max-age=0');

            ob_start();
            $write = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $write->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                'success' => true,
                'filename' => $filename,
                'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($xlsData)
            );
            die(json_encode($response));
        }
    }