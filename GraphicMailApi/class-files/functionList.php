<?php

// key   = Adjusted function name - full lowercase text, no hyphens or underscores. If you enter 'functionname' the call() method will accept 'functionName', 'function_name', 'FunctionName' etc...
// value = GraphicMail function name

$functionList = array(
	'copymailinglist'             => 'post_copy_mailinglist',
	'createmailinglist'           => 'post_create_mailinglist',
	'deletestatusfrommailinglist' => 'post_delete_status_from_mailinglist',
	'getcredits'                  => 'get_all_credits',
	'getsubscriberinfo'           => 'get_subscriber_info',
	'getimportqueuedataset'       => 'get_importqueue_dataset',
	'getmailinglist'              => 'get_mailinglist',
	'getmailinglists'             => 'get_mailinglists',
	'importdataset'               => 'post_import_dataset',
	'importmailinglist'           => 'post_import_mailinglist',
	'insertdata'                  => 'post_insertdata',
	'subscribe'                   => 'post_subscribe',
	'unsubscribe'                 => 'post_unsubscribe',
);
