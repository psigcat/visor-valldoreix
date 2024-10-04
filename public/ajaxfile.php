<?php

include "./config.php";

$request = "";
$response = array();

if(isset($_POST['request']) && !empty($_POST['request'])) {
	$request = $_POST['request'];

	// Fetch números de calles
	// https://mapa.psig.es/bellamar/ajaxfile.php?carrer=68%20Riba&request=carrersNum

	if ($request == 'carrersNum' && isset($_POST['carrer']) && !empty($_POST['carrer'])) {

		$query = "SELECT npol_num1, npol_ccar, ST_AsGeoJSON(ST_Transform(geom, 3857)) as geom FROM sit_base.carrer_accessos WHERE npol_ccar='".$_POST['carrer']."' ORDER BY npol_num1";
		$result = pg_query($con, $query);

		while ($row = pg_fetch_assoc($result)) {
			$response[] = array(
				"npol_num1" => $row['npol_num1'],
				"npol_ccar" => $row['npol_ccar'],
				"geom" => $row['geom']
			);
		}
	}

	// Fetch números de calles
	// https://mapa.psig.es/bellamar/ajaxfile.php?refcat=08055A00100077&request=catasterGeom

	else if ($request == 'catasterGeom' && isset($_POST['refcat']) && !empty($_POST['refcat'])) {

		$query = "SELECT refcat, coorx, coory, ST_AsGeoJSON(ST_Transform(geom, 3857)) as geom FROM cadastre.parcela WHERE refcat='".$_POST['refcat']."'";
		$result = pg_query($con, $query);

		if (pg_numrows($result) > 0) {
		    $row = pg_fetch_assoc($result);

			$response[] = array(
			  "refcat" => $row['refcat'],
			  "coorx" => (float)$row['coorx'],
			  "coory" => (float)$row['coory'],
			  "geom" => $row['geom']
			);
		}
	}

	// Fetch fecha última actualización
	// https://mapa.psig.es/bellamar/ajaxfile.php?request=lastupdate

	else if ($request == 'lastupdate') {
		$result = pg_select($con, 'obres_bellamar.metadades', array('descripcio' => 'update'));
		$response = array('msg' => $result[0]['data']);
	}
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
echo json_encode($response);
die;