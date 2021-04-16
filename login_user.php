<?php
session_start();
include_once 'config.php';
include_once 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
if (isset($_POST['user_username'], $_POST['user_password'])) {
    $stmt = $pdo->prepare('SELECT * FROM user_accounts WHERE username = ? AND password = ?');
    $stmt->execute([ $_POST['user_username'], $_POST['user_password'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($account) {
        $_SESSION['user_loggedin'] = true;
        $_SESSION['user_id'] = $account['id'];
        header('Location: index.php');
        exit;
    } else {
        $msg = 'Identifiant / Mot de Passe Incorect';
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Connexion Admin</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
        <link href="admin/admin.css" rel="stylesheet" type="text/css">
	</head>
	<body class="login">
        <form action="" method="post" class="">
            <input type="text" name="user_username" placeholder="Identifiant" required>
            <input type="password" name="user_password" placeholder="Mot de Passe">
            <input type="submit" value="Connexion">
            <p><?=$msg?></p>
        </form>
    </body>
</html>
