function launch_amas_requete(path) {
	reset_affichage();
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
			affiche_resultat_amas(this.responseText);
		}
	};
	/* récuperer le nombre de critères */
	var nb_criteres = document.getElementById("tab_critere").rows.length - 1;
	/* et créer le paramètre de la requete php */
	var poid = "";
	for (var i = 0; i < nb_criteres;i++) {
		poid += document.getElementById(i+"ID").value+";";
	}
	/* récupérer le nombre de thèmes */
	var nb_theme = document.getElementById("tab_theme").rows.length;
	/* et créer le paramètre de la requete php */
	var theme = "";
	for (var i = 0; i < nb_theme;i++) {
		if (document.getElementById(i+"theme").checked) {
			theme += document.getElementById(i+"theme").value+";";
		}
	}
	/* créer la requete php : path + nomPlugin + nomFichier + parametre */
	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/launcher_AMAS_requete.php?q=" + poid + ";" + theme;
	/* envoyer la requete php */
	xhttp.open("GET", requete, true);
	xhttp.send();
}

function reset_affichage() {
	document.getElementById("nomSalle_1").innerHTML = "";
	document.getElementById("note_1").innerHTML = "";
	document.getElementById("lien_1").innerHTML = "";
	document.getElementById("idSalle_1").innerHTML = "";
	document.getElementById("nomSalle_2").innerHTML = "";
	document.getElementById("note_2").innerHTML = "";
	document.getElementById("lien_2").innerHTML = "";
	document.getElementById("idSalle_2").innerHTML = "";
	document.getElementById("nomSalle_3").innerHTML = "";
	document.getElementById("note_3").innerHTML = "";
	document.getElementById("lien_3").innerHTML = "";
	document.getElementById("idSalle_3").innerHTML = "";
	document.getElementById("div_results_2").innerHTML = "";
}

function affiche_resultat_amas(responseText) {
	/* récupérer la réponse et split sur ':SEP: */
	var res = responseText.split(':SEP:');
	/* mettre à jours l'affichage des résultats */
	if (res.length >= 5) {
		document.getElementById("div_results_2").style.visibility = 'visible';
		document.getElementById("div_results_2").style.display = 'block';
		document.getElementById("res").style.visibility = 'visible';
		document.getElementById("res").style.display = 'block';
		document.getElementById("div_results").style.visibility = 'visible';
		document.getElementById("div_results").style.display = 'block';
		document.getElementById("res_1").style.visibility = 'visible';
		document.getElementById("res_1").style.display = 'block';
		document.getElementById("nomSalle_1").innerHTML = res[1];
		document.getElementById("note_1").innerHTML = res[2] + "%";
		document.getElementById("lien_1").innerHTML = res[3];
		document.getElementById("idSalle_1").innerHTML = res[4];
	} else {
		document.getElementById("res_1").style.visibility = 'hidden';
		document.getElementById("res_1").style.display = 'none';
	}
	if (res.length >= 9) {
		document.getElementById("res_2").style.visibility = 'visible';
		document.getElementById("res_2").style.display = 'block';
		document.getElementById("nomSalle_2").innerHTML = res[5];
		document.getElementById("note_2").innerHTML = res[6] + "%";
		document.getElementById("lien_2").innerHTML = res[7];
		document.getElementById("idSalle_2").innerHTML = res[8];
	} else {
		document.getElementById("res_2").style.visibility = 'hidden';
		document.getElementById("res_2").style.display = 'none';
	}
	if (res.length >= 13) {
		document.getElementById("res_3").style.visibility = 'visible';
		document.getElementById("res_3").style.display = 'block';
		document.getElementById("nomSalle_3").innerHTML = res[9];
		document.getElementById("note_3").innerHTML = res[10] + "%";
		document.getElementById("lien_3").innerHTML = res[11];
		document.getElementById("idSalle_3").innerHTML = res[12];
	} else {
		document.getElementById("res_3").style.visibility = 'hidden';
		document.getElementById("res_3").style.display = 'none';
	}
	if (res.length < 5) {
		document.getElementById("div_results_2").style.visibility = 'visible';
		document.getElementById("div_results_2").style.display = 'block';
		document.getElementById("div_results_2").innerHTML = "Pas de suggestion";
	}
}

function hide_result() {
	document.getElementById("div_results").style.visibility = 'hidden';
	document.getElementById("div_results").style.display = 'none';
	document.getElementById("div_results_2").style.visibility = 'hidden';
	document.getElementById("div_results_2").style.display = 'none';
	document.getElementById("res").style.visibility = 'hidden';
	document.getElementById("res").style.display = 'none';
	document.getElementById("res_1").style.visibility = 'hidden';
	document.getElementById("res_1").style.display = 'none';
	document.getElementById("res_2").style.visibility = 'hidden';
	document.getElementById("res_2").style.display = 'none';
	document.getElementById("res_3").style.visibility = 'hidden';
	document.getElementById("res_3").style.display = 'none';
}

function launch_amas_feedback_choice(path) {
	hide_result();
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
			if (this.responseText == "") {
				newPage.alert("Vous avez utilisé le feedback trop de fois\nPas de modification des notes");
			}
		}
	};
	/* créer la requete php : path + nomPlugin + nomFichier + parametre */
	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/launcher_AMAS_feedback_choice.php?q=" + param;
	/* envoyer la requete php */
	xhttp.open("GET", requete, true);
	xhttp.send();
}

function launch_amas_feedback_saisieNotes(path) {
	/* récuperer le nombre de critères */
	$nb_criteres = document.getElementById("tab_saisieNote").rows.length - 1;
	/* et commencer créer le paramètre de la requete php */
	var poid = "";
	for ($i = 0; $i < $nb_criteres; $i++) {
		poid += document.getElementById($i+"IDsaisieNote").value+";";
	}
	var expertise = document.getElementById("id_expertise_2").value;
	var selectBox = document.getElementById("selectBox");
	var nomSalle = selectBox.options[selectBox.selectedIndex].value;
	/* construction du paramètre */
	var param = nomSalle + ";" + expertise + ";" + poid;
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
			if (this.responseText == "") {
				alert("Vous avez utilisé le feedback trop de fois\nPas de modification des notes");
			} else {
				alert("BDD modifiée");
			}
		}
	};
	/* créer la requete php : path + nomPlugin + nomFichier + parametre */
	var requete = path + "/Systeme-de-recommandation-de-Live-Escape-Game/launcher_AMAS_feedback_saisieNotes.php?q=" + param;
	/* envoyer la requete php */
	xhttp.open("GET", requete, true);
	xhttp.send();
	unset_choix_Salle();
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

function close_menu_deroulant() {
	var checkboxes = document.getElementById("checkboxes");
	checkboxes.style.display = "none";
	expanded = false;
}

function choix_Salle() {
	document.getElementById("id_saisieNote").style.visibility = 'visible';
	document.getElementById("id_saisieNote").style.display = 'block';
	var selectBox = document.getElementById("selectBox");
	var selectedValue = selectBox.options[selectBox.selectedIndex].value;
	document.getElementById("selected_salle").innerHTML = "Veuillez noter " + selectedValue;
}

function unset_choix_Salle() {
	document.getElementById("id_saisieNote").style.visibility = 'hidden';
	document.getElementById("id_saisieNote").style.display = 'none';
	var selectBox = document.getElementById("selectBox");
	selectBox.selectedIndex = 0;
	document.getElementById("selected_salle").innerHTML = "" ;
}




function show_range_value(id_range, id_show) {
	document.getElementById(id_show).innerHTML = document.getElementById(id_range).value;
}
