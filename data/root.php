<?php
	echo "{ ";
	echo "\"name\": \"server\",";
	echo "		\"entitytype\": \"server\", "; 
	echo "\"id\": \"server\",";
	echo "\"children\": [";
	//echo "	{ ";
	$id="";
	if(isset($_GET['id'])) { $id=$_GET["id"]; }
	if ($id=="server") { getDatabases(); };
	if(isset($_GET['entity'])) 
	{ 
		$entity=$_GET["entity"]; 
		if ($entity=="database")
		{			
			getTables($id);
		}
		if ($entity=="table")
		{
			getFields($id);
		}		
		if ($entity=="field")
		{
			getFieldMetaData($id);
		}			
	}

	echo "] "; 
	echo "} ";

function getDatabases()
{	
	$con=mysqli_connect("localhost","","");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$i=0;
	$result = mysqli_query($con,"SHOW DATABASES");
	while($row = mysqli_fetch_array($result))
	{
			$dbName=$row['Database'] ;
			$i=$i+1;
			if ($i>1) 
			{
				echo ","; 
			}
			echo "	{" ;
			echo "		\"entitytype\": \"database\", "; 
			echo "		\"name\": \"".$dbName."\", "; 
			echo "		\"id\": \"server*".$dbName."\", "; 
			echo "		\"children\": true "; 
			echo "	} " ; 	
	}
	mysqli_close($con);
}
	
function getTables($pId)
{		
	
	$MyArray=explode("*",$pId);
	$DB=$MyArray[1];	
	$con=mysqli_connect("localhost","","");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$i=0;
	$sql="SHOW TABLES FROM ".$DB;	
	$result = mysqli_query($con,$sql);
	if (!$result) 
	{
		echo "DB Error, could not list tables\n";
		echo 'MySQL Error: ' . mysqli_error($con);
		exit;
	}
	
	while($row = mysqli_fetch_array($result))
	{
			$tblName=$row[0] ;
			$i=$i+1;
			if ($i>1) 
			{
				echo ","; 
			}
			echo "	{" ;
			echo "		\"entitytype\": \"table\", "; 
			echo "		\"name\": \"".$tblName."\", "; 
			echo "		\"id\": \"".$DB."*".$tblName."\", "; 
			echo "		\"children\": true "; 
			echo "	} " ; 	
	}	
}	

function getFields($pId)
{		
	//output($pId);
	$MyArray=explode("*",$pId);
	$DB=$MyArray[0];	
	$TBL=$MyArray[1];	
	//output("DB:".$DB);	
	//output("TBL:".$TBL);	
	$con=mysqli_connect("localhost","","",$DB);
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$i=0;
	$sql="SHOW COLUMNS FROM ".$TBL;	
	//output("sql:".$sql);
	$result = mysqli_query($con,$sql);
	if (!$result) 
	{
		echo "DB Error, could not list tables<br>";
		echo 'MySQL Error: ' . mysqli_error($con);
		exit;
	}	
	while($row = mysqli_fetch_array($result))
	{
	
			$FieldName=$row[0] ;
			$i=$i+1;
			if ($i>1) 
			{
				echo ","; 
			}
			echo "	{" ;
			echo "		\"entitytype\": \"field\", "; 
			echo "		\"name\": \"".$FieldName."\", "; 
			echo "		\"id\": \"".$DB."*".$TBL."*".$FieldName."\", "; 
			echo "		\"children\": true "; 
			echo "	} " ; 	
	}	
}	


function getFieldMetaData($pId)
{		
	
	//output($pId);
	$MyArray=explode("*",$pId);
	$DB=$MyArray[0];	
	$TBL=$MyArray[1];	
	$field=$MyArray[2];	
	
	$con=mysqli_connect("localhost","","",$DB);
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	mysql_select_db('test');
	$result = mysql_query("select ".$field." from ".$TBL." LIMIT 1");
	if (!$result) 
	{
		die('Query failed: ' . mysql_error());
	}
	/* get column metadata */
	$i = 0;
	while ($i < mysql_num_fields($result)) 
	{
		
		$meta = mysql_fetch_field($result, $i);
		if (!$meta) 
		{
			echo "No information available<br />\n";
		}
		
			$i++;
			$type=$meta->type;
			$max_length=$meta->max_length;
			if ($i>1) 
			{
				echo ","; 
			}
			echo "	{" ;
			echo "		\"entitytype\": \"fieldMeta\", "; 
			echo "		\"name\": \"Type:".$type."\", "; 
			echo "		\"id\": \"".$DB."*".$TBL."*".$field."*".$type."\", "; 
			echo "		\"children\": true "; 
			echo "	}, " ; 	
			echo "	{" ;
			echo "		\"entitytype\": \"fieldMeta\", "; 
			echo "		\"name\": \"Max Length:".$max_length."\", "; 
			echo "		\"id\": \"".$DB."*".$TBL."*".$field."*".$max_length."\", "; 
			echo "		\"children\": true "; 
			echo "	} " ; 				
			$i = mysql_num_fields($result);
		/*
		echo "<pre>
		blob:         $meta->blob
		
		multiple_key: $meta->multiple_key
		name:         $meta->name
		not_null:     $meta->not_null
		numeric:      $meta->numeric
		primary_key:  $meta->primary_key
		table:        $meta->table
		
		unique_key:   $meta->unique_key
		unsigned:     $meta->unsigned
		zerofill:     $meta->zerofill
		</pre>";
		*/
		
	}
mysql_free_result($result);
}	

function output($pString)
{
	echo "<br>".$pString."<br>";
}
	?>