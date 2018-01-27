<?php
////////////////////
// Funnymay Infos //
// WEX.NZ         //
////////////////////

	$link = "https://wex.nz/api/3/info";
	$fcontents = implode ('', file ($link));

	echo $fcontents;

?>