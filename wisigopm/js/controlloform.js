// JavaScript Document

/* generale */
function RicavaBrowser(QualeBrowser)
{
    if (navigator.userAgent.indexOf("MSIE") != (-1))
    {
        var Classe = "Msxml2.XMLHTTP";
        if (navigator.appVersion.indexOf("MSIE 5.5") != (-1));
        {
            Classe = "Microsoft.XMLHTTP";
        } 
        try
        {
            OggettoXMLHTTP = new ActiveXObject(Classe);
            OggettoXMLHTTP.onreadystatechange = QualeBrowser;
            return OggettoXMLHTTP;
        }
        catch(e)
        {
            alert("Errore: l'ActiveX non verrà eseguito!");
        }
    }
    else if (navigator.userAgent.indexOf("Mozilla") != (-1))
    {
        OggettoXMLHTTP = new XMLHttpRequest();
        OggettoXMLHTTP.onload = QualeBrowser;
        OggettoXMLHTTP.onerror = QualeBrowser;
        return OggettoXMLHTTP;
    }
    else
    {
        alert("L'esempio non funziona con altri browser!");
    }
}


/* accessi.php */
function controlloFormAccessi(){
	errore="";
	flag=true;
	data_da=document.getElementById('datepicker3').value;
	data_a=document.getElementById('datepicker4').value;
	
	if (data_da!=""){
		controllo=ControlloData(data_da);
		if (controllo==false){
			errore+="La data iniziale non è nel formato corretto!<br>"; 
			flag=false;
		}
	}
	else {
		errore+="Inserire la data iniziale!<br>";
		flag=false; 
	}
	
	if (data_a!=""){
		controllo=ControlloData(data_a);
		if (controllo==false){
			errore+="La data finale non è nel formato corretto!<br>"; 
			flag=false;
		}
	}
	else {
		errore+="Inserire la data finale!<br>"; 
		flag=false;
	}
	
	
	if (flag==false){
		document.getElementById('riga_errore').style.display="block";
		document.getElementById('errore').innerHTML=errore;
		document.getElementById('loading').style.display="none";
		document.body.scrollTop = document.documentElement.scrollTop = 0;
	}
	else {
		document.getElementById('riga_errore').style.display="none";
		document.getElementById('errore').innerHTML="";
		document.getElementById('loading').style.display="block";			
		document.body.scrollTop = document.documentElement.scrollTop = 0;
		document.forms['form_accessi'].submit();
		
		
	}

}


function attivaSelect(id_p,ordine,id,boutique) {
	if (document.getElementById(id).value!=""){
		valore=document.getElementById(id).value;
		var url = "config/salvaDisponibilita.php?valore="+valore+"&id_p="+id_p+"&ordine="+ordine+"&id="+id+"&boutique="+boutique;
		XMLHTTP = RicavaBrowser(attivaSelect2);
		XMLHTTP.open("GET", url, true);
		XMLHTTP.send(null);
	}
}

function attivaSelect2(){
	if (XMLHTTP.readyState == 4)
    {
		array=XMLHTTP.responseText;
		array=eval(array);
		id=array[0][0];
		if (id!=false){
			document.getElementById(id).disabled=true;
		}
	}
}


function attivaSelectArrivato(id_p,ordine,id,boutique) {
    if (document.getElementById(id).value!=""){
        valore=document.getElementById(id).value;
        var url = "config/salvaArrivato.php?valore="+valore+"&id_p="+id_p+"&ordine="+ordine+"&id="+id+"&boutique="+boutique;
        XMLHTTP = RicavaBrowser(attivaSelectArrivato2);
        XMLHTTP.open("GET", url, true);
        XMLHTTP.send(null);
    }
}

function attivaSelectArrivato2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);
        id=array[0][0];
        if (id!=false){
            document.getElementById(id).disabled=true;
        }
    }
}


function ControlloData(stringa){
 
  // Struttura l'espressione regolare
  var espressione = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
 
  // Effettua il test sulla stringa e
  //   ritorna il risultato con un alert
  if (!espressione.test(stringa))  {
    return false;
  } else {
    return true;
  }
}


/* fatture_corrispettivi */
function selezionaTuttoFattura(){
	check=document.getElementsByName("check[]");
	for (i=0; i<check.length; i++){
		check[i].checked=true;
	}
}

function deselezionaTuttoFattura(){
	check=document.getElementsByName("check[]");
	for (i=0; i<check.length; i++){
		check[i].checked=false;
	}
}

function controlloFattureCorr(){
	errore="";
	flag=true;
	check=document.getElementsByName('check[]');

	flag2=false;
	for (i=0; i<check.length; i++){
		if (check[i].checked==true){
			flag2=true;
			break;
		}
	}
	
	if (flag2==false){
		errore+="Selezionare almeno un ordine!";
		flag=false;
	}
	
	if (flag==false){
		alert(errore);
	}
	
	return flag;
}




/* dashboard */
function attivaOrdiniAnno(valore){
    var url = "config/caricaOrdiniAnno.php?valore="+valore;
    XMLHTTP = RicavaBrowser(attivaOrdiniAnno2);
    XMLHTTP.open("GET", url, true);
    XMLHTTP.send(null);

}

function attivaOrdiniAnno2(){
    if (XMLHTTP.readyState == 4)
    {
        document.getElementById('ordini_anno').innerHTML="";
        array=XMLHTTP.responseText;
        array=eval(array);
        document.getElementById('titolo_ordini_anno').innerHTML="ORDINI PER MESE ANNO "+array[0][12];
        Morris.Bar({
            element: 'ordini_anno',
            data: [
                { y: ' Gen', a:  array[0][0], b: 6000 },
                { y: ' Feb', a:  array[0][1], b: 6000 },
                { y: ' Mar', a:  array[0][2], b: 6000 },
                { y: ' Apr', a:  array[0][3], b: 6000 },
                { y: ' Mag', a:  array[0][4], b: 6000 },
                { y: ' Giu', a:  array[0][5], b: 6000 },
                { y: ' Lug', a:  array[0][6], b: 6000 },
                { y: ' Ago', a:  array[0][7], b: 6000 },
                { y: ' Set', a:  array[0][8], b: 6000 },
                { y: ' Ott', a:  array[0][9], b: 6000 },
                { y: ' Nov', a:  array[0][10], b: 6000 },
                { y: ' Dic', a:  array[0][11], b: 6000 }
            ],
            xkey: 'y',
            ykeys: ['a','b'],
            labels: ['Fatturato mensile','Media Obiettivo mensile'],
            barColors: ['#3BAFDA', '#E9573F']
        });
    }
}

