<?php

    $link = mysqli_connect("localhost", "root", "root", "formulaire");
    
    if($link === false){
        die("Erreur. Ne peux pas rejoindre la banque de données." . mysqli_connect_error());
	}
	
	//Fetching des données du formulaire

    $date = mysqli_real_escape_string($link, $_REQUEST['date']);
    $heure = mysqli_real_escape_string($link, $_REQUEST['heure']);
    $personnes = mysqli_real_escape_string($link, $_REQUEST['personnes']);
    $courriel = mysqli_real_escape_string($link, $_REQUEST['courriel']);

    $dayOfDate = date('D', strtotime($date));

	//Verification de la date et l'heure

    if ($date == '' || $heure == '' || $personnes == '' || $courriel == '' ) {
        die('Veuillez remplir tous les champs du formulaire.' . mysqli_connect_error());
    } else if (($dayOfDate == 'Fri' && intval($heure) > 21) || ($dayOfDate != 'Sat' && $dayOfDate != 'Fri' && intval($heure) > 20)) {
        die('Nous sommes fermé pour la plage sélectionnée. Veuillez consulter notre horaire.' . mysqli_connect_error());
    }

	//Tables disponibles

    $personneSearchMin = 0;
    $personneSearchMax = 2;
    $available = 5;
    if ($personnes <= 4 && $personnes > 2) {
        $personneSearchMin = 2;
        $personneSearchMax = 4;
    } else if ($personnes <= 8 && $personnes > 4) {
        $personneSearchMin = 4;
        $personneSearchMax = 8;
        $available = 2;
    }

    $sql = "SELECT COUNT(*) as count FROM reservations WHERE date = '$date' AND heure = '$heure' AND personnes > $personneSearchMin AND personnes <= $personneSearchMax";

    $result = mysqli_query($link, $sql);

    $row = mysqli_fetch_array($result);

    $used = $row[0]['count'];

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" />
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
		<link rel="stylesheet" type="text/css" href="theme.css"/>
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
		<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
		<title>Vérification</title>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg">
			<div class="navbar-content container col-lg-11">
				<div class="navbar-brand col-1"><img src="images/logo.png" alt="logo"></div>
				<button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav">	
						<li class="nav-item active">
							<a class="nav-link" href="accueil.html">Accueil</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="apropos.html" >À propos</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Menu <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-item"><a href="menu-midi.html">Midi</a></li>
								<li class="dropdown-item"><a href="menu-soir.html">Soir</a></li>
								<li class="dropdown-item"><a href="menu-carte.html">Carte des vins</a></li>
							</ul>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="photos.html">Photos</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="reservations.html" style="color:#29C2E4;">Réserver en ligne</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="nousjoindre.html">Nous joindre</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
        
        <?php
			//Envoi des données vers la base de données
            if ($used < $available) {
                $sql = "INSERT INTO reservations (date, heure, personnes, courriel) VALUES ('$date', '$heure', '$personnes', '$courriel')";
                if(mysqli_query($link, $sql)){
                    echo "Votre réservation a étée confirmée.";
                    mail ($courriel, "Réservation Au Pates Freches", "Votre réservation a étée confirmée pour le " . $date . " à " . $heure . ". À bientot!");
                } else{
                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                }
            } else {
                echo 'Réservation échouée. Veuillez choisir une autre plage horaire.';
            }
            
            mysqli_close($link);
        ?>

		<div id="footer">		
			<div class="footer-contentbox container col-xl-9 col-12">
				<div class="row">			
					<div class="footer-content col-12 col-lg-4 col-md-4">
						<h3>À propos</h3>				
						<p>Situé dans un ancien hangar, le restaurant à des allures de cave à vin ancestrale de la région de Toscane, à laquelle s'est
							ajouté un décor enchanteur et créatif qui séduira les amateurs de bonne bouffe en bonne compagnie.</p>
					</div>
					<div class="footer-content col-12 col-lg-4 col-md-4">
						<h3>Nous joindre</h3>	
						<div class="row">
							<p class="col-4"><b>Adresse:</b></p>
							<p class="coord col-7">2827 , chemin Merivale Ottawa (ON) K2H5B6 </p>
						</div>
						<div class="row">
							<p class="col-4"><b>Téléphone:</b></p>
							<p class="coord col-7">613 829-4783 </p>
						</div>
						<div class="row">
							<p class="col-4"><b>Courriel:</b></p>
							<p class="coord col-7">info@auxpatesfraiches.ca </p>
						</div>
					</div>
					<div class="footer-content col-12 col-lg-4 col-md-4">
						<h3>Médias sociaux</h3>
						<p>Suivez-nous sur les médias sociaux!</p>
						<div class="social-medias">
							<div class="row">
								<a href="#" class="fa fa-youtube"></a>
								<a href="#" class="fa fa-facebook"></a>
								<a href="#" class="fa fa-twitter"></a>
								<a href="#" class="fa fa-instagram"></a>
							</div>
						</div>
					</div>				
				</div>
			</div>				
			<div id="copyrights" class="col-12">
				<p>Copyrights 2019</p>
			</div>
		</div>
	</body>
</html>