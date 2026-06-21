<?php
session_start();
include 'connect.php';

$activePage = '';
$pageTitle  = 'Registracija';

$registriranKorisnik = false;
$msg = '';

// Obrada registracije
if (isset($_POST['ime'])) {
    $ime      = trim($_POST['ime']);
    $prezime  = trim($_POST['prezime']);
    $username = trim($_POST['username']);
    $lozinka  = $_POST['pass'];
    $lozinka2 = $_POST['passRep'];
    $razina   = 0;

    if (empty($ime) || empty($prezime) || empty($username)) {
        $msg = 'Sva polja su obavezna!';
    } elseif ($lozinka !== $lozinka2 || empty($lozinka)) {
        $msg = 'Lozinke se ne podudaraju!';
    } else {
        $hashed_password = password_hash($lozinka, PASSWORD_BCRYPT);

        // Provjera postoji li korisničko ime (prepared statement)
        $sql  = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime = ?";
        $stmt = mysqli_stmt_init($dbc);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $msg = 'Korisničko ime već postoji! Odaberite drugo.';
        } else {
            // Unos novog korisnika (prepared statement)
            $sql2  = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)";
            $stmt2 = mysqli_stmt_init($dbc);
            if (mysqli_stmt_prepare($stmt2, $sql2)) {
                mysqli_stmt_bind_param($stmt2, 'ssssi', $ime, $prezime, $username, $hashed_password, $razina);
                if (mysqli_stmt_execute($stmt2)) {
                    $registriranKorisnik = true;
                } else {
                    $msg = 'Greška pri registraciji. Pokušajte ponovo.';
                }
                mysqli_stmt_close($stmt2);
            }
        }
        mysqli_stmt_close($stmt);
    }
}

include 'header.php';
?>

<main>
<div class="form-page">
    <h1>Registracija</h1>

    <?php if ($registriranKorisnik): ?>
        <div class="msg msg-success">
            Korisnik je uspješno registriran! <a href="administracija.php" style="color:var(--accent);font-weight:700;">Prijavi se</a>
        </div>
    <?php else: ?>

        <?php if (!empty($msg)): ?>
            <div class="msg msg-error"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <form enctype="multipart/form-data" action="" method="POST" id="regForm">

            <div class="form-item">
                <label for="ime">Ime</label>
                <div class="form-field">
                    <input type="text" name="ime" id="ime" class="form-field-textual">
                </div>
                <span id="porukaIme" class="bojaPoruke"></span>
            </div>

            <div class="form-item">
                <label for="prezime">Prezime</label>
                <div class="form-field">
                    <input type="text" name="prezime" id="prezime" class="form-field-textual">
                </div>
                <span id="porukaPrezime" class="bojaPoruke"></span>
            </div>

            <div class="form-item">
                <label for="username">Korisničko ime</label>
                <div class="form-field">
                    <input type="text" name="username" id="username" class="form-field-textual" autocomplete="username">
                </div>
                <span id="porukaUsername" class="bojaPoruke"></span>
            </div>

            <div class="form-item">
                <label for="pass">Lozinka</label>
                <div class="form-field">
                    <input type="password" name="pass" id="pass" class="form-field-textual" autocomplete="new-password">
                </div>
                <span id="porukaPass" class="bojaPoruke"></span>
            </div>

            <div class="form-item">
                <label for="passRep">Ponovite lozinku</label>
                <div class="form-field">
                    <input type="password" name="passRep" id="passRep" class="form-field-textual" autocomplete="new-password">
                </div>
                <span id="porukaPassRep" class="bojaPoruke"></span>
            </div>

            <div class="form-item">
                <button type="reset"  class="btn btn-secondary">Poništi</button>
                <button type="submit" class="btn btn-primary" id="slanje">Registriraj se</button>
            </div>

        </form>

        <script>
        document.getElementById('slanje').addEventListener('click', function(event) {
            var ok = true;

            var ime = document.getElementById('ime').value.trim();
            if (ime.length === 0) {
                ok = false;
                document.getElementById('ime').style.border = '1.5px dashed #e8311a';
                document.getElementById('porukaIme').textContent = 'Unesite ime!';
            } else {
                document.getElementById('ime').style.border = '1.5px solid #22c55e';
                document.getElementById('porukaIme').textContent = '';
            }

            var prezime = document.getElementById('prezime').value.trim();
            if (prezime.length === 0) {
                ok = false;
                document.getElementById('prezime').style.border = '1.5px dashed #e8311a';
                document.getElementById('porukaPrezime').textContent = 'Unesite prezime!';
            } else {
                document.getElementById('prezime').style.border = '1.5px solid #22c55e';
                document.getElementById('porukaPrezime').textContent = '';
            }

            var username = document.getElementById('username').value.trim();
            if (username.length === 0) {
                ok = false;
                document.getElementById('username').style.border = '1.5px dashed #e8311a';
                document.getElementById('porukaUsername').textContent = 'Unesite korisničko ime!';
            } else {
                document.getElementById('username').style.border = '1.5px solid #22c55e';
                document.getElementById('porukaUsername').textContent = '';
            }

            var pass    = document.getElementById('pass').value;
            var passRep = document.getElementById('passRep').value;
            if (pass.length === 0 || passRep.length === 0 || pass !== passRep) {
                ok = false;
                document.getElementById('pass').style.border    = '1.5px dashed #e8311a';
                document.getElementById('passRep').style.border = '1.5px dashed #e8311a';
                document.getElementById('porukaPass').textContent    = 'Lozinke nisu iste!';
                document.getElementById('porukaPassRep').textContent = 'Lozinke nisu iste!';
            } else {
                document.getElementById('pass').style.border    = '1.5px solid #22c55e';
                document.getElementById('passRep').style.border = '1.5px solid #22c55e';
                document.getElementById('porukaPass').textContent    = '';
                document.getElementById('porukaPassRep').textContent = '';
            }

            if (!ok) event.preventDefault();
        });
        </script>

    <?php endif; ?>

</div>
</main>

<?php
mysqli_close($dbc);
include 'footer.php';
?>
