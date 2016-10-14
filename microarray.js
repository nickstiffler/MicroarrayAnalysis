

function updateProjects() {
	if($('#proj_table')) {
		$.getJSON('ajax/project.php', 'projects=1', updateProjectsResult);
	}
}

function updateProjectsResult(result) {
	var table = $('#proj_table');
	for(proj in result) {
		var row = table.insertRow(0);
		var nameCell = row.insertCell(0);
		nameCell.appendChild(document.createTextNode(result[proj].name));
		var userCell = row.insertCell(1);
		userCell.appendChild(document.createTextNode(result[proj].user));
		var dateCell = row.insertCell(2);
		dateCell.appendChild(document.createTextNode(result[proj].date));
		var commentsCell = row.insertCell(3);
		commentsCell.appendChild(document.createTextNode(result[proj].comments));
		var editCell = row.insertCell(4);
		var edit = document.createElement("a");
		edit.href = "#";
		edit.setAttribute("onclick", "editProject(" + result[proj].id + ");");
		var glyph = document.createElement("i");
		glyph.setAttribute("class", "icon-pencil");
		edit.appendChild(glyph);
		editCell.appendChild(edit);
	}
}

function editProject(id) {
	$('#editProj').modal('show');

	$.getJSON('ajax/experiment.php', 'exps=1', editProjectResult);
}

function editProjectResult(results) {
	var exps_list = $("#exps_list");
	for(result in results) {
		exps_list.appendChild(document.createTextNode(results[result].name));
	}
}

function submitProject() {
	var name = $('#proj_name').val();
	var user = $('#proj_user').val();
	var comments = $('#proj_comments').val();

	if(name == "" || user == "") {
		$("#formError").show();
		return;
	}

	$.get("ajax/project.php", "name=" + name + "&user=" + user + "&comments=" + comments, submitProjectResult, "text");

	$('proj_exps').empty();
}

function submitProjectResult(result) {
	//alert(result);
	updateProjects();
	$("#newProj").modal('hide');

}

function deleteProject() {
	// I didn't implement this because the site is not secure yet
}

function clearNewProject() {
	$('proj_name').val("");
	$('proj_user').val("");
	$('proj_comments').val("");
	$('new_proj_exps').empty();
}

function updateExps() {
	if($('#exp_table')) {

		$.getJSON('ajax/experiment.php', 'exps=1', updateExpsResult);
	}
}

function updateExpsResult(result) {

	var table = $('#exp_table');
	for(exp in result) {
		var row = table.insertRow(0);
		var nameCell = row.insertCell(0);
		nameCell.appendChild(document.createTextNode(result[exp].name));
		var userCell = row.insertCell(1);
		userCell.appendChild(document.createTextNode(result[exp].user));
		var dateCell = row.insertCell(2);
		dateCell.appendChild(document.createTextNode(result[exp].date));
		var commentsCell = row.insertCell(3);
		commentsCell.appendChild(document.createTextNode(result[exp].comments));
		var sizeCell = row.insertCell(4);
		sizeCell.appendChild(document.createTextNode(result[exp].num_rows));
		var editCell = row.insertCell(5);
		var edit = document.createElement("a");
		edit.href = "#";
		edit.setAttribute("onclick", "editExp(" + result[exp].id + ");");
		var glyph = document.createElement("i");
		glyph.setAttribute("class", "icon-pencil");
		edit.appendChild(glyph);
		editCell.appendChild(edit);
	}
}

function editExp(id) {
	$('#editExp').modal('show');

}

function submitExp() {
	$("#save-gpr").addClass("disabled");
	$("#gpr-load").alert();

	//var name = document.exp.name.value;
	//var user = document.exp.user.value;
	//var comments = document.exp.comments.value;

	//if(name == "" || user == "" || ) {
	//    $("#formError").show();
	//    return;
	//}

	var file = document.exp.gpr.files[0]; //Files[0] = 1st file
	//  alert(file.size);
	var reader = new FileReader();

	reader.onload = sendFile;
	reader.readAsText(file);




	//  $.get("ajax/project.php", "name=" + name + "&user=" + user + "&comments=" + comments, submitExpResult, "text");
}

function sendFile(event) {

	var result = event.target.result;
	var fileName = document.exp.gpr.files[0].name;
	var name = document.exp.name.value;
	var user = document.exp.user.value;
	var comments = document.exp.comments.value;

	//if(name == "" || user == "" || fileName == "") {
	//    $("#formError").show();
	//    return;
	// }



	$.post('ajax/experiment.php', {
		data: result,
		filename: fileName,
		name: name,
		user: user,
		comments: comments
	}, submitExpResult);

}

function submitExpResult(result) {
	//alert(result);
	updateExps();
	document.exp.reset();

	$("#newExp").modal('hide');
	//setTimeout("checkUpload", 1000);

}


function deleteExp() {

}

function getColumns() {
	if($("#filters") && $("#outputs")) {
		$.getJSON("ajax/configure.php", "columns=1", getColumnsResult);
	}
}

function getColumnsResult(result) {

	var filters = $("#filters");
	for(column in result) {
		var option = document.createElement("option");
		option.text = result[column].col;
		option.value = result[column].col;
		filters.add(option);
	}

	var outputs = $("#outputs");
	for(column in result) {
		var option = document.createElement("option");
		option.text = result[column].col;
		option.value = result[column].col;
		outputs.add(option);
	}
}

function updateConExps() {
	if($('#con_exp_table')) {

		$.getJSON('ajax/configure.php', 'proj_exps=1', updateConExpsResult);
	}
}

