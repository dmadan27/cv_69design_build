
/*
	UPDATE DATA

	Description : Updata Data untuk 

		a. Set Saldo Kas Kecil = 0 
		b. Set Saldo Sub Kas Kecil = 0
*/

	UPDATE kas_kecil set saldo = 0
		 where id
		 	 in 
		 	 	(
		 	 		'KK001',
					'KK002',
					'KK003',
					'KK004',
					'KK005'
		 	 	)

	UPDATE sub_kas_kecil set saldo = 0
	 where id
	 	 in 
	 	 	(
	 	 		'SKK001',
				'SKK002',
				'SKK003',
				'SKK004',
				'SKK005'
	 	 	)

