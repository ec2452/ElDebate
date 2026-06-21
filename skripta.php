<?php
session_start();

// Samo prijavljeni administrator smije dodavati vijesti.
// (unos.html ostaje dostupan kao statična stranica iz Faze II, ali stvarni
// upis u bazu se odbija ako korisnik nije admin.)
if (!isset($_SESSION['username']) || !isset($_SESSION['level']) || $_SESSION['level'] != 1) {
    header('Location: administracija.php');
    exit;
}

include 'connect.php';

$activePage = '';
$pageTitle  = 'Pregled vijesti';

define('UPLPATH', 'img/');

// Dohvat podataka iz POST forme
$title    = isset($_POST['title'])    ? trim($_POST['title'])   : '';
$about    = isset($_POST['about'])    ? trim($_POST['about'])   : '';
$content  = isset($_POST['content'])  ? trim($_POST['content']) : '';
$category = isset($_POST['category']) ? $_POST['category']      : '';
$archive  = isset($_POST['archive'])  ? 1 : 0;
$datum    = date('d.m.Y.');

// Server-side provjera obaveznih polja (JS validacija se može zaobići)
if ($title === '' || $about === '' || $content === '') {
    header('Location: unos.html?greska=prazna_polja');
    exit;
}

// Premještanje slike – samo dopuštene ekstenzije, novo (sigurno) ime datoteke
$image = '';
if (!empty($_FILES['pphoto']['name']) && $_FILES['pphoto']['error'] === UPLOAD_ERR_OK) {
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($_FILES['pphoto']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowedExt, true)) {
        $image = uniqid('img_') . '.' . $ext;
        move_uploaded_file($_FILES['pphoto']['tmp_name'], UPLPATH . $image);
    }
}

// Pohrana vijesti u bazu (prepared statement)
$sql  = "INSERT INTO vijesti (datum, naslov, sazetak, tekst, slika, kategorija, arhiva) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_stmt_init($dbc);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ssssssi', $datum, $title, $about, $content, $image, $category, $archive);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
mysqli_close($dbc);

include 'header.php';
?>

<div class="article-page">

    <span class="article-category-tag">
        <?php echo htmlspecialchars(strtoupper($category)); ?>
    </span>

    <h1 class="article-title">
        <?php echo htmlspecialchars($title); ?>
    </h1>

    <p class="article-meta">OBJAVLJENO: <?php echo htmlspecialchars($datum); ?></p>

    <?php if (!empty($image) && file_exists(UPLPATH . $image)): ?>
        <img class="article-hero" src="<?php echo UPLPATH . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>">
    <?php endif; ?>

    <div class="article-body">
        <p><em><?php echo htmlspecialchars($about); ?></em></p>
        <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
    </div>

    <?php if ($archive): ?>
        <p class="msg msg-info" style="margin-top:20px;">Ova vijest je označena za arhivu – neće biti prikazana na naslovnici.</p>
    <?php endif; ?>

    <p style="margin-top:30px;">
        <a href="administracija.php" class="btn btn-secondary">← Natrag na administraciju</a>
    </p>

</div>

<?php include 'footer.php'; ?>
