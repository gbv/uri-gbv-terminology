<!DOCTYPE html>
<html lang="<?= $LANGUAGE ?? 'de' ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($TITLE ?? '???') ?></title>
    <link rel="stylesheet" href="<?= $BASE ?? './' ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?= $BASE ?? './' ?>bootstrap-vzg.css">
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand hidden-xs" href="<?= $BASE ?? './' ?>../">uri.gbv.de</a>
          <a class="navbar-brand" href="<?= $BASE ?? './' ?>">terminology</a>
          <?php if ($KOS ?? '') { ?>
          <a class="navbar-brand" href="<?= "$BASE$KOS/" ?>"><?= $KOS ?></a>
          <?php if ($ID ?? '') { ?>
          <a class="navbar-brand" href="<?= $SELF ?? '' ?>"><?= $ID ?></a>
          <?php } } ?>
        </div>
      </div>
    </nav>
    <div class="container">
      <h1><?= htmlspecialchars($TITLE ?? '???') ?></h1>
