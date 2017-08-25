<table class="table">
<?php

foreach(['creator','publisher','contributor'] as $field) {
    row_list($field, $JSKOS, $field, 'uri_link_with_label', 'user');
}
foreach(['created','issued','modified'] as $field) {
    if ($JSKOS->$field) {
        row($field, $JSKOS->$field, 'time');
    }
}

?>
</table>
