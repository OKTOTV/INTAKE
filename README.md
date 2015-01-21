INTAKE
======

Online Abgabemaske

Erlaubt einfachsten Datei Upload beliebiger größe via Browser. Unsterstützt werden auch mobile Endgeräte sowie mehrere Sprachen.

Zur installation werden benötigt:

- Ein Webserver mit einer mindest PHP Version von 5.5.x
- Eine Datenbank deiner Wahl (mysql, sqlite, etc..)
- Genügend Speicherplatz sowie Schreibrechte im %PROJEKTORDNER%/web/uploads/files verzeichnis
- (optional, empfohlen) Ein Mailaccount für das System

Nach entpacken in deinem Webverzeichnis, solltest du die Commandline Befehle

intake:warmup sowie intake:create_admin ausführen.

intake:warmup solltest du bei jedem Update von INTAKE durchführen
intake:create_admin erstellt einen Administrator. Du kannst andere Benutzer anschließend im Administrationsbereich erstellen.
