<?php
	header('Content-Type: application/json');
	include('holidays.php');

	echo  'var data = '.json_encode(getRedDays());
?>