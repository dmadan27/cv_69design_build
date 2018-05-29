$(document).ready(function () {
	generateID();
	setStatus();
	setStatusDetail();
	
	$('#submit_proyek').prop('value', 'action-add');
	$('#id').prop('disabled', true);
    //Initialize Select2 Elements
    $('#skc').select2(); 
    setSkc();

 	//Date picker
    $('.datepicker').datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      todayHighlight: true,
      orientation:"bottom auto",
      todayBtn: true,
    });
 	
    // tambah detail
    $('#tambah_detail').on('click', function(){
    	console.log(listDetail);
    	resetModal();
    	$('#modalDetail').modal();
    });

    // tambah skc
	$('#tambah_skc').on('click', function(){
    	console.log(listSkc);
    	addSkc();
    });    

    $('#form_detail').submit(function(e){
    	e.preventDefault();
    	addDetail();

    	return false;
    });

    // Submit Proyek
    $('#form_proyek').submit(function(e){
    	e.preventDefault();
    	submit();

    	return false;
    });

    // on change field
    $('.field').on('change', function(){
    	if(this.value !== ""){
    		$('.field-'+this.id).removeClass('has-error').addClass('has-success');
    		$(".pesan-"+this.id).text('');
    	}
    	else{
    		$('.field-'+this.id).removeClass('has-error').removeClass('has-success');
    		$(".pesan-"+this.id).text('');
    	}
    });
 });

// ================ Function detail proyek =================== //

	/**
	*
	*/
	function addDetail(){
		var index = indexDetail++;

		var persentase = parseFloat($('#persentase').val().trim()) ? parseFloat($('#persentase').val().trim()) : $('#persentase').val().trim();
		var total_detail = parseFloat($('#total_detail').val().trim()) ? parseFloat($('#total_detail').val().trim()) : $('#total_detail').val().trim();

		var data = {
			index: index,
			angsuran: $('#angsuran').val().trim(),
			persentase: persentase,
			total_detail: total_detail,
			status_detail: $('#status_detail').val().trim(),
		};

		validDetail(data);

		console.log('Index : '+index);
		console.log('Index Utama: '+indexDetail);
	}

	/**
	*
	*/
	function validDetail(data){
		$.ajax({
			url: BASE_URL+'proyek/action-add-detail/',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function(){

			},
			success: function(output){
				console.log(output);
				if(output.status){
					// tambah data ke list
					listDetail.push(data);

					// '<input type="text" class="form-control" value="'+data.angsuran+'" onchange="onChange_angsuran('+index+', this)">'
					// '<div class="input-group"><input type="number" min="0" step="any" onchange="onChange_persentase('+data.index+',this)" class="form-control input-sm" value="'+data.persentase+'"><span class="input-group-addon">%</span></div>'

					var status = function(value){
						return (data.status_detail.toLowerCase() == value.toLowerCase()) ? 'selected' : ''
					};

					// tambah data ke tabel
					$('#detail_proyekTable > tbody:last-child').append(
						'<tr>'+
							'<td></td>'+ // no
							'<td>' // angsuran
								+'<input type="text" class="form-control input-sm" value="'+data.angsuran+'" '+
								'onchange="onChange_angsuran('+data.index+', this)"></td>'+
							'<td style="width: 150px;">'+
								'<div class="input-group">'+
									'<input type="number" min="0" max="100" step="any" onchange="onChange_persentase('+data.index+',this)" '+
									'class="form-control input-sm" value="'+data.persentase+'">'+
									'<span class="input-group-addon">%</span>'+
								'</div></td>'+ // persentase
							'<td>'+
								'<div class="input-group">'+
									'<span class="input-group-addon">Rp</span>'+
									'<input type="number" min="0" step="any" onchange="onChange_total('+data.index+',this)" '+
									'class="form-control input-sm" value="'+data.total_detail+'">'+
								'</div></td>'+ // total
							'<td>'+
								'<select onchange="onChange_status('+data.index+', this)" class="form-control input-sm">'+
									'<option '+status('BELUM DIBAYAR')+'>BELUM DIBAYAR</option>'+
									'<option '+status('LUNAS')+'>LUNAS</option>'+
								'</select>'+
							'</td>'+ // status
							'<td>'+btnAksi_detail(data.index)+'</td>'+ // aksi
						'</tr>'
					);
					numbering_listDetail();
					console.log(listDetail);

					$("#modalDetail").modal('hide');
					resetModal();
				}
				else{
					// decrement index utama
					indexDetail -= 1;
					setError(output.error);
				}	
			},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            console.log(jqXHR, textStatus, errorThrown);
	        }
		})
	}

	/**
	*
	*/
	function numbering_listDetail(){
		$('#detail_proyekTable tbody tr').each(function(index){
			$(this).children('td:eq(0)').html(index+1);	
		});
	}

	/**
	*
	*/
	function btnAksi_detail(index){
		var btn = '<button type="button" class="btn btn-danger btn-flat btn-sm"'+
					' title="Hapus data dari list" onclick="delete_detail('+index+', this)">'+
					'<i class="fa fa-trash"></i></button>';

		return btn;
	}

	/**
	*
	*/
	function onChange_angsuran(index, val){
		$.each(listDetail, function(i, item){
			if(item.index == index){
				item.angsuran = val.value;
			}
		});
		numbering_listDetail();

		console.log(listDetail);

	}

	/**
	*
	*/
	function onChange_persentase(index, val){
		$.each(listDetail, function(i, item){
			if(item.index == index){
				item.persentase = parseFloat(val.value);
			}
		});
		numbering_listDetail();

		console.log(listDetail);
	}

	/**
	*
	*/
	function onChange_total(index, val){
		$.each(listDetail, function(i, item){
			if(item.index == index){
				item.total_detail = parseFloat(val.value);
			}
		});
		numbering_listDetail();

		console.log(listDetail);
	}

	/**
	*
	*/
	function onChange_status(index, val){
		$.each(listDetail, function(i, item){
			if(item.index == index){
				item.status_detail = val.value;
			}
		});
		numbering_listDetail();

		console.log(listDetail);
	}

