<!DOCTYPE html>
<html> 
    <head>
		<title>Todo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="author" content="Biju Maharjan">
        <meta name="description" content="todo">
        <meta name="date" content="July 11, 2017">   

        <script type="text/javascript" src="lib/dhtmlx/codebase/dhtmlx.js" ></script>
		<script type="text/javascript" src="lib/jQuery/jquery-1.11.1.js" ></script>
		
        <link rel="stylesheet" type="text/css" href="lib/dhtmlx/codebase/dhtmlx.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>

    <body onload="doOnLoad();">
        <div id="containter" style="width:100%; height:100%;"></div>
    </body>
</html>

<script type="text/javascript">
    
    /*
     * [onLoad Function]
     */
    doOnLoad = function () {
		loadLayout();
		loadMenu();
		loadGrid();
    }
    
    /*
     * [Create the layout structure]
     */
	loadLayout = function() {
		todoLayoutObj = new dhtmlXLayoutObject({
			parent: document.body,
			pattern: "1C"
		});
		todoLayoutObj.cells('a').setText("TODO!!");
	}
	
    /*
     * [Create the menu and menu click functions]
     */
	loadMenu = function() {
		todoMenuObj = todoLayoutObj.cells('a').attachMenu({
			icons_path: "lib/dhtmlx/codebase/imgs/",	
			items:	[
						{id:"refresh", text:"Refresh"},
						{id:"add", text:"Add"},
						{id:"edit", text:"Edit"},
						{id:"delete", text:"Delete"}
					]
		});
		
		todoMenuObj.attachEvent("onClick", function(id, zoneId, cas){
			if (id == "refresh") {
				gridRefresh();
			} else if (id == 'add') {
				gridDataUI(this, 'i');
			} else if (id == 'edit') {
				gridDataUI(this, 'u');
			} else if (id == 'delete') {
				gridDataDelete();
			}
		});
	}
	
    /*
     * [Create the grid]
     */
	loadGrid = function() {
		todoGridObj = todoLayoutObj.cells("a").attachGrid();
		todoGridObj.setImagePath("lib/dhtmlx/codebase/imgs/");                 
        todoGridObj.setHeader("ID, Name, Description, Date",null,["text-align:center;","text-align:center;","text-align:center","text-align:center"]);
        todoGridObj.attachHeader("#text_filter,#text_filter,#text_filter,#text_filter");
        todoGridObj.setInitWidths("120,250,500,250");
		todoGridObj.setColAlign("center,left,left,left");
		todoGridObj.setColTypes("ro,ro,ro,ro");
		todoGridObj.setColSorting("int,str,str,str");
		todoGridObj.init();
		
		gridRefresh();
	}
	
    /*
     * [Function to refresh the data in grid]
     */
	gridRefresh = function() {
		var sqlString = 'CALL spa_todo_select()';
        var loadURL = "generics/dataCollector.php?sql=" + sqlString;
        
        todoGridObj.clearAll();
		todoGridObj.load(loadURL);
	}
	
    /*
     * [Refresh the delete data in the grid]
     */
	gridDataDelete = function() {
		var selected_row = todoGridObj.getSelectedRowId();
		if (selected_row == null) {
			showMessage('Please select the row to be deleted.');
			return;
		}
		
		var todo_id = todoGridObj.cells(selected_row,0).getValue();
		
		var data = {
            "function": "todoDelete",
			"todo_id":todo_id
        };
		
		var url = "generics/routeTodo.php";
        data = $.param(data);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: url,
            async: true,
            data: data,
            complete: function(data) {
                gridRefresh();
            }
        });
		
	}
	
    /*
     * [Open the popup for todo insert and update]
     */
	var todoFormPopupObj;
	gridDataUI = function(obj, mode) {
		var todo_id = '';
		var todo_name = '';
		var todo_desc = '';
		
        if(mode == 'u') {
			var selected_row = todoGridObj.getSelectedRowId();
			if (selected_row == null) {
				showMessage('Please select the row to be updated.');
				return;
			}
			
			todo_id = todoGridObj.cells(selected_row,0).getValue();
			todo_name = todoGridObj.cells(selected_row,1).getValue();
			todo_desc = todoGridObj.cells(selected_row,2).getValue();
		}
		
		if (!todoFormPopupObj) {
            todoFormPopupObj = new dhtmlXPopup();
        }
		
		var x = window.dhx4.absLeft(obj);
        var y = window.dhx4.absTop(obj);
        var w = obj.offsetWidth;
        var h = obj.offsetHeight;
        
		todoFormPopupObj.show(20,20,100,100);
		
        todoFormObj = todoFormPopupObj.attachForm([
			{type: "settings", offset: 2, labelWidth: 80, inputWidth: 200, labelAlign: 'right'},
			{type: "label", label: "Todo Item"},
			{type: "input",    name: "id",    value: todo_id, label: "ID", hidden: 1},
			{type: "input", name: "name",     value: todo_name, label: "Name", required: 1, hidden: 0},
			{type: "input", name: "desc",     value: todo_desc, label: "Description", required: 0, hidden: 0, rows: 5},
            {"type": "block", "blockOffset": 0 , "list": [
			     {type: "button", name: "save", value: "Save", offsetLeft: 15}, 
                 {"type":"newcolumn"},
			     {type: "button", name: "cancel", value: "Cancel"}
            ]}
		]);
		
		todoFormObj.attachEvent('onButtonClick',function(name) {
            /*
             * [Save functionality for todo form]
             */
			if(name == 'save') {
				if(!todoFormObj.validate()){
					return;
				}
				var name = todoFormObj.getItemValue('name');
				var desc = todoFormObj.getItemValue('desc');
				var id = todoFormObj.getItemValue('id');
				var function_call = (mode == 'i' ? 'todoInsert' : 'todoUpdate');
				
				var data = {
					"function": function_call,
					"todo_name":name,
					"todo_desc":desc,
					"todo_id": id
				};
				
				var url = "generics/routeTodo.php";
				data = $.param(data);

				$.ajax({
					type: "POST",
					dataType: "json",
					url: url,
					async: true,
					data: data,
					complete: function(data) {
						todoFormPopupObj.hide();
						gridRefresh();
					}
				});
				
			} else if (name == 'cancel') {
				todoFormPopupObj.hide();
			}
		});
	}
	
	/*
     * [Alert Messaging]
     */
	showMessage = function(message) {
		dhtmlx.alert({
			text: message,
		});
	}
	
</script>

