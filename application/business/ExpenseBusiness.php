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

class ExpenseBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $expense - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($expense, &$idPerson=null, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Expense();
			$where[] = $obj->getAdapter()->quoteInto(EXP_ID_EXPENSE_TYPE.' = ?', $expense[EXP_ID_EXPENSE_TYPE]);
			$where[] = $obj->getAdapter()->quoteInto(EXP_ID_FAMILY.' = ?', $expense[EXP_ID_FAMILY]);
			
			$rows = count($obj->fetchAll($where));
			
			if($rows == 0)
			{				
				$id = $obj->insert($expense);				
				Logger::loggerOperation('Registro inserido na tabela '. TBL_EXPENSE .' [id='.$id.']');
			}
			else
			{				 
				$oldObj = $obj->fetchAll($where);
				
				foreach($oldObj as $old){
					if(!$old->{EXP_STATUS}){
					
						$old->{EXP_STATUS} = "h";
						
						$data = array(
							EXP_ID_EXPENSE_TYPE		=> $old->{EXP_ID_EXPENSE_TYPE},
							EXP_ID_FAMILY 			=> $old->{EXP_ID_FAMILY},
							EXP_EXPENSE_VALUE	 	=> $old->{EXP_EXPENSE_VALUE},
							EXP_STATUS				=> $old->{EXP_STATUS},
						);
						
						$where = $obj->getAdapter()->quoteInto(EXP_ID_EXPENSE.' = ?', $old->{EXP_ID_EXPENSE});
						
						$idUpdate = $obj->update($data, $where);
						
						Logger::loggerOperation('Registro modificado na tabela '.TBL_EXPENSE.
							' ['.EXP_ID_EXPENSE_TYPE.'='.$expense[EXP_ID_EXPENSE_TYPE].']'.
							' ['.EXP_ID_FAMILY.'='.$expense[EXP_ID_FAMILY].']');
							
						try{
							$userId = UserLogged::getUserId();
						}catch(UserNotLoggedException $e){
							$labelResources = Zend_Registry::get(LABEL_RESOURCES);
							Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$labelResources->notPermission.' - '.$e);
							trigger_error($labelResources->notPermission , E_USER_ERROR);
						}
						
						Zend_Loader::loadClass('PersonChangeHistory');

						$objExpense = array();
						$objExpense[PCH_ID_REFERENCE_FOREIGN]	= $old->{EXP_ID_EXPENSE};						
						$objExpense[PCH_ID_USER]				= $userId;
						$objExpense[PCH_ID_PERSON]				= $idPerson;
						$admin = AuthBusiness::isAdministrator();
						($admin === TRUE) ? $objExpense[PCH_ID_RESOURCE] = parent::loadIdResource(FAMILYEXPENSE_CONTROLLER, Constants::ADMIN) :
											$objExpense[PCH_ID_RESOURCE] = parent::loadIdResource(FAMILYEXPENSE_CONTROLLER, Constants::COORDINATOR);
						$objExpense[PCH_DATE_OPERATION]		= date("Y-m-d");
						$objExpense[PCH_TABLE_NAME]			= TBL_EXPENSE;
						
						Zend_Loader::loadClass('HistoryBusiness');
						HistoryBusiness::save($objExpense, $db);
					}
				}
				
				$id = $obj->insert($expense);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_EXPENSE .' [id='.$id.']');				
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{			
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->expense->save->fail, E_USER_ERROR);
		}
	}
	
	public static function loadByFamily($idFamily)
	{
		$obj = new Expense();
		try
		{
			$where = $obj->getAdapter()->quoteInto(EXP_ID_FAMILY.' = ?', $idFamily);
			return $obj->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->expense->save->fail, E_USER_ERROR);
		}
	}
	
	public static function loadAll()
	{
		$obj = new ExpenseType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(EXT_STATUS.' not in (?)', Constants::DISABLE);
			return $obj->fetchAll($where, EXT_EXPENSE);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->expense->save->fail, E_USER_ERROR);
		}
	}
	
	public static function load($id)
	{
		$obj = new ExpenseType();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(EXT_ID_EXPENSE.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(EXT_STATUS.' not in (?)', Constants::DISABLE);
			return $obj->fetchRow($where);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->expense->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Exclui despesa
	 */
	public static function drop($expense, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Expense();
			
			$where[] = $obj->getAdapter()->quoteInto(EXP_ID_EXPENSE_TYPE.' = ?', $expense[EXP_ID_EXPENSE_TYPE]);
			$where[] = $obj->getAdapter()->quoteInto(EXP_ID_FAMILY.' = ?', $expense[EXP_ID_FAMILY]);
				 
			$oldObj = $obj->fetchAll($where);
				
			foreach($oldObj as $old){
				
				if(!$old->{EXP_STATUS}){
					
					$old->{EXP_STATUS} = "h";
					
					$data = array(
						EXP_ID_EXPENSE_TYPE		=> $old->{EXP_ID_EXPENSE_TYPE},
						EXP_ID_FAMILY 			=> $old->{EXP_ID_FAMILY},
						EXP_EXPENSE_VALUE	 	=> $old->{EXP_EXPENSE_VALUE},
						EXP_STATUS				=> $old->{EXP_STATUS},
					);
					
					$where = $obj->getAdapter()->quoteInto(EXP_ID_EXPENSE.' = ?', $old->{EXP_ID_EXPENSE});
					
					$id = $obj->update($data, $where);
					
					Logger::loggerOperation('Registro modificado na tabela '.TBL_EXPENSE.
						' ['.EXP_ID_EXPENSE_TYPE.'='.$expense[EXP_ID_EXPENSE_TYPE].']'.
						' ['.EXP_ID_FAMILY.'='.$expense[EXP_ID_FAMILY].']');
				}
			}
			
			if($mt) $db->commit();
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->expense->drop->fail, E_USER_ERROR);
		}
	}
}
