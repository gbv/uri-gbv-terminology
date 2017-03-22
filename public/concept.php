<table class="table">
<tr>
<td>notation</td>
<td><?=
    implode(', ', array_map(
        function ($n) { return '<code>'.htmlspecialchars($n).'</code>'; },
        $JSKOS->notation
    ));
?></td>  
</tr>
<?php if (count($JSKOS->broader)) {
    echo "<tr><td>broader</td><td>";
    echo implode('<br>', array_map(
        function ($x) { 
            $uri = $x->uri;
            $href = $uri;
            return '<a href="'.htmlspecialchars($href).'">'
                   .htmlspecialchars($uri).'</a>'; 
        },
        $JSKOS->broader
    ));
    echo "</td></tr>";
}
?>
</table>
<?php
include 'jskos.php';
