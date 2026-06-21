<?php
session_start();
include 'connect.php';
define('UPLPATH', 'img/');

$activePage = 'admin';
$pageTitle  = 'Administracija';

// Korisnik je admin samo ako sesija to potvrđuje (postavlja se isključivo
// nakon uspješne prijave provjerene prepared statementom – vidi blok PRIJAVA)
$isLoggedInAdmin = isset($_SESSION['username']) && isset($_SESSION['level']) && $_SESSION['level'] == 1;

// -------------------------------------------------------
// BRISANJE (dopušteno isključivo prijavljenom adminu)
// -------------------------------------------------------
if ($isLoggedInAdmin && isset($_POST['delete'])) {
    $id = intval($_POST['id']);

    $sql  = "DELETE FROM vijesti WHERE id = ?";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: administracija.php');
    exit;
}

// -------------------------------------------------------
// IZMJENA / UPDATE (dopušteno isključivo prijavljenom adminu)
// -------------------------------------------------------
if ($isLoggedInAdmin && isset($_POST['update'])) {
    $id       = intval($_POST['id']);
    $title    = trim($_POST['title']);
    $about    = trim($_POST['about']);
    $content  = trim($_POST['content']);
    $category = $_POST['category'];
    $archive  = isset($_POST['archive']) ? 1 : 0;

    // Osnovna server-side validacija obaveznih polja
    if ($title === '' || $about === '' || $content === '') {
        header('Location: administracija.php?greska=prazna_polja');
        exit;
    }

    $picture = '';
    if (!empty($_FILES['pphoto']['name']) && $_FILES['pphoto']['error'] === UPLOAD_ERR_OK) {
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['pphoto']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExt, true)) {
            $picture = uniqid('img_') . '.' . $ext;
            move_uploaded_file($_FILES['pphoto']['tmp_name'], UPLPATH . $picture);
        }
    }

    if ($picture !== '') {
        $sql  = "UPDATE vijesti SET naslov=?, sazetak=?, tekst=?, kategorija=?, arhiva=?, slika=? WHERE id=?";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ssssisi', $title, $about, $content, $category, $archive, $picture, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } else {
        $sql  = "UPDATE vijesti SET naslov=?, sazetak=?, tekst=?, kategorija=?, arhiva=? WHERE id=?";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ssssii', $title, $about, $content, $category, $archive, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    header('Location: administracija.php');
    exit;
}

// -------------------------------------------------------
// PRIJAVA
// -------------------------------------------------------
$uspjesnaPrijava = false;
$admin           = false;
$imeKorisnika    = '';
$msg             = '';

if (isset($_POST['prijava'])) {
    $prijavaImeKorisnika      = $_POST['username'];
    $prijavaLozinkaKorisnika  = $_POST['lozinka'];

    // Prepared statement – zaštita od SQL injection
    $sql  = "SELECT korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime = ?";
    $stmt = mysqli_stmt_init($dbc);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $prijavaImeKorisnika);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $levelKorisnika);
        mysqli_stmt_fetch($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0 && password_verify($prijavaLozinkaKorisnika, $lozinkaKorisnika)) {
            $uspjesnaPrijava = true;
            $admin = ($levelKorisnika == 1);

            $_SESSION['username'] = $imeKorisnika;
            $_SESSION['level']    = $levelKorisnika;
        } else {
            $msg = 'Pogrešno korisničko ime ili lozinka. <a href="registracija.php">Registriraj se</a>';
        }
    }
    mysqli_stmt_close($stmt);
}

// -------------------------------------------------------
// ODJAVA
// -------------------------------------------------------
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: administracija.php');
    exit;
}

include 'header.php';
?>

<main>
<div class="admin-page">

<?php
// ----- PRIKAZ OVISNO O STANJU -----

// 1) Korisnik je admin (iz sesije ili upravo prijavljen)
$isAdmin = ($uspjesnaPrijava && $admin)
        || (isset($_SESSION['username']) && $_SESSION['level'] == 1);

// 2) Prijavljen ali nije admin
$isUserNotAdmin = ($uspjesnaPrijava && !$admin)
               || (isset($_SESSION['username']) && $_SESSION['level'] == 0);

