# SymconHYP

SymconHYP ist eine Erweiterung für die Heimautomatisierung IP Symcon. Diese Erweiterung sellt eine Integration des Hyperion TV Hintergrundbeleuchtung bereit.

## Einrichtung

Die Einrichtung erfolgt über die Modulverwaltung von Symcon. Nach der Installation des Moduls sollte der Dienst neugestartet werden. Jetzt kann eine neue Instanz vom Typ "_Hyperion Server" angelegt und konfiguriert werden. In der Konfigurationsmaske könnt ihr die Adresse und den Port hinterlegen. Zusätzlich könnt Ihr eine Priorität festlegen. Da bei Hyperion kein Status abgefragt werden kann, sind die Funktionen auf das Ändern begrenzt.

## Einstellungen

* **Host**  _Der Hostname bzw. die IP-Adresse des Hyperion Server_
* **Port**  _Der Port des Hyperion Server_
* **Priorität**  _Mit welcher Priorität die Änderungen an den Hyperion Server gesendet werden_

**Schalter**

* **Löschen** _Es werden alle Einstellungen für die hinterlegte Priorität zurückgesetzt._
* **Globales Löschen** _Es werden alle Einstellungen zurückgesetzt._
* **Farbe XXX** _Stellt zum Testen die Beleuchtung auf XXX_

## Voraussetzung

* Hyperion Server (https://github.com/tvdzwan/hyperion)
* ab Symcon Version 4

## Funktionen

  // Einstellungen zurücksetzen (Prioritätsabhängig)
  HYP_Clear($hyperionId, false);

  // Einstellungen zurücksetzen (Global)
  HYP_Clear($hyperionId, true);

  // Stellt eine Farbe ein (bei 3x 0 sind die LEDs aus)
  HUE_SetColor($bridgeId, $red, $green, $blue);
