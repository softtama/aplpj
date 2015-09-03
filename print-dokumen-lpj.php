<?php
if(!isset($_GET['lpj_id'])) {
	header("location:index.php");
}

$lpj_id = $_GET['lpj_id'];

$path = "dokumen-lpj/";
$filename = "DOKUMEN_LPJ-ID-".$lpj_id.".pdf";
if (!file_exists($path.$filename)) {
	echo "Tidak ada";
	//header("location:download-lpj.php?lpj_id=".$lpj_id);
}

?>

<script type='text/javascript'>
	var wnd = window.open("dokumen-lpj/DOKUMEN_LPJ-ID-<?php echo $lpj_id ?>.pdf");
	wnd.print();
</script>