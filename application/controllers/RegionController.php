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
 * Jefferson Barros Lima  - W3S		   				05/05/2008	                       Create file 
 * 
 */
require_once('BasicController.php');

class RegionController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Region');
		parent::setControllerHelp('Region');
		
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('Region');
		Zend_Loader::loadClass('RegionBusiness');
		Zend_Loader::loadClass('UFBusiness');
		Zend_Loader::loadClass('CityBusiness');
		Zend_Loader::loadClass('NeighborhoodBusiness');
		Zend_Loader::loadClass('RegionForm');
		Zend_Loader::loadClass('RegionValidator');
		Zend_Loader::loadClass('NeighborhoodRegionBusiness');
		Zend_Loader::loadClass('NeighborhoodRegion');
		
		$frm				= new RegionForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
	}
	
	/**
	 * Lista todas as regiões cadastradas no sistema
	 */
	function indexAction()
	{
		$this->view->regions 		= RegionBusiness::loadAll();		
	}
	
	/**
	 * Exibe formulário para criação de uma nova região
	 */
	function newAction()
	{
		$this->view->state 			= UFBusiness::loadAll();
	}
	
	function cityAction()
	{
		$errorMessages = RegionValidator::validateRegionState($this->view->form);
		if(count($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->view->state 			= UFBusiness::loadAll();
			return;
		}
		$result						= UFBusiness::load($this->view->form->getState())->current();
		$this->view->state 			= UFBusiness::loadAll();
		$this->view->city 			= $result->findDependentRowset(CLS_CITY);
	}
	
	function neighborhoodAction()
	{
		$errorMessages = RegionValidator::validateRegionState($this->view->form);
		RegionValidator::validateRegionCity($this->view->form, $errorMessages);
		if(count($errorMessages) > 0)
		{
			$this->view->errorMessages 	= $errorMessages;			
			$this->view->state 			= UFBusiness::loadAll();
			if(!$errorMessages[$this->view->form->state()])
			{
				$result					= UFBusiness::load($this->view->form->getState())->current();
				$this->view->city 		= $result->findDependentRowset(CLS_CITY);
			}
			return;
		}
		$result						= UFBusiness::load($this->view->form->getState())->current();		
		$this->view->state 			= UFBusiness::loadAll();
		$this->view->city 			= $result->findDependentRowset(CLS_CITY);	
		$this->view->neighborhoods 	= NeighborhoodBusiness::findByCity($this->view->form->getCity());
	}
	
	/**
	 * Exibe formulário para edição de uma região
	 */
	function viewAction()
	{
		$frm = $this->view->form;
		
		$this->view->region = RegionBusiness::find($frm->getIdRegion());
		
		try
		{
			if($this->view->region == null)
			{	
				throw new Zend_Exception(BasicBusiness::getLabelResources()->region->load->fail);
			}
			else
			{	
				$neighborhood = $this->view->region->findManyToManyRowset(CLS_NEIGHBORHOOD, CLS_NEIGHBORHOODREGION)->current();
				
				if($neighborhood != null && sizeof($neighborhood) > 0)
				{
					$city = $neighborhood->findParentRow(CLS_CITY);
					
					if($city != null && sizeof($city) > 0)
					{
						$uf = $city->findParentRow(CLS_UF)->{UF_ABBREVIATION};
						
						if($uf != null && sizeof($uf) > 0)
						{
							$this->view->form->setState($uf);		
						}
						else
						{
							throw new Zend_Exception(BasicBusiness::getLabelResources()->uf->load->fail);
						}
					}
					else
					{
						throw new Zend_Exception(BasicBusiness::getLabelResources()->city->load->fail);
					}
					
					$this->view->city	= $neighborhood->findParentRow(CLS_CITY)->{CTY_CITY};	
					$idCity				= $neighborhood->findParentRow(CLS_CITY)->{CTY_ID_CITY};
					
					if(is_null($idCity))
					{
						throw new Zend_Exception(BasicBusiness::getLabelResources()->city->load->fail);
					}
					else
					{
						$this->view->neighborhoods 	= NeighborhoodBusiness::findByCity($idCity);				
					}
				}
				else
				{	
					throw new Zend_Exception(BasicBusiness::getLabelResources()->neighborhood->load->fail);
				}
			}
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError($e->getMessage());
			
			//seta mensagem de erro e retorna fluxo da aplicação para "view.phtml"
			$this->view->errorMessages 	= $e->getMessage();
			return;
			
			trigger_error($e->getMessage(), E_USER_ERROR);
		}	
	}
		
	/**
	 * Salva nova região (cadastro)
	 */
	function addAction()
	{
		$frm = $this->view->form;
		$errorMessages = RegionValidator::validateInsert($frm);
		RegionValidator::validateRegionNeighborhood($frm, $errorMessages);
		RegionValidator::validateEqualName($frm, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages 	= $errorMessages;			
			$this->view->state 			= UFBusiness::loadAll();
			if(!$errorMessages[$this->view->form->state()])
			{
				$result					= UFBusiness::load($this->view->form->getState())->current();
				$this->view->city 		= $result->findDependentRowset(CLS_CITY);
				if(!$errorMessages[$this->view->form->city()])
				{
					$this->view->neighborhoods 	= NeighborhoodBusiness::findByCity($this->view->form->getCity());
				}
			}
			return;
		}
		
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->beginTransaction();

		$frm->setIdRegion( RegionBusiness::save($this->assembleRegion($frm), $db) );
		NeighborhoodRegionBusiness::saveAll($this->assembleNeighborhoodRegion($frm), $db);

		$db->commit();
		$db->closeConnection();

		$this->_redirect(REGION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Salva região (edição)
	 */
	function editAction()
	{
		$frm = $this->view->form;

		$errorMessages = RegionValidator::validateEdit($frm);
		RegionValidator::validateRegionNeighborhood($frm, $errorMessages);
		RegionValidator::validateEqualEditName($frm, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages 	= $errorMessages;			
			if(!$errorMessages[$this->view->form->idRegion()])
			{
				$this->view->region 		= RegionBusiness::find($frm->getIdRegion());				
				$uf 						= $this->view->region->findManyToManyRowset(CLS_NEIGHBORHOOD, CLS_NEIGHBORHOODREGION)->current()->findParentRow(CLS_CITY)->findParentRow(CLS_UF)->{UF_ABBREVIATION};
				$this->view->form->setState($uf);
				$this->view->city			= $this->view->region->findManyToManyRowset(CLS_NEIGHBORHOOD, CLS_NEIGHBORHOODREGION)->current()->findParentRow(CLS_CITY)->{CTY_CITY};
				
				$idCity 					= $this->view->region->findManyToManyRowset(CLS_NEIGHBORHOOD, CLS_NEIGHBORHOODREGION)->current()->findParentRow(CLS_CITY)->{CTY_ID_CITY};
				$this->view->form->setCity($idCity);
				
				$this->view->neighborhoods 	= NeighborhoodBusiness::findByCity($idCity);
			}
			return;
		}

		NeighborhoodRegionBusiness::delete($frm->getIdRegion());
		RegionBusiness::save($this->assembleRegion($frm));
		NeighborhoodRegionBusiness::saveAll($this->assembleNeighborhoodRegion($frm));

		$this->_redirect(REGION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Remove uma região
	 */
	function dropAction()
	{
		$frm = $this->view->form;		
		$errorMessages = RegionValidator::validateIdRegion($frm);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages 	= $errorMessages;
			$this->view->regions 		= RegionBusiness::loadAll();
			return;
		}
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->beginTransaction();

		NeighborhoodRegionBusiness::delete($frm->getIdRegion(), $db);
		RegionBusiness::delete($frm->getIdRegion(), $db);
		
		$db->commit();
		$db->closeConnection();
		
		$this->_redirect(REGION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Confirma se região será excluida
	 */
	function confirmAction()
	{
		$frm = $this->view->form;
		
		// Verifica se o "id" informado pelo usuário é válido
		$errorMessages = RegionValidator::validateIdRegion($frm);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			$this->view->regions 		= RegionBusiness::loadAll();
			
			$this->_redirect(REGION_CONTROLLER.'/'.DEFAULT_DROP_ACTION);
			return;
		}
		$this->view->region 		= RegionBusiness::find($frm->getIdRegion());
	}
	
	function successAction()
	{
		;
	}

	private function assembleRegion(RegionForm $frm)
	{
		if(!empty($frm))
		{
			$region[RGN_ID_REGION]	= $this->getValue($frm->getIdRegion());
			$region[RGN_REGION]		= $this->getValue($frm->getRegion());

			return $region;
		}
		return NULL;
	}

	private function assembleNeighborhoodRegion(RegionForm $frm)
	{
		if(!empty($frm))
		{
			if(is_array($frm->getNeighborhood()))
			{
				$nbh = NULL;
				foreach($frm->getNeighborhood() as $current)
				{
					$nbhRegion[NHR_ID_REGION]		= $this->getValue($frm->getIdRegion());
					$nbhRegion[NHR_ID_NEIGHBORHOOD]	= $this->getValue($current);
					$nbh[] = $nbhRegion;
				}
				return $nbh;
			}
			else
			{
				$nbhRegion[NHR_ID_REGION]		= $this->getValue($frm->getIdRegion());
				$nbhRegion[NHR_ID_NEIGHBORHOOD]	= $this->getValue($frm->getNeighborhood());
				return $nbhRegion;
			}
		}
		return NULL;
	}
	
	private function getValue($value, $default = NULL)
	{
		if(Utils::isEmpty($value))
			return $default;
		else
			return $value;
	}
}