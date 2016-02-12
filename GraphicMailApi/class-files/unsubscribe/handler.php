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

	if (substr($this->result,0,1) == '2') {
		$rtn['data']['already_unsubscribed'] = '1';
	}
	else {
		$rtn['data']['already_unsubscribed'] = '0';
	}
}
