<?php

if ($this->result == '') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'No response from GraphicMail';
}
else if ($this->result == '0|Dataset does not exist!') {
	$rtn['status']  = 'OK';
	$rtn['data']['valid_id'] = '0';
	$rtn['data']['name']     = NULL;
}
else if (substr($this->result, 0, 2) == '0|') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = substr($this->result,2);
}
else if (substr($this->result, 0, 5) == '<?xml') {
	$res = json_decode(json_encode(simplexml_load_string($this->result)),true);

	$a = [];

	if (isset($res['entry']['0'])) {
		// multiple rows
		foreach ($res['entry'] as $i => $d) {
			$a[$i]['email']         = $d['emailaddress'];
			$a[$i]['mobile_number'] = is_array($d['mobilenumber']) ? '' : $d['mobilenumber'];
			$a[$i]['password']      = is_array($d['password']) ? '' : $d['password'];
			foreach ($d['column'] as $col_id => $col_data) {
				$a[$i]['col_'.($col_id+1)] = is_array($col_data) ? '' : $col_data;
			}
		}
	}
	else if (isset($res['entry']['emailaddress'])) {
		// single result
		$a[0]['email']         = $res['entry']['emailaddress'];
		$a[0]['mobile_number'] = is_array($res['entry']['mobilenumber']) ? '' : $res['entry']['mobilenumber'];
		$a[0]['password']      = is_array($res['entry']['password']) ? '' : $res['entry']['password'];
		foreach ($res['entry']['column'] as $col_id => $col_data) {
			$a[0]['col_'.($col_id+1)] = is_array($col_data) ? '' : $col_data;
		}
	}
	else if (isset($res[0])){
		$a = NULL;
	}

	if ($a === NULL || count($a) > 0) {
		$rtn['status'] = 'OK';
		$rtn['data']['valid_id'] = '1';
		$rtn['data']['name']     = $res['@attributes']['name'];
		$rtn['data']['row']      = $a;
	}
	else {
		// something's amiss...
		$rtn['status']  = 'ERR';
		$rtn['message'] = 'Unexpected response from GraphicMail';
	}

}
else {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'Unexpected response from GraphicMail';
}
