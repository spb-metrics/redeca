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

class EmploymentBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $employment - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($employment, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Employment();
			if($employment[EMP_ID_EMPLOYMENT] == false)
			{
				if(empty($employment[EMP_ID_ADDRESS]))
					$employment[EMP_ID_ADDRESS] = null;
					
				$id = $obj->insert($employment);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_EMPLOYMENT .' [id='.$id.']');
			}
			else
			{
				$where = $obj->getAdapter()->quoteInto(EMP_ID_EMPLOYMENT.' = ?', $employment[EMP_ID_EMPLOYMENT]);
				
				$obj->update($employment, $where);
				$id = $employment[EMP_ID_EMPLOYMENT];
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_EMPLOYMENT.
					' ['.EMP_ID_EMPLOYMENT.'='.$employment[EMP_ID_EMPLOYMENT].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->employment->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Insere e atualiza o relacionamento de telephone com employment
	 */
	public static function saveEmploymentPhone($employmentPhone, &$delete=null, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new EmploymentPhone();
			$rows = count($obj->find($employmentPhone[EMT_ID_EMPLOYMENT],$employmentPhone[EMT_ID_TELEPHONE]));
			if($rows == 0)
			{ 
				$id = $obj->insert($employmentPhone);				
				Logger::loggerOperation('Registro inserido na tabela '. TBL_EMPLOYMENT_TELEPHONE .' [id='.$id.']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(EMT_ID_EMPLOYMENT.' = ?', $employmentPhone[EMT_ID_EMPLOYMENT]);
				$where[] = $obj->getAdapter()->quoteInto(EMT_ID_TELEPHONE.' = ?', $employmentPhone[EMT_ID_TELEPHONE]);
				
				if($delete === true){
					$obj->delete($where);
					TelephoneBusiness::drop($employmentPhone[PRT_ID_TELEPHONE], $db);
					Logger::loggerOperation('Registro deletado na tabela '.TBL_PERSON_TELEPHONE.
						' ['.PRT_ID_TELEPHONE.'='.$employmentPhone[PRT_ID_TELEPHONE].']'.
						' ['.PRT_ID_PERSON.'='.$employmentPhone[PRT_ID_PERSON].']');
				}
				else
				{
					$id = $obj->update($employmentPhone, $where);
					Logger::loggerOperation('Registro modificado na tabela '.TBL_PERSON_TELEPHONE.
						' ['.PRT_ID_TELEPHONE.'='.$employmentPhone[PRT_ID_TELEPHONE].']'.
						' ['.PRT_ID_PERSON.'='.$employmentPhone[PRT_ID_PERSON].']');
				}
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
}
