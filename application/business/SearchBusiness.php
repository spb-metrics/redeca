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
 * Jefferson Barros Lima  - W3S		    			05/03/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class SearchBusiness extends BasicBusiness
{	
	/**
	 * Procura na tabela per_document usando equal
	 * 
	 */
	public static function searchDocumentEqual($type, $number)
	{
		$obj = new Document();
		try
		{
			$where = $obj->getAdapter()->quoteInto($type.' = ?',$number);
			$res= $obj->fetchAll($where);
			return $res;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Procura na tabela per_document usando like
	 * 
	 */
	public static function searchDocumentLike($type, $number)
	{
		$obj = new Document();
		try
		{
			$where = $obj->getAdapter()->quoteInto($type.' LIKE ?',$number.'%');
			$res= $obj->fetchAll($where);
			return $res;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Procura na tabela per_ctps usando equal
	 * 
	 */
	public static function searchCtpsEqual($type, $number)
	{
		$obj = new Ctps();
		try
		{
			$where = $obj->getAdapter()->quoteInto($type.' = ?',$number);
			$res= $obj->fetchAll($where);
			return $res;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Procura na tabela per_ctps usando like
	 * 
	 */
	public static function searchCtpsLike($type, $number)
	{
		$obj = new Ctps();
		try
		{
			$where = $obj->getAdapter()->quoteInto($type.' LIKE ?',$number.'%');
			$res= $obj->fetchAll($where);
			return $res;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Procurar nas tabelas per_document e per_ctps
	 */
	public static function searchAllDocuments($number)
	{
		$db = Zend_Registry::get(DB_CONNECTION);		
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		try
		{
			$select = $db->select()->from(TBL_DOCUMENT);
			$select->orWhere(DOC_RG_NUMBER.' LIKE ?', $number.'%');
			$select->orWhere(DOC_CPF.' LIKE ?',$number.'%');
			$select->orWhere(DOC_TITLE_NUMBER.' LIKE ?',$number.'%');
			$select->orWhere(DOC_SUS_CARD.' LIKE ?',$number.'%');
			$select->orWhere(DOC_NIS.' LIKE ?',$number.'%');
			$document = $db->fetchAll($select);
			
			foreach($document as $doc){
				$all[] = $doc;
				$prsId[] = $doc->{DOC_ID_PERSON};
			}
			
			$select = $db->select()->from(TBL_CTPS);
			$select->orWhere(CTS_NUMBER.' LIKE ?', $number.'%');
			$ctps = $db->fetchAll($select);
						
			$i = 0;
			foreach($ctps as $cts){
				$key = array_search($cts->{CTS_ID_PERSON}, $prsId);
				if($key != null) $all[] = $cts;
				$key = null;
			}
			
			return $all; 
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->additional->load->fail, E_USER_ERROR);
		}
	}
	
	public static function searchPeopleOfFamily($idFamily)
	{
		$obj = new Family();
		try
		{
			$where = $obj->getAdapter()->quoteInto(FAM_ID_FAMILY.' = ?', $idFamily);
			$family = $obj->fetchAll($where);
			
			return $family;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->additional->load->fail, E_USER_ERROR);
		}
	}
	
	public static function searchPerson($frm)
	{	
		$config = Zend_Registry::get(CONFIG);
		
		$obj = new Person();
		
		try
		{	
			if($frm->getPrsName()){
				$metaname = MetaPhoneClass::getMetaPhone($frm->getPrsName());
				
				if(strlen($metaname) > 0 )
				{	
					$arrayMetaname = explode($config->metaname->delimiter, $metaname);
					
					foreach($arrayMetaname as $unique)
					{	
						if(strlen($unique) > 0)
						{
							$array[] = $unique;
						}
					}			
	
					if(sizeof($array) > 0)
					{
						if($frm->getPrsNickname())
						{
							$parenthesis = '((';
						}
						
						$where = $obj->getAdapter()->quoteInto($parenthesis.PRS_METANAME.' LIKE ?','%'.$config->metaname->delimiter.$array[0].$config->metaname->delimiter.'%');
						
						if(sizeof($array) > 1)
						{	
							for($index = 1; $index < sizeof($array); $index ++)
							{
								$where .= $obj->getAdapter()->quoteInto(' AND '.PRS_METANAME.' LIKE ?','%'.$config->metaname->delimiter.$array[$index].$config->metaname->delimiter.'%');
							}
							if($frm->getPrsNickname())
							{
								$where .= ')';
							}
						}
						else
						{
							if($frm->getPrsNickname())
							{
								$where .= ')';
							}	
						}		
					}
				}
				else
				{
					$where = $obj->getAdapter()->quoteInto(PRS_METANAME.' = ?',$metaname);
				}
			}	
			
			if($frm->getPrsNickname()){
				$metanickname = MetaPhoneClass::getMetaPhone($frm->getPrsNickname());
				
				if(strlen($metanickname) > 0 )
				{	
					$arrayMetaNickname = explode($config->metaname->delimiter, $metanickname);
				
					foreach($arrayMetaNickname as $unique)
					{	
						if(strlen($unique) > 0)
						{
							$arrayNick[] = $unique;
						}
					}
					
					if(sizeof($arrayNick) > 0)
					{	
						if($where)
						{
							$where .= $obj->getAdapter()->quoteInto(' OR '.PRS_METANICKNAME.' LIKE ?','%'.$config->metaname->delimiter.$arrayNick[0].$config->metaname->delimiter.'%');	
							
							if(sizeof($arrayNick) > 1)
							{
								for($iterator = 1; $iterator < sizeof($arrayNick); $iterator ++)
								{	
									$where .= $obj->getAdapter()->quoteInto(' AND '.PRS_METANICKNAME.' LIKE ?','%'.$config->metaname->delimiter.$arrayNick[$iterator].$config->metaname->delimiter.'%');
								}
							}	
							$where .= ')';
							
						}
						else
						{
							$where = $obj->getAdapter()->quoteInto(PRS_METANICKNAME.' LIKE ?','%'.$config->metaname->delimiter.$arrayNick[0].$config->metaname->delimiter.'%');
							
							if(sizeof($arrayNick) > 1)
							{
								for($iterator = 1; $iterator < sizeof($arrayNick); $iterator ++)
								{
									$where .= $obj->getAdapter()->quoteInto(' AND '.PRS_METANICKNAME.' LIKE ?','%'.$config->metaname->delimiter.$arrayNick[$iterator].$config->metaname->delimiter.'%');
								}
							}
						}
					}
					else
					{
						if($where)
						{
							$where .= $obj->getAdapter()->quoteInto(' OR '.PRS_METANICKNAME.' = ?',$metanickname);
							$where .= ')';
						}
						else
						{
							$where = $obj->getAdapter()->quoteInto(PRS_METANICKNAME.' = ?',$metanickname);
						}
					}
				}
			}
			
			if($where){
				if($frm->getPrsSex())
					$where .= $obj->getAdapter()->quoteInto(' AND '.PRS_SEX.' = ?',$frm->getPrsSex());
							
				if($frm->getPrsFirstAge() && $frm->getPrsSecondAge()){
					$firstYear = date('Y') - $frm->getPrsFirstAge();
					$secondYear =  date('Y') - $frm->getPrsSecondAge();
					
					$firstYear = $firstYear.'-00-00';
					$secondYear = $secondYear.'-00-00';
					
					$where .= $obj->getAdapter()->quoteInto(' AND '.PRS_BIRTH_DATE.' < ?',$firstYear);
					$where .= $obj->getAdapter()->quoteInto(' AND '.PRS_BIRTH_DATE.' > ?',$secondYear);
				}
			}
			
			$person = $obj->fetchAll($where);
			
			if($frm->getPrsRegion())
				foreach($person as $prs){
					$family = $prs->findDependentRowset(CLS_FAMILY);
					foreach($family as $fam)				
						$familyId = $fam->findParentRow(CLS_FAMILY_ID);
					unset($family);
					
					$familyResidence = $familyId->findDependentRowset(CLS_FAMILYRESIDENCE);
					foreach($familyResidence as $famRes)
						$residence = $famRes->findParentRow(CLS_RESIDENCE);
					unset($familyResidence);
														
					$address = $residence->findParentRow(CLS_ADDRESS);
					unset($residence);
																
					$neighborhood = NeighborhoodBusiness::findById($address->{ADR_ID_ADDRESS});
					unset($address);
										
					foreach($neighborhood as $nbh)
						$neighborhoodRegion = $nbh->findDependentRowset(CLS_NEIGHBORHOODREGION);
					unset($neighborhood);
										
					foreach($neighborhoodRegion as $nhr)						
						if($nhr->{NHR_ID_REGION} == $frm->getPrsRegion())							
							$result[] = $prs;					
					unset($neighborhoodRegion);
				}
			else
				$result = $person;
			
			return $result;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->additional->load->fail, E_USER_ERROR);
		}
	}
}