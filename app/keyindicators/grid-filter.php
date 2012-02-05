<?php
require_once __DIR__ . "/../utils/db.php";

// collect request parameters
$start  = isset($_REQUEST['start'])  ? $_REQUEST['start']  :  0;
$count  = isset($_REQUEST['limit'])  ? $_REQUEST['limit']  : 200;
$sort   = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : null;
$filters = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : null;

if ($sort == null)
{
	$sortProperty = 'name';
	$sortDirection = 'ASC';
}
else
{
	$sortProperty = $sort[0]->property;
	$sortDirection = $sort[0]->direction;
}

// GridFilters sends filters as an Array if not json encoded
if (is_array($filters)) {
    $encoded = false;
} else {
    $encoded = true;
    $filters = json_decode($filters);
}

$where = ' TRUE ';
$qs = '';

// loop through filters sent by client
if (is_array($filters)) {
    for ($i=0;$i<count($filters);$i++){
        $filter = $filters[$i];

        // assign filter data (location depends if encoded or not)
        if ($encoded) {
            $field = $filter->field;
            $value = $filter->value;
            $compare = isset($filter->comparison) ? $filter->comparison : null;
            $filterType = $filter->type;
        } else {
            $field = $filter['field'];
            $value = $filter['data']['value'];
            $compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
            $filterType = $filter['data']['type'];
        }

        switch($filterType){
            case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
            case 'list' :
                if (strstr($value,',')){
                    $fi = explode(',',$value);
                    for ($q=0;$q<count($fi);$q++){
                        $fi[$q] = "'".$fi[$q]."'";
                    }
                    $value = implode(',',$fi);
                    $qs .= " AND ".$field." IN (".$value.")";
                }else{
                    $qs .= " AND ".$field." = '".$value."'";
                }
            Break;
            case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
            case 'numeric' :
                switch ($compare) {
                    case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
                    case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
                    case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
                }
            Break;
            case 'date' :
                switch ($compare) {
                    case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
                    case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
                    case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
                }
            Break;
        }
    }
    $where .= $qs;
}

if ($_COOKIE['uclg_section'] == 'latin-america')
	$where .= ' AND idRegion = 1';
else
if ($_COOKIE['uclg_section'] == 'north-america')
	$where .= ' AND idRegion = 2';
else
if ($_COOKIE['uclg_section'] == 'africa')
	$where .= ' AND idRegion = 3';
else
if ($_COOKIE['uclg_section'] == 'asia-pacific')
	$where .= ' AND idRegion = 4';
else
if ($_COOKIE['uclg_section'] == 'ue')
	$where .= ' AND idRegion = 5';
else
if ($_COOKIE['uclg_section'] == 'hors-ue')
	$where .= ' AND idRegion = 6';
else
if ($_COOKIE['uclg_section'] == 'eurasia')
	$where .= ' AND idRegion = 7';
else
if ($_COOKIE['uclg_section'] == 'mewa')
	$where .= ' AND idRegion = 8';

$query = "SELECT * FROM countries WHERE ".$where;
$query .= " ORDER BY ".$sortProperty." ".$sortDirection;

$query .= " LIMIT ".$start.",".$count;
	
$db = getDB();
$result = mysql_query( "SELECT id FROM countries WHERE ".$where, $db) or die(mysql_error());
$count = mysql_num_rows($result);
$result = mysql_query($query, $db) or die(mysql_error());
$rows = Array();
while($row = mysql_fetch_assoc( $result)) {
    array_push($rows, $row);
}

echo json_encode(Array(
    "total"=>$count,
    "data"=>$rows
));

?>