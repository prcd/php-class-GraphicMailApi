<?php

if ($this->result == '') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'No response from GraphicMail';
}
else if (substr($this->result, 0, 2) == '0|') {
	$rtn['status']  = 'ERR';
	$rtn['message'] = substr($this->result,2);
}
else if (substr($this->result, 0, 2) == '1|')  {
	$rtn['status'] = 'OK';

	$rtn['data']['inserted'] = '1';
	$rtn['data']['updated']  = '0';
}
else if (substr($this->result, 0, 2) == '2|')  {
	$rtn['status'] = 'OK';

	$rtn['data']['inserted'] = '0';
	$rtn['data']['updated']  = '1';
}
else {
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'Unexpected response from GraphicMail';
}
