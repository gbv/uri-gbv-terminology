<p>
An dieser Stellen werden Normdaten und andere Terminologien (engl. <em>Knowledge Organization Systems</em>)
als Linked Open Data bereitgestellt.
</p>

<p>
Für Terminologien die vom GBV oder dessen Teilnehmern herausgegeben werden, können URIs
der Form <code>http://uri.gbv.de/terminology/...</code> vergeben werden.
</p>

<h2>Ausgewählte Terminologien</h2>
<ul class="narrower">
  <li>Basisklassifikation (<a href="bk">BK</a>)</li>
  <li>Lizenzen (<a href="license">license</a>)</li>
  <li>Hornbostel-Sachs-Systematik (<a href="hornbostelsachs">hornbostelsachs</a>)</li>
</ul>

<h2>Alle verfügbaren Terminologien (unsortiert)</h2>
<ul class="narrower">
  <?php foreach($JSKOS as $kos) {
    echo "<li>";
    echo $kos->prefLabel['de'] ?? $kos->prefLabel['en'];
    $id = $kos->notation[0];
    echo " (<a href='$id'>$id</a>)</li>";
  } ?>
</ul>

<h2>Weitere Terminologien</h2>
<ul class="narrower">
  <li>
    <a href="bartoc">BARTOC</a> erfasst alle im GBV relevanten Normdateien und Terminologien.
    Einträge aus BARTOC können nach JSKOS umgesetzt hier abgerufen werden, z.B.
    <a href="bartoc/15">http://bartoc.org/en/node/15</a>.
  </li>
  <li><a href="leibniz">Systematik der Leibniz-Bibliographie</a> (z.Z. offline)</li>
  <li><a href="aad">Gattungsbegriffe der Arbeitsgemeinschaft Alte Drucke</a> (AAD), in Vorbereitung</li>
  <li>Göttinger Online-Klassifikation (GOK), in Vorbereitung</li>
</ul>
