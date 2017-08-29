<table class="table">
<?php

include_once 'utils.php';

include 'item.php';
include 'license.php';

row('Umfang', $JSKOS->extent);

if ($JSKOS->languages) {
    row('Sprachen', $JSKOS->languages->implode(', '));
}

?>
</table>
<?php

if ($JSKOS->concepts) { 
    # TODO: AccessPoint with ->api and ->suggest?
    # TODO: show form to lookup and search
}

include('meta.php'); ?>