/* inserisci_prodotto.php */
function attivaSottoCategoria(valore,mode,parent) {
    if (valore!=""){
        var url = "config/sottoCategoria.php?valore="+valore+"&mode="+mode+"&parent="+parent;
        XMLHTTP = RicavaBrowser(attivaSottoCategoria2);
        XMLHTTP.open("GET", url, true);
        XMLHTTP.send(null);
    }
    else {
        // se ho selezionato l'opzione vuota della select elimino tutte le righe che si trovano di seguito
        // se non ci sono righe il valore della select saà undefined e non entrerà nel if seguente
        mode=mode+1;
        selectCat=document.getElementById('rigaSC'+mode);
        // il ciclo continua fino a che non ci sono più select di seguito
        while (selectCat!=undefined){
            select_id='sottocategoria'+(mode-1);
            selectCategoria=document.getElementById(select_id);
            for(i=selectCategoria.options.length-1;i>=1;i--)
            {
                selectCategoria.remove(i);
            }
            selectCat.style.display='none';
            selectCat.className="form-group has-feedback";
            elem1=selectCat.getElementsByClassName("form-control-feedback")[0];
            elem1.className="form-control-feedback";
            selectCat.getElementsByClassName("form-control-feedback")[0].style.display='none';
            if (selectCat.getElementsByClassName("help-block")[0]!=undefined) {
                selectCat.getElementsByClassName("help-block")[0].style.display = 'none';
            }
            mode=mode+1;
            selectCat=document.getElementById('rigaSC'+mode);

        }

        //nascondo i campi aggiuntivi
        document.getElementById('tipologia_borsa_donna').style.display='none';
        document.getElementById('tipologia_borsa_uomo').style.display='none';
        document.getElementById('dimensioni_borsa_lungh').style.display='none';
        document.getElementById('dimensioni_borsa_alt').style.display='none';
        document.getElementById('dimensioni_borsa_prof').style.display='none';
        document.getElementById('dimensioni_borsa_alt_manico').style.display='none';
        document.getElementById('dimensioni_borsa_lungh_trac').style.display='none';
        document.getElementById('tip_accessori_donna').style.display='none';
        document.getElementById('tip_accessori_uomo').style.display='none';
        document.getElementById('dim_accessorio_lungh').style.display='none';
        document.getElementById('dim_accessorio_prof').style.display='none';
        document.getElementById('dim_accessorio_alt').style.display='none';
        document.getElementById('tip_calz_donna').style.display='none';
        document.getElementById('tip_calz_uomo').style.display='none';
        document.getElementById('dim_calzatura_alt_tacco').style.display='none';
        document.getElementById('dim_calzatura_alt_plateau').style.display='none';
        document.getElementById('dim_calzatura_lungh_soletta').style.display='none';
        document.getElementById('tipo_tacco_donna').style.display='none';
        document.getElementById('tipo_suola').style.display='none';
        document.getElementById('tipo_punta_donna').style.display='none';
        document.getElementById('tipo_punta_uomo').style.display='none';
        document.getElementById('cint_lunghezza').style.display='none';
        document.getElementById('cint_altezza').style.display='none';
        document.getElementById('vestibilita_abiti').style.display='none';
        document.getElementById('vestibilita_topwear').style.display='none';
        document.getElementById('vestibilita_pantaloni').style.display='none';
        document.getElementById('vestibilita_gonne').style.display='none';
        document.getElementById('vestibilita_camicie_uomo').style.display='none';
        document.getElementById('vestibilita_camicie_donna').style.display='none';
        document.getElementById('vestibilita_capispalla').style.display='none';
        document.getElementById('vestibilita_giacche').style.display='none';
    }
}

