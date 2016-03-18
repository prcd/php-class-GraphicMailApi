<?php

if ($this->result == '') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'No response from GraphicMail';
}
else if (substr($this->result, 0, 2) == '0|') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = substr($this->result,2);
}
else if (substr($this->result, 0, 5) == '<?xml'){
	$rtn['status'] = 'OK';
	
	$res = json_decode(json_encode(simplexml_load_string($this->result)),true);

	if ($res['dataset'][0]) {
		// multiple rows
		$i=0;
		foreach ($res['dataset'] AS $dataset) {
			$a[$i]['id']   = $dataset['datasetid'];
			$a[$i]['name'] = $dataset['name'];
			$i++;
		}
	}
	else if ($res['dataset']['datasetid']) {
		// single result
		$a[0]['id']   = $res['dataset']['datasetid'];
		$a[0]['name'] = $res['dataset']['name'];
	}
	else {
		// no results
		$a = NULL;
	}
	
	$rtn['data'] = $a;
}
else {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'Unexpected response from GraphicMail';
}
