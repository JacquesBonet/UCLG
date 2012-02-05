<style>
<!--
	.odd {
		background-color: #f5f5f5;
		padding: 5px;
		border-bottom: 1px solid #ccc;
	}
	.even {
		padding: 5px;
		border-bottom: 1px solid #ccc;
	}
	.contentpaneopen th {
		background-color: #4B7DB0;
		color: white;
		padding: 5px;
	}
-->
</style>
<!-- JooDatabase: initial template for new databases  -->
<table class="contentpaneopen" width="100%">
	<tr>
	<!-- Select titles by first char -->
		<td align="center" valign="top">{joodb alphabox}</td>
	</tr>
	<tr>
	<!-- Search box -->
		<td align="center" valign="top">{joodb searchbox}</td>
	</tr>
	<tr>
		<!-- Remember {joodb FIELDNAME} is replaced by field content  -->
		<th>#C_DEFAULT_HEADER</th>
	</tr>
	<tr>
		<td>
		<!-- Loop here... dont remove the two joodb loop declarations -->
		<!-- You can limit the length of field by adding a |111 to field wildcard e.g. {joodb longtext|150} -->
		{joodb loop}
			#C_DEFAULT_LOOP
		{joodb loop}
		<br><h3>{joodb nodata}</h3>
		</td>
	</tr>
	<tr>
	<!-- Page Navigation -->
		<td align="center" valign="top">{joodb pagenav}<br/>{joodb pagecount}{joodb limitbox}</td>
	</tr>
</table>
