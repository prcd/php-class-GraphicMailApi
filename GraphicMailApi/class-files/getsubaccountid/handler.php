<?php

if ($this->result == '') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'No response from GraphicMail';
}
else if ($this->result == '0|Subaccount does not exist.') {
	$rtn['status'] = 'OK';
	$rtn['data']['username_exists'] = '0';
	$rtn['data']['id']              = NULL;
}
else if (substr($this->result, 0, 2) == '0|') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = substr($this->result,2);
}
else if (ctype_digit($this->result)) {
	$rtn['status'] = 'OK';
	$rtn['data']['username_exists'] = '1';
	$rtn['data']['id']              = $this->result;
}
else {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'Unexpected response from GraphicMail';
}
