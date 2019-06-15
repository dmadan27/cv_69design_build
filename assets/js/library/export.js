/**
 * Object Export Data.
 */
const Export = {
    /**
     * Memanggil url export excel.
     * 
     * @param {FormData} body Object berisi tanggal atau range tanggal data yang ingin diexport (opsional).
     * @param {string} method Nama method export excel yang ingin direquest untuk mendapatkan file excel.
     * @param {string} id String id yang diperlukan untuk melakukan export (opsional).
     */
    'excel': async ({ method, id = "", body = new FormData() }) => {
        const res = await fetch(BASE_URL + 'export/' + method + "/" + id, {
            method: "POST",
            body: body,
        });

        if (res.status == 200) {
            const data = await res.json();
            console.log(data);
            if (data.success) {
                let $a = $("<a>");
                $a.attr("href", data.file);
                $("body").append($a);
                $a.attr("download", data.filename);
                $a[0].click();
                $a.remove();
            } else {
                throw new InfoException(data.message);
            }
        } else {
            throw new Error("Tidak dapat terhubung ke server (" + res.status + ").");
        }

        // $.ajax({
        //     type: "POST",
        //     url: BASE_URL + 'export/' + method + "/" + id,
        //     data: {
        //         bulan: 06,
        //         tahun: 2019,
        //     },
        //     dataType: "JSON",
        //     success: (response) => {
        //         console.log('%cResponse getExport: ', 'color: blue; font-weight: bold', response);
        //         if (response.success) {
        //             let $a = $("<a>");
        //             $a.attr("href", response.file);
        //             $("body").append($a);
        //             $a.attr("download", response.filename);
        //             $a[0].click();
        //             $a.remove();
        //         } else {
        //             throw new InfoException(response.message);
        //         }
        //     },
        //     error: (jqXHR, textStatus, errorThrown) => { // error handling
        //         console.log('%cResponse Error Export ' + method, 'color: red; font-weight: bold', { jqXHR, textStatus, errorThrown });
        //         throw ("Terjadi Kesalahan Teknis, Silahkan Coba Kembali");
        //     },
        // });
    }
}