function attivaSottoCategoria2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);

        // azzero prima tutte le select che ci sono dopo

        mode=parseInt(array[0][2])+1;
        selectCat=document.getElementById('rigaSC'+mode);
        // il ciclo continua fino a che non ci sono più select di seguito
        while (selectCat!=undefined){
            select_id='sottocategoria'+(mode-1);
            selectCategoria=document.getElementById(select_id);
            for(i=selectCategoria.options.length-1;i>=1;i--)
            {
                selectCategoria.remove(i);
            }
            selectCat.style.display='none';
            selectCat.className="form-group has-feedback";
            elem1=selectCat.getElementsByClassName("form-control-feedback")[0];
            elem1.className="form-control-feedback";
            selectCat.getElementsByClassName("form-control-feedback")[0].style.display='none';
            if (selectCat.getElementsByClassName("help-block")[0]!=undefined) {
                selectCat.getElementsByClassName("help-block")[0].style.display = 'none';
            }
            mode=mode+1;
            selectCat=document.getElementById('rigaSC'+mode);

        }

        // se ci sono sottocategorie entro nell'if e creo la select
        if (array[0][0]!=""){
            mode=parseInt(array[0][2])+1;
            if (array[0][2]==1){
                array[0][3]='';
            }

            select_id='sottocategoria'+(mode-1);
            riga_id='rigaSC'+(mode);
            select_cat=document.getElementById(select_id);
            for (i=0; i<array.length; i++){
                var option = document.createElement("option");
                option.text = array[i][1];
                option.value = array[i][0];
                select_cat.add(option);
            }
            select_cat.setAttribute("onchange","attivaSottoCategoria(this.value,"+mode+",'"+array[0][3]+"')");

            document.getElementById(riga_id).style.display='block';


        }



        if (array[0][4]=="Borse donna"){
            resetCampi();
            document.getElementById('tipologia_borsa_donna').style.display='block';
            document.getElementById('dimensioni_borsa_lungh').style.display='block';
            document.getElementById('dimensioni_borsa_alt').style.display='block';
            document.getElementById('dimensioni_borsa_prof').style.display='block';
            document.getElementById('dimensioni_borsa_alt_manico').style.display='block';
            document.getElementById('dimensioni_borsa_lungh_trac').style.display='block';
        }
        else if (array[0][4]=="Borse uomo"){
            resetCampi();
            document.getElementById('tipologia_borsa_uomo').style.display='block';
            document.getElementById('dimensioni_borsa_lungh').style.display='block';
            document.getElementById('dimensioni_borsa_alt').style.display='block';
            document.getElementById('dimensioni_borsa_prof').style.display='block';
            document.getElementById('dimensioni_borsa_alt_manico').style.display='block';
            document.getElementById('dimensioni_borsa_lungh_trac').style.display='block';
        }
        else if (array[0][4]=="Accessori donna"){
            resetCampi();
            document.getElementById('tip_accessori_donna').style.display='block';
            document.getElementById('dim_accessorio_lungh').style.display='block';
            document.getElementById('dim_accessorio_prof').style.display='block';
            document.getElementById('dim_accessorio_alt').style.display='block';
        }
        else if (array[0][4]=="Accessori uomo"){
            resetCampi();
            document.getElementById('tip_accessori_uomo').style.display='block';
            document.getElementById('dim_accessorio_lungh').style.display='block';
            document.getElementById('dim_accessorio_prof').style.display='block';
            document.getElementById('dim_accessorio_alt').style.display='block';
        }
        else if (array[0][4]=="Calzature donna"){
            resetCampi();
            document.getElementById('tip_calz_donna').style.display='block';
            document.getElementById('dim_calzatura_alt_tacco').style.display='block';
            document.getElementById('dim_calzatura_alt_plateau').style.display='block';
            document.getElementById('dim_calzatura_lungh_soletta').style.display='block';
            document.getElementById('tipo_tacco_donna').style.display='block';
            document.getElementById('tipo_suola').style.display='block';
            document.getElementById('tipo_punta_donna').style.display='block';
        }
        else if (array[0][4]=="Calzature uomo"){
            resetCampi();
            document.getElementById('tip_calz_uomo').style.display='block';
            document.getElementById('dim_calzatura_alt_tacco').style.display='block';
            document.getElementById('dim_calzatura_alt_plateau').style.display='block';
            document.getElementById('dim_calzatura_lungh_soletta').style.display='block';
            document.getElementById('tipo_suola').style.display='block';
            document.getElementById('tipo_punta_uomo').style.display='block';
        }
        else if (array[0][4]=="Cinture"){
            resetCampi();
            if (document.getElementById('categoria').value==13){
                document.getElementById('tip_accessori_uomo').style.display='block';
                document.getElementById('dim_accessorio_lungh').style.display='block';
                document.getElementById('dim_accessorio_prof').style.display='block';
                document.getElementById('dim_accessorio_alt').style.display='block';
            }
            else if (document.getElementById('categoria').value==3){
                document.getElementById('tip_accessori_donna').style.display='block';
                document.getElementById('dim_accessorio_lungh').style.display='block';
                document.getElementById('dim_accessorio_prof').style.display='block';
                document.getElementById('dim_accessorio_alt').style.display='block';
            }
            document.getElementById('cint_lunghezza').style.display='block';
            document.getElementById('cint_altezza').style.display='block';
        }
        else if (array[0][4]=="Abiti"){
            resetCampi();
            document.getElementById('vestibilita_abiti').style.display='block';
        }
        else if (array[0][4]=="Topwear"){
            resetCampi();
            document.getElementById('vestibilita_topwear').style.display='block';
        }
        else if (array[0][4]=="Pantaloni" || array[0][4]=="Jeans"){
            resetCampi();
            document.getElementById('vestibilita_pantaloni').style.display='block';
        }
        else if (array[0][4]=="Gonne"){
            resetCampi();
            document.getElementById('vestibilita_gonne').style.display='block';
        }
        else if (array[0][4]=="Camicie uomo"){
            resetCampi();
            document.getElementById('vestibilita_camicie_uomo').style.display='block';
        }
        else if (array[0][4]=="Camicie donna"){
            resetCampi();
            document.getElementById('vestibilita_camicie_donna').style.display='block';
        }
        else if (array[0][4]=="Capispalla"){
            resetCampi();
            document.getElementById('vestibilita_capispalla').style.display='block';
        }
        else if (array[0][4]=="Giacche"){
            resetCampi();
            document.getElementById('vestibilita_giacche').style.display='block';
        }
        else {
            resetCampi();
        }


        // questa parte serve per mantenere selezionata l'opzione della sottocategoria ogni volta che vengono creati le select per ogni livello di sottocategoria. Si entra nell'if solo dal secondo livello di sottocategoria
        if (array[0][2]>1){
            id=parseInt(array[0][2])-1;  // si parte dalla sottocategoria immediatamente sopra
            idSottoCat=array[0][3].split("/"); // recupero gli id di tutte le sottocategorie parent
            p=idSottoCat.length-2; // questo serve perchè l'array 'idSottoCat' ha sempre un record vuoto (quello dopo lo slash)
            // faccio un ciclo partendo dalla categoria immediatamente sopra e arrivando al primo livello di sottocategoria (escludendo la categoria madre);
            for (k=id; k>=1; k--){
                var sottoCat = document.getElementById('sottocategoria'+k);
                // scorro tutte le opzioni e quella che è uguale al corrispettivo elemento dell'array idSottoCat la seleziono
                for (i=0; i<sottoCat.options.length; i++){
                    if (sottoCat.options[i].value==idSottoCat[p]){
                        sottoCat.options[i].selected = true;
                    }
                }
                p=p-1;
            }
        }

    }
}

function resetCampi(){
    // questa parte serve per attivare le altre caratteristiche del prodotto a seconda della categoria scelta
    document.getElementById('tipologia_borsa_donna').style.display='none';
    document.getElementById('tipologia_borsa_uomo').style.display='none';
    document.getElementById('dimensioni_borsa_lungh').style.display='none';
    document.getElementById('dimensioni_borsa_alt').style.display='none';
    document.getElementById('dimensioni_borsa_prof').style.display='none';
    document.getElementById('dimensioni_borsa_alt_manico').style.display='none';
    document.getElementById('dimensioni_borsa_lungh_trac').style.display='none';
    document.getElementById('tip_accessori_donna').style.display='none';
    document.getElementById('tip_accessori_uomo').style.display='none';
    document.getElementById('dim_accessorio_lungh').style.display='none';
    document.getElementById('dim_accessorio_prof').style.display='none';
    document.getElementById('dim_accessorio_alt').style.display='none';
    document.getElementById('tip_calz_donna').style.display='none';
    document.getElementById('tip_calz_uomo').style.display='none';
    document.getElementById('dim_calzatura_alt_tacco').style.display='none';
    document.getElementById('dim_calzatura_alt_plateau').style.display='none';
    document.getElementById('dim_calzatura_lungh_soletta').style.display='none';
    document.getElementById('tipo_tacco_donna').style.display='none';
    document.getElementById('tipo_suola').style.display='none';
    document.getElementById('tipo_punta_donna').style.display='none';
    document.getElementById('tipo_punta_uomo').style.display='none';
    document.getElementById('cint_lunghezza').style.display='none';
    document.getElementById('cint_altezza').style.display='none';
    document.getElementById('vestibilita_abiti').style.display='none';
    document.getElementById('vestibilita_topwear').style.display='none';
    document.getElementById('vestibilita_pantaloni').style.display='none';
    document.getElementById('vestibilita_gonne').style.display='none';
    document.getElementById('vestibilita_camicie_uomo').style.display='none';
    document.getElementById('vestibilita_camicie_donna').style.display='none';
    document.getElementById('vestibilita_capispalla').style.display='none';
    document.getElementById('vestibilita_giacche').style.display='none';
}

/* taglia-colore.php */
function caricaAttributi() {
    prodotti=document.getElementById('prodotti').value;
    if (prodotti==""){
        // nessun valore inserito
        document.getElementById('riga_errore').style.display="block";
        document.getElementById('errore').innerHTML="Inserire un valore!";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }
    else if (isNaN(prodotti) || prodotti<=0){
        // il valore inserito è una stringa o è <=0
        document.getElementById('riga_errore').style.display="block";
        document.getElementById('errore').innerHTML="Valore non corretto!";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('prodotti').value="";
    }
    else {
        document.getElementById('riga_errore').style.display="none";
        document.getElementById('errore').innerHTML="";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('loading').style.display="block";
        var url = "config/recuperoTaglieScalari.php";
        XMLHTTP = RicavaBrowser(caricaAttributi2);
        XMLHTTP.open("GET", url, true);
        XMLHTTP.send(null);
    }
}

