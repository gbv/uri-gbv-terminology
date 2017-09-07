<!DOCTYPE html>
<html lang="<?= $LANGUAGE ?? 'de' ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($TITLE ?? '???') ?></title>
    <link rel="stylesheet" href="<?= $BASE ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $BASE ?>css/bootstrap-vzg.css">
    <link rel="stylesheet" href="<?= $BASE ?>css/leaflet.css">
    <script src="<?=$BASE."js/leaflet.js"?>"></script>
    <script src="<?=$BASE."js/jquery.js"?>"></script>
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand hidden-xs" href="<?= $BASE ?>../">uri.gbv.de</a>
          <a class="navbar-brand" href="<?= $BASE ?>">terminology</a>
          <?php if ($VOCID ?? '') { 
          ?><a class="navbar-brand" href="<?= "$BASE$VOCID/" ?>"><?= $VOCID ?></a>              
          <?php if ($NOTATION ?? '') { 
          ?><a class="navbar-brand" href="#"><?= $NOTATION ?></a>
          <?php } } 
        ?></div>
        <ul class="nav navbar-nav navbar-right">
          <li style="border: none"><a href="<?= $BASE ?>about/">Hilfe</a></li>
        </ul>
      </div>
    </nav>
    <div class="container">
      <h1><?= htmlspecialchars($TITLE ?? '???') ?></h1>
