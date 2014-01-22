dojo.require("dijit.layout.BorderContainer");
dojo.require("dojox.layout.ContentPane");

dojo.require("dijit.Menu");
dojo.require("dijit.MenuItem");

dojo.require("dojo.dnd.Source")
dojo.require("dojo.dnd.common");
dojo.require("dojo.dnd.Container");
dojo.require("dijit.tree.dndSource")



//dojo.require("dojo.parser");
//dojo.require("dojo.store.Memory");
//dojo.require("dijit.tree.ObjectStoreModel");
//dojo.require("dijit.Tree");


dojo.addOnLoad(initPage)
 
function initPage()
 {
	//document.write("HI");
	
	var borderLayout = new dijit.layout.BorderContainer( 
				{"title":"My SQL", "style":"width:100%; height:100%", "design": "sidebar", "gutters":true , "splitter":true, "liveSplitters":true}, dojo.byId("body"));		
	var cpLeft = new dojox.layout.ContentPane({"title":"Settings","region":"left","class":"container", "splitter":true,
	 style:"width:350px; background-color:#D3E0ED" });
	var cpRight = new dojox.layout.ContentPane({"title":"Settings","region":"center","class":"container", "splitter":true, style:"width:350px; background-color:#D3E0ED"} );
	
	cpLeft.set("content","<div id='dvTreeView' ></div>");
	cpRight.set("content","<div id='dvDetails'>Right</div>")
	
	cpRight.style.backgroundColor='red';
	

	
	borderLayout.addChild(cpLeft);
	borderLayout.addChild(cpRight);
	
	borderLayout.startup();
	createTree();
		
	dojo.byId("dvDetails").style.width="100%";
	dojo.byId("dvDetails").style.height="100%";
	
	
	var tgt = dojo.dnd.AutoSource("dvDetails", 
							{						
								
								checkAcceptance: function(source, nodes) 
								{ 
									return true 
								},
								/*
								checkItemAcceptance: function(a)
								{
									return true;
								},
								
								dndAccept: function()
								{
									return true;
								},*/
								onDrop: function(source, nodes, copy)
								{
																																			
									var item = dijit.getEnclosingWidget(nodes[0]).item;
									if (item.entitytype=="table")
									{
										alert("Table");
									}
									if (item.entitytype=="field")
									{
										createFieldEditForm(item)
									}
									log(item);
									return true;
									
								}
							})
	
 }
function kill(pId){ dijit.registry.forEach(function(widget,index,hash) { if(widget.id==pId) {widget.destroy();};});}
function createFieldEditForm(pID)
{
	alert("Edit fiedl");
}
function log(p) {console.log(p)}
function createTree()
 {
	try
	{
		
		require([
				"dojo/ready", "dojo/store/JsonRest",
				"dijit/Tree", "dijit/tree/ObjectStoreModel"
			], function(ready, JsonRest, Tree, ObjectStoreModel){

				// create store
				usGov = new JsonRest({
					target: "data/",
					getChildren: function(object){
						// object may just be stub object, so get the full object first and then return it's list of children
						return this.get("root.php?id="+object.id+"&entity="+object.entitytype).then(function(fullObject){ return fullObject.children; });
					}
				});

				// create model to interface Tree to store
				model = new ObjectStoreModel({
					store: usGov,
					getRoot: function(onItem){ this.store.get("root.php?id=server&entity=server").then(onItem); },
					mayHaveChildren: function(object){ return "children" in object;	}
				});

				ready(function(){
					tree = new Tree({ 
						model: model,	
						dndController: "dijit.tree.dndSource", 	
						type: "text",
						checkAcceptance: function() {return false},
						openOnClick: true,
						dndData: "test data",	
						data: "test data",	
						getIconClass: function(pItem) { return pItem.entitytype+"Node";	}
					}, "dvTreeView"); // make sure you have a target HTML element with this id
					tree.startup();
				});
			});
		
	}
	catch(e) { alert(arguments.callee.toString().split("(")[0]+"\n"+e.message) }
 }