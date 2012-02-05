<?php
require_once __DIR__ . "/../utils/db.php";

// recherche des fichiers pdf

// collect request parameters
$start  = isset($_REQUEST['start'])  ? $_REQUEST['start']  :  0;
$limit  = isset($_REQUEST['limit'])  ? $_REQUEST['limit']  : 200;
$sort   = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : null;
$filters = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : null;

// GridFilters sends filters as an Array if not json encoded
if (is_array($filters)) {
    $encoded = false;
} else {
    $encoded = true;
    $filters = json_decode($filters);
}

$filterRegions = Array();

// take the region id in place of the region name
$filter = null;
if (is_array($filters))
{
	for ($i=0;$i<count($filters);$i++)
	{
        if ($encoded)
			$filterRegions[$i] = getRegionIdFromRegion( $filters[$i]->value);
		else
			$filterRegions[$i] = getRegionIdFromRegion( $filters[$i]['data']['value']);
	}
}
	
$count = 0;
$rows = Array();

$root_dir = __DIR__ . "/../../images/stories/pdf/country-profiles";
$dir_content = scandir( $root_dir);

if ($sort[0]->direction == "ASC")
    asort( $dir_content);
else
    asort( $dir_content);

foreach( $dir_content as $key => $content)
{
  $path = $root_dir .'/'.$content;
  
  if(is_file($path) && is_readable($path) && strpos( $content, ".pdf") != FALSE)
  {
    $pos = strrpos( $content, ".pdf");
    $country = substr( $content, 0, $pos);
	$region = getRegionIdFromCountry( $country);
	if ($region == 0)								// autre methode de calcul de la region Algeria_3.pdf
	{
	    $pos = strrpos( $content, "_");
        $country = substr( $content, 0, $pos);
        $region = substr( $content, $pos + 1, strlen( $content) - $pos - 5);
	}
	if ($_COOKIE['uclg_section'] == 'latin-america')
	{
	  if ($region != 7)
		continue;
	}
	else
	if ($_COOKIE['uclg_section'] == 'north-america')
	{
	  if ($region != 9)
		continue;
	}
	if ($_COOKIE['uclg_section'] == 'africa')
	{
	  if ($region != 3)
		continue;
	}
	else
	if ($_COOKIE['uclg_section'] == 'asia-pacific')
	{
	  if ($region != 4)
		continue;
	}
	else
	if ($_COOKIE['uclg_section'] == 'ue')
	{
	  if ($region != 10)
		continue;
	}
	else
	if ($_COOKIE['uclg_section'] == 'hors-ue')
	{
	  if ($region != 6)
		continue;
	}
	else
	if ($_COOKIE['uclg_section'] == 'eurasia')
	{
	  if ($region != 5)
		continue;
	}
	else
	if ($_COOKIE['uclg_section'] == 'mewa')
	{
	  if ($region != 8)
		continue;
	}

	if (count( $filterRegions) > 0)
	{
		$i=0;
		for ($i = 0;$i<count($filterRegions);$i++)
		{
			if ($filterRegions[$i] == $region)
				break;
		}
		if ($i == count($filterRegions))
			continue;
	}

    $count++;

    if ($count <= $start)
      continue;
    if (($count - $start) <= $limit)
    {
        $row["id"] = $count;
        $row["region"] = ucwords($region);
        $row["name"] = ucwords($country);
        $row["download"] = "/images/stories/pdf/country-profiles/$content#view=Fit";
        array_push($rows, $row);
    }
  }
}

echo json_encode(Array(
    "total"=>$count,
    "data"=>$rows
));


?>

