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
 * @copyright Fundação Telefônica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Araçatuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guarujá - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de São Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de Várzea Paulista - http://www.varzeapaulista.sp.gov.br 
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
	 * Inicialização
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
	 * Exibe formulário e lista todas as classificações cadastradas
	 */
	function indexAction()
	{
		//carrega todas as classificações cadastradas no banco de dados
		$this->view->classifications = ClassificationBusiness::loadAll();
	}
	
	/**
	 * Salva um nova classificação
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
			//valida as informações inseridas no form pelo usuário  
			$errorMessages = ClassificationValidator::validateClassificationAdd($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega variável com a(s) mensagem(ens) de erro de validação
				$this->view->errorMessages = $errorMessages;
				
				//carrega todas as classificações cadastradas no banco de dados
				$this->view->classifications = ClassificationBusiness::loadAll();
				
				//retorna para o template atual exibindo as mensagens de validação
				return;
			}
			
			//converte variáveis do form para objeto do tipo "classification" 
			$classification = $this->assembleFormToClassification($this->view->form);
			
			//persiste as informações da classificação na base de dados
			ClassificationBusiness::save($classification);
			
			//redireciona fluxo da aplicação para página de sucesso
			$this->_redirect(CLASSIFICATION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
		}
	}
	
	/**
	 * Exclui uma classificação cadastrada
	 */
	function dropAction()
	{
		//valida as informações inseridas no form pelo usuário
		$errorMessages = ClassificationValidator::validateClassificationId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			return;
		}

		//converte variáveis do form para objeto do tipo "classification"		
		$classification = $this->assembleFormToClassification($this->view->form);

		//remove as informações da classificação da base de dados
		ClassificationBusiness::drop($classification[ECT_ID_ENTITY_CLASSIFICATION]);
		
		//redireciona fluxo da aplicação para página "index" do perfil 
		//$this->_redirect(CLASSIFICATION_CONTROLLER.'/'.DEFAULT_INDEX_ACTION);
		
		//redireciona fluxo da aplicação para página de sucesso
		$this->_redirect(CLASSIFICATION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe formulário para edição de uma classificação cadastrada
	 */
	function viewAction()
	{
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = ClassificationValidator::validateClassificationId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			
			//carrega todas as classificações cadastradas no banco de dados
			$this->view->classifications = ClassificationBusiness::loadAll();
			
			//retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		//recupera do banco de dados o objeto "Classificação" a ser editado, de acordo com o "id" informado
		$objectClassification = ClassificationBusiness::load($this->view->form->getId());
				
		//converte o objeto "Classificação" retornado para variáveis do form
		$this->assembleClassificationToForm($objectClassification);
		
		//carrega novamente todas as classificações cadastradas na base de dados
		$this->view->classifications = ClassificationBusiness::loadAll();
	}
	
	/**
	 * Salva uma classificação editada
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
			//valida as informações inseridas no form pelo usuário  
			$errorMessages = ClassificationValidator::validateClassification($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega variável com a(s) mensagem(ens) de erro de validação
				$this->view->errorMessages = $errorMessages;
				
				//carrega todas as classificações cadastradas no banco de dados
				$this->view->classifications = ClassificationBusiness::loadAll();
				
				//Retorna para o template atual exibindo as mensagens de validação
				return;
			}
			
			//converte variáveis do form para objeto do tipo "classification" 
			$classification = $this->assembleFormToClassification($this->view->form);
			
			//persiste as informações da classificação na base de dados
			ClassificationBusiness::save($classification);
			
			//redireciona fluxo da aplicação para página de sucesso
			$this->_redirect(CLASSIFICATION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
		}
	}
	
	/**
	 * Exibe a tela de confirmação de exclusão de uma classificação
	 * 
	 */
	function confirmAction()
	{
		//action a ser chamada caso o usuário confirma a exclusão 
		$this->view->action = DEFAULT_DROP_ACTION;
		
		//verifica se o "id" informado pelo usuário é válido
		$errorMessages = ClassificationValidator::validateClassificationId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		//carrega as classificações a serem excluídas
		$this->view->objectClassifications = ClassificationBusiness::load($this->view->form->getId());		
	}
	
	/**
	 * Recupera as informações do form e retorna no array
	 */	
	function assembleFormToClassification(ClassificationForm $frm)
	{	
		if(!Utils::isEmpty($frm))
		{
			//cria uma variável array de nome "classification" 
			$classification = array();
		
			if(Utils::isEmpty($frm->getId()))
			{
				$classification[ECT_ID_ENTITY_CLASSIFICATION] = null;
			}
			else
			{
				//adiciona uma variável - id - do form no array "classification"
				$classification[ECT_ID_ENTITY_CLASSIFICATION] = $frm->getId();	
			}
			
			if(Utils::isEmpty($frm->getClassificationName()))
			{
				//adiciona uma variável - classificationName - do form no array "classification"
				$classification[ECT_ENTITY_CLASSIFICATION] = null;
			}			
			else
			{
				//adiciona uma variável - classificationName - do form no array "classification"
				$classification[ECT_ENTITY_CLASSIFICATION] = $frm->getClassificationName();				
			}
			
			//adiciona uma variável - status - do form no array "classification"
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
		
		//seta no form o "id" da "Classificação"
		$this->view->form->setId($classification->{ECT_ID_ENTITY_CLASSIFICATION});
		
		//seta no form o nome da "Classificação"
		$this->view->form->setClassificationName($classification->{ECT_ENTITY_CLASSIFICATION});
		
		//seta no form o status da "Classificação"
		$this->view->form->setStatus($classification->{ECT_STATUS});
	}
	
	//redireciona aplicação para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
}