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

class IncomeBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $income - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($income, $nameController, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{	
			$obj = new Income();
			$where[] = $obj->getAdapter()->quoteInto(ICM_ID_INCOME.' = ?', $income[ICM_ID_INCOME]);
			$where[] = $obj->getAdapter()->quoteInto(ICM_ID_PERSON.' = ?', $income[ICM_ID_PERSON]);
			$where[] = $obj->getAdapter()->quoteInto(ICM_STATUS.' <> ?', Constants::HISTORY);
			
			$resIncome = $obj->fetchAll($where);
			
			if((count($resIncome) == 0) && ($income[ICM_VALUE] || $income[ICM_VALUE]==0))
			{
				$id = $obj->insert($income);				
				Logger::loggerOperation('Registro inserido na tabela '. TBL_INCOME .' [id='.$id.']');
			}
			else
			{	
				if(count($resIncome) > 0)
				{
					foreach($resIncome as $icm)
					{
						$incomeOld[ICM_ID_INCOME] 		= $icm->{ICM_ID_INCOME};
						$incomeOld[ICM_ID_EMP_INCOME]	= $icm->{ICM_ID_EMP_INCOME};
						$incomeOld[ICM_ID_PERSON]		= $icm->{ICM_ID_PERSON};
						$incomeOld[ICM_REGISTER_DATE]	= $icm->{ICM_REGISTER_DATE};
						$incomeOld[ICM_VALUE]			= $icm->{ICM_VALUE};
						$incomeOld[ICM_STATUS]			= Constants::HISTORY;
					}
					
					$whereIncome = $obj->getAdapter()->quoteInto(ICM_ID_EMP_INCOME.' = ?', $incomeOld[ICM_ID_EMP_INCOME]);
					$idOld = $obj->update($incomeOld, $whereIncome);								
					Logger::loggerOperation('Registro modificado na tabela '.TBL_INCOME.' ['.ICM_ID_EMP_INCOME.'='.$incomeOld[ICM_ID_EMP_INCOME].']');								
				}
				
				if($income[ICM_VALUE] || $income[ICM_VALUE]==0)	
				{
					$id = $obj->insert($income);
					Logger::loggerOperation('Registro inserido na tabela '. TBL_INCOME .' [id='.$id.']');	
				}
				
				if($id)
				{
					try
					{
						$userId = UserLogged::getUserId();
					}
					catch(UserNotLoggedException $e)
					{
						$labelResources = Zend_Registry::get(LABEL_RESOURCES);
						Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$labelResources->notPermission.' - '.$e);
						trigger_error($labelResources->notPermission , E_USER_ERROR);
					}
					
					$objIncome = array();
					$objIncome[PCH_ID_REFERENCE_FOREIGN]	= $id;						
					$objIncome[PCH_ID_USER]					= UserLogged::getUserId();
					$objIncome[PCH_ID_PERSON]				= $incomeOld[ICM_ID_PERSON];
					$objIncome[PCH_ID_RESOURCE] 			= parent::loadIdResource($nameController);
					$objIncome[PCH_DATE_OPERATION]			= date("Y-m-d");
					$objIncome[PCH_TABLE_NAME]				= TBL_INCOME;
					
					HistoryBusiness::save($objIncome, $db);
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
			trigger_error(parent::getLabelResources()->income->save->fail, E_USER_ERROR);
		}
	}
	
	
	
	/**
	 * Verifica se a pessoa já tem cadastro referente a renda
	 */
	public static function existPerson($idPerson, &$db)
	{	
		try
		{
			$obj = new Income();
		
			//verifica se na tabela renda "emp_income" existe registro referente ao "idPerson"  			
			$where[] = $obj->getAdapter()->quoteInto(ICM_ID_PERSON.' = ?', $idPerson);
			$where[] = $obj->getAdapter()->quoteInto(ICM_STATUS.' is null', null);
			$row = count($obj->fetchAll($where));
			
			if($row > 0)
			{
				return true;
			}
			
			return false;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->income->load->failDB, E_USER_ERROR);		
		}
	}
	
	/**
	 * Atualiza o campo "status" da tabela "renda" - "emp_income"
	 */
	public static function updateStatus($idPerson = null, $idIncome = null, &$db = null)
	{	
		$obj = new Income();
		
		//busca na tabela renda a linha referente aos parâmetros passados
		$where = $obj->getAdapter()->quoteInto(ICM_ID_PERSON.' = ?', $idPerson);
		$rows  = $obj->fetchAll($where);	
			
		if(sizeof($rows) > 0)
		{		
			if($db == null)
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->beginTransaction();
				$transaction = true;
			}
			
			try
			{
				Zend_Loader::loadClass('Constants');
				
				$data = array();
				$data[ICM_STATUS] = Constants::HISTORY;
				
				foreach($rows as $uniqueIncome)
				{
					$table = new SocialProgram();
					$table->update($data, $where);
					
					if($transaction)
					{
						$db -> commit();
					}
					
					Logger::loggerOperation('Campo status referente a renda da pessoa '.$idPerson.' atualizado com sucesso');
				}
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();	
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->income->error->failDB, E_USER_ERROR);		
			}		
		}
	}
	
	/**
	 * Carrega os tpos de renda
	 */
	public static function loadIncomeType()
	{
		$obj = new IncomeType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(ICT_STATUS.' not in (?)', Constants::DISABLE);
			return $obj->fetchAll($where, ICT_INCOME);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->income->error->failDB, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega os tipos de situaão de mercado
	 */
	public static function loadEmploymentStatus()
	{
		$obj = new EmploymentStatus();
		try
		{
			$where = $obj->getAdapter()->quoteInto(EMS_STATUS.' not in (?)', Constants::DISABLE);
			return $obj->fetchAll($where, EMS_EMPLOYMENT_STATUS);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->income->error->failDB, E_USER_ERROR);
		}
	}
	
	public static function loadOneEmploymentStatus($id)
	{
		Zend_Loader::loadClass('EmploymentStatus');
		$obj = new EmploymentStatus();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(EMS_ID_EMPLOYMENT_STATUS.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(EMS_STATUS.' not in (?)', Constants::DISABLE);
			return $obj->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->income->error->failDB, E_USER_ERROR);
		}
	}
}