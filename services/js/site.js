function open_form()
{
	nbr = form.formulaire_name.length;
	//alert(nbr);
	var i = 0;
	var myArray = ['cacher', 'rate', 'souscription', 'bonus', 'souscriber_p'];
	for(i = 0; i <= nbr; i++)
	{
		document.getElementById(myArray[i]).style.display="none";
		if(form.formulaire_name.options[i].selected)
		{
			option_value = form.formulaire_name.options[i].value;
			document.getElementById(option_value).style.display="block";
		}
	}
}
//function multiple form pricebook offer
function open_form_pricebook()
{
    nbr = form.formulaire_name.length;
	//alert(nbr);
	var i = 0;
	var myArray = ['cacher', 'campagne', 'transfer_fee', 'special_number', 'self_service', 'month_fee_product'];
	for(i = 0; i <= nbr; i++)
	{
		document.getElementById(myArray[i]).style.display="none";
		if(form.formulaire_name.options[i].selected)
		{
			option_value = form.formulaire_name.options[i].value;
			document.getElementById(option_value).style.display="block";
		}
	}
}
//fonction de data
function type_offer(form)
{
	type = document.getElementById('choice_type');
	not_data = document.getElementById('not_data');
	unit_not_data = document.getElementById('unit');
	data = document.getElementById('data');

	//selection des id pour leur donner un attribut requi lorsque data n'est pas selectionné
	event_type = document.getElementById("event_type");
	
	//si c'est sms ou mms
	sms_ou_mms = document.getElementById("sms_ou_mms");
	if(form.choice_type.value == "DATA") 
	{
		valeur = document.getElementById('valeur');
		if(valeur.hasAttribute('required'))
			valeur.removeAttribute('required');
		not_data.setAttribute("style","display:none;");
		unit_not_data.setAttribute("style","display:none;");
		data.setAttribute("style","display:block");
		sms_ou_mms.setAttribute("style","display:none");

		event_type.removeAttribute('required');
		
		//en cas de modification cette partie permettra de delete de desabled inséré
		if(data.hasAttribute('disabled'))
			data.removeAttribute('disabled');
		
		//a delete en cas de sousci
		data.setAttribute('required','required');
		if(unit_not_data.hasAttribute('required'))
			unit_not_data.removeAttribute('required');	
		if(sms_ou_mms.hasAttribute('required'))
			sms_ou_mms.removeAttribute('required');
		not_data.setAttribute("disabled","disabled");
		unit_not_data.setAttribute("disabled","disabled");
		sms_ou_mms.setAttribute("disabled","disabled");
	}
	else if(form.choice_type.value == "SMS" || form.choice_type.value == "MMS")
	{
		not_data.setAttribute("style","display:block;");
		unit_not_data.setAttribute("style","display:none;");
		data.setAttribute("style","display:none");
		sms_ou_mms.setAttribute("style","display:block");
		//selectionner automatiquement sms ou mms
		if(form.choice_type.value == "SMS")
		{
			if(!form.sms_ou_mms.options[1].hasAttribute('selected'))
				form.sms_ou_mms.options[1].setAttribute('selected','selected');
			if(form.sms_ou_mms.options[2].hasAttribute('selected'))
				form.sms_ou_mms.options[2].removeAttribute('selected');
		}
		if(form.choice_type.value == "MMS")
		{
			if(!form.sms_ou_mms.options[2].hasAttribute('selected'))
				form.sms_ou_mms.options[2].setAttribute('selected','selected');
			if(form.sms_ou_mms.options[1].hasAttribute('selected'))
				form.sms_ou_mms.options[1].removeAttribute('selected');
		}
		event_type.setAttribute('required','required');
		
		//en cas de modification cette partie permettra de delete de desabled inséré
		if(sms_ou_mms.hasAttribute('disabled'))
			sms_ou_mms.removeAttribute('disabled');	
		
		//a delete en cas de sousci
		if(unit_not_data.hasAttribute('required'))
			unit_not_data.removeAttribute('required');	
		if(data.hasAttribute('required'))
			data.removeAttribute('required');	
		sms_ou_mms.setAttribute('required','required');
		unit_not_data.setAttribute("disabled","disabled");
		data.setAttribute("disabled","disabled");
		
	}
	else
	{
		not_data.setAttribute("style","display:block;");
		unit_not_data.setAttribute("style","display:block;");
		data.setAttribute("style","display:none");
		sms_ou_mms.setAttribute("style","display:none");

		event_type.setAttribute('required','required');
		
		//en cas de modification cette partie permettra de delete de desabled inséré
		if(unit_not_data.hasAttribute('disabled'))
			unit_not_data.removeAttribute('disabled');	
		
		//a delete en cas de sousci
		unit_not_data.setAttribute('required','required');
		if(sms_ou_mms.hasAttribute('required'))
			sms_ou_mms.removeAttribute('required');
		if(data.hasAttribute('required'))
			data.removeAttribute('required');	
		data.setAttribute("disabled","disabled");
		sms_ou_mms.setAttribute("disabled","disabled");
	}
}