function caricaAttributi2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);
        var taglia=array[0][0];
        var scalare=array[0][1];
        document.getElementById('loading').style.display="none";
        prodotti=document.getElementById('prodotti').value;
        document.getElementById('prodotti').value="";
        if (document.getElementById('tabella_prodotti') != undefined ){
            numeroTr=document.getElementById('tabella_prodotti').getElementsByTagName("tr").length;
            taglia_sel=document.getElementsByName("taglia[]");
            scalare_sel=document.getElementsByName("scalare[]");

            stringa="<thead><tr><th></th><th style=\"text-align:left\" width=154>Scalare *</th><th style=\"text-align:left\" width=154>Taglia *</th><th></th></thead></tr><tbody>";
            for (i=0; i<(numeroTr-1); i++){
                stringa+="<tr><td><label class=\"control-label\"><b>Prodotto "+(i+1)+" *</b></label></td>";
                stringa+="<td><select style='width: 270px;' name=\"scalare[]\" class=\"input-text select_attr\" onchange='attivaTagliaScalare(this.value,"+(i+1)+")'><option value=\"\">...</option>";
                for (j=0; j<scalare.length; j++){
                    stringa+="<option value=\""+scalare[j][0]+"\"";
                    val=scalare[j][0];
                    if (val==scalare_sel[i].value){
                        stringa += " selected ";
                    }
                    stringa+=">"+scalare[j][1]+"</option>";
                }
                stringa+="</select></td>";
                stringa+="<td><select name=\"taglia[]\" id=\"taglia"+(i+1)+"\" class=\"input-text select_attr\"><option value=\"\">...</option>";
                for (j=0; j<taglia.length; j++){
                    stringa+="<option value=\""+taglia[j][0]+"\\"+taglia[j][1]+"\"";
                    val=taglia[j][0]+"\\"+taglia[j][1];
                    if (val==taglia_sel[i].value){
                        stringa += " selected ";
                    }
                    stringa+=">"+taglia[j][1]+"</option>";
                }
                stringa+="</select></td><td><img class='img_delete' src='img/delete.png' width=32 onclick=\"deleteRow(this)\" /></td></tr>";
            }

            k=numeroTr-1;
            for (i=0; i<prodotti; i++){
                stringa+="<tr><td><label class=\"control-label\"><b>Prodotto "+(k+i+1)+" *</b></label></td>";
                stringa+="<td><select style='width: 270px;' name=\"scalare[]\" class=\"input-text select_attr\" onchange='attivaTagliaScalare(this.value,"+(k+i+1)+")'><option value=\"\">...</option>";
                for (j=0; j<scalare.length; j++){
                    stringa+="<option value=\""+scalare[j][0]+"\">"+scalare[j][1]+"</option>";
                }
                stringa+="</select></td>";
                stringa+="<td><select name=\"taglia[]\" id=\"taglia"+(k+i+1)+"\" class=\"input-text select_attr\"><option value=\"\">...</option>";
                /*for (j=0; j<taglia.length; j++){
                    stringa+="<option value=\""+taglia[j][0]+"\\"+taglia[j][1]+"\">"+taglia[j][1]+"</option>";
                }*/
                stringa+="</select></td><td><img class='img_delete' src='img/delete.png' width=32 onclick=\"deleteRow(this)\" /></td></tr>";
            }
            document.getElementById('tabella_prodotti').innerHTML=stringa;

        }
        else {
            stringa="<table class=\"table table-th-block table-success\" id=\"tabella_prodotti\" cellpadding=8><thead><tr><th></th><th style=\"text-align:left\" width=154>Scalare *</th><th width=154 style=\"text-align:left\">Taglia</th><th></th></thead></tr><tbody>";


            for (i=0; i<prodotti; i++){
                stringa+="<tr><td><label class=\"control-label\"><b>Prodotto "+(i+1)+" *</b></label></td>";
                stringa+="<td><select style='width: 270px;' name=\"scalare[]\" class=\"input-text select_attr\" onchange='attivaTagliaScalare(this.value,"+(i+1)+")'><option value=\"\">...</option>";
                for (j=0; j<scalare.length; j++){
                    stringa+="<option value=\""+scalare[j][0]+"\">"+scalare[j][1]+"</option>";
                }
                stringa+="</select></td>";
                stringa+="<td><select name=\"taglia[]\" id=\"taglia"+(i+1)+"\" class=\"input-text select_attr\"><option value=\"\">...</option>";
                /*for (j=0; j<taglia.length; j++){
                    stringa+="<option value=\""+taglia[j][0]+"\\"+taglia[j][1]+"\">"+taglia[j][1]+"</option>";
                }*/
                stringa+="</select></td><td><img class='img_delete' src='img/delete.png' width=32 onclick=\"deleteRow(this)\" /></td></tr>";
            }
            stringa+="</table>";
            document.getElementById('riga_attr').innerHTML=stringa;
            document.getElementById('bottoni_attr').style.display='block';
        }
    }


}

function svuota(id){
    num_option=document.getElementById(id).options.length;
    for(a=num_option;a>0;a--){
        document.getElementById(id).options[a]=null;
    }
}


function attivaTagliaScalare(valore,indice) {
    if (valore==""){
        id="taglia"+indice;
        svuota(id);
    }
    else {
        var url = "config/recuperoTaglie.php?valore="+valore+"&indice="+indice;
        XMLHTTP = RicavaBrowser(attivaTagliaScalare2);
        XMLHTTP.open("GET", url, true);
        XMLHTTP.send(null);
    }
}


function attivaTagliaScalare2() {
    if (XMLHTTP.readyState == 4) {
        array = XMLHTTP.responseText;
        array = eval(array);

        id="taglia"+array[0][2];
        svuota(id);
        select = document.getElementById(id);

        for (i=0; i<array.length; i++){
            var opt = document.createElement('option');
            opt.value = array[i][0]+"\\"+array[i][1];
            opt.innerHTML = array[i][1];
            select.appendChild(opt);
        }
    }
}





function deleteRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
    if (document.getElementById('tabella_prodotti').getElementsByTagName("tr").length ==1){
        document.getElementById('riga_attr').innerHTML="";
        document.getElementById('bottoni_attr').style.display='none';
    }
    else {
        label=document.getElementById('tabella_prodotti').getElementsByClassName("label prodotto");
        for (i=0; i<label.length; i++){
            label[i].innerHTML="<b>Prodotto "+(i+1)+" *</b>";
        }
    }
}

