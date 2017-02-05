
/**
 * Fonction qui retourne le code html pour générer une notification
 * @param message : Message html a afficher dans la notification
 * @param type = alert | warning | info | success
 */
function getHTMLNotification(message,type){
	html = "<div class='alert alert-"+type+" alert-dismissible' role='alert'>";
	html+= "	<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
	html+= "		<span aria-hidden='true'>&times;</span>";
	html+= "	</button>";
	html+= 		message;
	html+= "</div>";

	return html;
}

/**
 * Ajoute le code html d'une alerte de type Erreur au container passé en paramètre
 */
function addError(container,message){
	return $(container).prepend(getHTMLNotification("<strong>Erreur : </strong>"+message,'danger'));
}


/**
 * Permet de récupérer une liste de valeurs
 * @param html_selector : selecteur HTML de la classe qui regroupe les input
 * @return Array de string
 */
function getVarMultiple(html_selector,format='array'){
	var vars = new Array();

	$(html_selector).each(function( index ) {
		vars.push($(this).val());
	});


	switch(format){
		case 'json':
			return JSON.stringify(vars);

	}
	//Par défaut on renvoie un tableaux de string
	return vars;
}

/********************************************************************************************************/


var EMAILS_CLASS = 'form-value-emails';
var NUM_TELS_CLASS = 'form-value-telephones';

var EMAILS_SELECTOR = '.'+ EMAILS_CLASS;
var NUM_TELS_SELECTOR = '.' + NUM_TELS_CLASS;

$(document).ready(function(){

	/***************************************************************************************************
	 *
	 *                                    GESTION DES INFORMATIONS DE BASE
	 *
	 ***************************************************************************************************/


	emails = getVarMultiple(EMAILS_SELECTOR);

	tels = getVarMultiple(NUM_TELS_SELECTOR);

	$('#add-email').click(function(){
		//On récupère les valeuyrs déjà renseignées 
		emails = getVarMultiple(EMAILS_SELECTOR);
		html= '<div class="row margin_bottom">';
		html+='		<div class="col-md-6">';
		html+='			<div class="input-group">';
		html+='				<span class="input-group-addon">Email '+ (emails.length + 1) + ':</span>';
		html+='				<input class="form-control '+EMAILS_CLASS+'" type="text" value="">';
		html+='			</div>';
		html+='		</div>';
		html+='		<div class="col-md-3"><button class="btn btn-default suppr"><span class="glyphicon glyphicon-trash"></span></button></div>';
		html+='</div>';


		//On regarde si le dernier email est renseigné
		if(emails[emails.length -1]!=""){
			$('#emails-list').append(html);
		}
	});

	$('#add-num-tel').click(function(){
		//On récupère les valeuyrs déjà renseignées 
		tels = getVarMultiple(NUM_TELS_SELECTOR);
		html= '<div class="row margin_bottom">';
		html+='		<div class="col-md-6">';
		html+='			<div class="input-group">';
		html+='				<span class="input-group-addon">Téléphone '+ (tels.length + 1) + ':</span>';
		html+='				<input class="form-control '+NUM_TELS_CLASS+'" type="text" value="">';
		html+='			</div>';
		html+='		</div>';
		html+='		<div class="col-md-3"><button class="btn btn-default suppr"><span class="glyphicon glyphicon-trash"></span></button></div>';
		html+='</div>';


		//On regarde si le dernier email est renseigné
		if(tels[tels.length -1]!=""){
			$('#tels-list').append(html);
		}
	});

	//Enregistrement des informations de base du profil
	$('#save-form-profil').click(function(){
		//On récupère toutes les informations
		emails = getVarMultiple(EMAILS_SELECTOR);
		tels = getVarMultiple(NUM_TELS_SELECTOR);
		site_web = $('#form-value-site-web').val();
		bureau = $('#form-value-bureau').val();

		//TODO : Ajout l'enregistrement des informations
		console.log("---------------------------------------------------------------");
		console.log("	Mise à jour des informations pour le profil :");
		console.log("	Emails : " + emails);
		console.log("	Numéros de téléphone : " + tels);
		console.log("	Bureau : " + bureau);
		console.log("	Site Web : " + site_web);
		console.log("---------------------------------------------------------------");
	});

	//Suppression de champs
	$(document).on("click",".suppr",function(){
		$(this).parent().parent().remove();
	});	

	/***************************************************************************************************
	 *
	 *                                         GESTION DE LA DESCRIPTION
	 *
	 ***************************************************************************************************/

	//Affichage du champ de la description
	$('#update-description').click(function(){
		$(this).addClass('hide');
		$('#form-description').removeClass('hide');
		$('#label-description').addClass('hide');
	});

	function cacherFormulaireDescription() {
		$('#update-description').removeClass('hide');
		$('#form-description').addClass('hide');
		$('#label-description').removeClass('hide');
	}

	//Sauvegarde du champ de la description
	$('#save-description').click(function(){
		description = $('#form-value-description').val();

		//TODO : enregistrement de la description
		console.log("---------------------------------------------------------------");
		console.log("	Mise à jour de la description du profil :");
		console.log("	Description : " + description);
		console.log("---------------------------------------------------------------");

		cacherFormulaireDescription();
	});

	//Annulation de la modification de description
	$('#cancel-description').click(function(){
		cacherFormulaireDescription();
	});




	/***************************************************************************************************
	 *
	 *                                         GESTION DES MOTS DE PASSE
	 *
	 ***************************************************************************************************/
	 //Enregistrement du nouveau mot de passe
	 $('#save-mdp-profil').click(function(){
	 	oldpass = $('#form-mdp-old').val();
	 	newpass = $('#form-mdp-new').val();
	 	confpass = $('#form-mdp-conf').val();

	 	if(newpass.length<5){
	 		addError('.alerts-mdp','Un mot de passe doit faire au moins 5 caractères.');
	 	}
	 	else if(newpass!=confpass){
	 		addError('.alerts-mdp','Le nouveau mot de passe et la confirmation ne correpondent pas.');
	 	}

	 	//TODO : Regarder la correspondance des mots de passe

		//TODO : enregistrement du mot de passe
	 });
});