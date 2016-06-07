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
 * Lucas dos Santos Borges Corr�a  - W3S		    05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class AreaController extends BasicController
{
	/**
	 * Inicializa��o
	 */
	function init()
	{
		parent::init();
		
		Zend_Loader::loadClass('EntityAreaType');
		Zend_Loader::loadClass('AreaForm');
		Zend_Loader::loadClass('AreaValidator');
		Zend_Loader::loadClass('AreaBusiness');
		
		$frm = new AreaForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
		
		$this->view->areas = AreaBusiness::loadAll();
		
		parent::setControllerResources('Area');
		parent::setControllerHelp('Area');
	}
	
	/**
	 * Exibe formul�rio e lista todas as �reas de atua��o cadastradas
	 */
	function indexAction()
	{
		;
	}
	
	/**
	 * Salva um nova �rea de atua��o
	 */
	function addAction()
	{	
		$errorMessages = AreaValidator::validateArea($this->view->form);
		AreaValidator::validateAreaEqualName($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$area = AreaForm::assembleFormToArea($this->view->form);
		AreaBusiness::save($area);
		$this->_redirect(AREA_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe a tela de confirma��o de exclus�o de uma area
	 * 
	 */
	function confirmAction()
	{
		$errorMessages = AreaValidator::validateAreaIdRequired($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$result = AreaBusiness::load($this->view->form->getId());
		$this->view->result = $result;
	}

	/**
	 * Exclui uma �rea de atua��o cadastrada
	 */
	function dropAction()
	{
		$errorMessages 	= AreaValidator::validateAreaIdRequired($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages 	= $errorMessages;
			return;
		}
		AreaBusiness::drop($this->view->form->getId());
		$this->_redirect(AREA_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Visualiza uma �rea de atua��o editada
	 */
	function viewAction()
	{	
		$errorMessages = AreaValidator::validateAreaIdRequired($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$result = AreaBusiness::load($this->view->form->getId());
		AreaForm::assembleAreaToForm($result);
	}
	
	/**
	 * Salva um nova �rea de atua��o editada
	 */
	function editAction()
	{	
		$errorMessages = AreaValidator::validateAreaIdRequired($this->view->form); 
		AreaValidator::validateAreaData($this->view->form, $errorMessages);
		AreaValidator::validateAreaEditEqualName($this->view->form, $errorMessages);
		AreaValidator::validateStatus($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$area = AreaForm::assembleFormToArea($this->view->form);
		AreaBusiness::save($area);
		$this->_redirect(AREA_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	function successAction()
	{
		;
	}
}