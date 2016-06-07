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

class AssociateEntityController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('AssociateEntity');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('EntityGroupForm');
		Zend_Loader::loadClass('EntityGroupValidator');
		Zend_Loader::loadClass('EntityBusiness');
		
		$form = new EntityGroupForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
	}

	/**
	 * Redireciona para formulário para desagrupar/agrupar entidades
	 */
	function indexAction()
	{
		$this->_redirect(ASSOCIATE_ENTITY_CONTROLLER.'/'.DEFAULT_VIEW_ACTION);
	}
	
	/**
	 * Visualiza formulário para desagrupar/agrupar entidades
	 */
	function viewAction()
	{
		$form = $this->view->form;

		if($form->getEntityId() != false)
			$this->view->entitiesGroup = EntityBusiness::loadGroupByEntity($form->getEntityId());

		$this->view->entities 		= EntityBusiness::loadAll();
		$this->view->entityTypes 	= EntityBusiness::loadAllTypes();
	}
	
	/**
	 * Agrupa uma ou mais entidades à entidade principal
	 */
	function groupAction()
	{
		$form = $this->view->form;
		$errorMessages = NULL;
		EntityGroupValidator::validateEntityData($form, $errorMessages);
		if(count($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;

			$this->view->entities 		= EntityBusiness::loadAll();
			$this->view->entityTypes 	= EntityBusiness::loadAllTypes();
			
			return;
		}
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->beginTransaction();
		EntityBusiness::dropGroupByEntity($form->getEntityId(), $db);
		EntityBusiness::saveAllGroup($this->assemble($form), $db);
		$db->commit();
		$db->closeConnection();

		$this->_redirect(ASSOCIATE_ENTITY_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	/**
	 * Exibe tela de sucesso
	 */
	public function successAction()
	{
		;
	}

	private function assemble(EntityGroupForm &$form)
	{
		$data=NULL;
		if(!empty($form))
		{
			Zend_Loader::loadClass(EntityGroup);
			if(is_array($form->getEntityTypeId()))
			{
				foreach($form->getEntityTypeId() as $type)
				{
					$entityGrroup[ENG_ID_ENTITY] 		= $this->getValue($form->getEntityId());
					$entityGrroup[ENG_ID_ENTITY_GROUP] 	= $this->getValue($type);
					$data[] = $entityGrroup;
				}
			}
			else
			{
				$entityGrroup[ENG_ID_ENTITY] 		= $this->getValue($form->getEntityId());
				$entityGrroup[ENG_ID_ENTITY_GROUP] 	= $this->getValue($form->getEntityTypeId());
				$data[] = $entityGrroup;
			}
		}
		return $data;
	}
	
	private function getValue($value, $default = NULL)
	{
		if(Utils::isEmpty($value))
			return $default;
		else
			return $value;
	}
}