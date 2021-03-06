<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * Class validation untuk validasi field-field form yang dikirim ke server
 * dari menentukan rule, jenis, max-min, dan filter input
 */
class Validation {
	
	private $rule = array();
	private $error = array();
	private static $message = array(
		'empty' => array(
			'indonesia' => ' Harus Diisi',
			'english' => ' Must be filled'
		),
		'string' => array(
			'indonesia' => ' Harus Berupa Huruf, atau Angka, atau Beberapa Karakter Simbol',
			'english' => ' Must be word, or number, or any standar symbol'
		),
		'word' => array(
			'indonesia' => '',
			'english' => ''
		),
		'number' => array(
			'indonesia' => '',
			'english' => ''
		),
		'value' => array(
			'indonesia' => '',
			'english' => ''
		),
		'email' => array(
			'indonesia' => ' Tidak sesuai',
			'english' => ''
		),
		'file' => array(
			'indonesia' => '',
			'english' => ''
		)

	);
	private $value = array();
	private $language = 'indonesia';

	/**
	 * Method set_rules 
	 * Fungsi untuk menambahkan rules
	 * @param field {string} menampung nilai dari variabel yg akan di cek
	 * @param label {string} pemberian nama untuk pesan error
	 * @param var {string} pemberian nama untuk get datanya
	 * @param rule {string} setting rule yang akan diberikan 
	 * 		contoh rule: 'jenis | min | max | required/not_required'
	 * 			jenis: string, huruf, angka, nilai, dan email
	 * 			min, max berupa int
	 * 			required: wajib diisi, not_required: tidak wajib
	 */
	public function set_rules($field, $label, $var, $rule){
		$this->rule[] = array(
			'field' => $field,
			'label' => $label,
			'var' => $var,
			'rule' => $rule,
		);
	}

	/**
	 * Method run
	 * Fungsi run, untuk menjalankan semua pengecekan validasi yang sebelumnya sudah di set
	 * @return result {array}
	 */
	public function run(){
		$cek = true;

		foreach($this->rule as $keyIndex => $rule){
			foreach($rule as $keyRule => $value){
				if($keyRule == "field") $field = $value;
				if($keyRule == "label") $label = $value;
				if($keyRule == "var") $var = $value;
				if($keyRule == "rule"){
					// explode rule
					$rule = explode("|", $value);
					$rule = array_map('trim', $rule);

					$jenis = strtolower($rule[0]);
					$min = $rule[1];
					$max = $rule[2];
					$required = (strtolower($rule[3]) == 'required') ? true : false;
					$this->error[$var] = "";
					$this->value[$var] = $field;

					$dataValidasi = array(
						'field' => $field,
						'label' => $label,
						'min' => $min,
						'max' => $max,
						'required' => $required,
					);

					$setValidasi = $this->set_validasi($jenis, $dataValidasi); 
					if(!$setValidasi['cek']){
						$cek = false;
						$this->error[$var] = $setValidasi['error'];
					}

					$output = array(
						'cek' => $cek,
						'error' => $this->error,
						'value' => $this->value,
					);
				} 	
			}
		}

		return $output;
	}

