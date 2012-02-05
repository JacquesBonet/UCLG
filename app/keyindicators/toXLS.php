<?php

require_once __DIR__ . "/../utils/db.php";

	$db = getDB();
    $filename = "countries.xls";

    // END CHANGING STUFF


    // first thing that we are going to do is make some functions for writing out
    // and excel file. These functions do some hex writing and to be honest I got 
    // them from some where else but hey it works so I am not going to question it 
    // just reuse


    // This one makes the beginning of the xls file
    function xlsBOF() {
        echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
        return;
    }

    // This one makes the end of the xls file
    function xlsEOF() {
        echo pack("ss", 0x0A, 0x00);
        return;
    }

    // this will write text in the cell you specify
    function xlsWriteLabel($Row, $Col, $Value ) {
        $L = strlen($Value);
        echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
        echo $Value;
        return;
    }

    $q = "SELECT * FROM countries ORDER BY name ASC";
    $qr = mysql_query( $q, $db) or die( mysql_error() );

    //start the object
     ob_start();

    // start the file
    xlsBOF();

    // these will be used for keeping things in order.
    $col = 0;
    $row = 0;

    // This tells us that we are on the first row
    $first = true;

    while( $qrow = mysql_fetch_assoc( $qr ) )
    {
        // Ok we are on the first row
        // lets make some headers of sorts
        if( $first )
        {
            foreach( $qrow as $k => $v )
            {
                // take the key and make label
                // make it uppper case and replace _ with ' '
                xlsWriteLabel( $row, $col, strtoupper( ereg_replace( "_" , " " , $k ) ) );
                $col++;
            }

            // prepare for the first real data row
            $col = 0;
            $row++;
            $first = false;
        }

        // go through the data
        foreach( $qrow as $k => $v )
        {

            // write it out
            xlsWriteLabel( $row, $col, $v );
            $col++;
        }

        // reset col and goto next row
        $col = 0;
        $row++;

    }

    xlsEOF();

    //write the contents of the object to a file
    file_put_contents($filename, ob_get_clean());

?>
