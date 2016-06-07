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
 * Fabricio Meireles Monteiro  - W3S		   	 	05/05/2008	                       Create file 
 * 
 */

require_once('BasicController.php');

class EntityInitialController extends BasicController
{
	/**
	 * Inicializa��o
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Entity');
		parent::setControllerHelp('EntityInitial');
		
		Zend_Loader::loadClass('Program');
		Zend_Loader::loadClass('ProgramBusiness');
		Zend_Loader::loadClass('AreaBusiness');
		Zend_Loader::loadClass('ClassificationBusiness');
		Zend_Loader::loadClass('Entity');
		Zend_Loader::loadClass('EntityForm');
		Zend_Loader::loadClass('UserForm');
		Zend_Loader::loadClass('EntityBusiness');
		Zend_Loader::loadClass('UserBusiness');
		Zend_Loader::loadClass('EntityValidator');
		Zend_Loader::loadClass('UserValidator');
		Zend_Loader::loadClass('EntityClassification');
		Zend_Loader::loadClass('EntityArea');
		Zend_Loader::loadClass('GroupBusiness');
		
		// Form para a busca de Endere�o
		Zend_Loader::loadClass('SearchAddressForm');
		
		// EntityForm
		$frmEntity = new EntityForm();
		$frmEntity->assembleRequest($this->_request);
		$this->view->entityForm = $frmEntity;

		// UserForm
		$frmUser = new UserForm();
		$frmUser->assembleRequest($this->_request);
		$this->view->userForm = $frmUser;
		// Setado para suprir funcionalidade de busca de Endere�os
		$this->view->form = $frmEntity;
	}
	
	function indexAction()
	{
		;
	}
	
	/**
	 * Exibe formul�rio para cria��o de pr� cadastro de entidade
	 */
	function initialNewAction()
	{
		//carrega todos os tipos de programas dispon�veis na rede
		$this->view->allTypeProgram = ProgramBusiness::loadAllTypeProgram();
		
		//carrega todas as �reas de atua��o dispon�veis na rede
		$this->view->allAreas = AreaBusiness::loadAllEnable();
		
		//carrega todos os tipos de classifica��o dispon�veis na rede
		$this->view->allClassifications = ClassificationBusiness::loadAllEnable();
		
		// carrega todos os grupo disponiveis na rede
		$this->view->groups = GroupBusiness::loadAllNotDisable();
	}
	
	/**
	 * Salva os dados do pr�-cadastro (cadastro)
	 */
	function initialAddAction()
	{	
		$errorMessages = null;
		
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = UserValidator::validateUserData($this->view->userForm);
		UserValidator::validateUserEqualCpf($this->view->userForm, $errorMessages);
		UserValidator::validateUserPassword($this->view->userForm, $errorMessages);
		EntityValidator::validateEntityName($this->view->entityForm, $errorMessages);
		EntityValidator::validateProgramId($this->view->entityForm, $errorMessages);
		EntityValidator::validateClassificationId($this->view->entityForm, $errorMessages);
		EntityValidator::validateAreaId($this->view->entityForm, $errorMessages);
		EntityValidator::validateCnpj($this->view->entityForm, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//carrega todos os tipos de programas dispon�veis na rede
			$this->view->allTypeProgram = ProgramBusiness::loadAllTypeProgram();
			
			//carrega todas as �reas de atua��o dispon�veis na rede
			$this->view->allAreas = AreaBusiness::loadAllEnable();
			
			//carrega todos os tipos de classifica��o dispon�veis na rede
			$this->view->allClassifications = ClassificationBusiness::loadAllEnable();
			
			// carrega todos os grupo disponiveis na rede
			$this->view->groups = GroupBusiness::loadAllNotDisable();
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//converte vari�veis do form para seus respectivos objetos: entidade ou usu�rio 
		$bean = $this->assembleFormToBean($this->view->entityForm, $this->view->userForm);
		
		//persiste as informa��es do perfil na base de dados
		EntityBusiness::save($bean);
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(ENTITYINITIAL_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Recupera as informa��es do form e retorna no array
	 */	
	function assembleFormToBean(EntityForm $entityForm, UserForm $userForm)
	{	
		if(!Utils::isEmpty($entityForm) && !Utils::isEmpty($userForm))
		{
			$bean = array();
			
			//dados da entidade
			$bean[0] = $entityForm->getEntityName();
			$bean[6] = $entityForm->getEntityCnpj();
			
			$bean[PROGRAM_TYPE] 		= $entityForm->getProgramId();
			$bean[CLASSIFICATION_TYPE]	= $entityForm->getClassificationId();
			$bean[AREA_TYPE]			= $entityForm->getAreaId();
			$bean[GROUP_TYPE]			= $entityForm->getGroupEntity();

			//dados do usu�rio coordenador			
			$bean[1] = $userForm->getName();
			$bean[2] = $userForm->getCpf();
			$bean[3] = $userForm->getLogin();
			$bean[4] = $userForm->getPassword();
			$bean[5] = $userForm->getEmail();
					
			return $bean;
		}
		
		return null;		
	}
	
	/**
	 * Redireciona aplica��o para tela de sucesso do respectivo controller
	 */
	function successAction()
	{
		;
	}
}