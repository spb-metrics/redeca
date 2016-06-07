function pagination(page)
{
	frm = document.getElementById('navigation');
	objPage = document.getElementById('page');
	objPage.value = page;
	frm.submit();
}

/**
 * função para mostrar e ocultar editar de informações complementates
 */
function showOrHideEdit(value, flag)
{
	view = document.getElementById("view"+value);
	edit = document.getElementById("edit"+value);
	
	if(flag == 'view')
	{
		view.style.display="none";
		edit.style.display="block";	
		edit.className="alert";
	}
	else if(flag == 'edit')
	{
		edit.style.display="none";
		view.style.display="block";		
	}
}
/**
 * Verifica se o objeto é do tipo array
 * @param {Object} obj
 */
function isArray(obj) {
   if (obj.constructor.toString().indexOf("Array") == -1)
      return false;
   else
      return true;
}
/**
 * Exibe componentes dado o Id ou um array de Id's
 * @param {Object} arrayObjId
 */
function show(arrayObjId)
{
	if(isArray(arrayObjId))
	{
		for (var i = 0; i < arrayObjId.length; i++) 
		{
			document.getElementById(arrayObjId[i]).style.display = 'inline';
		}
	}
	else
	{
		document.getElementById(arrayObjId).style.display = 'inline';
	}
}
/**
 * Oculta componentes dado o Id ou array de Id's
 * @param {Object} arrayObjId
 */
function hide(arrayObjId)
{
	if(isArray(arrayObjId))
	{
		for (var i = 0; i < arrayObjId.length; i++) 
		{
			document.getElementById(arrayObjId[i]).style.display = 'none';
		}
	}
	else
	{
		document.getElementById(arrayObjId).style.display = 'none';
	}
}

/**
 * Alterna entre exibir/esconder um componente html dado o ID
 */
function switchDisplay(idObj)
{
	display = document.getElementById(idObj).style.display;
	if(display == 'none')
		document.getElementById(idObj).style.display = 'inline';
	else if(display == '' || display == 'inline')
		document.getElementById(idObj).style.display = 'none';
}

/**
 * Essa função tem por finalidade exibir uma tag "div" que escondida ou vice-versa. 
 * 
 * @param {Object} nameDivPrimary : "div" que deve ser "exibido" 
 * @param {Object} nameDivSecondary : "div" que deve ser "escondido"
 */
function showDiv(nameDivPrimary, nameDivSecondary)
{
	var divPrimary = document.getElementById(nameDivPrimary);
	var divSecondary = document.getElementById(nameDivSecondary);

	divPrimary.style.display = "inline";
	divSecondary.style.display = "none";
}
/**
 * Altera o valor action de um Form
 * @param {String} value - Contem o valor da action a ser utilizada 
 * @param {Object} frm - referencia do form que deseja alterar
 */
function actionName(value, frm) 
{	
	frm.action = value;
	return true;
}

/**
 * Exibe ou não a div de emprego para renda
 * @param {String} 	divId 		- id da div que deve ou não ser mostrada.
 * @param {String} 	selectId 	- id do select que será resgatado o valor.
 * @param {Integer} typeEmp 	- numero com o tipo de situação de trabalho.
 */
function showEmployment(divId, selectId, typeEmp)
{
	var divStyle 		= document.getElementById(divId);
	var selectOption 	= document.getElementById(selectId);
	
	var flag = true;
	for(var i=0; i < selectOption.options.length; i++)
	{
		if(selectOption.options[i].selected == true)
		{
			if((selectOption.options[i].value == typeEmp) || (selectOption.options[i].value == ""))
			{
				flag = false;
			}
		}
	}
	
	if(flag == true)
	{
		divStyle.style.display = 'block';
	}
	else
	{
		divStyle.style.display = 'none';
	}
}

/**
 * Seta um valor para o input hidden "checked" no relatório
 * Através deste é possivel saber qual select o usuario clicou pela ultima vez
 * @param {String} num - Contem o valor que será setado no hidden
 * @param {String} id - Contem o id do hidden
 */
function checkSelect(num, id)
{
	var elemHidden = document.getElementById(id);
	elemHidden.value = num;
}

/**
 * Função que envia informações do respectivo form informado
 * 
 * @param {Object} value: corresponde a "action" que deseja disparar
 * @param {Object} idForm: identificação do form a ser enviado 
 */
function submitForm(value, idForm)
{	
	if(idForm != undefined || idForm != null)
	{
		if(value != undefined || value != null)
		{
			var objectForm = document.getElementById(idForm);
			objectForm.action = value;
			objectForm.submit();	
		}
		else
		{
			alert("Ação para o respectivo form inválida");
		}	
	}
	else
	{
		alert("Identificação do form inválida");
	}
}

/**
 * Essa função verifica a quantidade de opções selecionadas pelo usuário em uma tag "select multiple".
 * Retorna FALSE caso usuário tenha selecionado mais de uma opção
 * 
 * Se o usuário selecionou mais de um opção, é retornado falso.
 * Se o usuário selecionou apenas uma opção, o fluxo da action continua conforme parâmetros informados.
 * 
 * @param {Object} urlAction: endereço URL da aplicação a ser chamado após verificação, caso usuário tenha selecionado apenas uma opção.
 * @param {Object} form: nome do form que terá as informações submetidas. 
 * @param {Object} idSelect: identificação da tag "select" que será validado.
 * 
 */
