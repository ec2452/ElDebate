ElDebate

Web portal s vijestima izrađen u PHP-u i MySQL-u. Posjetitelji mogu čitati vijesti po kategorijama, a prijavljeni administratori upravljaju sadržajem kroz administracijsko sučelje.

Projekt je izrađen u sklopu kolegija Programiranje web aplikacija na Tehničkom veleučilištu u Zagrebu (2025./26.).

Mogućnosti
Naslovna stranica s pregledom najnovijih vijesti
Prikaz pojedinog članka i popis vijesti po kategorijama
Unos novih vijesti putem forme (unos.html → skripta.php)
Administracija: dodavanje, izmjena i brisanje vijesti (CRUD)
Registracija korisnika i prijava sa sesijama
Tehnologije
PHP (mysqli)
MySQL
HTML i CSS
XAMPP za lokalno pokretanje (Apache + MySQL)
Pokretanje lokalno
Instaliraj XAMPP i pokreni Apache i MySQL.
Kloniraj repozitorij u mapu htdocs:
cd C:\xampp\htdocs
git clone https://github.com/ec2452/ElDebate.git
U phpMyAdmin (http://localhost/phpmyadmin) uvezi datoteku debate_portal.sql (Import).
Po potrebi prilagodi podatke za spajanje na bazu u connect.php.
Otvori http://localhost/ElDebate/.
Administracija

Za testiranje je u bazi unaprijed kreiran administratorski račun:

Korisničko ime	Lozinka
admin	password

Ovi podaci služe samo za lokalno testiranje. Ako projekt postavljaš na pravi server, obavezno promijeni lozinku: novi hash generiraj funkcijom password_hash() i ažuriraj zapis u tablici korisnik.

Sigurnost
SQL injection: upiti u administracija.php i registracija.php koriste mysqli prepared statemente.
Lozinke: spremaju se hashirane s password_hash() (bcrypt), a provjeravaju s password_verify().
Sesije: prijavljeni korisnik prati se kroz session_start() i $_SESSION.
XSS: ispis podataka escapa se funkcijom htmlspecialchars().
Struktura projekta
ElDebate/
├── css/style.css           – stilovi
├── img/                    – slike uz vijesti
├── connect.php             – spajanje na bazu
├── header.php / footer.php – zajednički dijelovi stranice
├── index.php               – naslovna stranica
├── clanak.php              – prikaz pojedine vijesti
├── kategorija.php          – vijesti po kategoriji
├── unos.html / skripta.php – forma i obrada unosa vijesti
├── administracija.php      – prijava administratora i CRUD
├── registracija.php        – registracija korisnika
└── debate_portal.sql       – izvoz baze podataka
Autor

Ema Čurla · ecurla@tvz.hr
