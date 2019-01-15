$(document).ready(function(){
	var url = $(location).attr("href").split('/');
	var urlNew = [];
	$.each(url, function(i, item) {
		if(item != "localhost") {
			urlNew.push(item);
		}
	});

	switch(urlNew[3].toLowerCase()){
		// menu proyek
		case 'proyek':
			$('.menu-proyek').addClass('active');
			break;

		// menu bank
		case 'bank':
			$('.menu-bank').addClass('active');
			break;
		
		// menu pengajuan kas kecil
		case 'pengajuan-kas-kecil':
			$('.menu-pengajuan-kas-kecil').addClass('active');
			break;
		
		// menu operasional proyek
		case 'operasional-proyek':
			$('.menu-operasional-proyek').addClass('active');
			break;

		// menu distributor
		case 'distributor':
			$('.menu-distributor').addClass('active');
			break;
		
		// menu operasional
		case 'operasional':
			$('.menu-operasional').addClass('active');
			break;
		
		// menu kas besar
		case 'kas-besar':
			$('.menu-kas-besar').addClass('active');
			break;

		// menu kas kecil
		case 'kas-kecil':
			$('.menu-kas-kecil').addClass('active');
			break;
		
		// menu sub kas kecil
		case 'sub-kas-kecil':
			$('.menu-sub-kas-kecil').addClass('active');
			break;
		
		// menu user
		case 'user':
			$('.menu-user').addClass('active');
			break;
		
		// menu mutasi sub kas kecil
		case 'saldo-kas-kecil':
			$('.menu-saldo-kas-kecil').addClass('active');
			break;

		// menu pengajuan sub kas kecil
		case 'pengajuan-sub-kas-kecil':
			$('.menu-pengajuan-sub-kas-kecil').addClass('active');
			if(urlNew[4] == 'laporan') $('.menu-laporan').addClass('active');
			else $('.menu-pengajuan').addClass('active');
			break;		
		
		default:
			$('.menu-beranda').addClass('active');
			break;
	}
});