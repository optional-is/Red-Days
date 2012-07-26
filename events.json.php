<?php
	header('Content-Type: application/json');
	// This could easily be a static file or your favourite programming language.
	// All that is needed is a key value pair of {UNIX-TIMESTAMP:'holiday name',...}

	// this include generates the strange moving holidays and is also used to create the dynamic icon
	include('holidays.php');

	// set it to a variable so no ajax needed, makes caching in the manifest possible
	echo  'var data = '.json_encode(getRedDays());
?>