//MAJ cout hts formulaire de souscription
function maj_cout_ttc_souscription()
{
	souscription_cost = document.getElementById('souscription_cost').value;
	sous_cost_ttc = document.getElementById('sous_cost_ttc');
	cum = souscription_cost+"+"+souscription_cost * (3/100);
	sous_cost_ttc.value = Math.round(eval(cum));
}

//open cost hts formulaire de souscription
function with_or_without_TTC(form)
{
	souscription_cost = document.getElementById('souscription_cost').value;
	sous_cost_ttc = document.getElementById('sous_cost_ttc');
	if( form.cost_hts.value == "NO" )
	{
		sous_cost_ttc.value = souscription_cost;
	}
	else
	{
		maj_cout_ttc_souscription();
	}
}

//calcule du champs cout hts du formulaire de rate
function maj_cout_ttc()
{
	cout_ht = document.getElementById("cout_hts");
	cout_hts = cout_ht.value;
	cout_ttc = document.getElementById("cout_ttc");
	cum = cout_hts +"+"+ cout_hts * (3/100);
	cout_ttc.value = Math.round(eval(cum));
	/*RegExp = /[0-9].?[0-9]{3}$/;
	if(RegExp.test(cout_hts))
	{
		alert("plus de chiffre apres la virgule");
		alert(cout_hts.indexOf(".",2));
	}
	else{}*/
}
//formulaire souscription
function cicle_types(form)
{
	number_renewal = document.getElementById("number_renewal");
	number_renewal.setAttribute("readOnly","readOnly");
	number_cycle = document.getElementById("number_cycle");
	if(form.type_cicle.value == "permant")
	{
		number_cycle.setAttribute("hidden","hidden");
		number_renewal.setAttribute("value","99999");
		if(number_renewal.hasAttribute("required"))
			number_renewal.removeAttribute("required");
	}
	else if(form.type_cicle.value =="one time")
	{
		number_cycle.setAttribute("hidden","hidden");
		number_renewal.setAttribute("value","0");
		if(number_renewal.hasAttribute("required"))
			number_renewal.removeAttribute("required");
	}
	else
	{
		if(number_renewal.hasAttribute('readOnly') && number_cycle.hasAttribute("hidden"))
		{
			number_renewal.removeAttribute("readOnly");
			number_cycle.removeAttribute("hidden");
		}
		if(!number_renewal.hasAttribute("required"))
			number_cycle.setAttribute("required","required");
		number_renewal.removeAttribute("value");
	}	
}
//function appartenant au formulaire de souscription s'occupant des msg de notifications
function open_msg_text_node(form)
{
	//recuprérations des ID des différent champs sur lesquelles je vais effectuer les traitements
	id = document.getElementById("msgs");
	success = document.getElementById("success_text");
	fail = document.getElementById("fail_text");
	already = document.getElementById("already_order_text");
	insuffisance_balance = document.getElementById("insuffisance_balance_text");
	nauthorized = document.getElementById("nauthorized_text");
	//alert("ok");

	//tableau d'élément à cacher
	var tab = ['success_text','fail_text','already_order_text','insuffisance_balance_text','nauthorized_text'];
	//tableau des textarea
	var tab2 = ['succes','fail','already_order','insuffisance_balance','nauthorized'];
	switch(form.msgs.value)
	{
		case "succes":
			if(document.getElementById(tab[0]).hasAttribute('hidden'))
				success.removeAttribute("hidden");
			if(document.getElementById(tab2[0]).hasAttribute("readOnly"))
				document.getElementById(tab2[0]).removeAttribute("readOnly");
		break;
		case "fail":
			if(document.getElementById(tab[1]).hasAttribute('hidden'))
				fail.removeAttribute("hidden");
			if(document.getElementById(tab2[1]).hasAttribute("readOnly"))
				document.getElementById(tab2[1]).removeAttribute("readOnly");
		break;
		case "Already order":
			if(document.getElementById(tab[2]).hasAttribute('hidden'))
				already.removeAttribute("hidden");
			if(document.getElementById(tab2[2]).hasAttribute("readOnly"))
				document.getElementById(tab2[2]).removeAttribute("readOnly");
		break;
		case "Insuffisance balance":
			if(document.getElementById(tab[3]).hasAttribute('hidden'))
				insuffisance_balance.removeAttribute("hidden");
			if(document.getElementById(tab2[3]).hasAttribute("readOnly"))
				document.getElementById(tab2[3]).removeAttribute("readOnly");
		break;
		case "Not authorized":
			if(document.getElementById(tab[4]).hasAttribute('hidden'))
				nauthorized.removeAttribute("hidden");
			if(document.getElementById(tab2[4]).hasAttribute("readOnly"))
				document.getElementById(tab2[4]).removeAttribute("readOnly");
		break;
		default :
			for(i = 0; i <= tab.length; i++)
			{
				if(document.getElementById(tab2[i]).value=="")
				{
					if(!document.getElementById(tab[i]).hasAttribute('hidden'))
						document.getElementById(tab[i]).setAttribute("hidden","hidden");
				}
				if(!document.getElementById(tab2[i]).hasAttribute('readOnly'))
					document.getElementById(tab2[i]).setAttribute("readOnly","readOnly");
			}
		break;
	}
}
//function pour selectionner tout les jours
	//cette fonction est potentiellement utilisé dans plusieur formulaire du genre
