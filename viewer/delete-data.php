<?php
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		$db = mysqli_connect('localhost', 'fnx_webServiceAPI', 'MtRq3RWe2f2rphrS', 'fnx_webServiceAPI');
		mysqli_set_charset($db, "utf8");

		$id = $_POST['id'];

		mysqli_query($db, "DELETE FROM `api-leads` WHERE id = '" . $id . "'");


		mysqli_close($db);
	}
?>