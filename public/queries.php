<?php if (count($QUERIES ?? [])) { ?>
<h3>Anfragen an DANTE-API</h3>
<table class="table">
  <thead>
    <tr>
      <th>time</th>
      <th>URL</th>
      <th class="text-right">duration</th>
      <th class="text-right">result</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($QUERIES as $Q) { ?>
    <tr>
      <td><?=$Q['time']?></td>
      <td>
        <a href="<?=htmlspecialchars($Q['url'])?>"><?=htmlspecialchars($Q['url'])?></a>
      </td>
      <td class="text-right"><?=sprintf("%d ms",1000*$Q['duration'])?></td>
      <td class="text-right"><?=count($Q['result'])?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php } ?>
