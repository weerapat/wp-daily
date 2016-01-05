<?php 
// echo '<pre>'.print_r($_POST,1).'</pre>';
if(count($_POST['key'])) {
	foreach ($_POST['key'] as $key => $value) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"https://sendy.askhanuman.co.th/subscribe");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
	            "email=".$_POST['email']."&list=".$value);

	// in real life you should use something like:
	// curl_setopt($ch, CURLOPT_POSTFIELDS, 
	//          http_build_query(array('postvar1' => 'value1')));

	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);

	curl_close ($ch);

	}
	echo $server_output;
}
else{
	echo "Please select a Topic!";
}

?>