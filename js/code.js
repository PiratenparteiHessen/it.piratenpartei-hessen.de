var error_global = false;
var form_global =  {};

$(document).ready(function() {
	debug.log("ready");
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

	initSpinner();

	$('#loading-modal').on('hidden.bs.modal', function (e) {
		debug.log("modal closing", error_global);
		if (error_global) {
			
		} else {
			debug.log("reload");
			location.reload();
		}
	});

	var hash = document.location.hash;
	if (hash && (hash.indexOf("#antrag") !== -1 || hash.indexOf("#kontakt") !== -1)) {
		/*debug.log("trigger click");
		debug.log(hash);/**/
		$('a[href="'+hash+'"]').trigger('click');
	}
});

$(document).click(function(e) {
	debug.log("document click handler");

	var href = $(e.target).attr('href');
	debug.log(href);

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

	if (href && (href.indexOf("#antrag") !== -1 || href.indexOf("#kontakt") !== -1)) {
		$('#response').hide();
		document.location.hash = href;
		setTimeout(scrollToTop, 5);
	} else if (href) {
		history.pushState("", document.title, window.location.pathname);
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

$('form').validator().on('submit', function(e) {
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
		debug.log("id", id);

		var name = id;
		name = name.replace("-form", "");
		debug.log("name", name);

		var data = new FormData();
		addFormData(this, data, name);

		var title = $(e.currentTarget).closest("div.panel").find("div.panel-heading").text();
		data.append("title", title);
		debug.log("title", title);
		debugFormData(data);

		prepareModal(title);
		initSpinner();

		form_global = {
				"id": id,
				"name": name,
				"title": title
		};

		$.ajax({
			url: 'api/'+name+"/",
			type: 'POST',
			data: data,
			processData: false,
			contentType: false,
			cache: false
		}).done(function(data) {
			debug.log("done");
			debug.log(data);
			$('#loading-modal').find("button").removeAttr("disabled");
			if (data.success) {
				/*/debug.log(data.message.trim());
				debug.log(!data.message.trim());/**/
				if (data.next && data.next == "code") {
					$('#modal_ok_button').hide();
					$('#modal_submit_button').show();
					$('#modal_submit_button').attr("disabled", "disabled");

					var html = '<div class="alert alert-success" role="alert"><p>Das Formular wurde erfolgreich übermittelt.</p></div>';
					html += '<p>Du erhälst nun eine Bestätigungs-E-Mail und kannst entweder den Link darin klicken oder den Bestätigungs-Code direkt hier eingeben.</p>';
					html += '<form>';
					html += '<div class="form-group has-feedback">';
					html += '<label for="confirmation">Bestätigungs-Code</label>';
					html += '<div class="input-group">';
					html += '<span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>';
					html += '<input type="text" class="form-control" id="confirmation" placeholder="Bestätigungs-Code" required>';
					html += '</div>';
					html += '<div class="help-block with-errors"></div>';
					html += '</div>';
					html += '</form>';
				} else {
					if (!data.message.trim()) {
						var html = '<div class="alert alert-success" role="alert"><p>Das Formular wurde erfolgreich übermittelt.</p></div>';
						html += '<p>Zurück zur Startseite</p>';
					} else {
						var html = '<div class="alert alert-warning" role="alert"><p>Debug:</p><pre>'+data.message+'</pre></div>';
					}
				}
				debug.log(html);

				error_global = false;
				debug.log("error_global", error_global);
				$('#loading_spinner_center').replaceWith(html);
			} else {
				error_global = true;
				debug.log("error_global", error_global);
				$('#loading_spinner_center').replaceWith('<div class="alert alert-warning" role="alert"><p>Es ist folgender Fehler aufgetreten:<br />'+data.message+'</p></div><p>Zurück zum Formular</p>');
			}
		}).fail(function() {
			debug.log("fail");
			error_global = true;
			debug.log("error_global", error_global);
			$('#loading-modal').find("button").removeAttr("disabled");
			$('#loading_spinner_center').replaceWith('<div class="alert alert-danger" role="alert">Es ist ein Fehler aufgetreten</div><p>Zurück zum Formular</p>');
		}).always(function(data) {
			debug.log("always");
			debug.log(data);

			if (data.token) {
				token_global = data.token;
				debug.log("token", token_global);
			}
		});
	}

	$('#modal_submit_button').off('click').on('click', function(e) {
		e.preventDefault();
		debug.log("click");
		debug.log(form_global);

		var form = $(e.currentTarget).closest("div.modal-content").find("form");
		debug.log(form);

		var data = new FormData();
		addFormData(form, data, "confirmation");
		debugFormData(data);

		prepareModal(form_global.title);
		initSpinner();

		$.ajax({
			url: 'api/'+form_global.name+"/",
			type: 'POST',
			data: data,
			processData: false,
			contentType: false,
			cache: false
		}).done(function(data) {
			debug.log("done");
			debug.log(data);
			$('#loading-modal').find("button").removeAttr("disabled");
			
			if (data.success) {
				if (!data.message.trim()) {
					var html = '<div class="alert alert-success" role="alert"><p>Das Formular wurde erfolgreich übermittelt.</p></div>';
					if (data.recipient == "mv") {
						html += '<p>Du erhälst nun eine Bestätigungs-E-Mail vom Ticketsystem der hessischen Mitgliederverwaltung.</p>'
					} else {
						html += '<p>Du erhälst nun eine Bestätigungs-E-Mail vom Ticketsystem der Hessen-IT.</p>'
					}
					html += '<p>Zurück zur Startseite</p>';
				} else {
					var html = '<div class="alert alert-warning" role="alert"><p>Debug:</p><pre>'+data.message+'</pre></div>';
				}
				debug.log(html);

				error_global = false;
				debug.log("error_global", error_global);
				$('#loading_spinner_center').replaceWith(html);
			} else {
				error_global = true;
				debug.log("error_global", error_global);
				$('#loading_spinner_center').replaceWith('<div class="alert alert-warning" role="alert"><p>Es ist folgender Fehler aufgetreten:<br />'+data.message+'</p></div><p>Zurück zum Formular</p>');
			}
		}).fail(function() {
			debug.log("fail");
			error_global = true;
			debug.log("error_global", error_global);
			$('#loading-modal').find("button").removeAttr("disabled");
			$('#loading_spinner_center').replaceWith('<div class="alert alert-danger" role="alert">Es ist ein Fehler aufgetreten</div><p>Zurück zum Formular</p>');/**/
		}).always(function(data) {
			debug.log("always");
			debug.log(data);

			if (data.token) {
				token_global = data.token;
				debug.log("token", token_global);
			}
		});
	});

	$('#loading-modal').on('input propertychange', '#confirmation', function() {
		debug.log("change", this, $(this).val());
		//if (this)
		$('#modal_submit_button').removeAttr("disabled");
	});
});

function addFormData(elem, data, name) {
	$(elem).find(":input").each(function(index, input) {
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
	data.append("csrf_token", token_global);
}

function debugFormData(data) {
	debug.log("debugFormData");
	debug.log(data);
	if (data.entries()) {
		for(var pair in data.entries()) {
			debug.log(pair[0]+ ', '+ pair[1]); 
		}
	}
}

function prepareModal(title) {
	$('#modal_ok_button').show();
	$('#modal_submit_button').hide();
	$('#loading-modal').find("div.modal-header h3").replaceWith("<h3>"+title+"</h3>");
	$('#loading-modal').find("div.modal-body").replaceWith('<div class="modal-body"><div style="min-height: 100px"><span id="loading_spinner_center" style="position: absolute;display: block;top: 50%;left: 50%;"></span></div></div>');
	$('#loading-modal').find("button").attr("disabled", "disabled");
	$('#loading-modal').modal('show');
}

function initSpinner() {
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
}

function scrollToTop() {
	debug.log("scrollToTop");
	$(window).scrollTop(0);
}