function check_all_day()
{
    //recuperation des jours
    jours = document.getElementsByName('jours[]');
    for(i=0; i <= jours.length; i++)
    {
       jours[i].click();
    }
}
document.getElementById('days').addEventListener("click", check_all_day);

function check_all_month()
{
    //recuperation des jours
    mois = document.getElementsByName('mois[]');
    for(i=0; i <= mois.length; i++)
    {
       mois[i].click();
    }
}
document.getElementById('month').addEventListener("click", check_all_month);

function check_all_week_day()
{
    //recuperation des jours
    wday = document.getElementsByName('wday[]');
    for(i=0; i <= wday.length; i++)
    {
       wday[i].click();
    }
}
document.getElementById('week_day').addEventListener("click", check_all_week_day);
//check all profile for user profile
function check_all_profile_prepaid()
{
    //recuperation des jours
    profile = document.getElementsByName('profile_prepaid[]');
    for(i=0; i <= profile.length; i++)
    {
       profile[i].click();
    }
}
document.getElementById('check_prepaid').addEventListener("click", check_all_profile_prepaid);

function check_all_profile_hybrid()
{
    //recuperation des jours
    profile = document.getElementsByName('profile_hybrid[]');
    for(i=0; i <= profile.length; i++)
    {
       profile[i].click();
    }
}
document.getElementById('check_hybrid').addEventListener("click", check_all_profile_hybrid);

function check_all_profile_pospaid()
{
    //recuperation des jours
    profile = document.getElementsByName('profile_pospaid[]');
    for(i=0; i <= profile.length; i++)
    {
       profile[i].click();
    }
}
document.getElementById('check_pospaid').addEventListener("click", check_all_profile_pospaid);