function controlloFormTagliaScalare(){
    errore="";
    flag=true;
    taglia=document.getElementsByName("taglia[]");
    scalare=document.getElementsByName("scalare[]");


    check=true;

    for (i=0; i<scalare.length; i++){
        if (scalare[i].value==""){
            check=false;
            break;
        }
    }

    if (scalare.length==0){
        check=false;
    }

    if (check==false){
        errore+="Non sono stati specificati tutti gli scalari!<br>";
        flag=false;
    }


    check2=true;

    for (i=0; i<taglia.length; i++){
        if (taglia[i].value==""){
            check2=false;
            break;
        }
    }

    if (taglia.length==0){
        check2=false;
    }



    if (check2==false){
        errore+="Non sono state specificate tutte le taglie!<br>";
        flag=false;
    }

    check3=true;
    if (check==true && check2==true){
        for (i=0; i<taglia.length; i++){
            for (j=i+1; j<taglia.length; j++){
                if (taglia[i].value==taglia[j].value && scalare[i].value==scalare[j].value){
                    check3=false;
                    break;
                }
            }
        }
    }

    if (check3==false){
        errore+="Sono stati inseriti prodotti uguali!<br>";
        flag=false;
    }


    if (flag==false){
        document.getElementById('riga_errore').style.display="block";
        document.getElementById('errore').innerHTML=errore;
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }
    else {
        document.getElementById('riga_errore').style.display="none";
        document.getElementById('errore').innerHTML="";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('loading').style.display="block";
    }



    return flag;
}

/* tabella_taglia_colore.php */
function controlloFormQta() {
    errore="";
    flag=true;
    qta=document.getElementsByName("qta[]");

    check=true;
    for (i=0; i<qta.length; i++){
        if (qta[i].value==""){
            check=false;
            break;
        }
    }

    if (check==false){
        errore+="Non sono state specificate tutte le quantità!<br>";
        flag=false;
    }



    if (flag==false){
        document.getElementById('riga_errore').style.display="block";
        document.getElementById('errore').innerHTML=errore;
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }
    else {
        document.getElementById('riga_errore').style.display="none";
        document.getElementById('errore').innerHTML=errore;
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('loading').style.display="block";
    }



    return flag;
}

function controlloFormProdotto(){
    document.getElementById('riga_errore').style.display="none";
    document.getElementById('errore').innerHTML="";
    var url = "config/controlloTaglieScalari.php";
    XMLHTTP = RicavaBrowser(controlloFormProdotto2);
    XMLHTTP.open("GET", url, true);
    XMLHTTP.send(null);
}

function controlloFormProdotto2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);
        errore="";
        flag=true;
        immagini=document.getElementById('immagini').value;

        if (immagini==""){
            errore+="Immagini non inserite!<br>";
            flag=false;
        }

        if (array[0][0]=="0"){
            errore+="Taglie e scalari non inseriti!";
            flag=false;
        }
        if (flag==false){
            document.getElementById('riga_errore').style.display="block";
            document.getElementById('errore').innerHTML=errore;
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        }
        else {
            flag2=false;
            erroreForm=document.getElementsByClassName("help-block");
            for (i=0; i<erroreForm.length; i++){
                if (erroreForm[i].style.display != "none"){
                    flag2=true;
                    break;
                }
            }
            if (flag2==true){
            }
            else {
                document.getElementById('riga_errore').style.display="none";
                document.getElementById('errore').innerHTML="";
                document.getElementById('loading').style.display="block";
                document.forms['form_prodotto'].submit();
                document.body.scrollTop = document.documentElement.scrollTop = 0;
            }

        }
    }


}

/* scala_disponibilita.php */
function scalaProdotti(){
    sku=document.getElementById('sku').value;
    if (sku==""){
        document.getElementById('riga_errore').style.display="block";
        document.getElementById('errore').innerHTML="Inserire l'id!";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('tab_prodotti').style.display="none";
        document.getElementById('tab_prodotti').innerHTML="";
    }

    else {
        document.getElementById('riga_errore').style.display="none";
        document.getElementById('errore').innerHTML="";
        document.getElementById('loading').style.display='block';
        var url = "config/recuperaRiassortimento.php?sku="+sku;
        XMLHTTP = RicavaBrowser(scalaProdotti2);
        XMLHTTP.open("GET", url, true);
        XMLHTTP.send(null);
    }
}

function scalaProdotti2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);
        document.getElementById('loading').style.display='none';
        if (array[0][0]=="1"){
            stringa="<div class=\"the-box full no-border\"><div class=\"table-responsive\"><table class=\"table table-th-block table-success\" cellpadding=5><thead><tr><th style=\"text-align:left\">Seleziona</th><th style=\"text-align:left\">Nome prodotto</th><th style=\"text-align:left\">Id prodotto</th><th style=\"text-align:left\">Qta attuale</th><th style=\"text-align:left\">Qta venduta *</th></thead></tr><tbody>";

            stringa+="<tr><td align=left><input type=\"radio\" name=\"check[]\" value=\""+array[0][4]+"\" checked/></td><td align=left>"+array[0][1]+"</td><td align=left>"+array[0][2]+"</td><td align=left>"+array[0][3]+"</td>";
            stringa+="<td align=left><input type=\"text\" name=\"qta_"+array[0][4]+"\" class=\"input-text\" id=\"qta_"+array[0][4]+"\" style=\"width:50px;text-align:left;margin-top:0\"/><input type=\"hidden\" name=\"qta_vecchia"+array[0][4]+"\" class=\"input-text\" id=\"qta_vecchia"+array[0][4]+"\" value="+array[0][3]+" /></td>";
            stringa+="</tr></tbody></table></div></div>";

            document.getElementById('tab_prodotti').style.display='block';
            document.getElementById('tab_prodotti').innerHTML=stringa;
            document.getElementById('bottoni').style.display='block';
            document.getElementById('selezionaTuttoDiv').style.display='none';
        }
        else if (array[0][0]=="2"){
            stringa="<div class=\"the-box full no-border\"><div class=\"table-responsive\"><table class=\"table table-th-block table-success\" cellpadding=5><thead><tr><th style=\"text-align:left\">Seleziona</th><th style=\"text-align:left\">Nome prodotto</th><th style=\"text-align:left\">Id prodotto</th><th style=\"text-align:left\">Qta attuale</th><th style=\"text-align:left\">Qta venduta *</th></thead></tr><tbody>";
            for (i=0; i<array.length; i++){
                stringa+="<tr><td align=left><input type=\"checkbox\" name=\"check[]\" value=\""+array[i][4]+"\" /></td><td align=left>"+array[i][1]+"</td><td align=left>"+array[i][2]+"</td><td align=left>"+array[i][3]+"</td><td align=left><input type=\"text\" name=\"qta_"+array[i][4]+"\" class=\"input-text\" id=\"qta_"+array[i][4]+"\" style=\"width:50px;text-align:left;margin-top:0\"/><input type=\"hidden\" name=\"qta_vecchia"+array[i][4]+"\" class=\"input-text\" id=\"qta_vecchia"+array[i][4]+"\" value="+array[i][3]+" /></td></tr>";
            }

            stringa+="</tbody></table></div></div>";

            document.getElementById('tab_prodotti').style.display='block';
            document.getElementById('tab_prodotti').innerHTML=stringa;
            document.getElementById('bottoni').style.display='block';
            document.getElementById('selezionaTuttoDiv').style.display='block';
        }
        else {
            document.getElementById('riga_errore').style.display="block";
            document.getElementById('errore').innerHTML=array[0][1]+"!";
            document.body.scrollTop = document.documentElement.scrollTop = 0;
            document.getElementById('tab_prodotti').style.display='none';
            document.getElementById('tab_prodotti').innerHTML="";
            document.getElementById('bottoni').style.display='none';
        }
    }
}


