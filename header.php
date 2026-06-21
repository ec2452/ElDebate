<?php

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | debate' : 'debate – Vaš news portal'; ?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">
            <span class="accent-dot">≡</span>debate
        </a>
    </div>
</header>

<nav>
    <div class="nav-inner">
        <a href="index.php"          class="<?php echo (isset($activePage) && $activePage==='home')   ? 'active' : ''; ?>">HOME</a>
        <a href="kategorija.php?id=mundo"   class="<?php echo (isset($activePage) && $activePage==='mundo')  ? 'active' : ''; ?>">MUNDO</a>
        <a href="kategorija.php?id=deporte" class="<?php echo (isset($activePage) && $activePage==='deporte')? 'active' : ''; ?>">DEPORTE</a>
        <a href="administracija.php" class="<?php echo (isset($activePage) && $activePage==='admin')  ? 'active' : ''; ?>">ADMINISTRACIJA</a>
    </div>
</nav>
