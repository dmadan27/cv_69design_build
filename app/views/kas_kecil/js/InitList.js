$(document).ready(function(){
	var kasKecilTable = $("#kasKecilTable").DataTable({
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
            url: BASE_URL+"kas-kecil/get-list/",
            type: 'POST',
           
        },
        
    });
        

    // $('#tambah').on('click', function(){
    //     if(this.value.trim() != ""){
    //         window.location.href = BASE_URL+'proyek/form/';
    //     }
    // });
});

function getView(id){
    // window.location.href = BASE_URL+'proyek/detail/'+id;
}