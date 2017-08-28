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
              <?php 
                foreach ($FORMATS as $format) {
                    echo '&nbsp;';
                    echo "<a href='$BASE?uri=$URI";
                    if ($format != 'html') echo "&format=$format";
                    echo "'>$format</a>";
                }
                if ($APIURL ?? 0) { ?><!-- TODO: fixme -->
              <a href="<?= htmlspecialchars($APIURL) ?>">dante</a>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      </div>
    </footer>
  </body>
</html>
