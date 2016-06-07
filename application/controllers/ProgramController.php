<?php
/*
 * This program is free software: you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation, either version 2 of the License, or 
 * (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details. 
 *
 * You should have received a copy of the GNU General Public License 
 * along with this program.  If not, see <http://www.gnu.org/licenses/>. 
 * 
 * @copyright Funda��o Telef�nica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Ara�atuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guaruj� - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de S�o Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de V�rzea Paulista - http://www.varzeapaulista.sp.gov.br 
 * 
 * @copyright Copyright (C) 2008
 * 
 * @license GNU General Public License (GPL) - http://www.gnu.org/licenses/gpl.html 
 * 
 * @author Consulting services for Social Networks Creation by Instituto Fonte para o Desenvolvimento Social  - < fonte@fonte.org.br> - http:// www.fonte.org.br 
 * 
 * @author Consulting services for Software Requirements  by WebUse - <webuse@webuse.com.br > - http://webuse.com.br 
 * 
 * @author Initial Software development by W3S Solutions - <w3s@w3s.com.br> - http://w3s.com.br 
 * 
 * Changelog
 * 
 * Author                                           Date                               History 
 * -----------------------------------------        ------------                       ------------------ 
 * Fabricio Meireles Monteiro  - W3S		   		05/05/2008	                       Create file 
 * 
 */
require_once('BasicController.php');

class ProgramController extends BasicController
{
	/**
	 * Inicializa��o
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Program');
		parent::setControllerHelp('Program');
		
		Zend_Loader::loadClass('ProgramForm');
		Zend_Loader::loadClass('ProgramBusiness');
		Zend_Loader::loadClass('ProgramType');
		Zend_Loader::loadClass('TargetMarket');
		Zend_Loader::loadClass('ProgramValidator');
		Zend_Loader::loadClass('Utils');
		
		$frm = new ProgramForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
	}
	
	/**
	 * Exibe formul�rio e lista todos os tipos de programas cadastrados
	 */
	function indexAction()
	{
		//carrega todos os tipos de programa cadastrados no banco de dados
		$this->view->programs = ProgramBusiness::loadAllTypeProgramDisable();
		
		//carrega todas as op��es de p�blico alvo
		$this->view->targetPublic = ProgramBusiness::loadAllTargetPublic();
	}
	
