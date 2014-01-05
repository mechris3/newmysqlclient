<?php



		
	
	$con=mysqli_connect("localhost","","","test");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	mysql_select_db('test');
	$result = mysql_query('select ID from testtable LIMIT 1');
	if (!$result) 
	{
		die('Query failed: ' . mysql_error());
	}
	/* get column metadata */
	$i = 0;
	while ($i < mysql_num_fields($result)) 
	{
		echo "Information for column $i:<br />\n";
		$meta = mysql_fetch_field($result, $i);
		if (!$meta) 
		{
			echo "No information available<br />\n";
		}
		echo "<pre>
		blob:         $meta->blob
		max_length:   $meta->max_length
		multiple_key: $meta->multiple_key
		name:         $meta->name
		not_null:     $meta->not_null
		numeric:      $meta->numeric
		primary_key:  $meta->primary_key
		table:        $meta->table
		type:         $meta->type
		unique_key:   $meta->unique_key
		unsigned:     $meta->unsigned
		zerofill:     $meta->zerofill
		</pre>";
		$i++;
	}
mysql_free_result($result);
	


function output($pString)
{
	echo "<br>".$pString."<br>";
}
	?>