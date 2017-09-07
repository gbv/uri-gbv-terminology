<?php include 'header.php'; ?>

<p>
  Unter <a href="<?=$BASE?>">http://uri.gbv.de/terminology/</a>
  werden verschiedene
  Normdateien, Klassifikationen, Thesauri, Wortlisten und andere
  Wissensorganisationssysteme (englisch "Knowledge Organization Systems")
  in einheitlicher Form bereitgestellt. Jedes System und jedes darin
  enthaltene Konzept ist eindeutig durch eine URI identifiziert.
  Falls das System innerhalb des GBV herausgegeben wird oder keine offiziellen
  URIs bekannt sind, werden eigene URIs vergeben.
</p>

<h2>Abruf per URI</h2>
<p>
  Falls die URI mit <code>http://uri.gbv.de/terminology/</code> beginnt, kann
  sie direkt aufgerufen werden, ansonsten per URL-Parameter <code>uri</code>.
</p>

<h2>Datenbasis</h2>
<p>
  <ul>
    <li>
      Der Vokabularserver DANTE (Datendrehscheibe für Normdaten und Terminologien) 
      dessen API unter <a href="https://api.dante.gbv.de/">api.dante.gbv.de</a>
      dokumentiert ist.
    </li>
    <li>
       Das Basel Register of Thesauri, Ontologies &amp; Classifications
       (<a href="http://bartoc.org/">BARTOC</a>) 
    </li>
    <li>...</li>
  </ul>
</p>

<?php include 'footer.php'; ?>

