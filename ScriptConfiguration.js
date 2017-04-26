
function Gestion_Salle(path,ID_theme,theme){

	//Récupération du nombre de lignes
	var arrayLignes = document.getElementById("ID_table_configuration").rows;
	var LenghtRows = arrayLignes.length;
	//Récupération de la colonne d'ajout d'une salle
	var LenghtColonnes = arrayLignes[LenghtRows-1].cells.length;
	//Récupération des input texte
	var input="";
	for(i = 0; i < LenghtColonnes; i++){
		if(i != ID_theme){
			input += document.getElementById("Ajout_Salle"+i).value+";";
		}
	}

	ListeTheme = "";
	for (i=0; i< theme.length; i++){ 
	    var Theme_Checked = document.getElementById("Ajout_Salle_Theme"+i).checked;
		if(Theme_Checked){
			ListeTheme = ListeTheme + theme[i] + ";";
		}
	}
	
	//On enlève le dernier ;
	if(ListeTheme.length != 0){
		ListeTheme = ListeTheme.substring(0, ListeTheme.length-1);
	}
	input += "/" + ListeTheme;
	/* créer la requete php : path + nomPlugin + nomFichier + parametre */
	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/Launcher_Salle.php?q=" + input;

	var xhttp;
	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
		} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	//fonction synchrone
	xhttp.onreadystatechange = function() {
		//si la réponse est correcte 
		if (this.readyState == 4 && this.status == 200) {}
	};
	
	xhttp.open("GET", requete, true);
	xhttp.send();
}


//Gestion des listes des thèmes déroulantes	
var enable = [];
function InitEnable(i){
	for(j=0;j<i;j++){
		enable.push(false);
	}
}

function showCheckboxes(i) {
  var checkboxes = document.getElementById("checkboxes"+i);
  if (!enable[i]) {
    checkboxes.style.display = "block";
    enable[i] = true;
  } else {
    checkboxes.style.display = "none";
    enable[i] = false;
  }
}

function Gestion_Note(path){
	var xhttp;
	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
		} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	/* fonction synchrone */
	xhttp.onreadystatechange = function() {
		/* si la réponse est correcte */
		if (this.readyState == 4 && this.status == 200) {}
	};

	var arrayLignes = document.getElementById("ID_table_configuration").rows;
	//-3 car colonne nomsalle, Theme, Lien
	var NbCritere = arrayLignes[0].cells.length - 3;
	var nbsalle = arrayLignes.length - 2;
	var NoteCritere = "";
	for(j=0;j<nbsalle;j++){
		NoteCritere = "";
		for(i=0;i<NbCritere;i++){
			var Note = document.getElementById(""+i+j).value;
			NoteCritere = NoteCritere + Note + ";";
		}
		NoteCritere = NoteCritere.substring(0, NoteCritere.length-1);
	    NoteCritere = NoteCritere + "/" + j;
	    var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/Launcher_Note.php?q=" + NoteCritere;
	    xhttp.open("GET", requete, true);
		xhttp.send();
	}
}

function Gestion_Theme(path,theme){
	/* créer la requete php : path + nomPlugin + nomFichier + parametre */
	
	var xhttp;
	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
		} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	/* fonction synchrone */
	xhttp.onreadystatechange = function() {
		/* si la réponse est correcte */
		if (this.readyState == 4 && this.status == 200) {}
	};

	var arrayLignes = document.getElementById("ID_table_configuration").rows;
	//-2 car première ligne nom des colonnes, dernière ligne ajout d'une nouvelle salles
	var nbsalle = arrayLignes.length - 2;
	var ListeTheme = "";
	for(j=0;j<nbsalle;j++)
	{
		ListeTheme = "";
		for (i=0; i< theme.length; i++){ 
	        var Theme_Checked = document.getElementById(theme[i]+j).checked;
			if(Theme_Checked){
				ListeTheme = ListeTheme + theme[i] + ";";
			}
	    }
	    if(ListeTheme.length == 0)
	    {
	    	ListeTheme = j;
	    }else{
	    	ListeTheme = ListeTheme.substring(0, ListeTheme.length-1);
	    	ListeTheme = ListeTheme + "/" + j;
	    }
	    var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/Launcher_Theme.php?q=" + ListeTheme;
	    xhttp.open("GET", requete, true);
		xhttp.send();
	}
	alert("Les thèmes ont été modifiés");
}


function Gestion_Lien(path){
	var xhttp;
	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
		} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	/* fonction synchrone */
	xhttp.onreadystatechange = function() {
		/* si la réponse est correcte */
		if (this.readyState == 4 && this.status == 200) {}
	};


	var arrayLignes = document.getElementById("ID_table_configuration").rows;
	var nbsalle = arrayLignes.length - 2;
	for(i=0;i<nbsalle;i++){
		var Lien = document.getElementById("Lien"+i).value;
		Lien = Lien + ";" + i;
		var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/Launcher_Lien.php?q=" + Lien;
	    xhttp.open("GET", requete, true);
		xhttp.send();
	}
}


function Ajout_theme(path){
	var theme = document.getElementById("Nouveau_theme").value;

	var xhttp;
	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
		} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	/* fonction synchrone */
	xhttp.onreadystatechange = function() {
		/* si la réponse est correcte */
		if (this.readyState == 4 && this.status == 200) {}
	};

	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/Launcher_New_Theme.php?q=" + theme;
	xhttp.open("GET", requete, true);
	xhttp.send();
}

function Suppression_theme(path){
	var theme = document.getElementById("ListeGestionTheme").value;

	var xhttp;
	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
		} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	/* fonction synchrone */
	xhttp.onreadystatechange = function() {
		/* si la réponse est correcte */
		if (this.readyState == 4 && this.status == 200) {}
	};

	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/Launcher_Delete_Theme.php?q=" + theme;
	xhttp.open("GET", requete, true);
	xhttp.send();
}


function Suppression_Salle(path,nom_salle){

	var xhttp;
	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
		} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	/* fonction synchrone */
	xhttp.onreadystatechange = function() {
		/* si la réponse est correcte */
		if (this.readyState == 4 && this.status == 200) {}
	};

	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/Launcher_Delete_Salle.php?q=" + nom_salle;
	xhttp.open("GET", requete, true);
	xhttp.send();
}

function Suppression_critere(path){
	var critere = document.getElementById("ListeGestionCritere").value;

	var xhttp;
	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
		} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	/* fonction synchrone */
	xhttp.onreadystatechange = function() {
		/* si la réponse est correcte */
		if (this.readyState == 4 && this.status == 200) {}
	};

	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/Launcher_Delete_Critere.php?q=" + critere;
	xhttp.open("GET", requete, true);
	xhttp.send();
}

function Ajout_critere(path){
	var critere = document.getElementById("Nouveau_Critere").value;
	var xhttp;
	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
		} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	/* fonction synchrone */
	xhttp.onreadystatechange = function() {
		/* si la réponse est correcte */
		if (this.readyState == 4 && this.status == 200) {}
	};

	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/Launcher_New_Critere.php?q=" + critere;
	xhttp.open("GET", requete, true);
	xhttp.send();
}
