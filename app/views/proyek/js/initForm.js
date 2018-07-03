$(document).ready(function () {
	setStatusDetail();
	
	if($('#submit_proyek').val() == 'action-add') generateID();
	else if($('#submit_proyek').val() == 'action-edit') getEdit($('#id').val().trim());
		
	$('#id').prop('disabled', true);
    //Initialize Select2 Elements
    $('#skk').select2({
    	placeholder: "Pilih Sub Kas Kecil",
		allowClear: true
    });

    setSkk();

 	//Date picker
    $('.datepicker').datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      todayHighlight: true,
      orientation:"bottom auto",
      todayBtn: true,
    });

    // input mask

 	
    // tambah detail
    $('#tambah_detail').on('click', function(){
    	console.log(listDetail);
    	resetModal();
    	$('#modalDetail').modal();
    });

    // tambah skk
	$('#tambah_skk').on('click', function(){
		if($('#skk').val() != null) addSkk();
    	console.log(listSkk);
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
			id: '',
			id_proyek: $('#id').val().trim(),
			angsuran: $('#angsuran').val().trim(),
			persentase: persentase,
			total_detail: total_detail,
			status_detail: $('#status_detail').val().trim(),
			aksi: 'tambah',
			delete: false,
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
							'<td>'+
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

	/**
	*
	*/
	function delete_detail(index, val){
		$(val).parent().parent().remove();
		$.each(listDetail, function(i, item){
			if(item.index == index) item.delete = true;
		});
		numbering_listDetail();
		console.log(listDetail);
	}

// =========================================================== //

// ===================== Function skk ======================== //

	/**
	*
	*/
	function addSkk(){
		var index = indexSkk++;

		var data = {
			index: index,
			id: '',
			id_proyek: $('#id').val().trim(), 
			id_skk: $('#skk').val().trim(),
			nama: $('#skk option:selected').text().split(' - ')[1],
			aksi: 'tambah',
			delete: false, 
		};

		// validDetail(data);
		console.log(data);

		if(validSkk(data.id_skk)){
			indexSkk -= 1;
			console.log(validSkk(data.id_skk));
		}
		else{
			console.log(validSkk(data.id_skk));
			listSkk.push(data);
			// insert tabel
			$('#sub_kas_kecilTable > tbody:last-child').append(
				'<tr>'+
					'<td></td>'+ // no
					'<td>'+data.id_skk+'</td>'+
					'<td>'+data.nama+'</td>'+
					'<td>'+btnAksi_skk(data.index)+'</td>'+ // aksi
				'</tr>'
			);

			$('#skk').val(null).trigger('change');
			numbering_listSkk();
		}
		
		console.log('Index : '+index);
		console.log('Index Utama: '+indexSkk);
	}
	
	/**
	*
	*/
	function validSkk(skk){
		var ada = false;

		$.each(listSkk, function(index, item){
			if(skk == item.id_skk && !item.delete) ada = true;
		});

		return ada;
	}

	/**
	*
	*/
	function numbering_listSkk(){
		$('#sub_kas_kecilTable tbody tr').each(function(index){
			$(this).children('td:eq(0)').html(index+1);	
		});
	}

	/**
	*
	*/
	function btnAksi_skk(index){
		var btn = '<button type="button" class="btn btn-danger btn-flat btn-sm"'+
					' title="Hapus data dari list" onclick="delete_skk('+index+', this)">'+
					'<i class="fa fa-trash"></i></button>';

		return btn;
	}

	/**
	*
	*/
	function delete_skk(index, val){
		$(val).parent().parent().remove();
		$.each(listSkk, function(i, item){
			if(item.index == index && item.aksi == 'edit') item.status = true;
			// else (item.index == index && item.aksi == 'tambah'){
				
			// }
		});
		numbering_listDetail();
		console.log(listSkk);
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

	var dataProyek = {
		id: $('#id').val().trim(),
		pemilik: $('#pemilik').val().trim(),
		tgl: $('#tgl').val().trim(),
		pembangunan: $('#pembangunan').val().trim(),
		luas_area: $('#luas_area').val().trim(),
		alamat: $('#alamat').val().trim(),
		kota: $('#kota').val().trim(),
		estimasi: $('#estimasi').val().trim(),
		total: $('#total').val().trim(),
		dp: $('#dp').val().trim(),
		cco: $('#cco').val().trim(),
		status: $('#status').val().trim(),
	}

	data.append('token', $('#token').val().trim());
	data.append('id', $('#id').val().trim());
	data.append('dataProyek', JSON.stringify(dataProyek));
	data.append('dataDetail', JSON.stringify(listDetail));
	data.append('dataSkk', JSON.stringify(listSkk));
	data.append('action', $('#submit_proyek').val().trim());

	return data;
}

/**
*
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
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			$('#submit_proyek').prop('disabled', false);
			$('#submit_proyek').html($('#submit_proyek').text());
		}

	})
}

/**
*
*/
function getEdit(id){
	$.ajax({
		url: BASE_URL+'proyek/get-edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {'token': $('#token').val().trim()},
		beforeSend: function(){},
		success: function(output){
			console.log(output);

			// each dataDetail
			$.each(output.dataDetail, function(i, data){
				var index = indexDetail++;
				var dataDetail = {
					index: index,
					id: data.id,
					id_proyek: data.id_proyek,
					angsuran: data.angsuran,
					persentase: data.persentase,
					total_detail: data.total_detail,
					status_detail: data.status_detail,
					aksi: 'edit',
					delete: false,
				}

				listDetail.push(dataDetail);

				var status = function(value){
					return (data.status_detail.toLowerCase() == value.toLowerCase()) ? 'selected' : ''
				};
				$('#detail_proyekTable > tbody:last-child').append(
					'<tr>'+
						'<td></td>'+ // no
						'<td>' // angsuran
							+'<input type="text" class="form-control input-sm" value="'+data.angsuran+'" '+
							'onchange="onChange_angsuran('+dataDetail.index+', this)"></td>'+
						'<td>'+
							'<div class="input-group">'+
								'<input type="number" min="0" max="100" step="any" onchange="onChange_persentase('+dataDetail.index+',this)" '+
								'class="form-control input-sm" value="'+data.persentase+'">'+
								'<span class="input-group-addon">%</span>'+
							'</div></td>'+ // persentase
						'<td>'+
							'<div class="input-group">'+
								'<span class="input-group-addon">Rp</span>'+
								'<input type="number" min="0" step="any" onchange="onChange_total('+dataDetail.index+',this)" '+
								'class="form-control input-sm" value="'+data.total_detail+'">'+
							'</div></td>'+ // total
						'<td>'+
							'<select onchange="onChange_status('+dataDetail.index+', this)" class="form-control input-sm">'+
								'<option '+status('BELUM DIBAYAR')+'>BELUM DIBAYAR</option>'+
								'<option '+status('LUNAS')+'>LUNAS</option>'+
							'</select>'+
						'</td>'+ // status
						'<td>'+btnAksi_detail(dataDetail.index)+'</td>'+ // aksi
					'</tr>'
				);
				numbering_listDetail();
			});	

			// each dataSkk
			$.each(output.dataSkk, function(i, data){
				var index = indexSkk++;
				var dataSkk = {
					index: index,
					id: data.id,
					id_proyek: data.id_proyek, 
					id_skk: data.id_skk,
					nama: data.nama,
					aksi: 'edit',
					delete: false, 
				}

				listSkk.push(dataSkk);

				var status = function(value){
					return (data.status_detail.toLowerCase() == value.toLowerCase()) ? 'selected' : ''
				};
				$('#sub_kas_kecilTable > tbody:last-child').append(
					'<tr>'+
						'<td></td>'+ // no
						'<td>'+data.id_skk+'</td>'+
						'<td>'+data.nama+'</td>'+
						'<td>'+btnAksi_skk(dataSkk.index)+'</td>'+ // aksi
					'</tr>'
				);
				numbering_listSkk();
			});
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
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
function setSkk(){
	$.ajax({
		url: BASE_URL+'proyek/get-skk',
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			console.log(data);
			$.each(data, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#skk').append(newOption).trigger('change');
			});
			$('#skk').val(null).trigger('change');
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
*
*/
function generateID(){
	$.ajax({
		url: BASE_URL+'proyek/get-last-id/',
		type: 'post',
		data: {token: $('#token').val().trim()},
		beforeSend: function(){},
		success: function(output){
			$('#id').val(output);	
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
*
*/
function resetForm(){
	// trigger reset form
	$('#form_proyek').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
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