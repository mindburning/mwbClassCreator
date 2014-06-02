mwbClassCreator
===============

Erstellt aus einer mwb Datei anpassbare Datenmodellklassen

Das zu exportierende PHP Klassen Template befindet sich in /mwb/templates/phpclass.php hat Zugriff auf das aktuelle Datenmodell.
Das Template wird je Tabelle geparst. Das Ergebnis jedes parser durchlaufs wird als eigenes Model vom Reader in Zielordner angelget.
Neben den Modellklassen wird zusätzlich noch eine inc.php im Exportverzeichnis angelegt, welche alle im Modell enthaltenen Klassen includiert.
