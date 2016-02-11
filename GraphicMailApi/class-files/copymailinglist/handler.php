<?php

if ($this->result == '')
{
	$rtn['status']  = 'ERR';
	$rtn['message'] = 'No response from GraphicMail';
}
else if (substr($this->result, 0, 2) == '0|')
{
	$rtn['status']  = 'ERR';
	$rtn['message'] = substr($this->result,2);
}
else
{
	$rtn['status'] = 'OK';
	$rtn['data']   = NULL;
}
