<?php

// emit table row unless value is empty
function row($label, $value) { 
    if (($value ?? '') == '') return;    
?>
  <tr>
    <tr>
    <td><?= htmlspecialchars($label) ?></td>
    <td><?= $value ?></td>
  </tr>
<?php }

// like sprintf but returns NULL if any argument is NULL
function formatted($format) {
    $args = func_get_args();
    array_shift($args);
    if (in_array(NULL, $args, TRUE)) return;
    return vsprintf($format, $args);
}
