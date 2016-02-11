<?php

if ($this->result == '') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'No response from GraphicMail';
}
else if ($this->result == '0|There are no emailaddress in this mailinglist.') { // this string is also returned if an invalid ID is submitted
	$rtn['status'] = 'OK';
	$rtn['data']   = NULL;
}
else if (substr($this->result, 0, 2) == '0|') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = substr($this->result,2);
}
else {
	$rtn['status'] = 'OK';

	$res = json_decode(json_encode(simplexml_load_string($this->result)),true);
	
	if ($res['email']['0']) {
		// multiple rows
		foreach ($res['email'] AS $d) {
			$a[$d['emailid']]['id']            = $d['emailid'];
			$a[$d['emailid']]['email_address'] = $d['emailaddress'];
			$a[$d['emailid']]['date']          = $d['date'];
			$a[$d['emailid']]['status']        = $d['status'];
			$a[$d['emailid']]['ip_address']    = $d['ip_address'];
		}
	}
	else if ($res['email']['emailid']) {
		// single result
		$a[$res['email']['emailid']]['id']            = $res['email']['emailid'];
		$a[$res['email']['emailid']]['email_address'] = $res['email']['emailaddress'];
		$a[$res['email']['emailid']]['date']          = $res['email']['date'];
		$a[$res['email']['emailid']]['status']        = $res['email']['status'];
		$a[$res['email']['emailid']]['ip_address']    = $res['email']['ip_address'];
	}
	else {
		// no results
		$a = NULL;
	}
	
	$rtn['data'] = $a;
}