	/**
	 * Method set_validasi
	 * fungsi pendukung untuk mengarahkan validasi sesuai dengan jenis yg di tentukan
	 * @param jenis {string} string, huruf, angka, nilai, dan email
	 * @param data {array} berupa array yg isinya field, label, min, max, dan required
	 * @return result {array}
	 */
	public function set_validasi($jenis, $data){
		// arahkan validasi sesuai dengan jenisnya
		switch ($jenis) {
			case 'string':
				// cek required
				if($data['required']){ // jika data kosong
					if(empty($data['field']) || $data['field'] == "") // jika data kosong
						$output = array('cek' => false, 'error' => $data['label']." Harus Diisi");
					else $output = $this->valid_string($data);
				}
				else{ // jika opsional
					if(!empty($data['field']) || $data['field'] != "") // jika diisi
						$output = $this->valid_string($data);
					else 
						$output = array('cek' => true, 'error' => ""); // jika dikosongkan
				}
				break;

			case 'huruf':
				// cek required
				if($data['required']){
					if(empty($data['field']) || $data['field'] == "") // jika data kosong
						$output = array('cek' => false, 'error' => $data['label']." Harus Diisi");
					else $output =$this->valid_huruf($data);
				}
				else{ // jika opsional
					if(!empty($data['field']) || $data['field'] != "") // jika diisi
						$output = $this->valid_huruf($data);
					else 
						$output = array('cek' => true, 'error' => ""); // jika dikosongkan
				}
				break;

			case 'angka':
				// cek required
				if($data['required']){
					if(empty($data['field']) || $data['field'] == "") // jika data kosong
						$output = array('cek' => false, 'error' => $data['label']." Harus Diisi");
					else $output = $this->valid_angka($data);
				}
				else{ // jika opsional
					if(!empty($data['field']) || $data['field'] != "") // jika diisi
						$output = $this->valid_angka($data);
					else 
						$output = array('cek' => true, 'error' => ""); // jika dikosongkan
				}
				break;

			case 'nilai':
				// cek required
				if($data['required']){
					if($data['field'] == "") // jika data kosong
						$output = array('cek' => false, 'error' => $data['label']." Harus Diisi");
					else $output = $this->valid_nilai($data);
				}
				else{ // jika opsional
					if($data['field'] != "") // jika disii
						$output = $this->valid_nilai($data);
					else 
						$output = array('cek' => true, 'error' => ""); // jika dikosongkan
				}
				break;

			case 'email':
				// cek required
				if($data['required']){
					if(empty($data['field']) || $data['field'] == "") // jika data kosong
						$output = array('cek' => false, 'error' => $data['label']." Harus Diisi");
					else $output = $this->valid_email($data);
				}
				else{ // jika opsional
					if(!empty($data['field']) || $data['field'] != "") // jika diisi
						$output = $this->valid_email($data);
					else 
						$output = array('cek' => true, 'error' => ""); // jika dikosongkan
				}
				break;
			
			default:
				die();
				break;
		}

		return $output;
	}

	/**
	 * Method valid_string
	 * Fungsi validasi untuk string
	 * mengecek alphanumeric dan beberapa karakter yang diijinkan
	 * @param data {array}
	 * @return result {array}
	 */
	private function valid_string($data){
		$cek = true;
		$pattern = "/^[a-zA-Z0-9-_,.@#%^*+=<>(){}!?&' \/\r\n]*$/";

		// cek pattern
		if(!preg_match($pattern, $data['field'])){
			$cek = false;
			$error = $data['label']." Harus Berupa Huruf, atau Angka, atau Beberapa Karakter Simbol";
		}
		else{
			// cek min - max
			if( (strlen($data['field']) >= $data['min']) 
				&& (strlen($data['field']) <= $data['max']) ) $error = "";
			else{
				$cek = false;
				$error = "Panjang ".$data['label']." Min. ".$data['min']." dan Maks. ".$data['max']." Karakter";
			} 
				
		}
		
		return $output = array('cek' => $cek, 'error' => $error);
	}

	/**
	 * Method valid_huruf
	 * Fungsi validasi untuk huruf
	 * hanya mengecek huruf
	 * @param data {array}
	 * @return result {array}
	 */
	private function valid_huruf($data){
		$cek = true;
		$pattern = "/^[a-zA-Z\s]*$/";

		// cek pattern
		if(!preg_match($pattern, $data['field'])){
			$cek = false;
			$error = $data['label']." Harus Berupa Huruf";
		}
		else{
			// cek min - max
			if( (strlen($data['field']) >= $data['min']) 
				&& (strlen($data['field']) <= $data['max']) ) $error = "";
			else{
				$cek = false;
				$error = "Panjang ".$data['label']." Min. ".$data['min']." dan Maks. ".$data['max']." Karakter";
			} 
				
		}
		
		return $output = array('cek' => $cek, 'error' => $error);
	}

	/**
	 * Method valid_angka
	 * Fungsi validasi untuk angka
	 * hanya mengecek angka, yang di cek hanya angkanya saja, bukan nilainya
	 * @param data {array}
	 * @return result {array}
	 */
	private function valid_angka($data){
		$cek = true;
		$pattern = "/^[0-9]*$/";

		// cek pattern
		if(!preg_match($pattern, $data['field'])){
			$cek = false;
			$error = $data['label']." Harus Angka";
		}
		else{
			// cek min - max
			if( (strlen($data['field']) >= $data['min']) 
				&& (strlen($data['field']) <= $data['max']) ) $error = "";
			else{
				$cek = false;
				$error = "Panjang ".$data['label']." Min. ".$data['min']." dan Maks. ".$data['max'];
			} 
				
		}
		
		return $output = array('cek' => $cek, 'error' => $error);

	}

