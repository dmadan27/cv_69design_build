$(document).ready(function(){
	var bankTable = $("#bankTable").DataTable({
        "language" : {
            "lengthMenu": "Tampilkan _MENU_ data/page",
            "zeroRecords": "Data Tidak Ada",
            "info": "Menampilkan _START_ s.d _END_ dari _TOTAL_ data",
            "infoEmpty": "Menampilkan 0 s.d 0 dari 0 data",
            "search": "Pencarian:",
            "loadingRecords": "Loading...",
            "processing": "Processing...",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        "lengthMenu": [ 10, 25, 75, 100 ],
        "pageLength": 10,
        order: [],
        processing: true,
        serverSide: true,
        ajax: {
            url: BASE_URL+"bank/get-list/",
            type: 'POST',
            data: {
                "token_bank_list" : $('#token_bank_list').val().trim(),
            }
        },
        "columnDefs": [
            {
                "targets":[0, 4], // disable order di kolom 1 dan 3
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
        	for(var i = 0; i < 5; i++){
        		if(i != 1 && i != 2) $('td:eq('+i+')', row).addClass('text-center'); 
         		if(i == 2) $('td:eq('+i+')', row).addClass('text-right'); // rata kanan untuk data saldo
        	}
        }
    });

});

/**
*
*/
function getView(id){
	window.location.href = BASE_URL+'bank/detail/'+id;
}

/**
*
*/
function getDelete(id){
// 	// swal(
//  //  		'Good job!',
//  //  		'You clicked the button!',
//  //  		'success'
// 	// )
// 	alert('You clicked the button!')
// 	swal({
//   type: 'error',
//   title: 'Oops...',
//   // text: 'Something went wrong!',
//   footer: '<a href>Why do I have this issue?</a>',
// })
}
