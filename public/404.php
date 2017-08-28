<p>
Unter der URI <code><?= htmlspecialchars($URI ?? '???'); ?></code> wurde kein Eintrag gefunden!
</p>
<?php 
http_response_code(404);
?>
