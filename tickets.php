<?php
session_start();
include 'functions.php';
// Connectez-vous à MySQL en utilisant la fonction ci-dessous
$pdo = pdo_connect_mysql();
// Récupère tous les noms de catégories de la table MySQL
$categories = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
// Requête MySQL qui sélectionne tous les tickets à partir de la databse
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$priority = isset($_GET['priority']) ? $_GET['priority'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';
// La page de pagination actuelle
$page = isset($_GET['page']) ? $_GET['page'] : 1;
//Le nombre maximum de tickets par page
$num_tickets_per_page = 5;
// Construire la chaîne SQL
$sql = '';
$sql .= $status != 'all' ? ' status = :status AND' : '';
$sql .= $category != 'all' ? ' category_id = :category AND' : '';
$sql .= $priority != 'all' ? ' priority = :priority AND' : '';
$sql .= $search ? ' title LIKE :search AND' : '';
$sql .= !isset($_SESSION['admin_loggedin']) ? ' private = 0 AND' : '';
$sql = !empty($sql) ? rtrim('WHERE ' . $sql, 'AND') : '';
// Récupérer les tickets depuis la base de données
$stmt = $pdo->prepare('SELECT * FROM tickets ' . $sql . ' ORDER BY created DESC LIMIT :current_page, :tickets_per_page');
// Paramètres de liaison
if ($status != 'all') {
	$stmt->bindParam(':status', $status);
}
if ($category != 'all') {
	$stmt->bindParam(':category', $category);
}
if ($priority != 'all') {
	$stmt->bindParam(':priority', $priority);
}
if ($search) {
	$s = '%' . $search . '%';
	$stmt->bindParam(':search', $s);
}
$stmt->bindValue(':current_page', ($page-1)*(int)$num_tickets_per_page, PDO::PARAM_INT);
$stmt->bindValue(':tickets_per_page', (int)$num_tickets_per_page, PDO::PARAM_INT);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Les requêtes ci-dessous obtiendront le nombre total de tickets
if (isset($_SESSION['admin_loggedin'])) {
	// Seuls les administrateurs peuvent voir les billets publics et privés
	$num_tickets = $pdo->query('SELECT COUNT(*) FROM tickets')->fetchColumn();
	$num_open_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "ouvert"')->fetchColumn();
	$num_closed_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "ferme"')->fetchColumn();
	$num_resolved_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "resolu"')->fetchColumn();
} else {
	$num_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE private = 0')->fetchColumn();
	$num_open_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "ouvert" AND private = 0')->fetchColumn();
	$num_closed_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "ferme" AND private = 0')->fetchColumn();
	$num_resolved_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "resolu" AND private = 0')->fetchColumn();
}
?>

<?=template_header('Tickets')?>

<div class="content tickets">

	<h2>Tout les Tickets</h2>

	<div class="tickets-list">
		<?php foreach ($tickets as $ticket): ?>
		<a href="view.php?id=<?=$ticket['id']?><?=isset($_SESSION['admin_loggedin']) && $ticket['private'] ? '&code=' . md5($ticket['id'] . $ticket['email']) : ''?>" class="ticket">
			<span class="con">
				<?php if ($ticket['status'] == 'ouvert'): ?>
				<i class="far fa-clock fa-2x"></i>
				<?php elseif ($ticket['status'] == 'resolu'): ?>
				<i class="fas fa-check fa-2x"></i>
				<?php elseif ($ticket['status'] == 'ferme'): ?>
				<i class="fas fa-times fa-2x"></i>
				<?php endif; ?>
			</span>
			<span class="con">
				<span class="title"><?=htmlspecialchars($ticket['title'], ENT_QUOTES)?></span>
				<span class="msg responsive-hidden"><?=htmlspecialchars($ticket['msg'], ENT_QUOTES)?></span>
			</span>
			<span class="con2">
				<span class="created responsive-hidden"><?=date('Y-m-d H:i:s', strtotime($ticket['created']))?></span>
				<span class="priority <?=$ticket['priority']?>"><?=$ticket['priority']?></span>
			</span>
		</a>
		<?php endforeach; ?>
	</div>

	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="tickets.php?status=<?=$status?>&category=<?=$category?>&priority=<?=$priority?>&search=<?=$search?>&page=<?=$page-1?>">Précedent</a>
		<?php endif; ?>
		<?php if (count($tickets) >= $num_tickets_per_page): ?>
		<a href="tickets.php?status=<?=$status?>&category=<?=$category?>&priority=<?=$priority?>&search=<?=$search?>&page=<?=$page+1?>">Suivant</a>
		<?php endif; ?>
	</div>

</div>

<?=template_footer()?>
