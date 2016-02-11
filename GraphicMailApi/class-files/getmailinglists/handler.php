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

	$res = json_decode(json_encode(simplexml_load_string($this->result)),true);
	
	if ($res['mailinglist']['0']) {
		// multiple rows
		foreach ($res['mailinglist'] AS $d) {
			$a[$d['mailinglistid']]['id']   = $d['mailinglistid'];
			$a[$d['mailinglistid']]['name'] = $d['description'];
		}
	}
	else if ($res['mailinglist']['mailinglistid']) {
		// single result
		$a[$res['mailinglist']['mailinglistid']]['id']   = $res['mailinglist']['mailinglistid'];
		$a[$res['mailinglist']['mailinglistid']]['name'] = $res['mailinglist']['description'];
	}
	else {
		// no results
		$a = NULL;
	}
	
	$rtn['data'] = $a;
}