// =========================================================== //

// ===================== Function skc ======================== //

	/**
	*
	*/
	function addSkc(){
		var index = indexSkc++;

		var data = {
			index: index,
			id_skc: $('#skc').val().trim(),
			nama: $('#skc option:selected').text().split(' - ')[1],
		};

		// validDetail(data);
		console.log(data);

		// insert tabel
		$('#detail_proyekTable > tbody:last-child').append(
			'<tr>'+
				'<td></td>'+ // no
				'<td>' // id
					+'<input type="text" class="form-control input-sm" value="'+data.angsuran+'" '+
					'onchange="onChange_angsuran('+data.index+', this)"></td>'+
				'<td>'+ // nama
					'<div class="input-group">'+
						'<input type="number" min="0" max="100" step="any" onchange="onChange_persentase('+data.index+',this)" '+
						'class="form-control input-sm" value="'+data.persentase+'">'+
						'<span class="input-group-addon">%</span>'+
					'</div></td>'+
				'<td>'+btnAksi_skc(data.index)+'</td>'+ // aksi
			'</tr>'
		);

		console.log('Index : '+index);
		console.log('Index Utama: '+indexSkc);
	}
	
	/**
	*
	*/
	function numbering_listSkc(){
		$('#sub_kas_kecilTable tbody tr').each(function(index){
			$(this).children('td:eq(0)').html(index+1);	
		});
	}

	/**
	*
	*/
	function btnAksi_skc(index){
		var btn = '<button type="button" class="btn btn-danger btn-flat btn-sm"'+
					' title="Hapus data dari list" onclick="delete_skc('+index+', this)">'+
					'<i class="fa fa-trash"></i></button>';

		return btn;
	}

// =========================================================== //

/**
*
*/
function getDataForm(){
	var data = new FormData();
	
	var luas_area = parseFloat($('#luas_area').val().trim()) ? parseFloat($('#luas_area').val().trim()) : $('#luas_area').val().trim();
	var estimasi= parseFloat($('#estimasi').val().trim()) ? parseFloat($('#estimasi').val().trim()) : $('#estimasi').val().trim();
	var total= parseFloat($('#total').val().trim()) ? parseFloat($('#total').val().trim()) : $('#total').val().trim();
	var dp= parseFloat($('#dp').val().trim()) ? parseFloat($('#dp').val().trim()) : $('#dp').val().trim();
	var cco = parseFloat($('#cco').val().trim()) ? parseFloat($('#cco').val().trim()) : $('#cco').val().trim();

	data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim());
	data.append('pemilik', $('#pemilik').val().trim());
	data.append('tgl', $('#tgl').val().trim());
	data.append('pembangunan', $('#pembangunan').val().trim());
	data.append('luas_area', $('#luas_area').val().trim());
	data.append('alamat', $('#alamat').val().trim());
	data.append('kota', $('#kota').val().trim());
	data.append('estimasi', $('#estimasi').val().trim());
	data.append('total', $('#total').val().trim());
	data.append('dp', $('#dp').val().trim());
	data.append('cco', $('#cco').val().trim());
	data.append('status', $('#status').val().trim());
	
	
	// data.append('')
	// data.append('')
	data.append('action', $('#submit_proyek').val().trim());

	return data;
}

/**
* function proses
*/
function submit(){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'proyek/'+$('#submit_proyek').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache :false,
		processData: false,
		beforeSend: function(){
			$('#submit_proyek').prop('disabled', true);
			$('#submit_proyek').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(!output.status){
				$('#submit_proyek').prop('disabled', false);
				$('#submit_proyek').html($('#submit_proyek').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else window.location.href = BASE_URL+'proyek/';
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log(jqXHR, textStatus, errorThrown);
		}

	})
}

/**
*
*/
function setError(error){
	$.each(error, function(index, item){
		console.log(index);

		if(item != ""){
			$('.field-'+index).removeClass('has-success').addClass('has-error');
			$('.pesan-'+index).text(item);
		}
		else{
			$('.field-'+index).removeClass('has-error').addClass('has-success');
			$('.pesan-'+index).text('');	
		}
	});
}

/**
*
*/
function setStatus(){
	var status = [
		{value: "BERJALAN", text: "BERJALAN"},
		{value: "SELESAI", text: "SELESAI"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option);
	});
}

/**
*
*/
function setStatusDetail(){
	var status = [
		{value: "BELUM DIBAYAR", text: "BELUM DIBAYAR"},
		{value: "LUNAS", text: "LUNAS"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status_detail").append(option);
	});
}

/**
*
*/
function setSkc(){
	$.ajax({
		url: BASE_URL+'proyek/get-skc',
		dataType: 'json',
		beforeSend: function(){

		},
		success: function(data){
			console.log(data);
			$('#skc').select2({
				data: data
			});

		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
        }
	})
}

/**
*
*/
function generateID(){
	$.ajax({
		url: BASE_URL+'proyek/get-last-id/',
		beforeSend: function(){

		},
		success: function(output){
			$('#id').val(output);	
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
        }
	})
}

/**
*
*/
function resetForm(){

}

/**
*
*/
function resetModal(){
	$('#form_detail').trigger('reset');

	// hapus semua pesan
	$('#form_detail .pesan').text('');

	// hapus semua feedback
	$('#form_detail .form-group').removeClass('has-success').removeClass('has-error');
}