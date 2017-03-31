    </div>
     <footer class="footer">
      <div class="container">
        <div class="row">
          <a href="https://www.gbv.de/impressum">Verbundzentrale des GBV (VZG)</a>
        </div>
        <?php if ($URI ?? 0) { ?>
        <div class="row">
          <a href="<?= htmlspecialchars($URI) ?>"><?= htmlspecialchars($URI) ?></a>
          <?php if ($APIURL ?? '') {
            echo "&nbsp;<a href='$APIURL'>jskos</a>";
# TODO: use this script as proxy instead? 
          } ?>
          <?php if ($SELF ?? 0) { 
			echo '&nbsp;';
			echo "<a href='$SELF'>html</a>";
			foreach ($RDF_FORMATS as $format) {
				echo '&nbsp;';
          		echo "<a href='$SELF?format=$format'>$format</a>";
			}
         } ?>
        </div>
        <?php } ?>
      </div>
    </footer>
  </body>
</html><?= $DEBUG ?? '' ?>
