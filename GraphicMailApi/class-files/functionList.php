<?php

// key   = Adjusted function name - full lowercase text, no hyphens or underscores. If you enter 'functionname' the call() method will accept 'functionName', 'function_name', 'FunctionName' etc...
// value = GraphicMail function name

$functionList = array(
	'copydataset'                 => 'post_copy_dataset',
	'copymailinglist'             => 'post_copy_mailinglist',
	'createmailinglist'           => 'post_create_mailinglist',
	'createsubaccount'            => 'post_create_subaccount',
	'deletefromdataset'           => 'post_delete_from_dataset',
	'deleteemail'                 => 'post_delete_emailaddress',
	'deletestatusfrommailinglist' => 'post_delete_status_from_mailinglist',
	'deletesubaccount'            => 'post_delete_subaccount',
	'getcredits'                  => 'get_all_credits',
	'getdataset'                  => 'get_dataset',
	'getdatasets'                 => 'get_datasets',
	'getimportqueuedataset'       => 'get_importqueue_dataset',
	'getencryptedlogin'           => 'get_encrypted_login',
	'getmailinglist'              => 'get_mailinglist',
	'getmailinglists'             => 'get_mailinglists',
	'getsubaccountid'             => 'get_subaccountid',
	'getsubscriberinfo'           => 'get_subscriber_info',
	'importdataset'               => 'post_import_dataset',
	'importmailinglist'           => 'post_import_mailinglist',
	'importnewsletter'            => 'post_import_newsletter',
	'insertdata'                  => 'post_insertdata',
	'renamedataset'               => 'post_rename_dataset',
	'subscribe'                   => 'post_subscribe',
	'unsubscribe'                 => 'post_unsubscribe',
	'unsubscribeall'              => 'post_unsubscribe_all',
);
