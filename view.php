<?php
session_start();
include 'functions.php';
// On se connete a MySQL avec la fonction ci dessous
$pdo = pdo_connect_mysql();
// Vérifier si le paramètre ID de l’URL existe
if (!isset($_GET['id'])) {
    exit('ID non specifier!');
}
// Requête MySQL qui sélectionne le ticket par la colonne ID, en utilisant la variable ID GET request
$stmt = $pdo->prepare('SELECT t.*, c.name AS category FROM tickets t LEFT JOIN categories c ON c.id = t.category_id WHERE t.id = ?');
$stmt->execute([ $_GET['id'] ]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);
// Vérifier l’existence du ticket
if (!$ticket) {
    exit('Ticket non trouvé  <a href="http://localhost/ppe3/">revenir en arrière</a>');
}
if ($ticket['private'] && (!isset($_GET['code']) || $_GET['code'] != md5($ticket['id'] . $ticket['email']))) {
    exit('Ceci est un ticket privée ! <a href="http://localhost/ppe3/">revenir en arrière</a>');
}
// Si le ticket est privé, ajoutez le code à l’URL
$private_url = $ticket['private'] ? '&code=' . md5($ticket['id'] . $ticket['email']) : '';
// état de mise à jour
if (isset($_GET['status'], $_SESSION['admin_loggedin']) && in_array($_GET['status'], array('ouvert', 'ferme', 'resolu'))) {
    $stmt = $pdo->prepare('UPDATE tickets SET status = ? WHERE id = ?');
    $stmt->execute([ $_GET['status'], $_GET['id'] ]);
    // Envoyer un courriel de ticket mis à jour à l’utilisateur
    send_ticket_email($ticket['email'], $ticket['id'], 'ticket-update-template.html');
    header('Location: view.php?id=' . $_GET['id'] . $private_url);
    exit;
}
// Vérifiez si le formulaire de commentaires a été soumis.
if (isset($_POST['msg']) && !empty($_POST['msg'])) {
    // Insérer le nouveau commentaire dans la table "tickets_comments"
    $stmt = $pdo->prepare('INSERT INTO tickets_comments (ticket_id, msg) VALUES (?, ?)');
    $stmt->execute([ $_GET['id'], $_POST['msg'] ]);
}
$stmt = $pdo->prepare('SELECT * FROM tickets_comments WHERE ticket_id = ? ORDER BY created DESC');
$stmt->execute([ $_GET['id'] ]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Ticket')?>

<div class="content view">

	<h2><?=htmlspecialchars($ticket['title'], ENT_QUOTES)?> <span class="<?=$ticket['status']?>">(<?=$ticket['status']?>)</span></h2>

    <div class="ticket">
        <div>
            <p>
                <span class="priority <?=$ticket['priority']?>"><?=$ticket['priority']?></span>
                <span class="sep">&bull;</span>
                <span class="category"><?=$ticket['category']?></span>
            </p>
            <p class="created"><?=date('d-m-Y H:i', strtotime($ticket['created']))?></p>
        </div>
        <p class="msg"><?=nl2br(htmlspecialchars($ticket['msg'], ENT_QUOTES))?></p>
    </div>

    <div class="comments">
        <?php foreach($comments as $comment): ?>
        <div class="comment">
            <div>
                <i class="fas fa-comment fa-2x"></i>
            </div>
            <p>
                <span><?=date('d-m-Y H:i', strtotime($comment['created']))?></span>
                <?=nl2br(htmlspecialchars($comment['msg'], ENT_QUOTES))?>
            </p>
        </div>
        <?php endforeach; ?>
        <form action="" method="post" class="responsive-width-100">
            <textarea name="msg" placeholder="Entrer un Commentaire" class="responsive-width-100"></textarea>
            <input type="submit" value="Poster un commentaire">
        </form>
    </div>

</div>

<?=template_footer()?>
