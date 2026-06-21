<?php
include 'connect.php';
define('UPLPATH', 'img/');

$kategorija = isset($_GET['id']) ? mysqli_real_escape_string($dbc, $_GET['id']) : '';

$categoryNames = [
    'mundo'   => 'MUNDO',
    'deporte' => 'DEPORTE',
];

$activePage = $kategorija;
$pageTitle  = isset($categoryNames[$kategorija]) ? $categoryNames[$kategorija] : 'Kategorija';

include 'header.php';
?>

<main>
<div class="container">

    <h2 class="section-title"><?php echo htmlspecialchars($pageTitle); ?></h2>

    <div class="articles-grid">
    <?php
    $query  = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='$kategorija' ORDER BY id DESC";
    $result = mysqli_query($dbc, $query);
    $count  = 0;

    while ($row = mysqli_fetch_array($result)):
        $count++;
    ?>
        <article class="article-card">
            <?php if (!empty($row['slika']) && file_exists(UPLPATH . $row['slika'])): ?>
                <img class="card-img" src="<?php echo UPLPATH . htmlspecialchars($row['slika']); ?>" alt="<?php echo htmlspecialchars($row['naslov']); ?>">
            <?php else: ?>
                <div class="card-img-placeholder">Bez slike</div>
            <?php endif; ?>
            <div class="card-body">
                <p class="card-category"><?php echo htmlspecialchars(strtoupper($row['kategorija'])); ?></p>
                <h3 class="card-title">
                    <a href="clanak.php?id=<?php echo $row['id']; ?>">
                        <?php echo htmlspecialchars($row['naslov']); ?>
                    </a>
                </h3>
                <p class="card-meta">
                    <span>· <?php echo htmlspecialchars($row['datum']); ?></span>
                </p>
            </div>
        </article>
    <?php endwhile; ?>

    <?php if ($count === 0): ?>
        <p class="msg msg-info" style="grid-column:1/-1">Nema vijesti u ovoj kategoriji.</p>
    <?php endif; ?>
    </div>

</div>
</main>

<?php
mysqli_close($dbc);
include 'footer.php';
?>
