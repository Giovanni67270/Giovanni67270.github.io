<?php
include 'functions.php';

$pdo = pdo_connect_mysql();
$msg = '';
// Récupère tous les noms de catégories de la table MySQL
$categories = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
// Vérifier si les données POST data existent (l’utilisateur a soumis le formulaire)
if (isset($_POST['title'], $_POST['email'], $_POST['msg'], $_POST['priority'], $_POST['category'], $_POST['private'])) {
    // Contrôles de validation...
    if (empty($_POST['title']) || empty($_POST['email']) || empty($_POST['msg']) || empty($_POST['priority'])) {
        $msg = 'Merci de completer le formulaire';
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $msg = 'Merci d entrer votre adresse email';
    } else {
        // Insérer un nouvel enregistrement dans la table des tickets
        $stmt = $pdo->prepare('INSERT INTO tickets (title, email, msg, priority, category_id, private) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([ $_POST['title'], $_POST['email'], $_POST['msg'], $_POST['priority'], $_POST['category'], $_POST['private'] ]);
        // Rediriger vers la page de ticket de vue, l’utilisateur devrait voir son ticket créé sur cette page
        header('Location: view.php?id=' . $pdo->lastInsertId() . ($_POST['private'] ? '&code=' . md5($pdo->lastInsertId() . $_POST['email']) : ''));
    }
}
?>

<?=template_header('Create Ticket')?>

<div class="content update">

	<h2>Crée Un Ticket</h2>

    <form action="create.php" method="post" class="responsive-width-100">
        <label for="title">Titre</label>
        <input type="text" name="title" placeholder="Titre" id="title" required>
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="adresse e-mail" id="email" required>
        <label for="category">Categories</label>
        <select name="category" id="category">
            <?php foreach($categories as $category): ?>
            <option value="<?=$category['id']?>"><?=$category['name']?></option>
            <?php endforeach; ?>
        </select>
        <label for="priority">Priorité</label>
        <select name="priority" id="priority" required>
            <option value="bas">Bas</option>
            <option value="moyen">Moyen</option>
            <option value="haut">Haut</option>
        </select>
        <label for="priority">Privée</label>
        <select name="private" id="private" required>
            <option value="0">Non</option>
            <option value="1">Oui</option>
        </select>
        <label for="msg">Message</label>
        <textarea name="msg" placeholder="Tapez votre texte ici" id="msg" required></textarea>
        <input type="submit" value="Créer">
    </form>

    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>

</div>

<?=template_footer()?>
