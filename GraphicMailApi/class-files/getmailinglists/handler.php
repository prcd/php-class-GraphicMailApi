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
		$i=0;
		foreach ($res['mailinglist'] AS $d) {
			$a[$i]['id']   = $d['mailinglistid'];
			$a[$i]['name'] = $d['description'];
			$i++;
		}
	}
	else if ($res['mailinglist']['mailinglistid']) {
		// single result
		$a[0]['id']   = $res['mailinglist']['mailinglistid'];
		$a[0]['name'] = $res['mailinglist']['description'];
	}
	else {
		// no results
		$a = NULL;
	}
	
	$rtn['data'] = $a;
}
