<?php

// key   = Adjusted function name - full lowercase text, prefix 'post_' removed
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
);