if ($isAdmin):
?>
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <h1>Administracija vijesti</h1>
        <a href="administracija.php?logout=1" class="btn btn-secondary" style="font-size:0.8rem;">Odjava (<?php echo htmlspecialchars($_SESSION['username'] ?? $imeKorisnika); ?>)</a>
    </div>

    <p style="margin-bottom:20px;">
        <a href="unos.html" class="btn btn-primary">+ Nova vijest</a>
    </p>

    <?php
    $query  = "SELECT * FROM vijesti ORDER BY id DESC";
    $result = mysqli_query($dbc, $query);

    while ($row = mysqli_fetch_array($result)):
    ?>
    <div class="admin-record">
        <div class="admin-record-header">
            <span>#<?php echo $row['id']; ?> &ndash; <?php echo htmlspecialchars($row['datum']); ?></span>
            <span class="card-category"><?php echo htmlspecialchars(strtoupper($row['kategorija'])); ?></span>
        </div>

        <form enctype="multipart/form-data" action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-item">
                <label>Naslov vijesti</label>
                <div class="form-field">
                    <input type="text" name="title" class="form-field-textual" value="<?php echo htmlspecialchars($row['naslov']); ?>">
                </div>
            </div>

            <div class="form-item">
                <label>Kratki sadržaj</label>
                <div class="form-field">
                    <textarea name="about" cols="30" rows="3" class="form-field-textual"><?php echo htmlspecialchars($row['sazetak']); ?></textarea>
                </div>
            </div>

            <div class="form-item">
                <label>Sadržaj vijesti</label>
                <div class="form-field">
                    <textarea name="content" cols="30" rows="5" class="form-field-textual"><?php echo htmlspecialchars($row['tekst']); ?></textarea>
                </div>
            </div>

            <div class="form-item">
                <label>Slika</label>
                <div class="form-field" style="display:flex;align-items:center;gap:12px;">
                    <input type="file" name="pphoto">
                    <?php if (!empty($row['slika'])): ?>
                        <img src="<?php echo UPLPATH . htmlspecialchars($row['slika']); ?>" width="80" style="border-radius:4px;">
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-item">
                <label>Kategorija</label>
                <div class="form-field">
                    <select name="category" class="form-field-textual">
                        <option value="mundo"   <?php echo $row['kategorija']==='mundo'   ? 'selected' : ''; ?>>Mundo</option>
                        <option value="deporte" <?php echo $row['kategorija']==='deporte' ? 'selected' : ''; ?>>Deporte</option>
                    </select>
                </div>
            </div>

            <div class="form-item">
                <label>
                    Arhiva:
                    <input type="checkbox" name="archive" <?php echo $row['arhiva'] ? 'checked' : ''; ?>>
                </label>
            </div>

            <div class="form-item">
                <button type="reset"  class="btn btn-secondary">Poništi</button>
                <button type="submit" name="update" class="btn btn-primary">Izmjeni</button>
                <button type="submit" name="delete" class="btn btn-danger"
                    onclick="return confirm('Jeste li sigurni da želite obrisati ovu vijest?')">Izbriši</button>
            </div>
        </form>
    </div>
    <?php endwhile; ?>

<?php elseif ($isUserNotAdmin): ?>

    <div class="msg msg-info">
        Bok <strong><?php echo htmlspecialchars($_SESSION['username'] ?? $imeKorisnika); ?></strong>!
        Uspješno ste prijavljeni, ali nemate administratorska prava za pristup ovoj stranici.
    </div>
    <a href="administracija.php?logout=1" class="btn btn-secondary">Odjava</a>

<?php else: ?>

    <!-- LOGIN FORMA -->
    <div class="login-box">
        <h2>Prijava</h2>

        <?php if (!empty($msg)): ?>
            <p class="msg msg-error"><?php echo $msg; ?></p>
        <?php endif; ?>

        <form action="" method="POST" id="loginForm">

            <div class="form-item">
                <label for="username">Korisničko ime</label>
                <div class="form-field">
                    <input type="text" name="username" id="loginUsername" class="form-field-textual" autocomplete="username">
                </div>
                <span id="porukaUsername" class="bojaPoruke"></span>
            </div>

            <div class="form-item">
                <label for="lozinka">Lozinka</label>
                <div class="form-field">
                    <input type="password" name="lozinka" id="loginLozinka" class="form-field-textual" autocomplete="current-password">
                </div>
                <span id="porukaLozinka" class="bojaPoruke"></span>
            </div>

            <div class="form-item">
                <button type="submit" name="prijava" class="btn btn-primary" style="width:100%;">Prijavi se</button>
            </div>

            <p style="text-align:center;font-size:0.85rem;margin-top:12px;">
                Nemaš račun? <a href="registracija.php" style="color:var(--accent);font-weight:700;">Registriraj se</a>
            </p>

        </form>
    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        var ok = true;
        var u = document.getElementById('loginUsername').value.trim();
        var p = document.getElementById('loginLozinka').value.trim();

        if (u.length === 0) {
            document.getElementById('porukaUsername').textContent = 'Unesite korisničko ime!';
            ok = false;
        } else {
            document.getElementById('porukaUsername').textContent = '';
        }
        if (p.length === 0) {
            document.getElementById('porukaLozinka').textContent = 'Unesite lozinku!';
            ok = false;
        } else {
            document.getElementById('porukaLozinka').textContent = '';
        }
        if (!ok) e.preventDefault();
    });
    </script>

<?php endif; ?>

</div><!-- /admin-page -->
</main>

<?php
mysqli_close($dbc);
include 'footer.php';
?>