function selezionaTuttoS(){
    check=document.getElementsByName("check[]");
    for (i=0; i<check.length; i++){
        check[i].checked=true;
    }
}

function deselezionaTuttoS(){
    check=document.getElementsByName("check[]");
    for (i=0; i<check.length; i++){
        check[i].checked=false;
    }
}

function controlloFormDisp(){
    errore="";
    flag=true;
    check=document.getElementsByName('check[]');

    flag2=false;
    for (i=0; i<check.length; i++){
        if (check[i].checked==true){
            flag2=true;
            break;
        }
    }

    if (flag2==false){
        errore+="Selezionare almeno un prodotto!<br>";
        flag=false;
    }

    flag3=true;
    for (i=0; i<check.length; i++){
        if (check[i].checked==true){
            value=check[i].value;
            nome="qta_"+value;
            qta=document.getElementById(nome).value;
            if (qta==""){
                flag3=false;
                break;
            }
        }
    }


    if (flag3==false){
        errore+="Quantità non inserite!<br>";
        flag=false;
    }

    flag4=true;
    for (i=0; i<check.length; i++){
        if (check[i].checked==true){
            value=check[i].value;
            nome="qta_"+value;
            qta=document.getElementById(nome).value;
            if (qta!="" && (isNaN(qta) || qta<=0)){
                flag4=false;
                break;
            }
        }
    }

    if (flag4==false){
        errore+="Formati quantità non corretti!<br>";
        flag=false;
    }

    flag5=true;

    for (i=0; i<check.length; i++){
        if (check[i].checked==true){
            value=check[i].value;
            nome="qta_"+value;
            qta=document.getElementById(nome).value;
            nome_vecchia="qta_vecchia"+value;
            qta_vecchia=document.getElementById(nome_vecchia).value;
            if (qta!="" && !isNaN(qta) && qta>0 && qta>qta_vecchia){
                flag5=false;
                break;
            }
        }
    }

    if (flag5==false){
        errore+="Quantità venduta superiore alla quantità attuale!<br>";
        flag=false;
    }


    if (flag==false){
        document.getElementById('riga_errore').style.display="block";
        document.getElementById('errore').innerHTML=errore;
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }
    else {
        document.getElementById('riga_errore').style.display="none";
        document.getElementById('errore').innerHTML="";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('loading').style.display="block";
    }



    return flag;
}

/* verifica_giacenza.php */
function verificaGiacenza(){
    sku=document.getElementById('sku').value;
    if (sku==""){
        document.getElementById('riga_errore').style.display="block";
        document.getElementById('errore').innerHTML="Inserire l'id!";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('tab_prodotti').style.display="none";
        document.getElementById('tab_prodotti').innerHTML="";
    }

    else {
        document.getElementById('riga_errore').style.display="none";
        document.getElementById('errore').innerHTML="";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('loading').style.display='block';
        var url = "config/verificaGiacenza.php?sku="+sku;
        XMLHTTP = RicavaBrowser(verificaGiacenza2);
        XMLHTTP.open("GET", url, true);
        XMLHTTP.send(null);
    }
}

function verificaGiacenza2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);
        document.getElementById('loading').style.display='none';
        document.getElementById('tab_prodotti').style.display='none';
        if (array[0][0]=="1"){
            stringa="<div class=\"the-box full no-border\"><div class=\"table-responsive\"><table class=\"table table-th-block table-success\" cellpadding=5><thead><tr><th style=\"text-align:left\">Nome prodotto</th><th style=\"text-align:left\">Id prodotto</th><th style=\"text-align:left\">Qta</th></thead></tr><tbody>";

            stringa+="<tr><td align=left>"+array[0][1]+"</td><td align=left>"+array[0][2]+"</td><td align=left>"+array[0][3]+"</td></tr>";

            stringa+="</tbody></table></div></div>";
            document.getElementById('tab_prodotti').style.display='block';
            document.getElementById('tab_prodotti').innerHTML=stringa;
        }
        else if (array[0][0]=="2"){
            stringa="<div class=\"the-box full no-border\"><div class=\"table-responsive\"><table class=\"table table-th-block table-success\" cellpadding=5><thead><tr><th style=\"text-align:left\">Nome prodotto</th><th style=\"text-align:left\">Id prodotto</th><th style=\"text-align:left\">Qta</th></thead></tr><tbody>";
            for (i=0; i<array.length; i++){
                stringa+="<tr><td align=left>"+array[i][1]+"</td><td align=left>"+array[i][2]+"</td><td align=left>"+array[i][3]+"</td></tr>";
            }
            stringa+="</tbody></table></div></div>";


            document.getElementById('tab_prodotti').style.display='block';
            document.getElementById('tab_prodotti').innerHTML=stringa;
        }
        else {
            document.getElementById('riga_errore').style.display="block";
            document.getElementById('errore').innerHTML=array[0][1]+"!";
            document.body.scrollTop = document.documentElement.scrollTop = 0;
            document.getElementById('tab_prodotti').innerHTML="";
            document.getElementById('tab_prodotti').style.display='none';
        }
    }
}

/* riassortimento.php */
function riassortimentoProdotti(){
    sku=document.getElementById('sku').value;
    if (sku==""){
        document.getElementById('riga_errore').style.display="block";
        document.getElementById('errore').innerHTML="Inserire lo sku!";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('tab_prodotti').style.display="none";
        document.getElementById('tab_prodotti').innerHTML="";
    }

    else {
        document.getElementById('riga_errore').style.display="none";
        document.getElementById('errore').innerHTML="";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('loading').style.display='block';
        var url = "config/recuperaRiassortimento.php?sku="+sku;
        XMLHTTP = RicavaBrowser(riassortimentoProdotti2);
        XMLHTTP.open("GET", url, true);
        XMLHTTP.send(null);
    }
}

