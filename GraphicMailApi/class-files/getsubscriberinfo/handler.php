<?php

if ($this->result == '') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'No response from GraphicMail';
}
else if ($this->result == '0|No Email specified, or invalid email') { // this string is also returned if the email does not exist in any mailing lists or datasets
	$rtn['status'] = 'OK';
	$rtn['data']['mailing_list'] = NULL;
	$rtn['data']['dataset']      = NULL;
}
else if (substr($this->result, 0, 2) == '0|') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = substr($this->result,2);
}
else {

	$rtn['status'] = 'OK';

	$res = json_decode(json_encode(simplexml_load_string($this->result)),true);

	// process mailing list results
	if ($res['mailinglists'] == 'None') {
		// no results
		$a['mailing_list'] = NULL;
	}
	else if (isset($res['mailinglists']['mailinglist']['mailinglistid'])) {
		// a single result
		$a['mailing_list'][0]['id']     = $res['mailinglists']['mailinglist']['mailinglistid'];
		$a['mailing_list'][0]['name']   = $res['mailinglists']['mailinglist']['mailinglistname'];
		$a['mailing_list'][0]['email']  = $res['mailinglists']['mailinglist']['email'];
		$a['mailing_list'][0]['status'] = $res['mailinglists']['mailinglist']['status'];
		$a['mailing_list'][0]['date']   = $res['mailinglists']['mailinglist']['date']; // eg 11/02/2016 10:22, 23/10/2014 04:05
	}
	else {
		// multiple results
		$i=0;
		foreach ($res['mailinglists']['mailinglist'] as $data) {
			$a['mailing_list'][$i]['id']     = $data['mailinglistid'];
			$a['mailing_list'][$i]['name']   = $data['mailinglistname'];
			$a['mailing_list'][$i]['email']  = $data['email'];
			$a['mailing_list'][$i]['status'] = $data['status'];
			$a['mailing_list'][$i]['date']   = $data['date']; // eg 11/02/2016 10:22, 23/10/2014 04:05
			$i++;
		}
	}

	// process dataset results
	if ($res['datasets'] == 'None') {
		// no results
		$a['dataset'] = NULL;
	}
	else if ($res['datasets']['dataset']['datasetid']) {
		// a single result
		$a['dataset'][0]['id']            = $res['datasets']['dataset']['datasetid'];
		$a['dataset'][0]['name']          = $res['datasets']['dataset']['datasetname'];
		$a['dataset'][0]['last_saved']    = $res['datasets']['dataset']['lastsaved']; // eg 2/4/2016 8:58:14 AM
		$a['dataset'][0]['mobile_number'] = $res['datasets']['dataset']['mobilenumber'] === array() ? '' : $res['datasets']['dataset']['mobilenumber'];
		for ($i=1;$i<=25;$i++) {
			$a['dataset'][0]['col'][$i]['name'] = $res['datasets']['dataset']['col'.$i]['colname'];
			$a['dataset'][0]['col'][$i]['data'] = $res['datasets']['dataset']['col'.$i]['coldata'] === array() ? '' : $res['datasets']['dataset']['col'.$i]['coldata'];
		}
	}
	else {
		// multiple results
		$d=0;
		foreach ($res['datasets']['dataset'] as $data) {
			$a['dataset'][$d]['id']            = $data['datasetid'];
			$a['dataset'][$d]['name']          = $data['datasetname'];
			$a['dataset'][$d]['last_saved']    = $data['lastsaved']; // eg 2/4/2016 8:58:14 AM
			$a['dataset'][$d]['mobile_number'] = $data['mobilenumber'] === array() ? '' : $data['mobilenumber'];
			for ($i = 1; $i <= 25; $i++) {
				$a['dataset'][$d]['col'][$i]['name'] = $data['col' . $i]['colname'];
				$a['dataset'][$d]['col'][$i]['data'] = $data['col' . $i]['coldata'] === array() ? '' : $data['col' . $i]['coldata'];
			}
			$d++;
		}
	}

	$rtn['data'] = $a;
}
