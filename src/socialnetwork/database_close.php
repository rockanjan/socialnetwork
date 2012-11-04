
<?php
//$query = 'SELECT * FROM current_catalog';
//$query = 'SELECT * FROM current_schema';
/*
$query = 'SELECT * FROM person';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
echo "<table>\n";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	echo "\t<tr>\n";
	foreach ($line as $col_value) {
		echo "\t\t<td>$col_value</td>\n";
	}
	echo "\t</tr>\n";
}
echo "</table>\n";
// Free resultset
pg_free_result($result);
*/
// Closing connection
pg_close($dbconn);

?>