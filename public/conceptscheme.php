<table class="table">
<tr>
<td>notation</td>
<td><?=
    implode(', ', array_map(
        function ($x) { return '<code>'.htmlspecialchars($x).'</code>'; },
        $JSKOS->notation
    ));
?></td>
</tr>
<td>license</td>
<td><?= 
    implode('<br>', array_map(
        function ($x) { 
            $uri = htmlspecialchars($x->uri);
            return "<a href='$uri'>$uri</a>";
        },
        $JSKOS->license
    ));
?></td>
</tr>
</table>
<?php
include 'jskos.php';
