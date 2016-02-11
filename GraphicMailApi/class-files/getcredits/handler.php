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
	
	$res = json_decode(json_encode(simplexml_load_string($this->result)),true);
	
	// corporate account details
	$rtn['data']['corp']['normal']        = $res['mainaccount']['normalcredits'];
	$rtn['data']['corp']['non_expire']    = $res['mainaccount']['nonexpirecredits'];
	$rtn['data']['corp']['monthly']       = $res['mainaccount']['monthlycredits'];
	$rtn['data']['corp']['inbox_preview'] = $res['mainaccount']['inboxpreviewcredtis'];
	$rtn['data']['corp']['mobile']        = $res['mainaccount']['mobilecredits'];
	$rtn['data']['corp']['sift']          = $res['mainaccount']['siftcredits'];
	
	// was a sub account id specified?
	if ($this->queryParams['SubAccountID'])
	{
		if ($res['subaccounts']['subaccount'])
		{
			$d  = $res['subaccounts']['subaccount'];
			
			$rtn['data']['sub_account']['id']            = $d['subaccountid'];
			$rtn['data']['sub_account']['username']      = $d['username'];
			$rtn['data']['sub_account']['normal']        = $d['normalcredits'];
			$rtn['data']['sub_account']['non_expire']    = $d['nonexpirecredits'];
			$rtn['data']['sub_account']['monthly']       = $d['monthlycredits'];
			$rtn['data']['sub_account']['inbox_preview'] = $d['inboxpreviewcredtis'];
			$rtn['data']['sub_account']['mobile']        = $d['mobilecredits'];
			$rtn['data']['sub_account']['sift']          = $d['siftcredits'];
		}
		else
		{
			$rtn['data']['sub_account'] = NULL;
		}
	}
	else
	{
		// list results
		if ($res['subaccounts']['subaccount']['0'])
		{
			// multiple results
			foreach($res['subaccounts']['subaccount'] as $d)
			{
				$id = $d['subaccountid'];
				
				$rtn['data']['sub_account'][$id]['id']            = $id;
				$rtn['data']['sub_account'][$id]['username']      = $d['username'];
				$rtn['data']['sub_account'][$id]['normal']        = $d['normalcredits'];
				$rtn['data']['sub_account'][$id]['non_expire']    = $d['nonexpirecredits'];
				$rtn['data']['sub_account'][$id]['monthly']       = $d['monthlycredits'];
				$rtn['data']['sub_account'][$id]['inbox_preview'] = $d['inboxpreviewcredtis'];
				$rtn['data']['sub_account'][$id]['mobile']        = $d['mobilecredits'];
				$rtn['data']['sub_account'][$id]['sift']          = $d['siftcredits'];
			}
		}
		else if ($res['subaccounts']['subaccount'])
		{
			// single result
			
			$d  = $res['subaccounts']['subaccount'];
			$id = $d['subaccountid'];
			
			$rtn['data']['sub_account'][$id]['id']            = $id;
			$rtn['data']['sub_account'][$id]['username']      = $d['username'];
			$rtn['data']['sub_account'][$id]['normal']        = $d['normalcredits'];
			$rtn['data']['sub_account'][$id]['non_expire']    = $d['nonexpirecredits'];
			$rtn['data']['sub_account'][$id]['monthly']       = $d['monthlycredits'];
			$rtn['data']['sub_account'][$id]['inbox_preview'] = $d['inboxpreviewcredtis'];
			$rtn['data']['sub_account'][$id]['mobile']        = $d['mobilecredits'];
			$rtn['data']['sub_account'][$id]['sift']          = $d['siftcredits'];
		}
		else
		{
			// no results
			$rtn['data']['sub_account'] = NULL;
		}
	}
}
