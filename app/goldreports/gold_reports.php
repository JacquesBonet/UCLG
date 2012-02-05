<?php

require_once __DIR__ . "/../utils/lang.php";
/**
 * Display the Header of the GOLD Report
 *
 */
function GoldReportHeader()
{
	echo "<div style='float:left;width:100%'>";
}

/**
 * Display the footer of the GOLD Report
 *
 */
function GoldReportFooter()
{
	echo "</div>";
	echo "<div style='float:left; width:100%; margin-left:120px;margin-right:120px'>";
	echo "<img alt='' src=\"/images/stories/diputacio_barcelona.png\" style='margin: auto;'/> <img alt='' src=\"/images/stories/logo_generalitat.png\" style='margin: auto;' /> <img alt='' src=\"/images/stories/citiesalliance.png\" style='margin: auto;' /> <img alt='' height='100' src=\"/images/stories/afd_logo.jpg\" style='margin: auto;' width='103' />";
	echo "</div>";
}

/**
 * Display the footer of the GOLD Report
 *
 */
function GoldReport1Footer()
{
	echo "</div>";
	echo "<div style='float:left; width:100%; margin-left:120px;margin-right:120px'>";
	echo "<img alt='' src=\"/images/stories/diputacio_barcelona.png\" style='margin: auto;'/> <img alt='' src=\"/images/stories/logo_generalitat.png\" style='margin: auto;' /> <img alt='' src=\"/images/stories/region_pays_de_la_loire.png\" style='margin: auto;' /> <img alt='' height='100' src=\"/images/stories/afd_logo.jpg\" style='margin: auto;' width='103' />";
	echo "</div>";
}



/**
 * Display the GOLD Report picture and under it a button to display the report under issuuu parameters
 *
 * @param   string   $picture   The picture must be located under "images/stories/gold-reports" of the website.
 * @param   string   $issudoc   The issu document to display when we click on the button. Could be empty
 */
function GoldReportPicture( $picture, $issudoc = '')
{
	$issuUrl = "";
	$pdfUrl = "";
	
	echo "<div style='float:left;width:300px;margin:80px 15px 10px 50px;'>";

	echo "<p style='margin-top: 0.49cm; margin-bottom: 0.49cm; text-align: center;'><img alt='' src=\"/images/stories/gold-reports/$picture\" />";
	if ($issudoc != "")
	{
		echo "<p style='text-align: center;'>";
        if (getLang() == "fr")
            $lang = "Lire le rapport complet";
        else if (getLang() == "es")
            $lang = "Lea el informe completo";
        else
            $lang = "Read full report";
		echo "<a class='read_full' data-mce-onclick='' href=\"http://issuu.com/uclggold/docs/$issudoc?mode=embed\">$lang</a>";
		echo "</p>";
	}
	echo "</div>";
}

/**
 * Display the beginning of the Chapter section
 * @param   string   $pdfDoc   		The picture must be located under "images/stories/gold-reports" of the website.
 *
 */
function GoldReportBeginChapter( $pdfDoc = null)
{
	echo "<div style='float:right;padding:5px 60px 10px 80px; border-left-style:solid;border-left-width:1px;'>";
    if ($pdfDoc != null)
	{
		if (getLang() == "fr")
			$lang = "Rapport complet";
		else if (getLang() == "es")
			$lang = "Informe completo";
		else
			$lang = "Full report";
	   	echo "<p style='text-align: center;margin-top:5px'><span style='font-size: 12pt;color:red'>$lang :</span></p>";
		echo "<p style='text-align: center;'><a class='gold_report' href=\"/images/stories/pdf/gold-reports/$pdfDoc#pagemode=bookmarks&amp;zoom=100\" target='_blank'>Gold Report</a></p>";
	}
    if (getLang() == "fr")
        $lang = "Chapitres";
    else if (getLang() == "es")
        $lang = "Capítulos";
    else
        $lang = "Chapters";
    echo "<p style='text-align: center;'><span style='font-size: 12pt;;color:red'>$lang:</span></p>";
}


/**
 * Display the end of the Chapter section. Mandatory with GoldReportBeginChapter
 *
 */
function GoldReportEndChapter()
{
	echo "</div>";
}

/**
 * Display a button with a link to a chapter of a GOLD Report
 *
 * @param   string   $title   		The chapter title.
 * @param   string   $pdfDoc   		The pdf doc. Path relative to "images/stories/gold-reports" directory of the website.
 * @param   string   $region        For a chapter concerning a region like Africa, $region indicate the region
 * @param   string   $pagenumber    The page number to display. Could be empty
 */
function GoldReportChapter( $title, $pdfDoc, $region = '', $pagenumber = '')
{
	$display = false;
	
	if ($_COOKIE['uclg_section'] == 'world')
		$display = true;
	else
        $display = isRegionTitle( $region);

    $cook = $_COOKIE['uclg_section'];
	if ($display)
	{
		if ($pagenumber != '')
			echo "<p style='text-align: center;margin:0px'><a class='gold_report' href=\"/images/stories/pdf/gold-reports/$pdfDoc#page=$pagenumber&amp;pagemode=bookmarks&amp;zoom=100\" target='_blank'>$title</a></p>";
		else
			echo "<p style='text-align: center;'><a class='gold_report' href=\"/images/stories/pdf/gold-reports/$pdfDoc#pagemode=bookmarks&amp;zoom=100\" target='_blank'>$title</a></p>";
	}
}

/**
 * Display a button with a link to a chapter of a GOLD Report, from Issuu website
 *
 * @param   string   $title   		The chapter title.
 * @param   string   $pdfDoc   		The pdf doc. Path relative to "images/stories/gold-reports" directory of the website.
 * @param   string   $region        For a chapter concerning a region like Africa, $region indicate the region
 * @param   string   $pagenumber    The page number to display. Could be empty
 */
function GoldReportIssuuChapter( $title, $issudoc, $region = '', $pagenumber = '')
{
	$display = false;
	
	if ($_COOKIE['uclg_section'] == 'world')
		$display = true;
	else
        $display = isRegionTitle( $region);

    $cook = $_COOKIE['uclg_section'];
	if ($display)
	{
		if ($pagenumber != '')
			echo "<a class='read_issuu' data-mce-onclick='' href=\"http://issuu.com/uclggold/docs/$issudoc/$pagenumber?mode=embed\" >$title</a>";
		else
			echo "<a class='read_issuu' data-mce-onclick='' href=\"http://issuu.com/uclggold/docs/$issudoc?mode=embed\" >$title</a>";
	}
}

function isRegionTitle( $region)
{
    if (strlen( $region) == 0)
        return true;
	if ( $region == $_COOKIE['uclg_section'] )
		return true;
    if ($region == "1" && $_COOKIE['uclg_section'] == "latin-america")
   		return true;
    if ($region == "2" && $_COOKIE['uclg_section'] == "north-america")
   		return true;
    if ($region == "3" && $_COOKIE['uclg_section'] == "africa")
   		return true;
    if ($region == "4" && $_COOKIE['uclg_section'] == "asia-pacific")
   		return true;
    if ($region == "5" && $_COOKIE['uclg_section'] == "ue")
   		return true;
    if ($region == "6" && $_COOKIE['uclg_section'] == "hors-ue")
   		return true;
    if ($region == "7" && $_COOKIE['uclg_section'] == "eurasia")
   		return true;
    if ($region == "8" && $_COOKIE['uclg_section'] == "mewa")
   		return true;
    return false;
}
?>



