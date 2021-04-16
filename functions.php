<?php
include 'config.php';
// Se connecter à MySQL en utilisant la fonction PDO
function pdo_connect_mysql() {
    try {
        // Connectez-vous à la base de données MySQL en utilisant PDO
    	return new PDO('mysql:host=' . db_host . ';dbname=' . db_name . ';charset=' . db_charset, db_user, db_pass);
    } catch (PDOException $exception) {
    	// Impossible de se connecter à la base de données MySQL, si cette erreur se produit assurez-vous que vous vérifiez vos paramètres db sont corrects!
    	exit('impossible de se connecter à la base de données');
    }
}
// En-tête du modèle
function template_header($title) {
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>$title</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
    <nav class="navtop">
    	<div>
    		<h1>GSB Entreprise</h1>
            <a href="index.php"><i class="fas fa-home"></i>Accueil</a>
            <a href="tickets.php"><i class="fas fa-ticket-alt"></i>Tickets</a>
            <a href="admin/index.php" target="_blank"><i class="fas fa-lock"></i>Admin</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
    	</div>
    </nav>
EOT;
}
// Pied de page du modèle
function template_footer() {
echo <<<EOT
    </body>
</html>
EOT;
}
// En-tête d’administration du modèle
function template_admin_header($title) {
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>$title</title>
		<link href="admin.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="admin">
        <header>
            <h1>Tableau Admin</h1>
            <a class="responsive-toggle" href="#">
                <i class="fas fa-bars"></i>
            </a>
        </header>
        <aside class="responsive-width-100 responsive-hidden">
            <a href="index.php"><i class="fas fa-ticket-alt"></i>Tickets</a>
            <a href="categories.php"><i class="fas fa-list"></i>Categories</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
        </aside>
        <main class="responsive-width-100">
EOT;
}
// En-tête d’administration du pied de page du modèle
function template_admin_footer() {
echo <<<EOT
        </main>
        <script>
        document.querySelector(".responsive-toggle").onclick = function(event) {
            event.preventDefault();
            var aside_display = document.querySelector("aside").style.display;
            document.querySelector("aside").style.display = aside_display == "flex" ? "none" : "flex";
        };
        </script>
    </body>
</html>
EOT;
}
?>
