<?php

if ($this->result == '') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'No response from GraphicMail';
}
else if ($this->result == '0|No Email specified, or invalid email') { // this string is returned if the email does not exist in any mailing lists or datasets
	$rtn['status'] = 'OK';
	$rtn['data']['mailing_list'] = NULL;
	$rtn['data']['dataset']      = NULL;
}
else if (substr($this->result, 0, 2) == '0|') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = substr($this->result,2);
}
else if(substr($this->result, 0, 5) == '<?xml'){

	$rtn['status'] = 'OK';

	$res = json_decode(json_encode(simplexml_load_string($this->result)),true);

	// process mailing list results
	$mailing_list = [];
	if (isset($res['mailinglists']['mailinglist']['mailinglistid'])) {
		// a single result
		$mailing_list[0] = $res['mailinglists']['mailinglist'];
	}
	else if (isset($res['mailinglists']['mailinglist'])){
		// multiple results
		$mailing_list = $res['mailinglists']['mailinglist'];
	}

	if (count($mailing_list)) {
		$i=0;
		foreach ($mailing_list as $data) {
			if (substr($data['mailinglistname'],0,8) != 'Deleted_') {// sometimes, when a subscriber is deleted from a list all details are returned but with mailing list name = 'Deleted_6_3_2_2016 10:14:06 AM'
				$a['mailing_list'][$i]['id']     = $data['mailinglistid'];
				$a['mailing_list'][$i]['name']   = $data['mailinglistname'];
				$a['mailing_list'][$i]['email']  = $data['email'];
				$a['mailing_list'][$i]['status'] = $data['status'];
				$a['mailing_list'][$i]['date']   = $data['date']; // eg 11/02/2016 10:22, 23/10/2014 04:05
				$i++;
			}
		}
	}

	// If no mailing lists recorded, set as NULL
	if (! count($a['mailing_list'])) {
		$a['mailing_list'] = NULL;
	}

	// process dataset results
	if ($res['datasets'] == 'None') {
		// no results
		$a['data_set'] = NULL;
	}
	else if ($res['datasets']['dataset']['datasetid']) {
		// a single result
		$a['data_set'][0]['id']            = $res['datasets']['dataset']['datasetid'];
		$a['data_set'][0]['name']          = $res['datasets']['dataset']['datasetname'];
		$a['data_set'][0]['last_saved']    = $res['datasets']['dataset']['lastsaved']; // eg 2/4/2016 8:58:14 AM
		$a['data_set'][0]['mobile_number'] = $res['datasets']['dataset']['mobilenumber'] === array() ? '' : $res['datasets']['dataset']['mobilenumber'];
		for ($i=1;$i<=25;$i++) {
			$a['data_set'][0]['col_'.$i]['name']  = $res['datasets']['dataset']['col'.$i]['colname'];
			$a['data_set'][0]['col_'.$i]['value'] = $res['datasets']['dataset']['col'.$i]['coldata'] === array() ? '' : $res['datasets']['dataset']['col'.$i]['coldata'];
		}
	}
	else {
		// multiple results
		$d=0;
		foreach ($res['datasets']['dataset'] as $data) {
			$a['data_set'][$d]['id']            = $data['datasetid'];
			$a['data_set'][$d]['name']          = $data['datasetname'];
			$a['data_set'][$d]['last_saved']    = $data['lastsaved']; // eg 2/4/2016 8:58:14 AM
			$a['data_set'][$d]['mobile_number'] = $data['mobilenumber'] === array() ? '' : $data['mobilenumber'];
			for ($i = 1; $i <= 25; $i++) {
				$a['data_set'][$d]['col_'.$i]['name']  = $data['col' . $i]['colname'];
				$a['data_set'][$d]['col_'.$i]['value'] = $data['col' . $i]['coldata'] === array() ? '' : $data['col' . $i]['coldata'];
			}
			$d++;
		}
	}

	$rtn['data'] = $a;
}
else {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'Unexpected response from GraphicMail';
}
