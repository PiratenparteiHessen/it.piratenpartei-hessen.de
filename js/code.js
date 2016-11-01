$(document).ready(function() {
	var ts = Math.round((new Date()).getTime() / 1000);
	var url = "https://www.piratenpartei-hessen.de/stats.xml.php?_="+ts;

	$.get(url, function(ml) {
		//debug.log(ml);
		var lists = [];
		$(ml).find('list').each(function(index, list) {
			var name = $(list).attr("name"); 
			//debug.log(name);
			lists.push(name); 
		});
		lists.sort();
		//debug.log(lists);
		$(lists).each(function(index, list) {
			$("#antrag-mailinglisten-moderation-mailingliste").append('<option>'+list+'</option>');
		});
	});

	var opts = {
		lines: 13, // The number of lines to draw
		length: 20, // The length of each line
		width: 10, // The line thickness
		radius: 30, // The radius of the inner circle
		corners: 1, // Corner roundness (0..1)
		rotate: 0, // The rotation offset
		direction: 1, // 1: clockwise, -1: counterclockwise
		color: '#000', // #rgb or #rrggbb or array of colors
		speed: 1, // Rounds per second
		trail: 60, // Afterglow percentage
		shadow: false, // Whether to render a shadow
		hwaccel: false, // Whether to use hardware acceleration
		className: 'spinner', // The CSS class to assign to the spinner
		zIndex: 2e9, // The z-index (defaults to 2000000000)
		top: 'auto', // Top position relative to parent in px
		left:'auto' // Left position relative to parent in px
	};

	var target = document.getElementById('loading_spinner_center');
	var spinner = new Spinner(opts).spin(target);
	
	$('#loading-modal').on('hidden.bs.modal', function (e) {
		debug.log("reload");
		location.reload();
	});
});

$(document).click(function(e) {
	var href = $(e.target).attr('href');
	//debug.log(href);

	switch(href) {
		case "#kontakt-email":
			$("#bs-hessen-it-mainnav > ul.navbar-nav > li").each(function(index, li) {
				$(li).removeClass("active");
			});
			$("li#kontakt-box").addClass("active");
			$("li#kontakt-navi").addClass("active");
			$("li#kontakt-footer").addClass("active");
			break;

		case "#homeimg":
		case "#antraege":
		case "#kontakt":
			e.preventDefault();
			e.stopPropagation();
			if ($("button#mainnav").hasClass("collapsed")) {
				$("button#mainnav").trigger('click');
			}
			$(href).trigger('click');
			break;

		default:
			if (href && href.charAt(0) == "#") {
				$("li#kontakt-box").removeClass("active");
				$("li#kontakt-navi").removeClass("active");
				$("li#kontakt-footer").removeClass("active");
			}
			break;
	}
});

$('div.tab-pane').attrchange({
	trackValues: true, 
	callback: function (event) {
		if (event.newValue.indexOf("active") !== -1) {
			//debug.log(event);
			//debug.log($(event.target));
			$(event.target).find("form").trigger('reset')
			$(event.target).find("input").first().focus();
		}
	}
});

$('.navbar-collapse a').click(function(e) {
	/*/debug.log($(e));
	debug.log($(e.currentTarget));/**/
	if (!$(e.currentTarget).hasClass("dropdown-toggle")) {
		$(".navbar-collapse").collapse('hide');
	}
});

$("#antrag-mailinglisten-schreibrechte-mailingliste").change(function(e) {
	var optionSelected = $("option:selected", this);
	//debug.log(optionSelected);

	var type = optionSelected.attr('data-type');
	//$('#antrag-mailinglisten-schreibrechte-nachweis').replaceWith($('#antrag-mailinglisten-schreibrechte-nachweis').clone().attr('type', type));

	if (type == "url") {
		$('#antrag-mailinglisten-schreibrechte-nachweis').closest("div.input-group").find("i")
			.removeClass("glyphicon-question-sign")
			.removeClass("glyphicon-envelope")
			.addClass("glyphicon-link");
	} else {
		$('#antrag-mailinglisten-schreibrechte-nachweis').closest("div.input-group").find("i")
			.removeClass("glyphicon-question-sign")
			.removeClass("glyphicon-link")
			.addClass("glyphicon-envelope");
	}

	//$('#antrag-mailinglisten-schreibrechte').validator('update');
});

$("input:file").fileinput({
	//uploadUrl: 'upload/', // you must set a valid URL here else you will get an error
	uploadAsync: true,
	showUpload: false,
	allowedFileExtensions : ['asc'],
	maxFileCount: 1,
	language: 'de'
});

$('form').validator().on('submit', function (e) {
	if (e.isDefaultPrevented()) {
		// handle the invalid form...
		debug.log("error");
		debug.log(e.isDefaultPrevented());
		debug.log($(this).validator('hasErrors'));
		debug.log($(this).validator('isIncomplete'));
		
	} else {
		// everything looks good!
		debug.log("ok");
		e.preventDefault();

		var id = e.currentTarget.id;
		//debug.log(id);

		var name = id;
		name = name.replace("-form", "");

		var data = new FormData();
		$(this).find(":input").each(function(index, input) {
			if (input.id.indexOf(name) !== -1) {
				/*/debug.log(input);
				debug.log($(input));/**/
				//debug.log(input.type);
				if (input.type == "file") {
					data.append(input.id, input.files[0]);
				} else {
					data.append(input.id, $(input).val());
				}
			}
		});
		data.append("csrf_token", token);

		var title = $(e.currentTarget).closest("div.panel").find("div.panel-heading").text();
		data.append("title", title);
		$('#loading-modal').find("div.modal-header h3").replaceWith("<h3>"+title+"</h3>");
		$('#loading-modal').find("button").attr("disabled", "disabled");
		$('#loading-modal').modal('show');
		$.ajax({
			url: 'api/'+name+"/",
			type: 'POST',
			data: data,
			processData: false,
			contentType: false,
			cache: false
		}).done(function(data) {
			debug.log("success");
			debug.log(data);
			$('#loading-modal').find("button").removeAttr("disabled");
			if (data.success) {
				/*/debug.log(data.message.trim());
				debug.log(!data.message.trim());/**/
				if (!data.message.trim()) {
					$('#loading_spinner_center').replaceWith('<div class="alert alert-success" role="alert"><p>Das Formular wurde erfolgreich übermittelt.</p><p>Zurück zu Startseite</p></div>');
				} else {
					$('#loading_spinner_center').replaceWith('<div class="alert alert-warning" role="alert"><p>Debug:</p><pre>'+data.message+'</pre></div>');
				}
			} else {
				$('#loading_spinner_center').replaceWith('<div class="alert alert-warning" role="alert"><p>Es ist folgender Fehler aufgetreten:<br />'+data.message+'</p><p>Zurück zu Startseite</p></div>');
			}
		}).fail(function() {
			debug.log("error");
			$('#loading-modal').find("button").removeAttr("disabled");
			$('#loading_spinner_center').replaceWith('<div class="alert alert-danger" role="alert">Es ist ein Fehler aufgetreten</div>');
		}).always(function() {
			debug.log("complete");
		});
	}
});