function reportMultipleSelected(urlAction, form, idSelect)
{
	//cria uma variável que recebe informações da tag SELECT.
	var select = document.getElementById(idSelect);

	//cria uma variável do tipo BOOLEANO.
	var verify = new Boolean(false);
	
	//verifica o tamanho do SELECT, e posteriormente faz a iteração no mesmo para ver a quantidade de "OPTION" que o usuário selecionou.
	for(count=0; count < select.options.length; count++)
	{				
		//verifica se "OPTION" foi selecionada pelo usuário.
		if(select.options[count].selected == true)
		{	
			//se "OPTION" foi selecionada setar variável "verify" para TRUE.
			if(verify == false)
			{	
				//seta variável "verify" para TRUE.
				verify = true;
			}
			else
			{					
				//caso usuário tenha selecionado mais de uma OPTION, a mensagem abaixo é exibida para o mesmo.
				alert('Selecione apenas uma opção para ser editada.');
				
				//ao retornar FALSE, a aplicação mantém fluxo na mesma URL chamada. 
				return false;
				
				//pára fluxo da condição FOR.
				break;					
			}				
		}			
	}
	
	//caso o usuário tenha selecionado apenas uma OPTION, a aplicação continua seu fluxo.
	actionName(urlAction, form);
}

/**
 * Mostrar e esconder senha
 */
function showAndHide(objDiv)
{
	pass = document.getElementById("password");
	if(pass.style.display == "block")
	{
		pass.style.display = "none";
	}
	else
	{
		pass.style.display = "block";	
	}
	
	/*
	div = document.getElementById('objDiv');
	if(div != undefined || div != null)
	{
		if(div.style.display == "block")
		{
			div.style.display = "none";
		}
		else
		{
			div.style.display = "block";	
		}	
	}
	*/
}

function setChecked(idElementForm, nameAtribute)
{	
	if (idElementForm != undefined && idElementForm != null) 
	{
		var valParent = document.getElementById(idElementForm).value;
		document.getElementById("elementParent").value = valParent;
	}
}


/**
 * Popula os campos de endereco quando selecionado um dos enderecos do resultado da busca
 * @param {Object} idFormFrom - Id do form da busca de CEP
 * @param {Object} idAddress - id do endereco desejado
 */
function addressHandler(idFormFrom, idAddress)
{
	if(idFormFrom != undefined && idFormFrom != null && idFormFrom != '')
	{
		fields 	= ["_adr_address_type","_adr_id_address","_adr_address", "_adr_zipcode", "_adr_uf", "_adr_city", 
		"_adr_neighborhood"];

		var formFrom = document.getElementById(idFormFrom);
		
		for(var i=0; i <fields.length; i++)
		{
			var fromField = idAddress + fields[i];
			var value=null;
			for(var k = 0; k < document.forms[idFormFrom].elements.length; k++)
			{
				if(document.forms[idFormFrom].elements[k].name == fromField)
					value = document.forms[idFormFrom].elements[k].value;
			}
			forms = parent.document.forms;
			
			for(var j=0; j <forms.length; j++)
			{
				if (forms[j].elements[fields[i]] != undefined) 
				{
					forms[j].elements[fields[i]].value = value;
					// NÃO PERMITE EDIÇÃO DOS CAMPOS PREENCHIDOS AUTOMATICAMENTE
					forms[j].elements[fields[i]].onkeypress=function(){return false;};
//					forms[j].elements[fields[i]].disabled=true;
				}
			}
		}
	}
}

/**
 * Adiciona mais uma linha de cadastro de telefone
 */
var my_div = null;
var newDiv = null;
function addPhone(id, ddd, number, type, option)
{			
	/*var div = document.createElement("div");
	div.setAttribute("id", "divPhone1");
	
	var inputDdd = document.createElement("input");
	inputDdd.setAttribute("name", ddd+"[]");
	inputDdd.setAttribute("size", "5");
	
	var inputNumber = document.createElement("input");
	inputNumber.setAttribute("name", number+"[]");
	inputNumber.setAttribute("size", "15");
	
	var inputId = document.createElement("hidden");
	inputId.setAttribute("name", id+"[]");
	
	var inputType = document.createElement("select");
	inputType.setAttribute("name", type+"[]");
	inputType.setAttribute("width", "15px");
		
	inputType.options[inputType.length] = new Option("Fixo", 1);
	inputType.options[inputType.length] = new Option("Celular", 2);
	inputType.options[inputType.length] = new Option("Fax", 3);
	
	div.appendChild(inputDdd);
	div.appendChild(inputNumber);
	div.appendChild(inputType);
	div.appendChild(inputId);
	
	var father = document.getElementById("divPhone");
	var fatherNode = father.parentNode;
	fatherNode.insertBefore(div, father.nextSibling);*/
	
	document.getElementById("divPhone").innerHTML = "<b>teste</b><input type='text' name'aaaa' value='aaaaa'>";
}
