<?php
session_start();
include 'functions.php';
// Connectez-vous à MySQL en utilisant la fonction ci-dessous
$pdo = pdo_connect_mysql();
// Récupérez les 3 nouveaux billets
if (isset($_SESSION['admin_loggedin'])) {
	$stmt = $pdo->prepare('SELECT * FROM tickets ORDER BY created DESC LIMIT 3');
} else {
	$stmt = $pdo->prepare('SELECT * FROM tickets WHERE private = 0 ORDER BY created DESC LIMIT 3');
}
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Obtenir le nombre total de tickets pour chaque statut
if (isset($_SESSION['admin_loggedin'])) {
	$num_open_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "ouvert"')->fetchColumn();
	$num_closed_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "ferme"')->fetchColumn();
	$num_resolved_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "resolu"')->fetchColumn();
} else {
	$num_open_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "ouvert" AND private = 0')->fetchColumn();
	$num_closed_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "ferme" AND private = 0')->fetchColumn();
	$num_resolved_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "resolu" AND private = 0')->fetchColumn();
}
?>

<?=template_header('Home')?>

<div class="content home">

	<h2>Accueil</h2>

	<div class="btns">
		<a href="create.php" class="btn">Ouvrir Un Ticket</a>
	</div>

	<div class="tickets-links responsive-width-100">
		<a href="tickets.php?status=ouvert" class="open responsive-width-100">
			<i class="far fa-clock fa-10x"></i>
			<span class="num"><?=number_format($num_open_tickets)?></span>
			<span class="title">Tickets Ouverts</span>
		</a>
		<a href="tickets.php?status=resolu" class="resolved responsive-width-100">
			<i class="fas fa-check fa-10x"></i>
			<span class="num"><?=number_format($num_resolved_tickets)?></span>
			<span class="title">Tickets Résolus</span>
		</a>
		<a href="tickets.php?status=ferme" class="closed responsive-width-100">
			<i class="fas fa-times fa-10x"></i>
			<span class="num"><?=number_format($num_closed_tickets)?></span>
			<span class="title">Tickets Fermé</span>
		</a>
	</div>

	<h2 class="new">Nouveaux Tickets</h2>

	<div class="tickets-list">
		<?php foreach ($tickets as $ticket): ?>
		<a href="view.php?id=<?=$ticket['id']?><?=isset($_SESSION['admin_loggedin']) && $ticket['private'] ? '&code=' . md5($ticket['id'] . $ticket['email']) : ''?>" class="ticket">
			<span class="con">
				<?php if ($ticket['status'] == 'ouvert'): ?>
				<i class="far fa-clock fa-2x"></i>
				<?php elseif ($ticket['status'] == 'ferme'): ?>
				<i class="fas fa-check fa-2x"></i>
				<?php elseif ($ticket['status'] == 'resolu'): ?>
				<i class="fas fa-times fa-2x"></i>
				<?php endif; ?>
			</span>
			<span class="con">
				<span class="title"><?=htmlspecialchars($ticket['title'], ENT_QUOTES)?></span>
				<span class="msg responsive-hidden"><?=htmlspecialchars($ticket['msg'], ENT_QUOTES)?></span>
			</span>
			<span class="con2">
				<span class="created responsive-hidden"><?=date('d-m-Y H:i', strtotime($ticket['created']))?></span>
				<span class="priority <?=$ticket['priority']?>"><?=$ticket['priority']?></span>
			</span>
		</a>
		<?php endforeach; ?>
	</div>

</div>

<?=template_footer()?>
