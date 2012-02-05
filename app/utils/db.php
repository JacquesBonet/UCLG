<?php

function getDB() {
    // These four parameters must be changed dependent on your MySQL settings
    $hostdb = 'localhost';   // MySQl host
    $userdb = 'uclggold_gold';    // MySQL username
    $passdb = 'AZErty123';    // MySQL password
    $namedb =  'uclggold_gold'; // MySQL database name

	
  	$link = mysql_connect ($hostdb, $userdb, $passdb, true);
		
    if (!$link) {
        // we should have connected, but if any of the above parameters
        // are incorrect or we can't access the DB for some reason,
        // then we will stop execution here
        die('Could not connect: ' . mysql_error());
    }
    
    mysql_set_charset('utf8',$link);

    $db_selected = mysql_select_db($namedb);
    if (!$db_selected) {
        die ('Can\'t use database : ' . mysql_error());
    }
    return $link;
}

function getRegionIdFromRegion( $region) {
	$link = getDB();
		
    $result = mysql_query( "SELECT id FROM region WHERE nombre = '" . $region . "'", $link) or die(mysql_error());
    $row = mysql_fetch_assoc( $result);

    mysql_close($link);

    if (isset( $row["id"]))
        return $row["id"];
    else
        return 0;
}


function getThemeIdFromTheme( $theme) {
	$link = getDB();

    $result = mysql_query( "SELECT id FROM themes WHERE themeName = '" . $theme . "'", $link) or die(mysql_error());
    $row = mysql_fetch_assoc( $result);

    mysql_close($link);

    if (isset( $row["id"]))
        return $row["id"];
    else
        return 0;
}

function getOrganisationIdFromOrganisation( $organisation) {
	$link = getDB();

    $result = mysql_query( "SELECT id FROM organisation WHERE organisationName = '" . $organisation . "'", $link) or die(mysql_error());
    $row = mysql_fetch_assoc( $result);

    mysql_close($link);

    if (isset( $row["id"]))
        return $row["id"];
    else
        return 0;
}

function getRegionIdFromCountry( $country) {
	$link = getDB();

	if ($i = strrpos( $country, ".pdf") > 0)
		$country = substr( $country, 0, $i);
    $result = mysql_query( "SELECT idRegion FROM countries WHERE name = '" . $country . "'", $link) or die(mysql_error());
    $row = mysql_fetch_assoc( $result);

    mysql_close($link);

    if (isset( $row["idRegion"]))
        return $row["idRegion"];
    else
        return 0;
}
?>
