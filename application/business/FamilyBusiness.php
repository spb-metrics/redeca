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

class FamilyBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $family - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($family, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Family();
			$rows = count($obj->find($family[FAM_ID_FAMILY], $family[FAM_ID_PERSON], $family[FAM_ID_KINSHIP]));
			if($rows == 0)
			{
				$id = $obj->insert($family);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_FAMILY .' [id='.$id.']');
			}
			else
			{
				$where = $obj->getAdapter()->quoteInto(FAM_ID_FAMILY.' = ?', $family[FAM_ID_FAMILY]);
				
				$id = $obj->update($family, $where);
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_FAMILY.
					' ['.FAM_ID_FAMILY.'='.$family[FAM_ID_FAMILY].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{			
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->family->save->fail, E_USER_ERROR);
		}
	}
	
	public static function assembleObjectFamily($bean, &$db=null)
	{
		$of = array();
		$of[FAM_ID_PERSON]	= $bean[FAM_ID_PERSON];
		$of[FAM_ID_FAMILY]	= $bean[FAM_ID_FAMILY];
		$of[FAM_ID_KINSHIP]	= $bean[FAM_ID_KINSHIP];
		
		self::saveFamilyByPerson($of, $db);
	}
	
	
	/**
	 * Persiste informações no DB 
	 * @param Array $family - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function saveFamilyByPerson($family, &$db=null)
	{	
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Family();
			
			$where[] = $obj->getAdapter()->quoteInto(FAM_ID_PERSON.' = ?', $family[FAM_ID_PERSON]);
			$allObj = $obj->fetchAll($where);			
			
			$rows = count($allObj);
			
			if($rows == 0)
			{
				$id = $obj->insert($family);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_FAMILY .' [id='.$id.']');
			}
			else
			{						
				$famObj[FAM_ID_KINSHIP] = $family[FAM_ID_KINSHIP];
				$famObj[FAM_ID_FAMILY] = $family[FAM_ID_FAMILY];
				$id = $obj->update($famObj, $where);
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_FAMILY.
					' ['.FAM_ID_FAMILY.'='.$family[FAM_ID_FAMILY].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->family->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Persiste um novo id de familia na tabela fam_family_id
	 */
	public static function saveFamilyId(&$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			$obj = new FamilyId();
			
			$id = $obj->insert();
			Logger::loggerOperation('Registro inserido na tabela '. TBL_FAMILY_ID .' [id='.$id.']');

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->family->save->fail, E_USER_ERROR);
		}
	}
	
	public static function loadFamilyRelationship($idPerson)
	{
		Zend_Loader::loadClass('Family');
		$objFamily = self::loadFamilyByIdPersonAndIdFamily($idPerson, null);		
		
		if(sizeof($objFamily) > 0)
		{
			$idFamilyReference =  $objFamily->current()->{FAM_ID_FAMILY}; 
		
			if($idFamilyReference != null)
			{
				return self::loadFamilyByIdFamily($idFamilyReference);	
			}
			else
			{
				trigger_error(parent::getLabelResources()->notFamilyAssociated->load->fail, E_USER_ERROR);
			}	
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * Carrega do banco as informações familiares de um id específico 
	 */
	public static function loadFamilyByIdFamily($idFamily)
	{	
		try
		{	
			$type = new FamilyId();
			
			if(!empty($idFamily))
			{	
				//busca na tabela família "fam_family_id" a linha referente ao "idFamily" 			
				$where = $type->getAdapter()->quoteInto(FID_ID_FAMILY.' = ?', $idFamily);
				
				$row = $type->fetchAll($where);
				
				return $row;
			}
			
			Logger::loggerOperation('Nenhum registro encontrado para o id de família '.$idFamily.' = '.implode(',' ,$idFamily));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->familyId->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega do banco as informações familiares dados os parâmetros da pessoa e família 
	 */
	public static function loadFamilyByIdPersonAndIdFamily($idPerson = null, $idFamily = null)
	{	
		try
		{	
			$type = new Family();
			
			if(!empty($idPerson) || !empty($idFamily))
			{	
				if($idPerson !=  null)
				{
					//busca na tabela família "fam_family" a linha referente ao "idPerson" 			
					$where[] = $type->getAdapter()->quoteInto(FAM_ID_PERSON.' = ?', $idPerson);
				}
				
				if($idFamily != null)
				{
					//busca na tabela família "fam_family" a linha referente ao "idFamily" 			
					$where[] = $type->getAdapter()->quoteInto(FAM_ID_FAMILY.' = ?', $idFamily);
				}
				
				$row = $type->fetchAll($where);
				
				return $row;
			}
			
			Logger::loggerOperation('Nenhum registro encontrado para a pessoa de id = '.$idPerson.' = '.implode(',' ,$idPerson));
			return;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->family->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os registros da tabela "fam_kinship_type" - Tipo de relação familiar
	 * 
	 */
	public static function loadAllKinshipType()
	{	
		$table	= new KinshipType();
	
		try
		{	
			$where = $table->getAdapter()->quoteInto(KST_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, KST_KINSHIP);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->kinship->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadKinshipType($id)
	{	
		$table	= new KinshipType();
	
		try
		{	
			$where[] = $table->getAdapter()->quoteInto(KST_ID_KINSHIP.' in (?)', $id);
			$where[] = $table->getAdapter()->quoteInto(KST_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchRow($where);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->kinship->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Persite as informações de representante familiar
	 */
	public static function saveRepresentative($idPerson, $idKinshipType, $db = null)
	{	
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{	
			$isAdult = Utils::isAdult($idPerson);
			
			if($isAdult)
			{	
				$objectRepresentative = array();
				$objectRepresentative[REP_ID_PERSON] = $idPerson;
				
				$objFamily = self::loadFamilyByIdPersonAndIdFamily($idPerson, null);
				
				if(sizeof($objFamily) <= 0)
				{
					if($idKinshipType != null)
					{
						$newIdFamily = self::saveFamilyId($db);
						
						if($newIdFamily != null)
						{
							$objectFamily = array();
							$objectFamily[FAM_ID_FAMILY]	= $newIdFamily;
							$objectFamily[FAM_ID_PERSON]	= $idPerson;
							$objectFamily[FAM_ID_KINSHIP]	= $idKinshipType;
							
							//persiste uma nova família na tabela "fam_family"
							self::save($objectFamily, $db);
							
							$objectRepresentative[REP_ID_FAMILY] = $newIdFamily;
						}
					}
					else
					{
						trigger_error(parent::getLabelResources()->failKinship->fail, E_USER_ERROR);
					}	
				}
				else
				{	
					if(sizeof($objFamily) == 1)
					{
						$uniqueFamily = $objFamily->current();
						
						$idFamily = $uniqueFamily->{FAM_ID_FAMILY};
						$objectRepresentative[REP_ID_FAMILY] = $idFamily;
						
						$uniqueIdFamily = $uniqueFamily->findParentRow(CLS_FAMILY_ID);
						
						$collRepresentative = $uniqueIdFamily->findDependentRowset(CLS_REPRESENTATIVE);
						foreach($collRepresentative as $or)
						{	
							$objectRepresentative[REP_ID_REPRESENTATIVE] = $or->{REP_ID_REPRESENTATIVE};
						} 	
					}
					else
					{
						trigger_error(parent::getLabelResources()->familyUnique->fail, E_USER_ERROR);
					}
				}	
				
				//persiste um novo representante na tabela "fam_representative"
				RepresentativeBusiness::save($objectRepresentative, $db);
			}
			else
			{
				trigger_error(parent::getLabelResources()->age->invalid, E_USER_ERROR);
			}
			
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{	
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->businessRelationshipFamily->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Exclui um ou mais registros da tabela Família
	 */
	public static function drop($idFamily = null, $idPerson = null, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$objTable = new Family();
			$res = 0;
			
			if(!is_null($idPerson) || !empty($idPerson) && !is_null($idFamily) || !empty($idFamily))
			{
				$where[] = $objTable->getAdapter()->quoteInto(FAM_ID_FAMILY.' = ?', $idFamily);
				$where[] = $objTable->getAdapter()->quoteInto(FAM_ID_PERSON.' = ?', $idPerson);
				
				$res = $objTable->delete($where);
				if($mt) $db->commit();
			}
			if($res > 0)
				Logger::loggerOperation('O relacionamento familiar com a pessoa de id = '.$idPerson.' foi excluído.');
			else
				Logger::loggerOperation('Nenhum registro foi excluído');
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->family->drop->fail, E_USER_ERROR);
		}
	}
}