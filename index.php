<?php

	// Tokens are stored in session so you have to initialize session data
	session_start();

	// Then include the NoCSRF class
	require_once('lib/nocsrf.php');

	// Generate CSRF token to use in form hidden field
	NoCSRF::enableOriginCheck();
	$token = NoCSRF::generate('csrf_token');

?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
		<title>Hessen-IT</title>

		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- krajee.com/file-input -->
		<link href="css/fileinput.min.css" rel="stylesheet">

		<!-- Hessen-IT -->
		<link href="css/styles.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="js/html5shiv.min.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body data-spy="scroll" data-target="#bs-hessen-it-mainnav">
  
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button id="mainnav" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-hessen-it-mainnav" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand">
						<img alt="Hessen-IT" src="img/pph_hessen_it.png" href="#homeimg"/>
					</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-hessen-it-mainnav">
					<ul class="nav navbar-nav">
						<li class="active"><a id="homeimg" href="#home" data-toggle="tab">Home <span class="sr-only">(current)</span></a></li>
						<li class="dropdown">
							<a id="antraege" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Anträge <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="#antrag-email" data-toggle="tab">E-Mail-Adresse</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="#antrag-mailingliste" data-toggle="tab">Mailingliste</a></li>
								<li><a href="#antrag-mailinglisten-moderation" data-toggle="tab">Mailinglisten-Moderation</a></li>
								<li><a href="#antrag-mailinglisten-schreibrechte" data-toggle="tab">Mailinglisten-Schreibrechte</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="#antrag-owncloud" data-toggle="tab">ownCloud-Konto</a></li>
							</ul>
						</li>
					</ul>
      
					<ul class="nav navbar-nav navbar-right">
						<li><a id="todos" href="https://jira.piratenpartei-hessen.de/plugins/servlet/shareYourJira/showFilter?&token=abc75366-8b12-4e83-8e3a-3b41f3a3d48f&id=2" target="_blank">Todos <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a></li>
						<li id="kontakt-box" class="dropdown">
							<a id="kontakt" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Kontakt <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li id="kontakt-navi"><a href="#kontakt-email" data-toggle="tab">E-Mail</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="https://twitter.com/HessenIT" target="_blank">Twitter <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a></li>
							</ul>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="home">
				<div class="panel panel-default">
					<div class="panel-heading">Willkommen bei der Hessen-IT der Piratenpartei Deutschland Landesverband Hessen</div>
					<div class="panel-body">
						<p>Hier kannst Du <a href="#antraege">Anträge</a> stellen, dich über unsere offenen <a id="todos" href="https://jira.piratenpartei-hessen.de/plugins/servlet/shareYourJira/showFilter?&token=abc75366-8b12-4e83-8e3a-3b41f3a3d48f&id=2" target="_blank">Todos <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> informieren oder mit uns <a href="#kontakt">Kontakt</a> aufnehmen.</p>
					</div>
				</div>
			</div>

			<div role="tabpanel" class="tab-pane" id="antrag-email">
				<div class="panel panel-default">
					<div class="panel-heading">Antrag auf Einrichtung einer E-Mail-Adresse</div>
					<div class="panel-body">
						<p>Nach Beschluss des <a href="https://wiki.piratenpartei.de/HE:Landesparteitage/2015.2/Protokoll">Landesparteitages 2015.2 (3./4.10.2015) <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> kann jedes hessische Parteimitglied eine @piratenpartei-hessen.de-E-Mail-Adresse beantragen.</p>
						<p>Fülle dazu einfach das nachfolgende Formular aus und schicke es ab:</p>
						<p>
							<form data-toggle="validator" role="form" id="antrag-email-form">
								<div class="form-group has-feedback">
									<label for="antrag-email-vorname">Mein Vorname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="antrag-email-vorname" placeholder="Vorname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-email-nachname">Mein Nachname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="antrag-email-nachname" placeholder="Nachname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-email-email">Meine derzeitige E-Mail-Adresse</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
										<input type="email" class="form-control" id="antrag-email-email" placeholder="E-Mail-Adresse" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-email-mitgliedsnummer">Meine Piratenpartei-Mitgliedsnummer</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
										<input type="number" class="form-control" id="antrag-email-mitgliedsnummer" placeholder="Mitgliedsnummer" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="antrag-email-nb" required> Die <a href="https://lists.piratenpartei-hessen.de/nutzungsbedingungen/" target="_blank">Nutzungsbedingungen Mail-Server <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> habe ich gelesen und akzeptiere diese
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="antrag-email-dse" required> Der Verarbeitung meiner Daten gemäß der <a href="https://www.piratenpartei-hessen.de/datenschutzerklaerung" target="_blank">Datenschutzerklärung <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> stimme ich zu
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="well">
									<div class="form-group has-feedback">
										<label class="control-label">PGP (optional)</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
											<input id="antrag-email-pgp" type="file" class="file" data-show-preview="false" aria-describedby="antrag-email-pgp-help">
										</div>
										<span id="antrag-email-pgp-help" class="help-block">Falls Du eine verschlüsselte Antwort wünscht, sende uns bitte Deinen <b>öffentlichen</b> PGP-Key mit. Eine Anleitung zu PGP befindet sich im <a href="https://wiki.piratenpartei.de/HowTo_PGP" target="_blank">Wiki <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</span>
									</div>
									<label for="antrag-email-anmerkung">Anmerkung (optional)</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-bullhorn"></i></span>
										<textarea class="form-control" rows="2" id="antrag-email-anmerkung" placeholder="Anmerkung"></textarea>
									</div>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-default">Abschicken</button>
								</div>
							</form>
						</p>
					</div>
				</div>
			</div>

			<div role="tabpanel" class="tab-pane" id="antrag-mailingliste">
				<div class="panel panel-default">
					<div class="panel-heading">Antrag auf Einrichtung einer Mailingliste</div>
					<div class="panel-body">
						<p>Nach Beschluss des <a href="https://wiki.piratenpartei.de/HE:Landesparteitage/2015.2/Protokoll">Landesparteitages 2015.2 (3./4.10.2015) <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> können der hessische Landesvorstand, seine Beauftragten, seine Angestellten, seine Untergliederungen oder Abgeordnete sowie mitarbeitende hessische Piraten aus Projektgruppen, Arbeitsgruppen und Arbeitskreisen Mailinglisten beantragen.</p>
						<p>Fülle dazu einfach das nachfolgende Formular aus und schicke es ab:</p>
						<p>
							<form data-toggle="validator" role="form" id="antrag-mailingliste-form">
								<div class="form-group has-feedback">
									<label for="antrag-mailingliste-vorname">Mein Vorname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="antrag-mailingliste-vorname" placeholder="Vorname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailingliste-nachname">Mein Nachname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="antrag-mailingliste-nachname" placeholder="Nachname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailingliste-email">Meine derzeitige E-Mail-Adresse</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
										<input type="email" class="form-control" id="antrag-mailingliste-email" placeholder="E-Mail-Adresse" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailingliste-mailingliste">Gewünschte Mailingliste</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
										<input type="text" class="form-control" id="antrag-mailingliste-mailingliste" placeholder="Mailingliste" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailingliste-personenkreis">Personenkreis</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-cloud"></i></span>
										<select class="form-control" id="antrag-mailingliste-personenkreis" required>
											<option value="" selected disabled>Personenkreis auswählen</option>
											<option>Abgeordnet</option>
											<option>Angestellt</option>
											<option>Arbeitsgruppe</option>
											<option>Arbeitskreis</option>
											<option>Beauftragtung</option>
											<option>Landesvorstand</option>
											<option>Projektgruppe</option>
											<option>Untergliederung</option>
										</select>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="antrag-mailingliste-nb" required> Die <a href="https://lists.piratenpartei-hessen.de/nutzungsbedingungen/" target="_blank">Nutzungsbedingungen Mail-Server <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> habe ich gelesen und akzeptiere diese
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="antrag-mailingliste-dse" required> Der Verarbeitung meiner Daten gemäß der <a href="https://www.piratenpartei-hessen.de/datenschutzerklaerung" target="_blank">Datenschutzerklärung <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> stimme ich zu
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="well">
									<div class="form-group has-feedback">
										<label class="control-label">PGP (optional)</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
											<input id="antrag-mailingliste-pgp" type="file" class="file" data-show-preview="false" aria-describedby="antrag-mailingliste-pgp-help">
										</div>
										<span id="antrag-mailingliste-pgp-help" class="help-block">Falls Du eine verschlüsselte Antwort wünscht, sende uns bitte Deinen <b>öffentlichen</b> PGP-Key mit. Eine Anleitung zu PGP befindet sich im <a href="https://wiki.piratenpartei.de/HowTo_PGP" target="_blank">Wiki <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</span>
									</div>
									<label for="antrag-mailingliste-anmerkung">Anmerkung (optional)</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-bullhorn"></i></span>
										<textarea class="form-control" rows="2" id="antrag-mailingliste-anmerkung" placeholder="Anmerkung"></textarea>
									</div>
								</div>
								<button type="submit" class="btn btn-default">Abschicken</button>
							</form>
						</p>
					</div>
				</div>
			</div>

			<div role="tabpanel" class="tab-pane" id="antrag-mailinglisten-moderation">
				<div class="panel panel-default">
					<div class="panel-heading">Antrag auf Einrichtung einer technische Moderation einer Mailingliste</div>
					<div class="panel-body">
						<p>Die technische Moderation einer Mailingliste umfasst in der Regel das Freischalten von E-Mails, die aus bestimmten Gründen nicht automatisch durchgelassen worden sind.</p>
						<p>Beachte bitte, dass Spam einmal pro Tag automatisch moderiert wird und ansonsten die Hessen-IT alle öffentlichen Mailinglisten min. 1-3x die Woche teilautomatisiert via Shellskript moderiert.</p>
						<p>Falls dennoch eine technische Moderation gewünscht ist, fülle dazu einfach das nachfolgende Formular aus und schicke es ab:</p>
						<p>
							<form data-toggle="validator" role="form" id="antrag-mailinglisten-moderation-form">
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-moderation-vorname">Mein Vorname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="antrag-mailinglisten-moderation-vorname" placeholder="Vorname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-moderation-nachname">Mein Nachname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="antrag-mailinglisten-moderation-nachname" placeholder="Nachname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-moderation-email">Meine derzeitige E-Mail-Adresse</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
										<input type="email" class="form-control" id="antrag-mailinglisten-moderation-email" placeholder="E-Mail-Adresse" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-moderation-mailingliste">Betroffene Mailingliste</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
										<select class="form-control" id="antrag-mailinglisten-moderation-mailingliste" required>
											<option value="" selected disabled>Mailingliste auswählen</option>
										</select>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-moderation-personenkreis">Personenkreis</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-cloud"></i></span>
										<select class="form-control" id="antrag-mailinglisten-moderation-personenkreis" required>
											<option value="" selected disabled>Personenkreis auswählen</option>
											<option>Abgeordnet</option>
											<option>Angestellt</option>
											<option>Arbeitsgruppe</option>
											<option>Arbeitskreis</option>
											<option>Beauftragtung</option>
											<option>Landesvorstand</option>
											<option>Projektgruppe</option>
											<option>Untergliederung</option>
										</select>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-moderation-mod-email">Gewünschte Moderations-E-Mail-Adresse</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
										<input type="email" class="form-control" id="antrag-mailinglisten-moderation-mod-email" placeholder="E-Mail-Adresse" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="antrag-mailinglisten-moderation-nb" required> Die <a href="https://lists.piratenpartei-hessen.de/nutzungsbedingungen/" target="_blank">Nutzungsbedingungen Mail-Server <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> habe ich gelesen und akzeptiere diese
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="antrag-mailinglisten-moderation-dse" required> Der Verarbeitung meiner Daten gemäß der <a href="https://www.piratenpartei-hessen.de/datenschutzerklaerung" target="_blank">Datenschutzerklärung <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> stimme ich zu
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="well">
									<div class="form-group has-feedback">
										<label class="control-label">PGP (optional)</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
											<input id="antrag-mailinglisten-moderation-pgp" type="file" class="file" data-show-preview="false" aria-describedby="antrag-mailinglisten-moderation-pgp-help">
										</div>
										<span id="antrag-mailinglisten-moderation-pgp-help" class="help-block">Falls Du eine verschlüsselte Antwort wünscht, sende uns bitte Deinen <b>öffentlichen</b> PGP-Key mit. Eine Anleitung zu PGP befindet sich im <a href="https://wiki.piratenpartei.de/HowTo_PGP" target="_blank">Wiki <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</span>
									</div>
									<label for="antrag-mailinglisten-moderation-anmerkung">Anmerkung (optional)</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-bullhorn"></i></span>
										<textarea class="form-control" rows="2" id="antrag-mailinglisten-moderation-anmerkung" placeholder="Anmerkung"></textarea>
									</div>
								</div>
								<button type="submit" class="btn btn-default">Abschicken</button>
							</form>
						</p>
					</div>
				</div>
			</div>

			<div role="tabpanel" class="tab-pane" id="antrag-mailinglisten-schreibrechte">
				<div class="panel panel-default">
					<div class="panel-heading">Antrag auf Schreibrechte auf einer besonderen Mailingliste</div>
					<div class="panel-body">
						<p>Nach Beschluss des <a href="https://wiki.piratenpartei.de/HE:Landesparteitage/2015.2/Protokoll">Landesparteitages 2015.2 (3./4.10.2015) <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> gibt es eine Reihe von besonderen Listen, für die es nicht automatisch eine Schreibberechtigung gibt.</p>
						<p>Falls Du Schreibrechte wünscht, fülle einfach das nachfolgende Formular aus und schicke es ab:</p>
						<p>
							<form data-toggle="validator" role="form" id="antrag-mailinglisten-schreibrechte-form">
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-schreibrechte-vorname">Mein Vorname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="antrag-mailinglisten-schreibrechte-vorname" placeholder="Vorname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-schreibrechte-nachname">Mein Nachname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="antrag-mailinglisten-schreibrechte-nachname" placeholder="Nachname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-schreibrechte-email">Meine Listen-E-Mail-Adresse</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
										<input type="email" class="form-control" id="antrag-mailinglisten-schreibrechte-email" placeholder="E-Mail-Adresse" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-schreibrechte-mailingliste">Betroffene Mailingliste</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
										<select class="form-control" id="antrag-mailinglisten-schreibrechte-mailingliste" required>
											<option value="" selected disabled>Mailingliste auswählen</option>
											<option data-type="url">Kreisvorständeliste</option>
											<option data-type="email">Mitgliederliste</option>
											<option data-type="url">Schatzmeisterliste</option>
											<option data-type="url">Verwaltungsliste</option>
										</select>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-mailinglisten-schreibrechte-nachweis">Nachweis</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
										<input type="text" class="form-control" id="antrag-mailinglisten-schreibrechte-nachweis" placeholder="Nachweis" aria-describedby="antrag-mailinglisten-schreibrechte-nachweis-help" required>
									</div>
									<div class="help-block with-errors"></div>
									<span id="antrag-mailinglisten-schreibrechte-nachweis-help" class="help-block">Es ist ein Nachweis zu erbringen, z.B. ein Link zum entsprechenden Protokoll, zur Web- oder Wiki-Seite oder im Falle der Mitgliederliste eine @piratenpartei-hessen.de-E-Mail-Adresse.</span>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="antrag-mailinglisten-schreibrechte-nb" required> Die <a href="https://lists.piratenpartei-hessen.de/nutzungsbedingungen/" target="_blank">Nutzungsbedingungen Mail-Server <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> habe ich gelesen und akzeptiere diese
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="antrag-mailinglisten-schreibrechte-dse" required> Der Verarbeitung meiner Daten gemäß der <a href="https://www.piratenpartei-hessen.de/datenschutzerklaerung" target="_blank">Datenschutzerklärung <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> stimme ich zu
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="well">
									<div class="form-group has-feedback">
										<label class="control-label">PGP (optional)</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
											<input id="antrag-mailinglisten-schreibrechte-pgp" type="file" class="file" data-show-preview="false" aria-describedby="antrag-mailinglisten-schreibrechte-pgp-help">
										</div>
										<span id="antrag-mailinglisten-schreibrechte-pgp-help" class="help-block">Falls Du eine verschlüsselte Antwort wünscht, sende uns bitte Deinen <b>öffentlichen</b> PGP-Key mit. Eine Anleitung zu PGP befindet sich im <a href="https://wiki.piratenpartei.de/HowTo_PGP" target="_blank">Wiki <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</span>
									</div>
									<label for="antrag-mailinglisten-schreibrechte-anmerkung">Anmerkung (optional)</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-bullhorn"></i></span>
										<textarea class="form-control" rows="2" id="antrag-mailinglisten-schreibrechte-anmerkung" placeholder="Anmerkung"></textarea>
									</div>
								</div>
								<button type="submit" class="btn btn-default">Abschicken</button>
							</form>
						</p>
					</div>
				</div>
			</div>

			<div role="tabpanel" class="tab-pane" id="antrag-owncloud">
				<div class="panel panel-default">
					<div class="panel-heading">Antrag auf Einrichtung eines ownCloud-Kontos</div>
					<div class="panel-body">
						<p>Nach Beschluss des <a href="https://wiki.piratenpartei.de/HE:Landesparteitage/2015.2/Protokoll">Landesparteitages 2015.2 (3./4.10.2015) <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> kann jedes hessische Parteimitglied mit einer @piratenpartei-hessen.de-E-Mail-Adresse ein ownCloud-Konto beantragen.</p>
						<p>Fülle dazu einfach das nachfolgende Formular aus und schicke es ab:</p>
						<p>
							<form data-toggle="validator" role="form" id="antrag-owncloud-form">
								<div class="form-group has-feedback">
									<label for="antrag-owncloud-vorname">Mein Vorname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="antrag-owncloud-vorname" placeholder="Vorname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-owncloud-nachname">Mein Nachname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="antrag-owncloud-nachname" placeholder="Nachname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="antrag-owncloud-email">Meine @piratenpartei-hessen.de-E-Mail</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
										<input type="email" class="form-control" id="antrag-owncloud-email" placeholder="E-Mail" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="antrag-owncloud-nb" required> Die <a href="https://owncloud.piratenpartei-hessen.de/apps/disclaimer/pdf/disclaimer.pdf" target="_blank">Nutzungsbedingungen ownCloud <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> habe ich gelesen und akzeptiere diese
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="antrag-owncloud-dse" required> Der Verarbeitung meiner Daten gemäß der <a href="https://www.piratenpartei-hessen.de/datenschutzerklaerung" target="_blank">Datenschutzerklärung <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> stimme ich zu
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="well">
									<div class="form-group has-feedback">
										<label class="control-label">PGP (optional)</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
											<input id="antrag-owncloud-pgp" type="file" class="file" data-show-preview="false" aria-describedby="antrag-owncloud-pgp-help">
										</div>
										<span id="antrag-owncloud-pgp-help" class="help-block">Falls Du eine verschlüsselte Antwort wünscht, sende uns bitte Deinen <b>öffentlichen</b> PGP-Key mit. Eine Anleitung zu PGP befindet sich im <a href="https://wiki.piratenpartei.de/HowTo_PGP" target="_blank">Wiki <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</span>
									</div>
									<label for="antrag-owncloud-anmerkung">Anmerkung (optional)</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-bullhorn"></i></span>
										<textarea class="form-control" rows="2" id="antrag-owncloud-anmerkung" placeholder="Anmerkung"></textarea>
									</div>
								</div>
								<button type="submit" class="btn btn-default">Abschicken</button>
							</form>
						</p>
					</div>
				</div>
			</div>

			<div role="tabpanel" class="tab-pane" id="kontakt-email">
				<div class="panel panel-default">
					<div class="panel-heading">Kontakt zur Hessen-IT</div>
					<div class="panel-body">
						<p>Uns erreichst du entweder über das nachfolgende Formular oder direkt per E-Mail unter <a href="mailto:it@piratenpartei-hessen.de">it@piratenpartei-hessen.de</a> (PGP: <a href="pgp/Piratenpartei_Hessen_IT_2015-2016_it@piratenpartei-hessen.de_(0x82DBD9AC)_pub.asc" target="_blank">ID 0x82DBD9AC</a>, Fingerprint 8C8E CD89 E391 75DA A1EF 924A 2B96 6264 82DB D9AC) und <a href="https://twitter.com/HessenIT">Twitter <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</p>
						<p>
							<form data-toggle="validator" role="form" id="kontakt-email-form">
								<div class="form-group has-feedback">
									<label for="kontakt-email-vorname">Mein Vorname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="kontakt-email-vorname" placeholder="Vorname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="kontakt-email-nachname">Mein Nachname</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="kontakt-email-nachname" placeholder="Nachname" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="kontakt-email-email">Meine derzeitige E-Mail-Adresse</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
										<input type="email" class="form-control" id="kontakt-email-email" placeholder="E-Mail-Adresse" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="kontakt-email-betreff">Betreff des Anliegens</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
										<input type="text" class="form-control" id="kontakt-email-betreff" placeholder="Betreff" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<label for="kontakt-email-body">Anliegen</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-comment"></i></span>
										<textarea class="form-control" rows="15" id="kontakt-email-body" placeholder="Anliegen" required></textarea>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group has-feedback">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="akzeptiert" id="kontakt-email-dse" required> Der Verarbeitung meiner Daten gemäß der <a href="https://www.piratenpartei-hessen.de/datenschutzerklaerung" target="_blank">Datenschutzerklärung <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a> stimme ich zu
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								<div class="well">
									<div class="form-group has-feedback">
										<label class="control-label">PGP (optional)</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
											<input id="kontakt-email-pgp" type="file" class="file" data-show-preview="false" aria-describedby="kontakt-email-pgp-help">
										</div>
										<span id="kontakt-email-pgp-help" class="help-block">Falls Du eine verschlüsselte Antwort wünscht, sende uns bitte Deinen <b>öffentlichen</b> PGP-Key mit. Eine Anleitung zu PGP befindet sich im <a href="https://wiki.piratenpartei.de/HowTo_PGP" target="_blank">Wiki <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</span>
									</div>
									<label for="kontakt-email-anmerkung">Anmerkung (optional)</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-bullhorn"></i></span>
										<textarea class="form-control" rows="2" id="kontakt-email-anmerkung" placeholder="Anmerkung"></textarea>
									</div>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-default">Abschicken</button>
								</div>
							</form>
						</p>
					</div>
				</div>
			</div>
		</div>


		<div class="navbar navbar-default navbar-fixed-bottom">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-hessen-it-metanav" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="bs-hessen-it-metanav">
					<ul class="nav navbar-nav">
						<li><a href="https://www.piratenpartei-hessen.de/impressum" target="_blank">Impressum <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a></li>
						<li><a href="https://www.piratenpartei-hessen.de/datenschutzerklaerung" target="_blank">Datenschutzerklärung <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a></li>
						<li id="kontakt-footer"><a href="#kontakt-email" data-toggle="tab">Kontakt</a></li>
					</ul>
				</div>
			</div>
		</div>


		<div id="loading-modal" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h3>Title</h3>
					</div>
					<div class="modal-body">
						<div style="min-height: 100px">
							<span id="loading_spinner_center" style="position: absolute;display: block;top: 50%;left: 50%;"></span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
					</div>
				</div>
			</div>
		</div>


		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="js/jquery-3.1.1.min.js"></script>

		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/validator.js"></script>

		<!-- krajee.com/file-input -->
		<script src="js/fileinput.min.js"></script>

		<!-- Debug -->
		<script src="js/ba-debug.min.js"></script>

		<!-- Attrchange -->
		<script src="js/attrchange.js"></script>

		<!-- Spin -->
		<script src="js/spin.min.js"></script>

		<!-- Hessen-IT -->
		<script src="js/code.js"></script>
		<script>var token = "<?php echo $token; ?>";</script>
	</body>
</html>
<?php
	/*/print_r($_SESSION);
	echo session_id();/**/
?>