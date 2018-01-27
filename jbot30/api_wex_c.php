<?php
////////////////////
// Funnymay Chart //
// WEX.NZ         //
////////////////////

	if(isset($_POST['pair'])) { $pair = $_POST['pair']; } elseif(isset($_GET['pair'])) { $pair = $_GET['pair']; } else { $pair = "btc_usd"; }
	$pair = htmlspecialchars(strip_tags(trim($pair)));

	$link = "https://wex.nz/exchange/$pair?old_charts=1";

	$fcontents = implode ('', file ($link));
	$fcontents = stristr ($fcontents, 'pairs');
	$fcontents = stristr ($fcontents, 'clear');
	$fcontents = stristr ($fcontents, '<script');
	$pos = strpos($fcontents, '<div id');
	$fcontents = substr ($fcontents, 0, $pos);
	$fcontents = stristr ($fcontents, '[[');
	$pos = strpos($fcontents, ']]');
	$fcontents = substr ($fcontents, 0, $pos);
	$fcontents = str_replace(" ", "", "$fcontents");
	$fcontents = str_replace("[", "", "$fcontents");
	$fcontents = str_replace("]", "", "$fcontents");
	$fcontents .= ",";

	$fcontents = explode (',', $fcontents);

	$i=0;
	$j=0;
	$volumechart="";

	$chart = "{\"candles\":[";

	while ($fcontents[$i]) {
		$tcandle[$j] = $fcontents[$i];
		$i++;
		$lowcandle[$j] = $fcontents[$i];
		$i++;
		$opencandle[$j] = $fcontents[$i];
		$i++;
		$closecandle[$j] = $fcontents[$i];
		$i++;
		$highcandle[$j] = $fcontents[$i];
		$i++;
		$volumecandle[$j] = $fcontents[$i];
		$i++;
		$chart .= "[".$tcandle[$j].",".$lowcandle[$j].",".$opencandle[$j].",".$closecandle[$j].",".$highcandle[$j]."],";
		$volumechart .= "[".$tcandle[$j].",".$volumecandle[$j]."],";
		$j++;
	}

	$chart = substr ($chart, 0, -1);
	$volumechart = substr ($volumechart, 0, -1);

	$chart .= "],\"volumes\":[$volumechart]}";

	echo $chart;

?>