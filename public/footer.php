    </div>
     <footer class="footer">
      <div class="container">
        <div class="row">
          <a href="https://www.gbv.de/impressum">Verbundzentrale des GBV (VZG)</a>
        </div>
        <?php if ($URI ?? 0) { ?>
        <div class="row">
          <a href="<?= htmlspecialchars($URI) ?>"><?= htmlspecialchars($URI) ?></a>
          <?php if ($SELF ?? 0) { 
			foreach ($FORMATS as $format) {
				echo '&nbsp;';
                echo "<a href='$SELF";
                if ($format != 'html') echo "?format=$format";
                echo "'>$format</a>";
			}
         } ?>
        </div>
        <?php } ?>
      </div>
    </footer>
  </body>
</html><?= $DEBUG ?? '' ?>
