Kurz-Anleitung

1.) Erstelle einen SysOrdner
2.) In diesem SysOrdner Einsatzarten und Fahrzeuge anlegen
3.) Einsätze anlegen :-)
4.) Erstelle eine (oder mehrere) normale Seite(n) mit dem Plugin "Einsatz Liste"
	- Wähle aus "Ausgangspunkt" den angelegten SysOrdner
	- Bisher ohne Verwendung: Detailansicht
5.) Template anpassen (am besten kopieren von firefighter/pi1/template.html in fileadmin/ und Pfad anpassen im Plugin)

Fertig :-)

Bei Fragen einfach schreiben an sfeni@sfeni.de oder auf www.feuerwehr-ilsfeld.de umschauen :-)



Hinweise zu Google GEO-Koordinaten und Maps:
- Im Feld "Google GEO-Koordinaten" müssen derzeit manuell die Koordinaten eingetragen werden, wenn man diese verwenden will im Frontend, Beispiel:
  49.055154, 9.244612
  (durch Komma getrennt)
  Eine Umstellung ist bereits geplant und getestet, es wird rggooglemap zum Einsatz kommen :-)

- Wenn Ihr die Template angegebenen Google-Maps Funktionen benutzen wollt einfach auf der gleichen Seite ein HTML-Element einfügen und ein bisschen was in den <head>
  Anmeldung für eure Domain: http://code.google.com/intl/de-DE/apis/maps/signup.html

Im <head>-Bereich:
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=EUER_GOOGLE_API_KEY" type="text/javascript"></script>

Das HTML-Element:
<div id="googlemap" style="width:100%; height:300px; margin-bottom:10px;"></div>
<script type="text/javascript">
function createMarker(point, markertext, Icon) {
	markerOptions = "";
	if(Icon) {
		var einsatzIcon = new GIcon(G_DEFAULT_ICON);
		einsatzIcon.image = "http://www.EUREDOMAIN.de/" + Icon;
		einsatzIcon.iconSize = new GSize(24, 24);
		einsatzIcon.shadowSize = new GSize(0, 0);
		einsatzIcon.iconAnchor = new GPoint(8, 8);
		markerOptions = { icon:einsatzIcon };
		var marker = new GMarker(point, markerOptions);
		map.addOverlay(marker);
	} else {
		var marker = new GMarker(point);
	}
	
	if(markertext != "") {
	GEvent.addListener(marker, "click", function() {
		marker.openInfoWindowHtml(markertext);
	});
	}
	return marker;
}

var map = new GMap2(document.getElementById("googlemap"));
map.addControl(new GSmallMapControl());
map.addControl(new GMapTypeControl());
map.setCenter(new GLatLng(49.055154, 9.244612), 13);
</script>