function updateConExpsResult(result) {

	var table = $('#con_exp_table');
	if(result.length == 0) {
		var row = table.insertRow(0);
		var cell = row.insertCell(0);
		cell.appendChild(document.createTextNode("Need to specify project."));
	}
	for(exp in result) {

		exps[result[exp].id] = "unused";

		var unused = "<tr><td>" + result[exp].name +
		"</td><td><input type='radio' name='" + result[exp].id +
		"' id='unused" + result[exp].id + "' onclick='setExpStatus(" +
		result[exp].id + ")' value='unused' checked /></td>";
		var main = "<td><input type='radio' onclick='setExpStatus(" +
		result[exp].id + ")' name='" + result[exp].id + "' id='main" +
		result[exp].id + "' value='main' /></td>";
		var control = "<td><input type='radio' onclick='setExpStatus(" +
		result[exp].id + ")' name='" + result[exp].id + "' id='control" +
		result[exp].id + "' value='control' /></td></tr>";

		$("#con_exp_table").append(unused + main + control);
	}
}

function setExpStatus(exp) {

	exps[exp] = $('input:radio[name=' + exp + ']:checked').val();
	$.post("ajax/configure.php", {
		status: exps
	});
}

function addFilter() {



	var filter = $("#filters").val();//options[filters_select.selectedIndex].value;

	var relationship = $('#relationship').val();//options[relationship.selectedIndex].value;
	var filter_value = $('#filter_value').val();

	filters.push({filter: filter, relationship: relationship, filter_value: filter_value});
	updateFilters();
}

function addOutput() {

	var stat = $("#stat").val();
	var outputs_select = $("#outputs").val();
	//output['ttest'] = 0;
	//if(document.getElementById("ttest").checked) {
	//	output['ttest'] = 1;
	//}

	outputs.push({stat: stat, outputs: outputs_select});
	updateOutputs();
}

function updateFilters() {
	$("#filter_div").empty();

	for(filter in filters) {
		$("#filter_div").append("<div>" + filters[filter]['filter'] + " " +
		filters[filter]['relationship'] + " " +
		filters[filter]['filter_value'] +
		" <a href='#' onclick='filters.splice(" + filter +
		", 1); updateFilters();'><i class='icon-minus-sign'></a></div>");

	}
	$.post("ajax/configure.php", {
		filters: JSON.stringify(filters)
	});
}

function updateOutputs() {

	$("#output_div").empty();
	var post = "{";
	for(output in outputs) {
		$("#output_div").append("<div>" + outputs[output]['stat'] + " " +
		outputs[output]['outputs'] + " <a href='#' onclick='outputs.splice(" +
		output + ", 1); updateOutputs();'><i class='icon-minus-sign'></a></div>");
		// post += output: {stat: outputs['stat'], outputs: outputs['outputs']};

	}

	$.post("ajax/configure.php", {
		outputs: JSON.stringify(outputs)
	});

}

function updateResults() {

	if($('#results')) {

		$.getJSON('ajax/view.php', 'proj_exps=1', updateResultsResult);
	}
	$('#progress_bar').css({
		'width': '40%'
	});
}

function updateResultsResult(result) {

	var table = $('#results');
	$('#progress_bar').css({
		'width': '60%'
	});

	for(var row in result) {

		var i = 0;
		var tablerow = table.insertRow(0);
		for(var key in result[row]) {
			var cell = tablerow.insertCell(i++);
			cell.appendChild(document.createTextNode(result[row][key]));
			//var countCell = tablerow.insertCell(1);
			// countCell.appendChild(document.createTextNode(result[row].Count));
		}
		$('#progress_bar').css({
			'width': '80%'
		});
	}
	var tablerow = table.insertRow(0);
	var cols = result[0];
	var i = 0;
	for(var key in cols) {
		var labelCell = tablerow.insertCell(i++);
		labelCell.appendChild(document.createTextNode(key));
	}
	$('#progress_bar').css({
		'width': '100%'
	});
	$('#loading').empty();
}

function updateSession(attribute) {
	if(attribute == "normalize") {
		$normalize = 0;
		if(document.getElementById("normalize").checked) {
			$normalize = 1;
		}
		$.getJSON("configure.php", "session=normalize&value=$normalize", updateSessionResponse);
	} else if(attribute == "ttest") {
		$ttest = 0;
		if(document.getElementById("ttest").checked) {
			$ttest = 1;
		}
		$.getJSON("configure.php", "session=ttest&value=$ttest", updateSessionResponse);
	} else if(attribute == "exps") {

	}
	else if(attribute == "filters") {

	} else if(attribute == "outputs") {

	}
}

function updateSessionResponse(result) {

}

function typeaheadExps() {
	if($('#exps_list')) {

		$.getJSON('ajax/experiment.php', 'exps=1', typeaheadExpsResult);
	}
}

var project_exps = new Array();

function typeaheadExpsResult(results) {

	var exps = new Array();
	for(var exp in results) {

		exps.push(results[exp].name);
	}

	$('#exps_list').typeahead({
		source: exps
	});
}

function addProjExp() {
	var exp = $("#exps_list").val();

	var div = $("#new_proj_exps");


	var line = document.createElement("div");
	line.appendChild(document.createTextNode(exp));

	var remove = document.createElement("a");
	var icon = document.createElement("i");
	icon.className = "icon-minus-sign";
	remove.appendChild(icon);
	remove.setAttribute("onclick", "");
	remove.href = "#";
	line.appendChild(remove);
	div.appendChild(line);

}

function sendExcel() {
	document.location = "ajax/view.php?excel=1";
}