function use_times(form)
{
	
	day = document.getElementById("dayss");
	hours = document.getElementById("hours");
	heure_debut = document.getElementById("heure_debut");
	heure_fin = document.getElementById("heure_fin");
	sc_heure_debut = document.getElementById("sc_heure_debut");
	sc_heure_fin = document.getElementById("sc_heure_fin");	
	
	jours = document.getElementsByName('jours[]');
	if(form.use_time.value=="other")
	{
		if(day.hasAttribute("hidden") && hours.hasAttribute("hidden"))
		{
			day.removeAttribute("hidden");
			hours.removeAttribute("hidden");
			heure_debut.removeAttribute("required");
			heure_fin.removeAttribute("required");
			sc_heure_debut.removeAttribute("required");
			sc_heure_fin.removeAttribute("required");
			//a delete en cas de pb
			day.removeAttribute("disabled");
			hours.removeAttribute("disabled");
			heure_debut.removeAttribute("disabled");
			heure_fin.removeAttribute("disabled");
			sc_heure_debut.removeAttribute("disabled");
			sc_heure_fin.removeAttribute("disabled");
		}//a delete en cas de pb
		if(day.hasAttribute("disabled"))
			day.removeAttribute("disabled");	
		for(i=0; i<=jours.length; i++)
		{
			if(jours[i].hasAttribute("disabled"))
				jours[i].removeAttribute('disabled');
		}
	}
	else
	{
		if(!day.hasAttribute("hidden") && !hours.hasAttribute("hidden"))
		{
			day.setAttribute("hidden","hidden");
			hours.setAttribute("hidden","hidden");
			heure_debut.setAttribute("required","required");
			heure_fin.setAttribute("required","required");
			sc_heure_debut.setAttribute("required","required");
			sc_heure_fin.setAttribute("required","required");
			//a delete en cas de pb
			day.setAttribute("disabled","disabled");
			hours.setAttribute("disabled","disabled");
			heure_debut.setAttribute("disabled","disabled");
			heure_fin.setAttribute("disabled","disabled");
			sc_heure_debut.setAttribute("disabled","disabled");
			sc_heure_fin.setAttribute("disabled","disabled");
		}//a delete en cas de pb
		if(!day.hasAttribute("disabled"))
			day.setAttribute("disabled","disabled");
		for(var i=0; i<=jours.length; i++)
		{
			if(!jours[i].hasAttribute("disabled"))
				jours[i].setAttribute('disabled','disabled');
		}
	}
}
//choisir le profile de l'abonné. Ce script est utiliser sur deux formulaire
function choice_profile(form)
{
	div_profile = document.getElementById("div_profile");
	profile = document.getElementById("profile");
	if(form.type_profile.value != "All")
	{
		div_profile.removeAttribute("hidden");
		profile.setAttribute("required","required");
	}
	else
	{
		if(!div_profile.hasAttribute("hidden") && profile.hasAttribute("required"))
		{
			div_profile.setAttribute("hidden","hidden");
			profile.removeAttribute("required");
			if(profile.hasAttribute("value"))
				profile.removeAttribute("value");
		}
	}
}
function affiche_liste_profile(form)
{
	Prepaid = document.getElementById('Prepaid');
	Hybrid = document.getElementById('Hybrid');
	Pospaid = document.getElementById('Pospaid');
	tab = ['Prepaid', 'Hybrid', 'Pospaid', 'All'];
	for(i = 0; i <= tab.length; i++)
	{
		if(!document.getElementById(tab[i]).hasAttribute('hidden'))
			document.getElementById(tab[i]).setAttribute("hidden","hidden");
		if(form.souscriber_profile.options[i].selected)
		{
			option_value = form.souscriber_profile.options[i].value;
			if(document.getElementById(option_value).hasAttribute('hidden'))
				document.getElementById(option_value).removeAttribute('hidden');
		}
	}
}
//verification de l'existance du numero de change
function is_num_change_exist()
{
	res = confirm("Do you really want to perform the update of this offer ?");
	if(res)
	{
		num_change = document.getElementById("num_change").value;
		etat_system_offre = document.getElementById('etat_system_offre').value;
		if(num_change == "" || etat_system_offre !="Close")
		{
			alert("The number of change are nessecary and this offer must be closed, please make it before updating.");
			return false;
		}
		else
		{
			return true;
		}
	}
}
