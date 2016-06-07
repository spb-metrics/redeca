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
 * Fabricio Meireles Monteiro  - W3S		   		05/05/2008	                       Create file 
 * 
 */
require_once('BasicController.php');

class ResidenceController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Residence','PostalCode');
		parent::setControllerHelp('Residence');
		
		Zend_Loader::loadClass(CLS_PERSON);
		Zend_Loader::loadClass(CLS_FAMILYRESIDENCE);
		Zend_Loader::loadClass(CLS_RESIDENCE);
		Zend_Loader::loadClass(CLS_LOCALITYTYPE);
		Zend_Loader::loadClass(CLS_MORADATYPE);
		Zend_Loader::loadClass(CLS_STATUSTYPE);
		Zend_Loader::loadClass(CLS_BUILDINGTYPE);
		Zend_Loader::loadClass(CLS_SUPPLYTYPE);
		Zend_Loader::loadClass(CLS_WATERTYPE);
		Zend_Loader::loadClass(CLS_ILLUMINATIONTYPE);
		Zend_Loader::loadClass(CLS_SANITARYTYPE);
		Zend_Loader::loadClass(CLS_TRASHTYPE);
		Zend_Loader::loadClass(CLS_TELEPHONETYPE);
		Zend_Loader::loadClass(CLS_ADDRESSTYPE);
		Zend_Loader::loadClass(CLS_PERSONTELEPHONE);
		Zend_Loader::loadClass(CLS_PERSONCHANGEHISTORY);
		Zend_Loader::loadClass(CLS_FAMILY);
		Zend_Loader::loadClass(CLS_UBS);
		Zend_Loader::loadClass(CLS_COVERAGEADDRESS);
		Zend_Loader::loadClass('ResidenceForm');
		Zend_Loader::loadClass('ResidenceBusiness');
		Zend_Loader::loadClass('FamilyBusiness');
		Zend_Loader::loadClass('FamilyResidenceBusiness');
		Zend_Loader::loadClass('PersonBusiness');
		Zend_Loader::loadClass('PersonForm');
		Zend_Loader::loadClass('PersonValidator');
		Zend_Loader::loadClass('ResidenceValidator');
		Zend_Loader::loadClass('TelephoneBusiness');
		Zend_Loader::loadClass('HistoryBusiness');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('Resource');
		
		$frm				= new ResidenceForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
		
		$this->view->ubs 			= ResidenceBusiness::loadAllUbs();
		$this->view->locality 		= ResidenceBusiness::loadLocality();
		$this->view->morada 		= ResidenceBusiness::loadMorada();
		$this->view->status			= ResidenceBusiness::loadStatus();
		$this->view->building		= ResidenceBusiness::loadBuilding();
		$this->view->supply 		= ResidenceBusiness::loadSupply();
		$this->view->water 			= ResidenceBusiness::loadWater();
		$this->view->illumination 	= ResidenceBusiness::loadIllumination();
		$this->view->sanitary 		= ResidenceBusiness::loadSanitary();
		$this->view->trash 			= ResidenceBusiness::loadTrash();
		$this->view->phoneType		= TelephoneBusiness::loadAllType();
		$this->view->operator		= UserLogged::isOperator();
	}
	
	/**
	 * Exibe as informações de Moradia
	 */
	function indexAction()
	{		
		//resgata parametro id do form
		$idPerson = $this->_request->getParam($this->view->form->id());
		if(empty($idPerson))
			$idPerson = $this->_request->getParam($this->view->form->personId());

		$this->view->form->setPersonId($idPerson);
		$this->view->form->setId($idPerson);				
		$errorMessages = PersonValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		if($idPerson)
		{				
			// resgata do banco a pessoa de acordo com o id			
			$resPerson = PersonBusiness::load($idPerson);
			
			// resgata family e phone de determinada pessoa
			foreach($resPerson as $prs){
				$family = $prs->findDependentRowset(CLS_FAMILY);
				$phone = $prs->findDependentRowset(CLS_PERSONTELEPHONE);
			}
			if(count($family) > 0)
			{
				// resgata familyId de determinada pessoa
				foreach($family as $fam)
					$familyId = $fam->findParentRow(CLS_FAMILY_ID);
				
				// resgata familyResidence de determinada pessoa
				$familyResidence = $familyId->findDependentRowset(CLS_FAMILYRESIDENCE);
				
				// resgata residence de determinada pessoa
				foreach($familyResidence as $famResidence){
					$residence = $famResidence->findParentRow(CLS_RESIDENCE);
					
					if(!$residence->{RES_STATUS}){			
						// seta no form as informações referentes a residencia
						$this->view->form->assembleResidenceToForm($residence);						
						
						// resgata e seta informações de um endereço			
						$this->view->address = $residence->findParentRow(CLS_ADDRESS);
						
						// resgata o id de UBS
						$ubs = ResidenceBusiness::loadUbs($residence->{RES_ID_RESIDENCE});
						foreach($ubs as $idUbs)
							$this->view->form->setUbs($idUbs->{CAD_ID_UBS});
					}
				}
				// seta informações de telefone
				$this->view->phone = $phone;
			}
			else
			{
				$this->view->address = true;
			}
		}
		
		//funcionalidade somente-leitura?
		if(ResourcePermission::isResourceReadOnly($this->_request))
		{
			$this->view->readOnly = TRUE;
		}
		else
		{
			$this->view->readOnly = FALSE;
		}
	}
	
	/**
	 * Popula o container
	 */
	function containerAction()
	{
		$this->indexAction();
	}
	
	/**
	 * Exibe formulário para edição de moradia
	 */
	function viewAction()
	{
		//resgata parametro id do form
		$idPerson = $this->_request->getParam($this->view->form->id());
		
		$errorMessages = PersonValidator::validatePersonId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		if($idPerson)
		{
			$this->view->form->setId($idPerson);
			
			// resgata do banco a pessoa de acordo com o id			
			$resPerson = PersonBusiness::load($idPerson);
			
			// resgata family e phone de determinada pessoa
			foreach($resPerson as $prs){
				$family = $prs->findDependentRowset(CLS_FAMILY);
				$personPhone = $prs->findDependentRowset(CLS_PERSONTELEPHONE);
			}
			
			// resgata familyId de determinada pessoa
			foreach($family as $fam)
				$familyId = $fam->findParentRow(CLS_FAMILY_ID);
				
			// resgata familyResidence de determinada pessoa
			$familyResidence = $familyId->findDependentRowset(CLS_FAMILYRESIDENCE);
			
			// resgata residence de determinada pessoa
			foreach($familyResidence as $famResidence){
				$residence = $famResidence->findParentRow(CLS_RESIDENCE);
				
				if(!$residence->{RES_STATUS}){
					// seta no form as informações referentes a residencia
					$this->view->form->assembleResidenceToForm($residence);
					
					// resgata e seta informações de um endereço			
					$address = $residence->findParentRow(CLS_ADDRESS);
					if(!is_null($address)){
						$adrType = $address->findParentRow(CLS_ADDRESSTYPE);
						$adrNbh = $address->findParentRow(CLS_NEIGHBORHOOD);
						$adrCity = $adrNbh->findParentRow(CLS_CITY);
						$adrUf = $adrCity->findParentRow(CLS_UF);
						$this->view->form->setAdrAddressType($adrType->{ADT_DESCRIPTION});
						$this->view->form->setAdrAddress($address->{ADR_ADDRESS});
						$this->view->form->setAdrIdAddress($address->{ADR_ID_ADDRESS});
						$this->view->form->setAdrZipcode($address->{ADR_ZIP_CODE});
						$this->view->form->setAdrNeighborhood($adrNbh->{NHD_NEIGHBORHOOD});
						$this->view->form->setAdrCity($adrCity->{CTY_CITY});
						$this->view->form->setAdrUf($adrUf->{UF_ABBREVIATION});
						$this->view->form->setAdrComplement($residence->{RES_COMPLEMENT});
						$this->view->form->setAdrNumber($residence->{RES_NUMBER});
						$this->view->form->setAdrReference($residence->{RES_REFERENCE_POINT});
					}
					
					// resgata o id de UBS
					$ubs = ResidenceBusiness::loadUbs($residence->{RES_ID_RESIDENCE});
					foreach($ubs as $idUbs)
						$this->view->form->setUbs($idUbs->{CAD_ID_UBS});
				}
			}
						
			// separa as informações contidas em telefone
			foreach($personPhone as $phone){	
				$tel = $phone->findParentRow(CLS_TELEPHONENUMBER);
				$ddd[] = $tel->{TNB_DDD};
				$number[] = $tel->{TNB_NUMBER};
				$type[] = $tel->{TNB_ID_TELEPHONE_TYPE};
				$id[] = $tel->{TNB_ID_TELEPHONE_NUMBER};
			}
			
			// seta informações de telefone
			$this->view->form->setDdd($ddd);
			$this->view->form->setPhone($number);
			$this->view->form->setPhoneType($type);
			$this->view->form->setIdPhone($id);
			
			//funcionalidade somente-leitura?
			if(ResourcePermission::isResourceReadOnly($this->_request))
			{
				$this->view->readOnly = TRUE;
			}
			else
			{
				$this->view->readOnly = FALSE;
			}
		}
	}
	
	/**
	 * Salva dados da moradia (edição)
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
			$errorMessages = ResidenceValidator::validateResidenceEdit($this->view->form);
			if(sizeof($errorMessages) > 0)
			{
				$this->view->errorMessages = $errorMessages;
				return;
			}
			
			$residence = ResidenceForm::assembleFormToResidence($this->view->form);						
			
			$idResidence = ResidenceBusiness::save($residence, $this->view->form->getPersonId());
			if($residence[RES_ID_RESIDENCE] == false)
			{				
				$idFamily = FamilyBusiness::loadFamilyByIdPersonAndIdFamily($this->view->form->getPersonId(), null);
				$family[FRS_ID_FAMILY] = $idFamily->current()->{FAM_ID_FAMILY};
				$family[FRS_ID_RESIDENCE] = $idResidence;				
				FamilyResidenceBusiness::save($family);
			}
			if($this->view->form->getUbs())
			{				
				$coverageAddress[CAD_ID_RESIDENCE] = $idResidence;
				$coverageAddress[CAD_ID_UBS] = $this->view->form->getUbs();							
				ResidenceBusiness::saveUbs($coverageAddress); 
			}
			
			$telephone = ResidenceForm::assembleFormToTelephone($this->view->form);
			self::telephoneManager($telephone);
			
			$this->_redirect(RESIDENCE_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.ResidenceForm::id().'/'.$this->view->form->getPersonId());
		}		
	}
	
	/**
	 * Salva dados do telefone
	 */
	function telephoneAction()
	{
		$errorMessages = ResidenceValidator::validateResidenceTelephone($this->view->form);		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$telephone = ResidenceForm::assembleFormToTelephone($this->view->form);
		self::telephoneManager($telephone);
	}
	
	function successAction()
	{
		$errorMessages = PersonValidator::validatePersonId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$id = $this->_request->getParam($this->view->form->id());
		$this->view->form->setId($id);
	}
	
	function telephoneManager($telephone)
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{			
			if(!is_null($telephone))
			{
				$prsPhone[PRT_ID_PERSON] = $this->view->form->getId();
				foreach($telephone as $tel)
				{
					if($tel[TNB_DDD] && $tel[TNB_NUMBER])
					{
						$id = TelephoneBusiness::save($tel);
					}
					
					if($id)
					{						
						$prsPhone[PRT_ID_TELEPHONE] = $id;						
						ResidenceBusiness::savePersonPhone($prsPhone);
						$id = null;
					}
					else
					{											
						if($tel[TNB_ID_TELEPHONE_NUMBER])
						{
							$prsPhone[PRT_ID_TELEPHONE] = $tel[TNB_ID_TELEPHONE_NUMBER];
							ResidenceBusiness::savePersonPhone($prsPhone, true);
						}
					}
				}
			}
		}
	}
}