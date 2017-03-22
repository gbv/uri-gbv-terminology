    </div>
     <footer class="footer">
      <div class="container">
        <div class="row">
          <a href="https://www.gbv.de/impressum">Verbundzentrale des GBV (VZG)</a>
        </div>
        <?php if ($URI) { ?>
        <div class="row">
          <a href="<?= htmlspecialchars($URI) ?>"><?= htmlspecialchars($URI) ?></a>
          <?php if ($APIURL ?? '') {
            echo "&nbsp;<a href='$APIURL'>jskos</a>";
# TODO: use this script as proxy instead? 
# TODO: add RDF output
          } ?>
          &nbsp;
          <a href="<?= $SELF ?>">html</a>
        </div>
        <?php } ?>
      </div>
    </footer>
  </body>
</html><?= $DEBUG ?? '' ?>
