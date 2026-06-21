<?php
$activePage = '';
include 'connect.php';
define('UPLPATH', 'img/');

// Dohvat ID-a iz GET parametra
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$query  = "SELECT * FROM vijesti WHERE id=$id AND arhiva=0 LIMIT 1";
$result = mysqli_query($dbc, $query);
$row    = mysqli_fetch_array($result);

if (!$row) {
    header('Location: index.php');
    exit;
}

$pageTitle = $row['naslov'];
include 'header.php';
?>

<div class="article-page">

    <span class="article-category-tag">
        <?php echo htmlspecialchars(strtoupper($row['kategorija'])); ?>
    </span>

    <h1 class="article-title">
        <?php echo htmlspecialchars($row['naslov']); ?>
    </h1>

    <p class="article-subtitle">
        <?php echo htmlspecialchars($row['sazetak']); ?>
    </p>

    <p class="article-meta">
        · <?php echo htmlspecialchars($row['datum']); ?>
    </p>

    <?php if (!empty($row['slika']) && file_exists(UPLPATH . $row['slika'])): ?>
        <img class="article-hero"
             src="<?php echo UPLPATH . htmlspecialchars($row['slika']); ?>"
             alt="<?php echo htmlspecialchars($row['naslov']); ?>">
    <?php endif; ?>

    <div class="article-body">
        <p><?php echo nl2br(htmlspecialchars($row['tekst'])); ?></p>
    </div>

</div>

<?php
mysqli_close($dbc);
include 'footer.php';
?>
