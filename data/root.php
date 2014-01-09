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
	//$con=mysqli_connect("127.0.0.1","","","");
	$con=mysqli_connect("localhost","root","password","test");
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
	$con=mysqli_connect("localhost","root","password");
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
	$con=mysqli_connect("localhost","root","password",$DB);
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
	$DB=$MyArray[0];	
	$TBL=$MyArray[1];	
	$field=$MyArray[2];	
	$mysqli = new mysqli("localhost", "root", "password", $DB);
	if ($mysqli->connect_errno) 
	{
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	$result = $mysqli->query("SELECT 1 AS _one, 'Hello' AS _two FROM ".$TBL);	
	//var_dump($result->fetch_fields());
	$result->fetch_fields();
	//$result->fetch_fields();
	//$i = 0;
	while ($i < mysqli_num_fields($result)) 
	{
		$i++;
		if ($i>1) 
		{
			echo ","; 
		}
		echo "	{" ;
		echo "		\"entitytype\": \"fieldMeta\", "; 
		//echo "		\"name\": \"Type:".$type."\", "; 
		echo "		\"name\": \"Type:type\", "; 
		//echo "		\"id\": \"".$DB."*".$TBL."*".$field."*".$type."\", "; 
		echo "		\"id\": \"$DB*$TBL*$field*$type\", "; 
		echo "		\"children\": true "; 
		echo "	}, " ; 	
		echo "	{" ;
		echo "		\"entitytype\": \"fieldMeta\", "; 
		//echo "		\"name\": \"Max Length:".$max_length."\", "; 
		echo "		\"name\": \"Max Length:$max_length\", "; 
		//echo "		\"id\": \"".$DB."*".$TBL."*".$field."*".$max_length."\", "; 
		echo "		\"id\": \"".$DB."*$TBL*$field*$max_length\", "; 
		echo "		\"children\": true "; 
		echo "	} " ; 		
		
	//	echo "HI";
	}
	//$result->fetch_fields();
}




function output($pString)
{
	echo "<br>".$pString."<br>";
}
	?>