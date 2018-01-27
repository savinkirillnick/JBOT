<?php
////////////////////
// Funnymay Trade //
// WEX.NZ         //
////////////////////

if(isset($_POST['data'])) { $data = $_POST['data']; } elseif(isset($_GET['data'])) { $data = $_GET['data']; } else { $data = ''; }
$data = htmlspecialchars(strip_tags(trim($data)));

if ($data) {

	$data = base64_decode($data);
	$data = json_decode($data, true);
	$data = json_decode($data, true);

	$post_data = $data["post_data"];
    $sign = $data["sign"];
    $key = $data["key"];
	$headers = array(
		'Sign: '.$sign,
		'Key: '.$key,
	);

	$ch = null;
	if (is_null($ch)) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; BTCE PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
	}
	curl_setopt($ch, CURLOPT_URL, 'https://wex.nz/tapi');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	$res = curl_exec($ch);

	if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));

	echo $res;

}

?>