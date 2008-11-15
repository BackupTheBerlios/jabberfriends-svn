<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>
<rss version="2.0">
  <channel>
    <title>JabberFriends - <?php echo $this->title; ?></title>
    <link>URL der Webpräsenz</link>
    <description>Kurze Beschreibung des Feeds</description>
    <language>Sprache des Feeds (z. B. "de-de")</language>
    <copyright>Autor des Feeds</copyright>
    <pubDate>Erstellungsdatum("Tue, 8 Jul 2008 2:43:19")</pubDate>
    
    <?php
    foreach($this->news as $record) {
        echo '<item>';
        echo '<title>'.$record->title.'</title>';
        echo '<link>http://localhost'.$this->action('news/view/'.$record->id).'</link>';
        echo '<guid>'.$record->id.'</guid>';
        echo '</item>';
    }
    ?>
    <item>
      <title>Titel des Eintrags</title>
      <description>Kurze Zusammenfassung des Eintrags</description>
      <link>Link zum vollständigen Eintrag</link>
      <author>Autor des Artikels, E-Mail-Adresse</author>
      <guid>Eindeutige Identifikation des Eintrages</guid>
      <pubDate>Datum des Items</pubDate>
    </item>
 
  </channel>
</rss>