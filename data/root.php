<?php

	session_start();
	
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
	//setSession();
	
	$con=mysqli_connect($_SESSION['server'],$_SESSION['username'],$_SESSION['password'],$_SESSION['db']);
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
	$con=mysqli_connect($_SESSION['server'],$_SESSION['username'],$_SESSION['password']);
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
			echo "		\"data\": \"".$DB."*".$tblName."\", "; 
			echo "		\"item\": \"".$DB."*".$tblName."\", "; 
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
	$con=mysqli_connect($_SESSION['server'],$_SESSION['username'],$_SESSION['password'],$DB);
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
	
	$MyArray=explode("*",$pId);
	//echo chr(13)."pId: ".$pId;
	$DB=$MyArray[0];	
	$TBL=$MyArray[1];	
	$field=$MyArray[2];	
	//echo chr(13)."DB: ".$DB;
	//echo chr(13)."TBL: ".$TBL;
	//echo chr(13)."field: ".$field;
	
	$mysqli = new mysqli($_SESSION['server'],$_SESSION['username'],$_SESSION['password'], $DB);
	if ($mysqli->connect_errno) 
	{
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	
	//$sql="SELECT 1 AS _one, '".$field."' AS _two FROM ".$TBL;
	$query="SELECT ".$field." FROM ".$TBL;





	if ($result = mysqli_query($mysqli, $query)) {

		/* Get field information for column 'SurfaceArea' */
		$finfo = mysqli_fetch_field_direct($result, 0);
		
		echo "{";		
		echo "		\"entitytype\": \"fieldMeta\", "; 		
		echo "		\"name\": \"Type:".$finfo->type."\", "; 		
		echo "		\"id\": \"$DB*$TBL*$field*$type\", "; 
		echo "		\"children\": false "; 
		echo "}";
		echo ",";
		echo "{";		
		echo "		\"entitytype\": \"fieldMeta\", "; 		
		echo "		\"name\": \"max_length:".$finfo->max_length."\", "; 		
		echo "		\"id\": \"$DB*$TBL*$field*$type\", "; 
		echo "		\"children\": false "; 
		echo "}";
		echo ",";
		echo "{";		
		echo "		\"entitytype\": \"fieldMeta\", "; 		
		echo "		\"name\": \"length:".$finfo->length."\", "; 		
		echo "		\"id\": \"$DB*$TBL*$field*$type\", "; 
		echo "		\"children\": false "; 
		echo "}";
		echo ",";		
		echo "{";		
		echo "		\"entitytype\": \"fieldMeta\", "; 		
		echo "		\"name\": \"Name:".$finfo->name."\", "; 		
		echo "		\"id\": \"$DB*$TBL*$field*$type\", "; 
		echo "		\"children\": false "; 
		echo "}";		


		
		//echo "\"id\": \"$DB*$TBL*$field*$type\", "; 
		//echo "\"Name\":\"".$finfo->name."\",";
		//echo "\"table\":\"".$finfo->table."\",";
		//echo "\"max_length\":\"".$finfo->max_length."\",";
		//echo "\"flags\":\"".$finfo->flags."\",";
		//echo "\"type\":\"".$finfo->type."\"";
		
		
		mysqli_free_result($result);
	}

	/* close connection */
	mysqli_close($mysqli);	
	
	//echo "	{" ;
	//echo "		\"entitytype\": \"fieldMeta\", "; 		
	//echo "		\"name\": \"Type:type\", "; 		
	//echo "		\"id\": \"$DB*$TBL*$field*$type\", "; 
	//echo "		\"children\": true "; 
	//echo "	}" ; 
	

}




function output($pString)
{
	echo "<br>".$pString."<br>";
}
	?>