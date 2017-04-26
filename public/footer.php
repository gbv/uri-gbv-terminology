    </div>
     <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <a href="https://www.gbv.de/impressum">Verbundzentrale des GBV (VZG)</a>
          </div>        
          <div class="col-md-6">
            <a href="https://github.com/gbv/uri-gbv-terminology" class="pull-right">sources</a>
          </div>
        </div>
        <?php if ($URI ?? 0) { ?>
          <div class="row">
            <div class="col-md-12">
              <a href="<?= htmlspecialchars($URI) ?>"><?= htmlspecialchars($URI) ?></a>
              <?php if ($SELF ?? 0) { 
                foreach ($FORMATS as $format) {
                    echo '&nbsp;';
                    echo "<a href='$SELF";
                    if ($format != 'html') echo "?format=$format";
                    echo "'>$format</a>";
                }
             } ?>
            <div>
          </div>
        <?php } ?>
      </div>
    </footer>
  </body>
</html><?= $DEBUG ?? '' ?>
