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
 * Jefferson Barros Lima  - W3S		    			26/03/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class TelephoneBusiness extends BasicBusiness
{
	
	public static function save($phone, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new TelephoneNumber();
			
			if($phone[TNB_ID_TELEPHONE_NUMBER] == false)
			{ 
				$obj->insert($phone);
				$id = $db->lastInsertId(TBL_TELEPHONE_NUMBER);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_TELEPHONE_NUMBER .' [id='.$id.']');
			}
			else
			{				
				$where = $obj->getAdapter()->quoteInto(TNB_ID_TELEPHONE_NUMBER.' = ?', $phone[TNB_ID_TELEPHONE_NUMBER]);				
				$obj->update($phone, $where);
				$id = $phone[TNB_ID_TELEPHONE_NUMBER];
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_TELEPHONE_NUMBER.
					' ['.TNB_ID_TELEPHONE_NUMBER.'='.$phone[TNB_ID_TELEPHONE_NUMBER].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->phone->save->fail, E_USER_ERROR);
		}
	}
	
	public static function drop($idPhone, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new TelephoneNumber();
			if($idPhone)
			{ 
				$where = $obj->getAdapter()->quoteInto(TNB_ID_TELEPHONE_NUMBER.' in (?)', $idPhone);
				$obj->delete($where);
				Logger::loggerOperation('Registro modificado na tabela '.TBL_TELEPHONE_NUMBER.
					' ['.TNB_ID_TELEPHONE_NUMBER.'='.$idPhone.']');
			}

			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->phone->save->fail, E_USER_ERROR);
		}
	}
	
	public static function dropEntityPhoneByEntity($idEntity, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new EntityTelephone();
			if($idEntity)
			{ 
				$where = $obj->getAdapter()->quoteInto(ETP_ID_ENTITY.' = ?', $idEntity);
				$obj->delete($where);
				Logger::loggerOperation('Registro modificado na tabela '.TBL_TELEPHONE_NUMBER.
					' ['.TNB_ID_TELEPHONE_NUMBER.'='.$idEntity.']');
			}

			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->phone->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Persiste registros nas tabelas TelephoneNumber e EntityTelephone
	 */
	public static function saveAllEntityPhones($phones, $idEntity, &$db=null)
	{
		$affected = 0;
		if(!empty($phones) && !empty($idEntity))
		{
			foreach($phones as $phone)
			{
				$phone[TNB_ID_TELEPHONE_NUMBER] = null;
				if($phone[TNB_NUMBER] && $phone[TNB_ID_TELEPHONE_TYPE] && $phone[TNB_DDD])
				{
					$idPhone = self::save($phone, $db);
					$entityPhone[ETP_ID_ENTITY] 	= $idEntity;
					$entityPhone[ETP_ID_TELEPHONE] 	= $idPhone;
					self::saveEntityPhone($entityPhone, $db);
					$affected++;
				}
			}
		}
		return $affected;
	}
	
	public static function saveEntityPhone($phone, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new EntityTelephone();
			$rows = count($obj->find($phone[ETP_ID_ENTITY], $phone[ETP_ID_TELEPHONE]));

			if($rows == 0)
			{ 
				$id = $obj->insert($phone);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_ENTITY_TELEPHONE .' [id='.$id.']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(ETP_ID_ENTITY.' = ?', $phone[ETP_ID_ENTITY]);
				$where[] = $obj->getAdapter()->quoteInto(ETP_ID_TELEPHONE.' = ?', $phone[ETP_ID_TELEPHONE]);
				$obj->update($phone, $where);
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_ENTITY_TELEPHONE.
					' ['.ETP_ID_ENTITY.'='.$phone[ETP_ID_ENTITY].']'.
					' ['.ETP_ID_TELEPHONE.'='.$phone[ETP_ID_TELEPHONE].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->phone->save->fail, E_USER_ERROR);
		}
	}


	public static function savePersonPhone($personPhone, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new PersonTelephone();
			$rows = count($obj->find($personPhone[PRT_ID_PERSON], $personPhone[PRT_ID_TELEPHONE]));
			
			if($rows == 0)
			{ 
				$id = $obj->insert($personPhone);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_PERSON_TELEPHONE .' [id='.$id.']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(PRT_ID_PERSON.' = ?', $personPhone[PRT_ID_PERSON]);
				$where[] = $obj->getAdapter()->quoteInto(PRT_ID_TELEPHONE.' = ?', $personPhone[PRT_ID_TELEPHONE]);
				
				$id = $obj->update($personPhone, $where);
				Logger::loggerOperation('Registro modificado na tabela '.TBL_TELEPHONE_NUMBER.
					' ['.TNB_ID_TELEPHONE_NUMBER.'='.$personPhone[TNB_ID_TELEPHONE_NUMBER].']');
			}

			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->phone->save->fail, E_USER_ERROR);
		}
	}

	public static function loadAll()
	{
		try
		{
			$obj = new TelephoneNumber();
			$rows = $obj->fetchAll();

			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->phone->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadByNumber($number)
	{
		Zend_Loader::loadClass('TelephoneNumber');
		try
		{
			$obj = new TelephoneNumber();
			
			$where = $obj->getAdapter()->quoteInto(TNB_NUMBER.' = ?', $number);
			$rows = $obj->fetchAll($where);

			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->phone->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadAllType()
	{
		Zend_Loader::loadClass('TelephoneType');
		try
		{
			$obj = new TelephoneType();
			$where = $obj->getAdapter()->quoteInto(TTP_STATUS.' not in (?)', Constants::DISABLE);
			$rows = $obj->fetchAll($where, TTP_TELEPHONE);

			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->phone->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadType($id)
	{
		Zend_Loader::loadClass('TelephoneType');
		try
		{
			$obj = new TelephoneType();
			$where[] = $obj->getAdapter()->quoteInto(TTP_ID_TELEPHONE.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(TTP_STATUS.' not in (?)', Constants::DISABLE);
			$rows = $obj->fetchAll($where);

			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->phone->load->fail, E_USER_ERROR);
		}
	}
	
}