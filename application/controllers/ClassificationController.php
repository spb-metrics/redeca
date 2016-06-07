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
 * Fabricio Meireles Monteiro  - W3S		    	05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class ClassificationController extends BasicController
{
	/**
	 * Inicializa��o
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Classification');
		parent::setControllerHelp('Classification');
		
		Zend_Loader::loadClass('ClassificationForm');
		Zend_Loader::loadClass('ClassificationBusiness');
		Zend_Loader::loadClass('EntityClassification');
		Zend_Loader::loadClass('EntityClassificationType');
		Zend_Loader::loadClass('ClassificationValidator');
		Zend_Loader::loadClass('Utils');
		
		$frm = new ClassificationForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
	}
	
	/**
	 * Exibe formul�rio e lista todas as classifica��es cadastradas
	 */
	function indexAction()
	{
		//carrega todas as classifica��es cadastradas no banco de dados
		$this->view->classifications = ClassificationBusiness::loadAll();
	}
	
	/**
	 * Salva um nova classifica��o
	 */
	function addAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = ClassificationValidator::validateClassificationAdd($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega todas as classifica��es cadastradas no banco de dados
				$this->view->classifications = ClassificationBusiness::loadAll();
				
				//retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "classification" 
			$classification = $this->assembleFormToClassification($this->view->form);
			
			//persiste as informa��es da classifica��o na base de dados
			ClassificationBusiness::save($classification);
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(CLASSIFICATION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
		}
	}
	
	/**
	 * Exclui uma classifica��o cadastrada
	 */
	function dropAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio
		$errorMessages = ClassificationValidator::validateClassificationId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			return;
		}

		//converte vari�veis do form para objeto do tipo "classification"		
		$classification = $this->assembleFormToClassification($this->view->form);

		//remove as informa��es da classifica��o da base de dados
		ClassificationBusiness::drop($classification[ECT_ID_ENTITY_CLASSIFICATION]);
		
		//redireciona fluxo da aplica��o para p�gina "index" do perfil 
		//$this->_redirect(CLASSIFICATION_CONTROLLER.'/'.DEFAULT_INDEX_ACTION);
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(CLASSIFICATION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe formul�rio para edi��o de uma classifica��o cadastrada
	 */
	function viewAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = ClassificationValidator::validateClassificationId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//carrega todas as classifica��es cadastradas no banco de dados
			$this->view->classifications = ClassificationBusiness::loadAll();
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//recupera do banco de dados o objeto "Classifica��o" a ser editado, de acordo com o "id" informado
		$objectClassification = ClassificationBusiness::load($this->view->form->getId());
				
		//converte o objeto "Classifica��o" retornado para vari�veis do form
		$this->assembleClassificationToForm($objectClassification);
		
		//carrega novamente todas as classifica��es cadastradas na base de dados
		$this->view->classifications = ClassificationBusiness::loadAll();
	}
	
	/**
	 * Salva uma classifica��o editada
	 */
	function editAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{		
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = ClassificationValidator::validateClassification($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega todas as classifica��es cadastradas no banco de dados
				$this->view->classifications = ClassificationBusiness::loadAll();
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "classification" 
			$classification = $this->assembleFormToClassification($this->view->form);
			
			//persiste as informa��es da classifica��o na base de dados
			ClassificationBusiness::save($classification);
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(CLASSIFICATION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
		}
	}
	
	/**
	 * Exibe a tela de confirma��o de exclus�o de uma classifica��o
	 * 
	 */
	function confirmAction()
	{
		//action a ser chamada caso o usu�rio confirma a exclus�o 
		$this->view->action = DEFAULT_DROP_ACTION;
		
		//verifica se o "id" informado pelo usu�rio � v�lido
		$errorMessages = ClassificationValidator::validateClassificationId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		//carrega as classifica��es a serem exclu�das
		$this->view->objectClassifications = ClassificationBusiness::load($this->view->form->getId());		
	}
	
	/**
	 * Recupera as informa��es do form e retorna no array
	 */	
	function assembleFormToClassification(ClassificationForm $frm)
	{	
		if(!Utils::isEmpty($frm))
		{
			//cria uma vari�vel array de nome "classification" 
			$classification = array();
		
			if(Utils::isEmpty($frm->getId()))
			{
				$classification[ECT_ID_ENTITY_CLASSIFICATION] = null;
			}
			else
			{
				//adiciona uma vari�vel - id - do form no array "classification"
				$classification[ECT_ID_ENTITY_CLASSIFICATION] = $frm->getId();	
			}
			
			if(Utils::isEmpty($frm->getClassificationName()))
			{
				//adiciona uma vari�vel - classificationName - do form no array "classification"
				$classification[ECT_ENTITY_CLASSIFICATION] = null;
			}			
			else
			{
				//adiciona uma vari�vel - classificationName - do form no array "classification"
				$classification[ECT_ENTITY_CLASSIFICATION] = $frm->getClassificationName();				
			}
			
			//adiciona uma vari�vel - status - do form no array "classification"
			$classification[ECT_STATUS] = $frm->getStatus();
			
			return $classification;
		}
		return null;
	}
	
	/**
	 * Converte objeto retornado para o form 
	 */
	function assembleClassificationToForm($classification)
	{	
		//se lista retornou mais de um objeto, pegar somente o primeiro objeto da lista
		foreach($classification as $object)
		{
			$classification = $object;
			break;
		}
		
		//seta no form o "id" da "Classifica��o"
		$this->view->form->setId($classification->{ECT_ID_ENTITY_CLASSIFICATION});
		
		//seta no form o nome da "Classifica��o"
		$this->view->form->setClassificationName($classification->{ECT_ENTITY_CLASSIFICATION});
		
		//seta no form o status da "Classifica��o"
		$this->view->form->setStatus($classification->{ECT_STATUS});
	}
	
	//redireciona aplica��o para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
}