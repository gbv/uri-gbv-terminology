<?php
if ($JSKOS->license) {
    row('Lizenz',
        implode('<br>', $JSKOS->license->map(
            function ($x) { 
                $uri = htmlspecialchars($x->uri);
                return "<a href='$uri'>$uri</a>";
            }
    )), 'copyright-mark');
}