	/**
	 * Salva um novo tipo de programa
	 */
	function addAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = ProgramValidator::validateProgram($this->view->form);
		ProgramValidator::validateProgramEqualName($this->view->form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//carrega todos os tipos de programa cadastrados no banco de dados
			$this->view->programs = ProgramBusiness::loadAllTypeProgramDisable();
			
			//carrega todas as op��es de p�blico alvo
			$this->view->targetPublic = ProgramBusiness::loadAllTargetPublic();
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//converte vari�veis do form para objeto do tipo "program" 
		$program = $this->assembleFormToProgram($this->view->form);
		
		//persiste as informa��es do perfil na base de dados
		ProgramBusiness::save($program);
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(PROGRAM_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe a tela de confirma��o de exclus�o de um programa
	 * 
	 */
	function confirmAction()
	{
		//action a ser chamada caso o usu�rio confirma a exclus�o 
		$this->view->action = DEFAULT_DROP_ACTION;
		
		//verifica se o "id" informado pelo usu�rio � v�lido
		$errorMessages = ProgramValidator::validateProgramId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		//carrega os programas a serem exclu�dos
		$this->view->objectPrograms = ProgramBusiness::loadDisable($this->view->form->getId());			
	}
	
	/**
	 * Exclui um tipo de programa cadastrado
	 */
	function dropAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio
		$errorMessages = ProgramValidator::validateProgramId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//carrega todos os tipos de programa cadastrados no banco de dados
			$this->view->programs = ProgramBusiness::loadAllTypeProgramDisable();
			
			//carrega todas as op��es de p�blico alvo
			$this->view->targetPublic = ProgramBusiness::loadAllTargetPublic();
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}

		//converte vari�veis do form para objeto "program"		
		$program = $this->assembleFormToProgram($this->view->form);
		
		//remove as informa��es do "program" da base de dados
		ProgramBusiness::drop($program[PGT_ID_PROGRAM_TYPE]);
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(PROGRAM_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe formul�rio para edi��o de um tipo de programa cadastrado
	 */
	function viewAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = ProgramValidator::validateProgramId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//carrega todos os tipos de programa cadastrados no banco de dados
			$this->view->programs = ProgramBusiness::loadAllTypeProgramDisable();
			
			//carrega todas as op��es de p�blico alvo
			$this->view->targetPublic = ProgramBusiness::loadAllTargetPublic();
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//recupera do banco de dados o objeto "Program" a ser editado, de acordo com o "id" informado
		$objectProgram = ProgramBusiness::loadDisable($this->view->form->getId());
		
		//converte o objeto "Program" vari�veis do tipo form
		$this->assembleProgramToForm($objectProgram);
		
		//carrega todos os tipos de programa cadastrados no banco de dados
		$this->view->programs = ProgramBusiness::loadAllTypeProgramDisable();
			
		//carrega todas as op��es de p�blico alvo
		$this->view->targetPublic = ProgramBusiness::loadAllTargetPublic();
	}
	
	/**
	 * Salva um tipo de programa editado
	 */
	function editAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = ProgramValidator::validateProgram($this->view->form);
		ProgramValidator::validateProgramId($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//carrega todos os tipos de programa cadastrados no banco de dados
			$this->view->programs = ProgramBusiness::loadAllTypeProgramDisable();
			
			//carrega todas as op��es de p�blico alvo
			$this->view->targetPublic = ProgramBusiness::loadAllTargetPublic();
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//converte vari�veis do form para objeto do tipo "program" 
		$program = $this->assembleFormToProgram($this->view->form);
		
		//persiste as informa��es do perfil na base de dados
		ProgramBusiness::save($program);
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(PROGRAM_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Recupera as informa��es do form e retorna no array
	 */	
	function assembleFormToProgram(ProgramForm $frm)
	{	
		if(!Utils::isEmpty($frm))
		{	
			//cria uma vari�vel array de nome "program" 
			$program = array();
		
			if(Utils::isEmpty($frm->getId()))
			{
				$program[PGT_ID_PROGRAM_TYPE] = null;
			}
			else
			{
				$program[PGT_ID_PROGRAM_TYPE] = $frm->getId();
			}
			
			if(Utils::isEmpty($frm->getProgramName()))
			{
				$program[PGT_PROGRAM_TYPE] = null;
			}
			else
			{
				$program[PGT_PROGRAM_TYPE] = $frm->getProgramName();
			}
			
			$program[PGT_ID_TARGET_MARKET] = $frm->getIdTargetPublic();
			
			$program[PGT_STATUS] = $frm->getStatus();
		
			return $program;
		}
		
		return null;		
	}
	
	/**
	 * Converte objeto retornado para o form 
	 */
	function assembleProgramToForm($programType)
	{	
		if(!Utils::isEmpty($programType))
		{
			//se lista retornou mais de um objeto, pegar somente o primeiro objeto da lista
			foreach($programType as $uniqueObject)
			{
				$programType = $uniqueObject;
				break;
			}
			
			//seta no form o nome do "Tipo de Programa"
			$this->view->form->setId($programType->{PGT_ID_PROGRAM_TYPE});
			
			//seta no form o "id" do "Tipo de Programa"
			$this->view->form->setProgramName($programType->{PGT_PROGRAM_TYPE});
			
			//seta no form o status do "Tipo de Programa"
			$this->view->form->setStatus($programType->{PGT_STATUS});
			
			if(count($programType) > 0)
			{
				//busca no objeto "Tipo de Programa" carregado o seu respectivo relacionamento com a tabela "P�blico Alvo"
				$idTargetPublic = $programType->findParentRow(CLS_TARGETMARKET);
				
				//seta no form o "id" do "P�blico Alvo"
				$this->view->form->setIdTargetPublic($idTargetPublic -> {TMK_ID_TARGET_MARKET});
			}
		}
	}
	
	//redireciona aplica��o para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
}