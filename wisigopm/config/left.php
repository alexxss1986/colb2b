<?php
$livello=$_SESSION["livello"];
$username=$_SESSION['username'];
echo "
<div class=\"sidebar-left sidebar-nicescroller\">
				<ul class=\"sidebar-menu\">";

if ($livello==4){
    echo "<li>
						<a href=\"utenti.php\">
							<i class=\"fa fa-folder-open icon-sidebar\"></i>
							<i class=\"fa fa-angle-right chevron-icon-sidebar\"></i>
							Utenti
						</a>
					</li>";
}
else {
    if ($livello != 1) {
        echo "<li><a href=\"dashboard.php\"><i class=\"fa fa-dashboard icon-sidebar\"></i>Dashboard</a></li>";


        echo "<li>
						<a href=\"#fakelink\">
							<i class=\"fa fa-folder-open icon-sidebar\"></i>
							<i class=\"fa fa-angle-right chevron-icon-sidebar\"></i>
							Catalogo
						</a>
						<ul class=\"submenu\">";
        if ($livello == 3) {
            echo "<li><a href=\"inserisci-prodotto.php\">Inserisci prodotti</a></li>";
            echo "<li><a href=\"aggiungi-taglie.php\">Aggiungi taglie</a></li>";
            echo "<li><a href=\"modifica-prodotto.php\">Modifica prodotto</a></li>";
            echo "<li><a href=\"elimina-prodotto.php\">Elimina prodotto</a></li>";
        }

        echo "<li><a href=\"prodotti.php\">Prodotti</a></li>";
        if ($livello == 0) {
            echo "<li><a href=\"prodotti-usa.php\">Prodotti USA</a></li>";

        }
							echo "<li><a href=\"prodottiSenzaImg.php\">Prodotti senza immagine</a></li>
						</ul>
					</li>";
        if ($livello == 3) {
            echo "<li>
						<a href=\"#fakelink\">
							<i class=\"fa fa-folder-open icon-sidebar\"></i>
							<i class=\"fa fa-angle-right chevron-icon-sidebar\"></i>
							Magazzino
						</a>
						<ul class=\"submenu\">
							<li><a href=\"scala_disponibilita.php\">Scala disponibilità</a></li>
							<li><a href=\"verifica_giacenza.php\">Verifica giacenza</a></li>
							<li><a href=\"riassortimento.php\">Riassortimento</a></li>
						</ul>
					</li>";
        }
        echo "<li>
						<a href=\"utenti.php\">
							<i class=\"fa fa-folder-open icon-sidebar\"></i>
							<i class=\"fa fa-angle-right chevron-icon-sidebar\"></i>
							Utenti
						</a>
					</li>";
    }
    echo "<li>
						<a href=\"#fakelink\">
							<i class=\"fa fa-folder-open icon-sidebar\"></i>
							<i class=\"fa fa-angle-right chevron-icon-sidebar\"></i>
							Vendite
						</a>
						<ul class=\"submenu\">
							<li><a href=\"ordini.php\">Ordini</a></li>";
    if ($livello == 0) {
        echo "<li><a href=\"ordini-magazzino.php\">Spostamento magazzini</a></li>
							    ";
        if ($username == "coltorti") {
            echo "<li><a href=\"ordini-magazzino2.php\">Creazione Magazzini</a></li>
							    ";
        }
        echo "<li><a href=\"fatture-corrispettivi.php\">Fatture corrispettivi</a></li>
							    ";
    }
    echo "</ul>
					</li>";
    if ($livello != 1) {
        echo "<li>
						<a href=\"#fakelink\">
							<i class=\"fa fa-folder-open icon-sidebar\"></i>
							<i class=\"fa fa-angle-right chevron-icon-sidebar\"></i>
							Statistiche
						</a>
						<ul class=\"submenu\">
							<li><a href=\"accessi.php\">Accessi</a></li>
							<li><a href=\"datiLocalita.php\">Località</a></li>
						</ul>
					</li>

					";
        echo "<li>
						<a href=\"#fakelink\">
							<i class=\"fa fa-folder-open icon-sidebar\"></i>
							<i class=\"fa fa-angle-right chevron-icon-sidebar\"></i>
							Reportistica
						</a>
						<ul class=\"submenu\">
							<li><a href=\"report.php\">Ordini</a></li>
						</ul>
					</li>

					";
    }

    if ($livello == 0 && $username == "coltorti") {
        echo "<li>
						<a href=\"#fakelink\">
							<i class=\"fa fa-folder-open icon-sidebar\"></i>
							<i class=\"fa fa-angle-right chevron-icon-sidebar\"></i>
							Geopricing
						</a>
						<ul class=\"submenu\">
							<li><a href=\"inserisci-geopricing.php\">Inserisci geopricing</a></li>
							<li><a href=\"visualizza-geopricing.php\">Visualizza geopricing</a></li>
						</ul>
					</li>

					";
    }


}
	echo "</ul></div>";