	/**
	 * Method valid_nilai
	 * Fungsi validasi untuk nilai
	 * hanya mengecek angka, dan nilainya yg dicek
	 * @param data {array}
	 * @return result {array}
	 */
	private function valid_nilai($data){
		$cek = true;
		$pattern = "/^[0-9.]*$/";

		// cek pattern
		if(!preg_match($pattern, $data['field'])){
			$cek = false;
			$error = $data['label']." Harus Berupa Angka";
		}
		else{
			// cek min - max
			if( ($data['field'] >= $data['min']) 
				&& ($data['field'] <= $data['max']) ) $error = "";
			else{
				$cek = false;
				$error = "Nilai ".$data['label']." Min. ".$data['min']." dan Maks. ".$data['max'];
			} 
				
		}
		
		return $output = array('cek' => $cek, 'error' => $error);
	}

	/**
	 * Method vaid_email
	 * Fungsi validasi untuk email
	 * @param data {array}
	 * @return result {array}
	 */
	private function valid_email($data){
		$cek = true;

		// cek pattern
		if(!filter_var($data['field'], FILTER_VALIDATE_EMAIL)){
			$cek = false;
			$error = "Format ".$data['label']." Tidak Sesuai";
		}
		else{
			// cek min - max
			if( (strlen($data['field']) >= $data['min']) 
				&& (strlen($data['field']) <= $data['max']) ) $error = "";
			else{
				$cek = false;
				$error = "Panjang ".$data['label']." Min. ".$data['min']." dan Maks. ".$data['max']." Karakter";
			} 
				
		}
		
		return $output = array('cek' => $cek, 'error' => $error);
	}

	/**
	 * Method validFile
	 * Fungsi validasi untuk file secara satuan
	 * @param config {array}
	 * 		$configFile = array(
	 * 			'jenis' => (gambar, office, pdf, compress),
	 * 			'error' => error,
	 * 			'size' => size,
	 * 			'tmp_name' => 'tmp_name',
	 * 			'max' => max
	 * 		);
	 * @return result {array}
	 */
	public function validFile($configFile){
		$jenisFile = $configFile['jenis'];
		$errorFile = $configFile['error'];
		$sizeFile = $configFile['size'];
		$tmp_nameFile = $configFile['tmp_name'];
		$max = $configFile['max'];

		// set array jenis file
		switch ($jenisFile) {
			case 'gambar':
				$arrayFile = array(
					'jpg' => 'image/jpeg',
					'png' => 'image/png',
					'gif' => 'image/gif',
				);
				break;

			case 'office':
				$arrayFile = array(
					'word' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
					'excel' => 'application/vnd.ms-excel',
				);
				break;

			case 'pdf':
				$arrayFile = array('pdf' => 'application/pdf');
				break;

			case 'compress':
				$arrayFile = array(
					'zip' => 'application/zip',
					'rar' => 'application/x-rar-compressed',
				);
				break;
			
			default:
				die();
				break;
		}

		// cek error value
		switch ($errorFile) {
			case UPLOAD_ERR_OK:
				$cekValid['error'] = "";
				break;
			
			case UPLOAD_ERR_NO_FILE:
				$output = array(
					'cek' => false,
					'error' => "Upload Gagal, Tidak Ada File Yang Terupload",
				);

				return $output;
				break;

			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				$output = array(
					'cek' => false,
					'error' => "Upload Gagal, Ukuran File Tidak Sesuai",
				);

				return $output;
				break;

			default:
				$output = array(
					'cek' => false,
					'error' => "Upload Gagal, Error Tidak Diketahui",
				);

				return $output;
				break;
		}

		// cek ukuran file
		if($sizeFile > $max){
			$output = array(
				'cek' => false,
				'error' => "Upload Gagal, Ukuran File Tidak Sesuai",
			);

			return $output;
		}

		// cek mime type file
		$fInfo = new finfo(FILEINFO_MIME_TYPE);
		if(false === $ext = array_search($fInfo->file($tmp_nameFile), $arrayFile, true)){
			$output = array(
				'cek' => false,
				'error' => "Upload Gagal, Format File Tidak Sesuai",
			);

			return $output;
		}

		// ganti nama file
		$namaFileBaru = sprintf('%s.%s', sha1_file($tmp_nameFile), $ext);

		$output = array(
			'cek' => true,
			'error' => "",
			'namaFile' => $namaFileBaru,
		);

		return $output;
	}

	/**
	 * Method validInput
	 * Proses validasi inputan
	 * @param data {string}
	 * @param upper {boolean} default true
	 * @return result {string}
	 */
	public function validInput($data, $upper = true){
		$data = trim($data); // trim input
		$data = stripslashes($data); // hilangkan strip slash
		$data = htmlspecialchars($data); // hilangkan special char
		$data = ($upper) ? strtoupper($data) : $data; // cek di upper / tidak

		return $data;
	}

}