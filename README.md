# debate – News Portal
## Programiranje web aplikacija, TVZ 2025/26

### Struktura projekta

```
debate_projekt/
├── css/style.css           – vanjski CSS
├── img/                    – slike vijesti
├── connect.php             – konekcija na bazu
├── header.php / footer.php – zajednički elementi stranice
├── index.php               – naslovna stranica (Faza I, III)
├── unos.html / skripta.php – forma i obrada unosa vijesti (Faza II)
├── clanak.php              – prikaz pojedine vijesti (Faza III)
├── kategorija.php          – prikaz vijesti po kategoriji (Faza III)
├── administracija.php      – CRUD i prijava administratora (Faza III, V)
├── registracija.php        – registracija korisnika (Faza V)
└── debate_portal.sql       – export baze podataka
```

### Postavljanje

1. Kopirati mapu `debate_projekt` u `htdocs` (XAMPP).
2. Pokrenuti Apache i MySQL.
3. U phpMyAdmin uvesti `debate_portal.sql` (Import).
4. Otvoriti `http://localhost/debate_projekt/`.

### Pristup administraciji

| Korisničko ime | Lozinka |
|---|---|
| `admin` | `password` |

Za promjenu lozinke generirati novi hash funkcijom `password_hash()` i ažurirati zapis u tablici `korisnik`.

### Faze projekta

| Faza | Opis | Datoteke |
|---|---|---|
| I | HTML/CSS struktura | `index.php`, `css/style.css` |
| II | HTML forma, PHP obrada | `unos.html`, `skripta.php` |
| III | PHP/MySQL, CRUD | `index.php`, `clanak.php`, `kategorija.php`, `administracija.php` |
| V | Sigurnost (autentikacija, sesije) | `administracija.php`, `registracija.php` |

### Sigurnosne mjere

- SQL injection: `mysqli` prepared statementi u `administracija.php` i `registracija.php`
- Lozinke: hashirane funkcijom `password_hash()` (bcrypt), provjera `password_verify()`
- Sesije: `session_start()`, podaci o prijavljenom korisniku u `$_SESSION`
- XSS: izlazni podaci escapani funkcijom `htmlspecialchars()`

---
Autor: Ema Čurla, ecurla@tvz.hr