function riassortimentoProdotti2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);
        document.getElementById('loading').style.display='none';
        document.getElementById('tab_prodotti').style.display='none';
        if (array[0][0]=="1"){
            stringa="<div class=\"the-box full no-border\"><div class=\"table-responsive\"><table class=\"table table-th-block table-success\" cellpadding=5><thead><tr><th style=\"text-align:left\">Seleziona</th><th style=\"text-align:left\">Nome prodotto</th><th style=\"text-align:left\">Sku prodotto</th><th style=\"text-align:left\">Qta attuale</th><th style=\"text-align:left\">Qta *</th></thead></tr><tbody>";
            stringa+="<tr><td align=left><input type=\"radio\" name=\"check[]\" value=\""+array[0][4]+"\" checked/></td><td align=left>"+array[0][1]+"</td><td align=left>"+array[0][2]+"</td><td align=left>"+array[0][3]+"</td>";
            stringa+="<td align=left><input type=\"text\" name=\"qta_"+array[0][4]+"\" class=\"input-text\" id=\"qta_"+array[0][4]+"\" style=\"width:50px;text-align:left;margin-top:0\"/></td>";
            stringa+="</tr></tbody></table></div></div>";

            document.getElementById('tab_prodotti').style.display='block';
            document.getElementById('tab_prodotti').innerHTML=stringa;
            document.getElementById('bottoni').style.display='block';
            document.getElementById('selezionaTuttoDiv').style.display='none';
        }
        else if (array[0][0]=="2"){
            stringa="<div class=\"the-box full no-border\"><div class=\"table-responsive\"><table class=\"table table-th-block table-success\" cellpadding=5><thead><tr><th style=\"text-align:left\">Seleziona</th><th style=\"text-align:left\">Nome prodotto</th><th style=\"text-align:left\">Sku prodotto</th><th style=\"text-align:left\">Qta attuale</th><th style=\"text-align:left\">Qta *</th></thead></tr><tbody>";
            for (i=0; i<array.length; i++){
                stringa+="<tr><td align=left><input type=\"checkbox\" name=\"check[]\" value=\""+array[i][4]+"\" /></td><td align=left>"+array[i][1]+"</td><td align=left>"+array[i][2]+"</td><td align=left>"+array[i][3]+"</td><td align=left><input type=\"text\" name=\"qta_"+array[i][4]+"\" class=\"input-text\" id=\"qta_"+array[i][4]+"\" style=\"width:50px;text-align:left;margin-top:0\"/></td></tr>";
            }

            stringa+="</tbody></table></div></div>";

            document.getElementById('tab_prodotti').style.display='block';
            document.getElementById('tab_prodotti').innerHTML=stringa;
            document.getElementById('bottoni').style.display='block';
            document.getElementById('selezionaTuttoDiv').style.display='block';
        }
        else {
            document.getElementById('riga_errore').style.display="block";
            document.getElementById('errore').innerHTML=array[0][1]+"!";
            document.body.scrollTop = document.documentElement.scrollTop = 0;
            document.getElementById('tab_prodotti').style.display='none';
            document.getElementById('tab_prodotti').innerHTML="";
            document.getElementById('bottoni').style.display='none';
        }
    }
}


function selezionaTuttoR(){
    check=document.getElementsByName("check[]");
    for (i=0; i<check.length; i++){
        check[i].checked=true;
    }
}

function deselezionaTuttoR(){
    check=document.getElementsByName("check[]");
    for (i=0; i<check.length; i++){
        check[i].checked=false;
    }
}

function controlloFormRiassortimento() {
    errore="";
    flag=true;
    check=document.getElementsByName('check[]');

    flag2=false;
    for (i=0; i<check.length; i++){
        if (check[i].checked==true){
            flag2=true;
            break;
        }
    }

    if (flag2==false){
        errore+="Selezionare almeno un prodotto!<br>";
        flag=false;
    }

    flag3=true;
    for (i=0; i<check.length; i++){
        if (check[i].checked==true){
            value=check[i].value;
            nome="qta_"+value;
            qta=document.getElementById(nome).value;
            if (qta==""){
                flag3=false;
                break;
            }
        }
    }


    if (flag3==false){
        errore+="Quantità non inserite!<br>";
        flag=false;
    }

    flag4=true;
    for (i=0; i<check.length; i++){
        if (check[i].checked==true){
            value=check[i].value;
            nome="qta_"+value;
            qta=document.getElementById(nome).value;
            if (qta!="" && (isNaN(qta) || qta<=0)){
                flag4=false;
                break;
            }
        }
    }

    if (flag4==false){
        errore+="Formati quantità non corretti!<br>";
        flag=false;
    }

    if (flag==false){
        document.getElementById('riga_errore').style.display="block";
        document.getElementById('errore').innerHTML=errore;
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }
    else {
        document.getElementById('riga_errore').style.display="none";
        document.getElementById('errore').innerHTML="";
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById('loading').style.display="block";
    }



    return flag;
}

/* aggiorna-prodotto.php */
function recuperaProdottiAggiorna() {
    sku=document.getElementById('sku').value;
    if (sku==""){
        document.getElementById('riga_errore').style.display='block';
        document.getElementById('errore').innerHTML='Inserire lo sku produttore!';
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }

    else {
        document.getElementById('loading').style.display='block';
        var url = "config/recuperaProdottiAggiorna.php?sku="+sku;
        XMLHTTP = RicavaBrowser(recuperaProdottiAggiorna2);
        XMLHTTP.open("GET", url, true);
        XMLHTTP.send(null);
    }
}

function recuperaProdottiAggiorna2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);
        document.getElementById('loading').style.display='none';
        if (array[0][0]=="0"){
            document.getElementById('tab_prodotti').innerHTML="";
            document.getElementById('tab_prodotti').style.display='none';
            document.getElementById('bottoni').style.display='none';
            document.getElementById('riga_errore').style.display='block';
            document.getElementById('errore').innerHTML=array[0][1]+"!";
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        }
        else {
            document.getElementById('riga_errore').style.display='none';
            document.getElementById('errore').innerHTML="";
            document.getElementById('bottoni').style.display='block';
            stringa="<div class=\"the-box full no-border\"><div class=\"table-responsive\"><table class=\"table table-th-block table-success\" style=\"width:630px\" cellpadding=5><thead><tr><th style=\"text-align:left\">Seleziona</th><th style=\"text-align:left\">Nome prodotto</th><th style=\"text-align:left\">Sku prodotto</th></thead></tr><tbody>";

            for (i=0; i<array.length; i++){
                stringa+="<tr><td align=left><input type='radio' checked name='check' value=\""+array[i][0]+"\" /></td><td align=left>"+array[i][1]+"</td><td align=left>"+array[i][2]+"</td></tr>";
            }
            stringa+="</tbody></table></div></div>";
            document.getElementById('tab_prodotti').style.display='block';
            document.getElementById('tab_prodotti').innerHTML=stringa;
        }
    }
}

/* aggiungi-prodotto-semplice.php */
function controlloFormAggiungiProdottoSemplice(){
    var url = "config/controlloTaglieScalari.php";
    XMLHTTP = RicavaBrowser(controlloFormAggiungiProdottoSemplice2);
    XMLHTTP.open("GET", url, true);
    XMLHTTP.send(null);
}

