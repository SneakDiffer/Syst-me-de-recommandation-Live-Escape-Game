function launch_amas_requete(path) {
	/* récupérer le bloc de résultats */
	var page = document.getElementById("div_results");
	/* le rendre visible */
	page.style.display='block';
	page.style.visibility='visible';
	/* créer une requete */
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
		if (this.readyState == 4 && this.status == 200) {
			/* récupérer la réponse et split sur ':SEP: */
			var res = this.responseText.split(':SEP:');
			/* mettre à jours l'affichage des résultats */
			document.getElementById("nomSalle_1").innerHTML = res[1];
			document.getElementById("note_1").innerHTML = res[2] + "%";
			document.getElementById("lien_1").innerHTML = res[3];
			document.getElementById("idSalle_1").innerHTML = res[4];
			document.getElementById("nomSalle_2").innerHTML = res[5];
			document.getElementById("note_2").innerHTML = res[6] + "%";
			document.getElementById("lien_2").innerHTML = res[7];
			document.getElementById("idSalle_2").innerHTML = res[8];
			document.getElementById("nomSalle_3").innerHTML = res[9];
			document.getElementById("note_3").innerHTML = res[10] + "%";
			document.getElementById("lien_3").innerHTML = res[11];
			document.getElementById("idSalle_3").innerHTML = res[12];
		}
	};
	/* récuperer le nombre de critères */
	$nb_criteres = document.getElementById("tab_critere").rows.length - 1;
	/* et créer le paramètre de la requete php */
	var poid = "";
	for ($i = 0; $i < $nb_criteres; $i++) {
		poid += document.getElementById($i+"ID").value+";";
	}
	/* récupérer le path vers les plugins */
	//var pluginUrl = '<?php echo plugins_url();?>';
	/* créer la requete php : path + nomPlugin + nomFichier + parametre */
	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/launcher_AMAS_requete.php?q=" + poid;
	/* envoyer la requete php */
	xhttp.open("GET", requete, true);
	xhttp.send();
}

function launch_amas_feedback_choice(path) {
	/* récupérer les radio bouton */
	var radio_buton_choice = document.getElementsByName("radio_buton_choice");
	/* trouver le choix de l'utilisateur */
	var choix = 1;
	var idSalle = "idSalle_1";
	var proposition = "";
	var tmp;
	for(var i = 0; i < radio_buton_choice.length; i++) {
		tmp = i + 1;
		proposition += tmp + ";" + document.getElementById("idSalle_" + tmp).innerHTML + ";";
		if(radio_buton_choice[i].checked == true) {
			choix = i + 1;
			/* récupérer l'id de la salle */
			idSalle = document.getElementById("idSalle_" + choix).innerHTML;
			/* ouvrir le lien de la salles */
			var newPage = window.open(document.getElementById('lien_'+choix).innerHTML, '_blank', 'toolbar=0,location=0,menubar=0');
		}
	}
	/* récuperer le nombre de critères */
	$nb_criteres = document.getElementById("tab_critere").rows.length - 1;
	/* et commencer créer le paramètre de la requete php */
	var poid = "";
	for ($i = 0; $i < $nb_criteres; $i++) {
		poid += document.getElementById($i+"ID").value+";";
	}
	var expertise = document.getElementById("id_expertise").value;
	/* construction du paramètre */
	var param = choix + ";" + idSalle + ";" + expertise + ";" + proposition + poid;
	/* créer une requete */
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
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("retour_feedback").innerHTML = this.responseText;
			///* récupérer le bloc de résultats */
			//var page = document.getElementById("div_results");
			/* le rendre visible */
			//page.style.display='none';
			//page.style.visibility='hidden';
			/*
			pb. si on met ca ici. l'utilisateur peut encore cliquer sur les autre choix 
			tant que l'alert n'est pas levé. */
			if (choix == 1) {
				newPage.alert("L'intelligence artificielle ne modifie pas les notes");
			} else {
				newPage.alert("L'intelligence artificielle modifie les notes");
			}
		}
	};
	/* créer la requete php : path + nomPlugin + nomFichier + parametre */
	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/launcher_AMAS_feedback_choice.php?q=" + param;
	/* envoyer la requete php */
	xhttp.open("GET", requete, true);
	xhttp.send();
}

var expanded = false;

function showCheckboxes() {
  var checkboxes = document.getElementById("checkboxes");
  if (!expanded) {
    checkboxes.style.display = "block";
    expanded = true;
  } else {
    checkboxes.style.display = "none";
    expanded = false;
  }
}