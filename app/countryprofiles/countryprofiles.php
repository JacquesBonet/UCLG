<script>
// Here we define Ext for the first time
   Ext = {
       buildSettings:{
          "scopeResetCSS": true  // Thanks, but I'll do my own scoping please
       }
   };
</script>
<link href="/extjs/resources/css/ext-all-scoped-gray.css" rel="stylesheet" />
<link href="/app/css/style.css" rel="stylesheet" type="text/css" />
<link href="/app/ux/grid/css/GridFilters.css" rel="stylesheet" type="text/css" />
<link href="/app/ux/grid/css/RangeMenu.css" rel="stylesheet" type="text/css" />

<?php
/*	if ($_SESSION['uclg_section'] == 'africa')
   	   echo '<link rel="stylesheet" type="text/css" href="/extjs/resources/css/uclg-africa.css" />';
	else
	if ($_SESSION['uclg_section'] == 'asia-pacific')
	   echo '<link rel="stylesheet" type="text/css" href="/extjs/resources/css/uclg-asia-pacific.css" />';
	else
	   echo '<link rel="stylesheet" type="text/css" href="/extjs/resources/css/ext-all-gray.css" />';
*/?>
<script type="text/javascript" src="/extjs/ext-all.js"></script>
<script type="text/javascript" src="/app/countryprofiles/grid-filter.js"></script>
<div id="idCountryprofiles">&nbsp;</div>

<?php

?>