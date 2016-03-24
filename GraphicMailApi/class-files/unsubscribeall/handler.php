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
	$rtn['data']['already_unsubscribed'] = '0';
}
else if (substr($this->result, 0, 2) == '2|') {
	$rtn['status'] = 'OK';
	$rtn['data']['already_unsubscribed'] = '1';
}
else {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'Unexpected response from GraphicMail';
}
