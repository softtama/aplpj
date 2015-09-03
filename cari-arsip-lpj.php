<?php require "connect.php";
include ("functions.php"); ?>
<div class='sub-content' id='daftar-lpj'>
	<div class='subtitle'>
		<div class='title'><h2>Pencarian Arsip LPJ</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
            <?php
            if (isset($_GET['delete_arsip'])) {
                if ($_GET['delete_arsip'] == 'error') {
            ?>
                <p class='inlinemsg err'>Gagal menghapus arsip LPJ. Silakan coba lagi nanti.</p>
            <?php
                } else if ($_GET['delete_arsip'] == 'success') {
            ?>
                <p class='inlinemsg suc'>Arsip LPJ berhasil dihapus.</p>
            <?php
                }
            }
            ?>
			<table id='table' class='table data'>
				<thead>
					<tr>
						<th>No</th>
						<th>Pengelola Dokumen</th>
						<th>Nama Kegiatan</th>
						<th>Waktu Kegiatan</th>
						<th class='nosort' width='70'>Perintah</th>
					</tr>
				</thead>
				<tbody>
			<?php
				$i = 1;
				$data = mysql_query("SELECT * FROM TB_DATA_LPJ WHERE LPJ_STATUS='3' AND (LPJ_ARSIP != NULL OR LPJ_ARSIP != '')");
				
				while ($tr = mysql_fetch_array($data)) { 
			?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $tr['USER_ID']; ?></td>
						<td><!--a href='index.php?page=form-data-utama-lpj&lpj_id=<?php echo $tr['LPJ_ID'] ?>'--><?php echo $tr['LPJ_NAMA_KEG'] ?><!--/a--></td>
						<td style='text-align: right;'><?php echo get_nama_bulan(substr($tr['LPJ_WAKTU_KEG'],0,2))." ".substr($tr['LPJ_WAKTU_KEG'],3,4) ?></td>
						<td class='center'><a class='ico ico-Download' href='download-arsip-lpj.php?lpj_id=<?php echo $tr['LPJ_ID'] ?>' title='Download arsip LPJ'></a>&nbsp;
							<?php if (is_pengelola_lpj($tr['LPJ_ID'], $_SESSION['login_session']['user_id'])) { ?><a class='ico ico-Delete' href='hapus-arsip-lpj.php?lpj_id=<?php echo $tr['LPJ_ID'] ?>' title='Hapus hanya arsip LPJ'></a><?php } else { ?><a class='ico ico-Delete disabled' title='Hapus tidak dapat dilakukan'></a><?php } ?></td>
					</tr>
			<?php
					$i++;
				}
			?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<link rel='stylesheet' type='text/css' href='./lib/datatables/css/jquery.dataTables.css'>
<script type='text/javascript' src='./lib/datatables/js/jquery.dataTables.js'></script>
<script type='text/javascript'>
$(document).ready(function(){
	$('#table').dataTable({
		"aoColumnDefs" : [{
			"bSortable" : false,
			"aTargets" : ["nosort"]
		}],
		language: {
			"sProcessing":   "Sedang memproses...",
			"sLengthMenu":   "Tampilkan &nbsp;_MENU_&nbsp; entri",
			"sZeroRecords":  "Tidak ditemukan data yang sesuai",
			"sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
			"sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
			"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
			"sInfoPostFix":  "",
			"sSearch":       "Cari arsip LPJ: &nbsp;&nbsp;&nbsp;",
			"sUrl":          "",
			"oPaginate": {
				"sFirst":    "Pertama",
				"sPrevious": "Sebelumnya",
				"sNext":     "Selanjutnya",
				"sLast":     "Terakhir"
			}
		}
	});
});
</script>