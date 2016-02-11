<?php

if ($this->result == '') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'No response from GraphicMail';
}
else if (substr($this->result, 0, 2) == '0|') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = substr($this->result,2);
}
else {
	$rtn['status'] = 'OK';
	
	$status = substr($this->result, 0, 1);
	
	if ($status == '1') {
		$rtn['data']['import'] = 'success';
		
		$p = explode(',', $this->result);
		
		$r = explode('=', $p[1]);
		$rtn['data']['report']['dataset_imported'] = $r[1];
		
		$r = explode('=', $p[2]);
		$rtn['data']['report']['dataset_updated'] = $r[1];
		
		$r = explode('=', $p[3]);
		$rtn['data']['report']['mailing_list_imported'] = $r[1];
		
		$r = explode('=', $p[4]);
		$rtn['data']['report']['mobile_list_imported'] = $r[1];
	}
	else if ($status == '2') {
		// status = 2
		$rtn['data']['import'] = 'queued';
	}
	else {
		$rtn['status']  = 'ERR';
		$rtn['message'] = 'Unexpected response from GraphicMail: '.$this->result;
	}
}
