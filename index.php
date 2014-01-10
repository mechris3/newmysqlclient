<?php
session_start();
// store session data
$_SESSION['views']=1;
?>
<html>
<head>
<style type="text/css">

@import  "javascripts/dojo183/dijit/themes/claro/claro.css" ; 
@import  "javascripts/dojo183/dojo/resources/dojo.css";
@import  "javascripts/dojo183/dojox/grid/resources/tundraGrid.css" ; 
</style>
<script type="text/javascript" src="javascripts/dojo183/dojo/dojo.js" djConfig="parseOnLoad: true" ></script>
<script type="text/javascript" src="javascripts/mysqlclient/init.js" ></script>
<!--
	<LINK REL="SHORTCUT ICON" HREF="favicon.png">
	<script type="text/javascript" src="windows_js/javascripts/prototype.js"> </script>
	<script type="text/javascript" src="windows_js/javascripts/window.js"> </script>
	<script type="text/javascript" src="windows_js/javascripts/effects.js"> </script>
	<link href="windows_js/themes/default.css" rel="stylesheet" type="text/css"/>
-->	
<!--  Add this to have a specific theme-->
<!--
<link href="windows_js/themes/lighting.css" rel="stylesheet" type="text/css"/>  
<style type="text/css">
	@import  "treeview.css?ts=#($H)#" ;
</style>

-->
<style type="text/css">
.serverNode 
{ 
	background-image:url('images/icon.png');
	background-repeat:no-repeat;
	width: 16px;
	height: 16px;
	background-position:0px 0px;
}

.databaseNode 
{ 
	background-image:url('images/icon.png');
	background-repeat:no-repeat;
	width: 16px;
	height: 16px;
	background-position:-16px 0px;
}
.tableNode 
{ 
	background-image:url('images/icon.png');
	background-repeat:no-repeat;
	width: 16px;
	height: 16px;
	background-position:-32px 0px;
}
.fieldNode 
{ 
	background-image:url('images/icon.png');
	background-repeat:no-repeat;
	width: 16px;
	height: 16px;
	background-position:-48px 0px;
}
.fieldMetaNode 
{ 
	background-image:url('images/icon.png');
	background-repeat:no-repeat;
	width: 16px;
	height: 16px;
	background-position:-64px 0px;
}

</style>
<style>
label {
width:200px;
float:left;
}
</style>
<title>New My SQL Client</title>
</head>
<body id="body" class="claro">

</body>
</html>