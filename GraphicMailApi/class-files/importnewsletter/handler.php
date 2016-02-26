<?php

if ($this->result == '') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'No response from GraphicMail';
}
else if (substr($this->result, 0, 2) == '0|') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = substr($this->result,2);
}
else if (substr($this->result, 0, 2) == '1|') {
	$rtn['status'] = 'OK';

	$arr = explode('|',$this->result);

	if (isset($arr[2])) {
		// newsletter created
		$rtn['data']['id'] = $arr[1];
	}
	else {
		// newsletter updated
		$rtn['data'] = NULL;
	}
}
else {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'Unexpected response from GraphicMail';
}
