<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* Class helper, berisi function-function pembantu
	*/
	class Helper{
		
		/**
		* Fungsi cetak angka dengan format rupiah
		* contoh: Rp. 1.590.850,00
		*/	
		public function cetakRupiah($value){
			$rupiah = 'Rp '.number_format($value, 2, ',', '.');
			return $rupiah;
		}

		/**
		* Fungsi cetak angka dengan format standar
		* contoh: 1.000,00, 10.500,00
		*/
		public function cetakAngka($value){
			$angka = number_format($value, 2, ',', '.');
			return $angka;
		}

		/**
		* Fungsi cetak tgl sesuai dengan format yang di inginkan
		* param $tgl harus berformat 'yyyy-mm-dd'
		* param $format : 
		* 'dd-mm-yyyy' (27-02-1995), 
		* 'yyyy-mm-dd' (2018-01-01) format default, 
		* 'd-m-y' (27 Februari 2018), 
		* 'yyyymmdd' (20180101), 
		* 'full (Senin, 27 Februari 1995)'
		*/
		public function cetakTgl($tgl, $format = 'yyyy-mm-dd'){
			//array hari
			$arrHari = array(
				1 => "Senin",
				2 => "Selasa",
				3 => "Rabu",
				4 => "Kamis",
				5 => "Jumat",
				6 => "Sabtu",
				7 => "Minggu",
			);

			// array bulan
			$arrBulan = array(
				1 => "Januari",
				2 => "Februari",
				3 => "Maret",
				4 => "April",
				5 => "Mei",
				6 => "Juni",
				7 => "Juli",
				8 => "Agustus",
				9 => "September",
				10 => "Oktober",
				11 => "November",
				12 => "Desember",
			);

			// explode tgl
			$tempTgl = explode('-', $tgl);
			$getTgl = $tempTgl[2];
			$getBulan = $tempTgl[1];
			$getTahun = $tempTgl[0];

			// format tgl
			switch ($format) {
				case 'dd-mm-yyyy':
					$cetak = $getTgl.'-'.$getBulan.'-'.$getTahun;
					break;
				
				case 'd-m-y':
					$cetak = $getTgl.' '.$arrBulan[(int)$getBulan].'-'.$getTahun;
					break;

				case 'yyyymmdd':
					$cetak = $getTahun.$getBulan.$getTgl;
					break;

				case 'full':
					$cetak = $arrHari[date('N', strtotime($tgl))].', '.$getTgl.' '.$arrBulan[(int)$getBulan].' '.$getTahun;
					break;

				default: // yyyy-mm-dd
					$cetak = $getTahun.'-'.$getBulan.'-'.$getTgl;
					break;
			}

			return $cetak;
		}

		/**
		* Fungsi mengganti data yang kosong menjadi '-' (garis strip)
		*/
		public function setKosong($data){
			$temp = ($data == "" || empty($data)) ? "-" : $data;
			return $temp;
		}

		/**
		*
		*/
		public function cekArray($data){
			
		}

		/**
		*
		*/
		public function reArrayFiles($file_post) {
		    $file_ary = array();
		    $file_count = count($file_post['name']);
		    $file_keys = array_keys($file_post);

		    for ($i=0; $i<$file_count; $i++) {
		        foreach ($file_keys as $key) {
		            $file_ary[$i][$key] = $file_post[$key][$i];
		        }
		    }

		    return $file_ary;
		}

	}