<?php
	$url_to_get = "http://test.beastozavr.net/json.123";
	$post_field = "data";
	
	error_reporting(E_ERROR | E_WARNING);
	// $_POST['data'] = '{
		// "Outcome":"Success",
		// "LastStep":"/thanks",
		// "Policy":"Car",
		// "PolicyName":"Young",
		// "WizardName":"fnxonline",
		// "Age":22,
		// "Gender":"male",
		// "EndDate":"20170310",
		// "UID":"102030",
		// "LeadType":"call",
		// "CID":"1000000",
		// "Term":"car",
		// "Campaign":"1000000",
		// "Source":"1000000",
		// "Medium":"1000000",
		// "Content":"1000000",
		// "GCLID":"1000000",
		// "lpurl":"1000000"
	// }';

	$ips = [
		"192.118.36.53",
		"212.22.211.69"
	];
	
	$certs = ["test"];
	
	
	$validation = [
		'Outcome' => ['success', 'failure'],
		'LastStep' => null,
		'Policy' => ['car', 'travel', 'mortgage'],
		'PolicyName' => null,
		'WizardName' => null,
		'Age' => null,
		'Gender' => ['male', 'female'],
		'EndDate' => null,
		'UID' => null,
		'LeadType' => null,
		'CID' => null,
		'Term' => null,
		'Campaign' => null,
		'Source' => null,
		'Medium' => null,
		'Content' => null,
		'GCLID' => null,
		'lpurl' => null
	];
	
	//IP check
	// if(!in_array($_SERVER['REMOTE_ADDR'], $ips)) {
	// 	 $error = 403;
	// 	 $result = "Forbidden";
	// }
	
	//Cert check when comes from other address
	// $url = $_SERVER['HTTP_REFERER'];
	// $orignal_parse = parse_url($url, PHP_URL_HOST);
	// $get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
	// $read = stream_socket_client("ssl://".$orignal_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);
	// $cert = stream_context_get_params($read);
	// $certinfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);
	// if(!in_array($certinfo['serialNumber'], $certs)) {
		// $error = 403;
		// $result = "Forbidden";
	// }
	
	$inputJSON = file_get_contents('php://input');
	$data= json_decode( $inputJSON, TRUE );
	if (empty($data) || !is_array($data)) {
		if (!empty($url_to_get)) {
			$data = json_decode(@file_get_contents($url_to_get), true);
			if (empty($data) && !empty($post_field)) {
				$data = json_decode($_POST[$post_field], true);
				if (empty($data) && !empty($_POST)) {
					$data = $_POST;
				}
			} 
		}
	}
		
	if (empty($data) || !is_array($data)) {
		$error = 422;
		$result = "Empty data";
	} else {
		$input = $data;
		if(is_array($input)) {
		foreach($validation as $k=>$validate) {
			if(!is_array($input) || !array_key_exists($k, $input)) {
				$error = 422;
				$result = "Wrong data structure";
			} else {
				if(is_array($validate)) {
					if(!in_array(strtolower($input[$k]), $validate)) {
						$error = 422;
						$result = "Wrong input data";
					}
				}
			}
			
		}
		$map = array(
			"cid" => "CID",
			"dh" => "WIZARDNAME",
			"dp" => "LASTSTEP",
			"dt" => "OUTCOME",
			"cd4" => "CID",
			"cd5" => "LPURL",
			"cd2" => "UID",
			"cd6" => "POLICY",
			"cd7" => "POLYCIYNAME",
			"cd10" => "WIZARDNAME",
			"cd8" => "GENDER",
			"cd9" => "LEADTYPE",
			"cd11" => "DEVICE",
			"gclid" => "GCLID",
			"cs" => "SOURCE",
			"cm" => "MEDIUM",
			"ck" => "KEYWORD",
			"cc" => "CONTENT",
			"cn" => "CAMPAIGN",
		);
		
		$request = $input_to_lower = array();
		
		$input_to_lower = array_change_key_case($input);
		
		foreach($map as $k=>$val) {
			foreach($input_to_lower as $j=>$row) {
				$request[$k] = $input_to_lower[strtolower($val)];
			}
		}
		
		$request['v']=1;
		$request['t']='pageview';
		$request['tid']='tid=UA-1458255-7';
		$request['z'] = rand(1, 100000);
		
		if(!isset($error)) {
			$url = "https://www.google-analytics.com/collect?";
			$url .= http_build_query($request);
			$file = base64_encode(file_get_contents($url));
		}
		} else {
			$error = 422;
			$result .= " Bad data";
		}
	}
	
	if(!$error) {
		$db = mysqli_connect('localhost', 'fnx_webServiceAPI', 'MtRq3RWe2f2rphrS', 'fnx_webServiceAPI');
		mysqli_set_charset($db, "utf8");
		
		class LeadItem {
			public $Date = '';
		}
		
		$item = new LeadItem();
		$item->Date = date('Y-m-d H:i:s');
		
		
		/*$sqlInsert = 'INSERT INTO `api-leads` (date) VALUES ';
		$sqlInsert .= "('" . $item->Date . "')";
		$sqlInsert = 'INSERT INTO `api-leads` (date) VALUES ';
		$sqlInsert .=  "('" . $item->Date . ",'.'" . $input->Outcome . "')";
		$sqlInsert .=  "('" . $item->Date . "')";*/
		$sqlInsert = 'INSERT INTO `api-leads` (date, outcome, lastStep, policy, policyName, wizardName, age, gender, endDate, uid, leadType, cid, term, campaign, source, medium, content, gclid, lpurl, device, userAgent) VALUES ';
		$sqlInsert .= "('" . $item->Date . "', '" . $input[Outcome] . "', '" . $input[LastStep] . "', '" . $input[Policy] . "', '" . $input[PolicyName] . "', '" . $input[WizardName] . "', '" . $input[Age] . "', '" . $input[Gender] . "', '" . $input[EndDate] . "', '" . $input[UID] . "', '" . $input[LeadType] . "', '" . $input[CID] . "', '" . $input[Term] . "', '" . $input[Campaign] . "', '" . $input[Source] . "', '" . $input[Medium] . "', '" . $input[Content] . "', '" . $input[GCLID] . "', '" . $input[lpurl] . "', '" . $input[device] . "', '" . $input[User_Agent] . "')";
		$sqlInsert .= ';';
		mysqli_query($db, $sqlInsert);
		
		mysqli_close($db);
	
		header('Content-Type: application/json');
		echo '{"status":ok,"file":"'.$file.'","description":"' . $url . '"}';
	} else {
		/*header($result, true, $error);*/
		echo '{"status":failed,"file":"","description":"'.$result.' - '.$error.'"}';
	}
	
	
	