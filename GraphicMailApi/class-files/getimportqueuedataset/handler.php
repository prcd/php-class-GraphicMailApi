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
	
	if ($res['queueitem']['0']) {
		$i = 1;
		
		// multiple rows
		foreach ($res['queueitem'] AS $d)
		{
			$a[$i]['imported']              = $d['imported'];
			$a[$i]['updated']               = $d['updated'];
			$a[$i]['mailing_list_imported'] = $d['mailinglistimported'];
			$a[$i]['mobile_list_imported']  = $d['mobilelistimported'];
			$a[$i]['status']                = $d['status'];
			$a[$i]['dataset_name']          = $d['datasetname'];
			$a[$i]['dataset_id']            = $d['datasetid'];
			$a[$i]['mailing_list_name']     = $d['mailinglistname'];
			$a[$i]['mailing_list_id']       = $d['mailinglistid'];
			$a[$i]['mobile_list_name']      = $d['mobilelistname'];
			$a[$i]['mobile_list_id']        = $d['mobilelistid'];
			$a[$i]['queue_date']            = $d['queuedate'];
			
			$i++;
		}
	}
	else if ($res['queueitem']['imported']) {
		// single result
		$a[1]['imported']              = $res['queueitem']['imported'];
		$a[1]['updated']               = $res['queueitem']['updated'];
		$a[1]['mailing_list_imported'] = $res['queueitem']['mailinglistimported'];
		$a[1]['mobile_list_imported']  = $res['queueitem']['mobilelistimported'];
		$a[1]['status']                = $res['queueitem']['status'];
		$a[1]['dataset_name']          = $res['queueitem']['datasetname'];
		$a[1]['dataset_id']            = $res['queueitem']['datasetid'];
		$a[1]['mailing_list_name']     = $res['queueitem']['mailinglistname'];
		$a[1]['mailing_list_id']       = $res['queueitem']['mailinglistid'];
		$a[1]['mobile_list_name']      = $res['queueitem']['mobilelistname'];
		$a[1]['mobile_list_id']        = $res['queueitem']['mobilelistid'];
		$a[1]['queue_date']            = $res['queueitem']['queuedate'];
	}
	else {
		// no results
		$a = NULL;
	}
	
	$rtn['data'] = $a;
}
