<?php
/*
Produit une page HTML standardisée.
Variables utilisées :
$TITLE
$H1
$HEADER #contenu du header autre que le h1 (h2, p...)
$NAV
$ASIDE
$MAIN #contenu de la section principale de la page, d'id "main"
$FOOTER
*/?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title><?= @ $TITLE ?></title>
<link rel="stylesheet" href="<?= $CSS ?? 'css/index.css' ?>"/>
<script type="text/javascript" src="js/index.js"></script>
</head>

<body>
  <header>
    <a href="."><img src="img/aschat.png" id="logo"/></a>
    <h1>Aschat</h1>
<?= @ $HEADER ?>
  </header>

  <nav>
<?= @ $NAV ?>
  </nav>

  <aside>
<?= @ $ASIDE ?>
  </aside>

  <section id="main">
<?= @ $MAIN ?>
  </section>

  <footer>
<?= @ $FOOTER ?>
  </footer>
</body>

</html>