function controlloFormAggiungiProdottoSemplice2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);
        errore="";
        flag=true;

        if (array[0][0]=="0"){
            errore+="Taglie e scalari non inseriti!";
            flag=false;
        }
        if (flag==false){
            document.getElementById('riga_errore').style.display="block";
            document.getElementById('errore').innerHTML=errore;
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        }
        else {
            flag2=false;
            erroreForm=document.getElementsByClassName("help-block");
            for (i=0; i<erroreForm.length; i++){
                if (erroreForm[i].style.display != "none"){
                    flag2=true;
                    break;
                }
            }
            if (flag2==true){
            }
            else {
                document.getElementById('riga_errore').style.display="none";
                document.getElementById('errore').innerHTML="";
                document.getElementById('loading').style.display="block";
                document.forms['form_prodotto'].submit();
                document.body.scrollTop = document.documentElement.scrollTop = 0;
            }

        }
    }


}


/* modifica-prodotto.php */
function recuperaProdottiVisualizza() {
    sku=document.getElementById('sku').value;
    if (sku==""){
        document.getElementById('riga_errore').style.display='block';
        document.getElementById('errore').innerHTML='Inserire l\'id del prodotto!';
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }

    else {
        document.getElementById('riga_errore').style.display='none';
        document.getElementById('errore').innerHTML='';
        document.getElementById('loading').style.display='block';
        var url = "config/recuperaProdotti.php?sku="+sku;
        XMLHTTP = RicavaBrowser(recuperaProdottiVisualizza2);
        XMLHTTP.open("GET", url, true);
        XMLHTTP.send(null);
    }
}

function recuperaProdottiVisualizza2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);
        document.getElementById('loading').style.display='none';
        if (array[0][0]=="0"){
            document.getElementById('tab_prodotti').innerHTML="";
            document.getElementById('tab_prodotti').style.display='none';
            document.getElementById('bottoni').style.display='none';
            document.getElementById('riga_errore').style.display='block';
            document.getElementById('errore').innerHTML=array[0][1]+'!';
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        }
        else {
            document.getElementById('bottoni').style.display='block';
            stringa="<div class=\"the-box full no-border\"><div class=\"table-responsive\"><table class=\"table table-th-block table-success\" style=\"width:630px\" cellpadding=5><thead><tr><th style=\"text-align:left\">Seleziona</th><th style=\"text-align:left\">Nome prodotto</th><th style=\"text-align:left\">Sku prodotto</th></thead></tr><tbody>";
            for (i=0; i<array.length; i++){
                stringa+="<tr><td align=left><input type='radio' name='check' value=\""+array[i][0]+"\" /></td><td align=left>"+array[i][1]+"</td><td align=left>"+array[i][2]+"</td></tr>";
            }
            stringa+="</tbody></table></div></div>";
            document.getElementById('tab_prodotti').style.display='block';
            document.getElementById('tab_prodotti').innerHTML=stringa;
        }
    }
}


function controlloFormModificaProdotto(){
    errore="";
    flag=true;
    check=document.getElementsByName('check');
    flag2=false;
    for (i=0; i<check.length; i++){
        if (check[i].checked==true){
            flag2=true;
        }
    }

    if (flag2==false){
        errore+="Selezionare almeno un prodotto!";
        flag=false;
    }


    if (flag==false){
        document.getElementById('riga_errore').style.display="block";
        document.getElementById('errore').innerHTML=errore;
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }


    return flag;
}

/* elimina-prodotto.php */
function recuperaProdottiElimina() {
    sku=document.getElementById('sku').value;
    if (sku==""){
        document.getElementById('riga_errore').style.display='block';
        document.getElementById('errore').innerHTML='Inserire lo sku!';
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }

    else {
        document.getElementById('riga_errore').style.display='none';
        document.getElementById('errore').innerHTML="";
        document.getElementById('loading').style.display='block';
        var url = "config/recuperaProdottiElimina.php?sku="+sku;
        XMLHTTP = RicavaBrowser(recuperaProdottiElimina2);
        XMLHTTP.open("GET", url, true);
        XMLHTTP.send(null);
    }
}

function recuperaProdottiElimina2(){
    if (XMLHTTP.readyState == 4)
    {
        array=XMLHTTP.responseText;
        array=eval(array);
        document.getElementById('loading').style.display='none';
        if (array[0][0]=="0"){
            document.getElementById('tab_prodotti').innerHTML="";
            document.getElementById('tab_prodotti').style.display='none';
            document.getElementById('bottoni').style.display='none';
            document.getElementById('riga_errore').style.display='block';
            document.getElementById('errore').innerHTML=array[0][1]+'!';
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        }
        else {
            document.getElementById('riga_errore').style.display='none';
            document.getElementById('errore').innerHTML="";
            document.getElementById('bottoni').style.display='block';
            stringa="<div class=\"the-box full no-border\"><div class=\"table-responsive\"><table class=\"table table-th-block table-success\" style=\"width:750px\" cellpadding=5><thead><tr><th style=\"text-align:left\">Seleziona</th><th style=\"text-align:left\">Nome prodotto</th><th style=\"text-align:left\">Sku prodotto</th><th width=300></th></thead></tr><tbody>";
            for (i=0; i<array.length; i++){
                stringa+="<tr><td align=left><input type='checkbox' name='check[]' value=\""+array[i][0]+"\" /></td><td align=left>"+array[i][1]+"</td><td align=left>"+array[i][2]+"</td>";
                if (array[i][3]=="configurable"){
                    stringa+="<td>Attenzione! Se elimini questo prodotto eliminerai anche tutte le taglie e i scalari</td>";
                }
                else {
                    stringa+="<td></td>";
                }
                stringa+="</tr>";
            }
            stringa+="</table>";
            document.getElementById('tab_prodotti').style.display='block';
            document.getElementById('tab_prodotti').innerHTML=stringa;
        }
    }
}

function selezionaTuttoElimina(){
    check=document.getElementsByName("check[]");
    for (i=0; i<check.length; i++){
        check[i].checked=true;
    }
}

function deselezionaTuttoElimina(){
    check=document.getElementsByName("check[]");
    for (i=0; i<check.length; i++){
        check[i].checked=false;
    }
}

function controlloFormEliminaProdotto(){
    errore="";
    flag=true;
    check=document.getElementsByName('check[]');
    flag2=false;
    for (i=0; i<check.length; i++){
        if (check[i].checked==true){
            flag2=true;
        }
    }

    if (flag2==false){
        errore+="Selezionare almeno un prodotto";
        flag=false;
    }

    if (flag==false){
        document.getElementById('riga_errore').style.display='block';
        document.getElementById('errore').innerHTML=errore+'!';
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }
    else {
        document.getElementById('riga_errore').style.display='none';
        document.getElementById('errore').innerHTML="";
        if (confirm("Sei sicuro di voler eliminare i prodotti selezionati?")){
            document.getElementById('loading').style.display='block';
            document.body.scrollTop = document.documentElement.scrollTop = 0;
            document.forms['form_prodotto'].submit();
        }
    }


    return flag;
}



