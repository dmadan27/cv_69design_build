<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalView_PKK">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Detail Pengajuan Kas Kecil</h4>
			</div>

				<!-- body modal -->
				<div class="modal-body">
					<!-- tgl dan nama pembayaran -->
                    
					<div class="row" style="padding:5px;">
                        <div class="col-lg-6">
                            <h4> <strong>DATA PENGAJUAN </strong></h4>
                            <table class="table table-hover">
                                <!-- ID -->
                                <tr>
                                    <td width="45%"><strong>ID Pengajuan</strong></td>
                                    <td id="res_id"></td>
                                </tr>
                                <!-- TGL -->
                                <tr>
                                    <td><strong>Tanggal Pengajuan</strong></td>
                                    <td id="tgl"></td>
                                </tr>
                                <!-- Nama -->
                                <tr>
                                    <td><strong>Nama Pengajuan</strong></td>
                                    <td id="nama"></td>
                                </tr>
                                <!-- Total -->
                                <tr>
                                    <td><strong>Total Pengajuan</strong></td>
                                    <td id="total"></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Disetujui</strong></td>
                                    <td id="total_disetujui"></td>
                                </tr>
                                <!-- Status -->
                                <tr>
                                    <td><strong>Status Pengajuan</strong></td>
                                    <td id="status"></td>
                                </tr>
                               <!-- Keterangan Perbaiki/Ditolak -->
                               <tr>
                                    <td><strong>Alasan Perbaiki</strong></td>
                                    <td id="alasan_perbaiki"></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-lg-6">
                            <h4> <strong>DATA KAS KECIL </strong></h4>
                            <table class="table table-hover">
                                <!-- ID Kas Kecil-->
                                <tr>
                                    <td><strong>ID Kas Kecil</strong></td>
                                    <td id="id"></td>
                                </tr>
                                <!-- Nama Kas Kecil -->
                                <tr>
                                    <td><strong>Kas Kecil</strong></td>
                                    <td id="kas_kecil"></td>
                                </tr>
                            </table>
                        </div>
                        
					</div>	

				</div>
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>

		</div>
	</div>
</div>