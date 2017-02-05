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

/**
 * Récupère la val associé au champ html_selector
 */
function getVar(){
	return $(html_selector).val();
}

/********************************************************************************************************/
var EMAILS_CLASS = 'form-value-emails';
var NUM_TELS_CLASS = 'form-value-telephones';

var EMAILS_SELECTOR = '.'+ EMAILS_CLASS;
var NUM_TELS_SELECTOR = '.' + NUM_TELS_CLASS;

$(document).ready(function(){
	console.log("Chargement de mon_profil.js");

	emails = getVarMultiple(EMAILS_SELECTOR);
	console.log(emails);

	tels = getVarMultiple(NUM_TELS_SELECTOR);
	console.log(tels);


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
		html+='		<div class="col-md-3"><button class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button></div>';
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
		html+='		<div class="col-md-3"><button class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button></div>';
		html+='</div>';


		//On regarde si le dernier email est renseigné
		if(tels[tels.length -1]!=""){
			$('#tels-list').append(html);
		}
	});
});