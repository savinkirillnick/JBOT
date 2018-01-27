<?php
////////////////////
// Funnymay Price //
// WEX.NZ         //
////////////////////

//---------------------------- get Prices

if(isset($_POST['pair'])) { $pair = $_POST['pair']; } elseif(isset($_GET['pair'])) { $pair = $_GET['pair']; } else { $pair = 0; }
$pair = htmlspecialchars(strip_tags(trim($pair)));

if(isset($_POST['strategy'])) { $strategy = $_POST['strategy']; } elseif(isset($_GET['strategy'])) { $strategy = $_GET['strategy']; } else { $strategy = 0; }
$strategy = htmlspecialchars(strip_tags(trim($strategy)));

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
$fcontents .= ",";

$fcontents = explode (',', $fcontents);

$i=0;
$j=0;

while ($fcontents[$i]) {
	$i++;
	$low[$j] = $fcontents[$i];
	$i++;
	$close[$j] = $fcontents[$i];
	$i++;
	$open[$j] = $fcontents[$i];
	$i++;
	$high[$j] = $fcontents[$i];
	$i = $i+2;
	$j++;
}

$lastprice = end($close);

$imax = $j;
$i=0;
$j=0;

$highsqueeze = max($high);
$lowsqueeze = min($low);
$rangeprice = max(max($open),max($close)) - min(min($open),min($close));


	if ($strategy == 0) {
		for ($i=0;$i<$imax;$i++){
			$body24[$j] = $open[$i];
			$j++;
			$body24[$j] = $close[$i];
			$j++;
		}
		$buyorder = min($body24);
		$sellorder = max($body24);
	}

	if ($strategy == 1) {
		for ($i=24;$i<$imax;$i++){
			$body12[$j] = $open[$i];
			$j++;
			$body12[$j] = $close[$i];
			$j++;
		}
		$buyorder = min($body12);
		$sellorder = max($body12);
	}

	if ($strategy == 2) {
		for ($i=36;$i<$imax;$i++){
			$body6[$j] = $open[$i];
			$j++;
			$body6[$j] = $close[$i];
			$j++;
		}
		$buyorder = min($body6);
		$sellorder = max($body6);
	}

	if ($strategy == 3) {
		for ($i=45;$i<$imax;$i++){
			$body2[$j] = $open[$i];
			$j++;
			$body2[$j] = $close[$i];
			$j++;
		}
		$buyorder = min($body2);
		$sellorder = max($body2);
	}

	if ($strategy == 4) {
		for ($i=0;$i<$imax;$i++){
			$body24[$j] = $open[$i];
			$j++;
			$body24[$j] = $close[$i];
			$j++;
		}
		for ($i=45;$i<$imax;$i++){
			$body2[$j] = $open[$i];
			$j++;
			$body2[$j] = $close[$i];
			$j++;
		}

		$maxhigh[2] = max($body2);
		$maxhigh[24] = max($body24);
		$minlow[2] = min($body2);
		$minlow[24] = min($body24);
		$spread24 = $maxhigh[24]-$minlow[24];
		$deltaHigh = $maxhigh[24]-$maxhigh[2];
		$deltaLow = $minlow[2]-$minlow[24];

		if (($deltaHigh/$spread24) <= 0.15) {
			$lowFix = (min($body2) + min($body24))/2;
			$highFix = max($body2) + ($lowFix-min($body24));
		} elseif (($deltaLow/$spread24) <= 0.15) {
			$highFix = (max($body2) + max($body24))/2;
			$lowFix = min($body24) - (max($body24)-$highFix);
		} else {
			$highFix = $maxhigh[24];
			$lowFix = $minlow[24];
		}

		$buyorder = $lowFix;
		$sellorder = $highFix;
	}

echo "{\"buyprice\":" . $buyorder . ", \"sellprice\":" . $sellorder . ", \"highsqueeze\":" . $highsqueeze . ", \"lowsqueeze\":" . $lowsqueeze . ", \"lastprice\":" . $lastprice . ", \"rangeprice\":" . $rangeprice . ", \"success\": 1}";

?>