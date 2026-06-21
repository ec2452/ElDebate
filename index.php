<?php
$activePage = 'home';
$pageTitle  = 'Početna';
include 'header.php';
include 'connect.php';
define('UPLPATH', 'img/');
?>

<main>
<div class="container">

    <!-- ===== MUNDO ===== -->
    <h2 class="section-title">MUNDO</h2>
    <div class="articles-grid">
    <?php
    $query  = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='mundo' ORDER BY id DESC LIMIT 4";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)):
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
    </div>
    <hr class="section-sep">

    <!-- ===== DEPORTE ===== -->
    <h2 class="section-title">DEPORTE</h2>
    <div class="articles-grid">
    <?php
    $query  = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='deporte' ORDER BY id DESC LIMIT 4";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)):
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
    </div>

</div><!-- /container -->
</main>

<?php
mysqli_close($dbc);
include 'footer.php';
?>
