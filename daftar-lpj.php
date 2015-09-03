<?php
require "connect.php"; 
include("functions.php");
?>

<div class='sub-content' id='daftar-lpj'>
	<div class='subtitle'>
		<div class='title'><h2>Tabel Daftar LPJ</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
			<?php
			if (isset($_GET['delete_lpj'])) {
				if ($_GET['delete_lpj'] == 'error') {
			?>
				<p class='inlinemsg err'>Gagal hapus LPJ. Silakan coba lagi nanti.</p>
			<?php
				} else if ($_GET['delete_lpj'] == 'success') {
			?>
				<p class='inlinemsg suc'>LPJ berhasil dihapus.</p>
			<?php
				}
			}
			?>
			<table id='table' class='table data display' width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>Pengelola Dokumen</th>
						<th>Nama Kegiatan</th>
						<th>Waktu Kegiatan</th>
						<th>Waktu Pembuatan</th>
						<th>Waktu Update</th>
						<th>Status</th>
						<th class='nosort' width='70'>Perintah</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$i=1;
					if (isset($_GET['status_lpj'])) {
						switch ($_GET['status_lpj']) {
							case '1':	$data = mysql_query("SELECT * FROM TB_DATA_LPJ WHERE LPJ_STATUS='1' AND AKTIF=TRUE");
							break;
							case '2':	$data = mysql_query("SELECT * FROM TB_DATA_LPJ WHERE LPJ_STATUS='2' AND AKTIF=TRUE");
							break;
							case '3':	$data = mysql_query("SELECT * FROM TB_DATA_LPJ WHERE LPJ_STATUS='3' AND AKTIF=TRUE");
							break;
							default:	$data = mysql_query("SELECT * FROM TB_DATA_LPJ WHERE AKTIF=TRUE");
							break;
						}
					} else {
						$data = mysql_query("SELECT * FROM TB_DATA_LPJ WHERE AKTIF=TRUE");
					}
					
					while ($tr = mysql_fetch_array($data)) { 
				?>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php echo $tr['USER_ID'] ?></td>
						<td><a href='index.php?page=form-data-utama-lpj&lpj_id=<?php echo $tr['LPJ_ID'] ?>'><?php echo $tr['LPJ_NAMA_KEG'] ?></a></td>
						<td style='text-align: right;'><?php echo get_nama_bulan(substr($tr['LPJ_WAKTU_KEG'],0,2))." ".substr($tr['LPJ_WAKTU_KEG'],3,4) ?></td>
						<td><?php echo date_format(date_create($tr['LPJ_TGL_BUAT']), 'd/m/Y H:m:s') ?></td>
						<td><?php echo date_format(date_create($tr['LPJ_TGL_UPDATE']), 'd/m/Y H:m:s') ?></td>
						<td><?php echo get_nama_status_lpj($tr['LPJ_STATUS']) ?> </td>
						<td class='center'><?php if (get_status_lpj($tr['LPJ_ID']) == '2' || get_status_lpj($tr['LPJ_ID']) == '3') { ?><a class='ico ico-Print' href='#' title='Print dokumen LPJ' onclick="javascript:var wnd = window.open('dokumen-lpj/DOKUMEN_LPJ-ID-<?php echo $tr['LPJ_ID'] ?>.pdf'); setTimeout(function() { wnd.print();}, 1000); "></a><?php } else { ?><a class='ico ico-Print disabled' title='Print tidak dapat dilakukan'></a><?php } ?>&nbsp;
							<?php if (get_status_lpj($tr['LPJ_ID']) == '2' || get_status_lpj($tr['LPJ_ID']) == '3') { ?><a class='ico ico-Download' href='download-lpj.php?lpj_id=<?php echo $tr['LPJ_ID'] ?>' title='Download dokumen LPJ dalam bentuk PDF'></a><?php } else { ?><a class='ico ico-Download disabled' title='Download tidak dapat dilakukan'></a><?php } ?>&nbsp;
							<?php if (is_pengelola_lpj($tr['LPJ_ID'], $_SESSION['login_session']['user_id'])) { ?><a class='ico ico-Delete' href='hapus-lpj.php?lpj_id=<?php echo $tr['LPJ_ID'] ?>' title='Hapus LPJ'></a><?php } else { ?><a class='ico ico-Delete disabled' title='Hapus tidak dapat dilakukan'></a><?php } ?></td>
					</tr>
				<?php
						$i=$i+1;
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
			"sSearch":       "Cari dokumen LPJ: &nbsp;&nbsp;&nbsp;",
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