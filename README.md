mwbClassCreator
===============

Erstellt aus einer mwb Datei anpassbare Datenmodellklassen

Das zu exportierende PHP Klassen Template befindet sich in /mwb/templates/phpclass.php und hat Zugriff auf das aktuelle Datenmodell.
Das Template wird je Tabelle geparst. Das Ergebnis jedes parser durchlaufs wird als eigenes Model vom Reader in Zielordner angelget.
Neben den Modellklassen wird zusätzlich noch eine inc.php im Exportverzeichnis angelegt, welche alle im Modell enthaltenen Klassen includiert.

``` php
mwbReader::getInstance("./model/")
		->renderFile('./model.mwb');
```

renderFile(...) erzeugt die php Modell Klassen und legt diese im vorhandenen Verzeichnis "model" im aktuellen Verzeichnis ab.

Die erzeugten Klassen besitzen alle Spalten incl. der getter & setter Methoden sowie Konstanten für den Zugriff auf den
Tabellennamen und die zugehörigen Spaltennamen. Zusätzlich werden zu den allgemeinen Zugriffsmethoden noch
Managementmethoden angelegt. Diese können verknüpfte Objekte anhand von Fremdschlüsselbeziehungen & Eigenschaften laden.
Diese Methoden nutzen jedoch einene in diesem package nicht enthaltene utils Klasse um Objekte zu laden.