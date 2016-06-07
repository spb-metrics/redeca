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
 * Jefferson Barros Lima  - W3S		   				24/04/2008	                       Create file 
 * 
 */
require_once 'BuilderQueryInterface.php';

/*prefix act */
require_once CLS_ACTIVITYDETAIL.'.php';
require_once CLS_CATEGORY.'.php';
require_once CLS_ACTIVITYCLASS.'.php';
require_once CLS_CLASSASSISTANCE.'.php';
require_once CLS_CLASSMODEL.'.php';
require_once CLS_STATUSCLASS.'.php';
/*prefix ast */
require_once CLS_ASSISTANCE.'.php';
require_once CLS_PROGRAM.'.php';
require_once CLS_PROGRAMTYPE.'.php';
require_once CLS_TARGETMARKET.'.php';
require_once CLS_ASSISTANCEPROFILE.'.php';
/*prefix con */
require_once CLS_REGION.'.php';
require_once CLS_NEIGHBORHOODREGION.'.php';
require_once CLS_CITY.'.php';
require_once CLS_NEIGHBORHOOD.'.php';
require_once CLS_ADDRESSTYPE.'.php';
require_once CLS_ADDRESS.'.php';
require_once CLS_ADDRESSNICKNAME.'.php';
require_once CLS_TELEPHONENUMBER.'.php';
require_once CLS_TELEPHONETYPE.'.php';
require_once CLS_UF.'.php';
/*prefix cov */
require_once CLS_COVERAGEADDRESS.'.php';
require_once CLS_COVERAGEHEALTHTYPE.'.php';
require_once CLS_UBS.'.php';
/*prefix csg*/
require_once CLS_CONSANGUINE.'.php';
require_once CLS_CONSANGUINETYPE.'.php';
/*prefix eas */
require_once CLS_ESPECIALASSISTANCE.'.php';
require_once CLS_LAWSUITORIGIN.'.php';
require_once CLS_OFFICIALLETTERORIGIN.'.php';
/*prefix edu */
require_once CLS_LEVELINSTRUCTION.'.php';
require_once CLS_DEGREETYPE.'.php';
require_once CLS_PERIODTYPE.'.php';
require_once CLS_REGISTRATION.'.php';
require_once CLS_SCHOOL.'.php';
require_once CLS_SCHOOLTYPE.'.php';
require_once CLS_SCHOOLYEARTYPE.'.php';
/*prefix emp_*/
require_once CLS_EMPLOYMENT.'.php';
require_once CLS_EMPLOYMENTSTATUS.'.php';
require_once CLS_EMPLOYMENTPHONE.'.php';
require_once CLS_INCOME.'.php';
require_once CLS_INCOMETYPE.'.php';
/*prefix ent */
require_once CLS_ENTITYCLASSIFICATION.'.php';
require_once CLS_ENTITYAREATYPE.'.php';
require_once CLS_ENTITYAREA.'.php';
require_once CLS_ENTITY.'.php';
require_once CLS_ENTITYCLASSIFICATIONTYPE.'.php';
require_once CLS_ENTITYGROUP.'.php';
require_once CLS_ENTITYGROUPTYPE.'.php';
require_once CLS_ENTITYTELEPHONE.'.php';
/*prefix exp */
require_once CLS_EXPENSETYPE.'.php';
require_once CLS_EXPENSE.'.php';
/*prefix fam */
require_once CLS_FAMILY.'.php';
require_once CLS_FAMILY_ID.'.php';
require_once CLS_KINSHIPTYPE.'.php';
require_once CLS_REPRESENTATIVE.'.php';
/*prefix gas */
require_once CLS_ASSISTANCEBENEFIT.'.php';
require_once CLS_ASSISTANCEBENEFITTYPE.'.php';
require_once CLS_GENERALASSISTANCE.'.php';
/*prefix hlt */
require_once CLS_HEALTH.'.php';
require_once CLS_PREGNANCY.'.php';
require_once CLS_FRAMEWORKHEALTH.'.php';
require_once CLS_FRAMEWORKHEALTHTYPE.'.php';
/*prefix per */
require_once CLS_PERSON.'.php';
require_once CLS_NATIONALITY.'.php';
require_once CLS_MARITALSTATUS.'.php';
require_once CLS_DOCUMENT.'.php';
require_once CLS_DEFICIENCYTYPE.'.php';
require_once CLS_CTPS.'.php';
require_once CLS_CIVILCERTIFICATE.'.php';
require_once CLS_DEFICIENCY.'.php';
require_once CLS_PERSONADDRESSTEMP.'.php';
require_once CLS_PERSONCHANGEHISTORY.'.php';
require_once CLS_PERSONTELEPHONE.'.php';
require_once CLS_RACE.'.php';
/*prefix res */
require_once CLS_RESIDENCE.'.php';
require_once CLS_SANITARYTYPE.'.php';
require_once CLS_MORADATYPE.'.php';
require_once CLS_LOCALITYTYPE.'.php';
require_once CLS_ILLUMINATIONTYPE.'.php';
require_once CLS_BUILDINGTYPE.'.php';
require_once CLS_FAMILYRESIDENCE.'.php';
require_once CLS_STATUSTYPE.'.php';
require_once CLS_SUPPLYTYPE.'.php';
require_once CLS_TRASHTYPE.'.php';
require_once CLS_WATERTYPE.'.php';
/*prefix sop */
require_once CLS_SOCIALPROGRAM.'.php';
require_once CLS_SOCIALPROGRAMORIGIN.'.php';
require_once CLS_SOCIALPROGRAMTYPE.'.php'; 

/* 
 * Declaração das classes que unem os módulos
 * Declaração deve ser realizada concatenando o nome das Classes principais dos módulos a serem unidos
 */
define("PERSON_HEALTH_REL", 			CLS_PERSON.'_'.CLS_HEALTH);
define("PERSON_PREGNANCY_REL", 			CLS_PERSON.'_'.CLS_PREGNANCY);
define("PERSON_LEVELINSTRUCTION_REL", 	CLS_PERSON.'_'.CLS_LEVELINSTRUCTION);
define("PERSON_FAMILY_REL", 			CLS_PERSON.'_'.CLS_FAMILY);
define("PERSON_SOCIALPROGRAM_REL", 		CLS_PERSON.'_'.CLS_SOCIALPROGRAM);
define("PERSON_CONSANGUINE_REL", 		CLS_PERSON.'_'.CLS_CONSANGUINE);
define("PERSON_INCOME_REL", 			CLS_PERSON.'_'.CLS_INCOME);
define("PERSON_ADDRESS_REL", 			CLS_PERSON.'_'.CLS_ADDRESS);
define("PERSON_TELEPHONENUMBER_REL", 	CLS_PERSON.'_'.CLS_TELEPHONENUMBER);
define("PERSON_ASSISTANCE_REL", 		CLS_PERSON.'_'.CLS_ASSISTANCE);
define("FAMILY_EXPENSE_REL", 			CLS_FAMILY.'_'.CLS_EXPENSE);
define("FAMILY_RESIDENCE_REL", 			CLS_FAMILY.'_'.CLS_RESIDENCE);
define("INCOME_TELEPHONE_REL", 			CLS_INCOME.'_'.CLS_TELEPHONENUMBER);
define("RESIDENCE_ADDRESS_REL", 		CLS_RESIDENCE.'_'.CLS_ADDRESS);
define("ASSISTANCE_GENERALASSISTANCE_REL", 	CLS_ASSISTANCE.'_'.CLS_GENERALASSISTANCE);
define("ASSISTANCE_ESPECIALASSISTANCE_REL",	CLS_ASSISTANCE.'_'.CLS_ESPECIALASSISTANCE);
define("ASSISTANCE_CLASSASSISTANCE_REL", 	CLS_ASSISTANCE.'_'.CLS_CLASSASSISTANCE);
define("ASSISTANCE_PROGRAM_REL", 		CLS_ASSISTANCE.'_'.CLS_PROGRAM);
define("ENTITY_TELEPHONENUMBER_REL", 	CLS_ENTITY.'_'.CLS_TELEPHONENUMBER);
define("CLASSMODEL_PROGRAM_REL", 		CLS_CLASSMODEL.'_'.CLS_PROGRAM);
define("ENTITY_PROGRAM_REL", 			CLS_ENTITY.'_'.CLS_PROGRAM);

abstract class BuilderQueryAbstract implements BuilderQueryInterface 
{
	const COLS_KEY 			= 'cols';
	const TABLE_NAME_KEY	= 'name';
	const _REFERENCE_FIELDS_KEY	= '_REFERENCE_FIELDS_KEY';

	const _PREFIX 			= '_PREFIX';
	const _FROM_KEY 		= '_FROM_KEY';
	const _FROM_TABLES_KEY 	= '_FROM_TABLES_KEY';
	const _COLUMNS_KEY 		= '_COLUMNS_KEY';
	const _OUTER_JOIN_KEY 	= '_OUTER_JOIN_KEY';
	const _INNER_JOIN_KEY	= '_INNER_JOIN_KEY';
	const _OUTER_JOIN_FIELDS_KEY = '_OUTER_JOIN_FIELDS_KEY';
	const _INNER_JOIN_FIELDS_KEY = '_INNER_JOIN_FIELDS_KEY';
	const _MAIN_TABLE_KEY	= '_MAIN_TABLE_KEY';

	const _WHERE_KEY		= '_WHERE_KEY';
	const _DEFAULT_JOIN_KEY = '_DEFAULT_JOIN_KEY';
	const _MODULE_JOIN_KEY 	= '_MODULE_JOIN_KEY';
	const _FILTER_KEY 		= '_FILTER_KEY';
	const _ORDER_BY_KEY 	= '_ORDER_BY_KEY';

	/* Mappings Join Modules columns */
	/**
	 * Mapeamento dos relacionamento entre módulos e os campos que os relacionam
	 * Example:
	 * PERSON_INCOME_REL => (Chave indicando o relacionamento entre modulos - não tem um padrão definido porém 
	 * sua definição (define) no início deste arquivo é necessária) 
	 * array(
	 * 	self::PERSON_PREFIX (Prefixo da tabela que tem relacionamento com o módulo) =>  PRS_ID_PERSON (Campo de relacionamento [DE]),
	 * 	self::INCOME_PREFIX (Prefixo da tabela que tem relacionamento com o módulo) => ICM_ID_PERSON (Campo de relacionamento [PARA])
	 * 	)
	 */	
	private $joinModules = array(
		PERSON_HEALTH_REL => array(
			self::PERSON_PREFIX => PRS_ID_PERSON,
			self::HEALTH_PREFIX => HLT_ID_PERSON
			),
		PERSON_PREGNANCY_REL => array(
			self::PERSON_PREFIX => PRS_ID_PERSON,
			self::PREGNANCY_PREFIX => PRG_ID_PERSON
			),
		PERSON_LEVELINSTRUCTION_REL => array(
			self::PERSON_PREFIX => PRS_ID_PERSON,
			self::LEVELINSTRUCTION_PREFIX => LIT_ID_PERSON
			),
		PERSON_FAMILY_REL => array(
			self::PERSON_PREFIX => PRS_ID_PERSON,
			self::REPRESENTATIVE_PREFIX => REP_ID_PERSON
			),
		PERSON_SOCIALPROGRAM_REL => array(
			self::PERSON_PREFIX => PRS_ID_PERSON,
			self::SOCIALPROGRAM_PREFIX => SPG_ID_PERSON
			),
		PERSON_CONSANGUINE_REL => array(
			self::PERSON_PREFIX => PRS_ID_PERSON,
			self::CONSANGUINE_PREFIX => CSG_ID_PERSON_FROM
			),
		PERSON_INCOME_REL => array(
			self::PERSON_PREFIX => PRS_ID_PERSON,
			self::INCOME_PREFIX => ICM_ID_PERSON
			),
		PERSON_ADDRESS_REL => array(
			self::PERSONADDRESSTEMP_PREFIX => PAT_ID_ADDRESS,
			self::ADDRESS_PREFIX => ADR_ID_ADDRESS
			),
		PERSON_TELEPHONENUMBER_REL => array(
			self::PERSONTELEPHONE_PREFIX => PRT_ID_TELEPHONE,
			self::TELEPHONE_PREFIX => TNB_ID_TELEPHONE_NUMBER
			),
		PERSON_ASSISTANCE_REL => array(
			self::PERSON_PREFIX => PRS_ID_PERSON,
			self::ASSISTANCE_PREFIX => AST_ID_PERSON
			),
		FAMILY_EXPENSE_REL => array(
			self::FAMILYID_PREFIX => FID_ID_FAMILY,
			self::EXPENSE_PREFIX => EXP_ID_FAMILY
			),
		FAMILY_RESIDENCE_REL => array(
			self::FAMILYID_PREFIX => FID_ID_FAMILY,
			self::FAMILYRESIDENCE_PREFIX => FRS_ID_FAMILY
			),
		INCOME_TELEPHONE_REL => array(
			self::EMPLOYMENTTELEPHONE_PREFIX => EMT_ID_TELEPHONE,
			self::TELEPHONE_PREFIX => TNB_ID_TELEPHONE_NUMBER
			),
		RESIDENCE_ADDRESS_REL => array(
			self::RESIDENCE_PREFIX => RES_ID_ADDRESS,
			self::ADDRESS_PREFIX => ADR_ID_ADDRESS
			),
		ASSISTANCE_GENERALASSISTANCE_REL => array(
			self::ASSISTANCE_PREFIX => AST_ID_ASSISTANCE,
			self::GENERALASSISTANCE_PREFIX => GAS_ID_ASSISTANCE
			),
		ASSISTANCE_ESPECIALASSISTANCE_REL => array(
			self::ASSISTANCE_PREFIX => AST_ID_ASSISTANCE,
			self::ESPECIALASSISTANCE_PREFIX => EAS_ID_ASSISTANCE
			),
		ASSISTANCE_CLASSASSISTANCE_REL => array(
			self::ASSISTANCE_PREFIX => AST_ID_ASSISTANCE,
			self::CLASSASSISTANCE_PREFIX => CLA_ID_ASSISTANCE
			),
		ASSISTANCE_PROGRAM_REL => array(
			self::ASSISTANCE_PREFIX => AST_ID_PROGRAM,
			self::PROGRAM_PREFIX => PGR_ID_PROGRAM
			),
		ENTITY_TELEPHONENUMBER_REL => array(
			self::ENTITYTELEPHONE_PREFIX => ETP_ID_TELEPHONE,
			self::TELEPHONE_PREFIX => TNB_ID_TELEPHONE_NUMBER
			),
		ENTITY_PROGRAM_REL => array(
			self::ENTITY_PREFIX => ENT_ID_ENTITY,
			self::PROGRAM_PREFIX => PGR_ID_ENTITY
			),
		CLASSMODEL_PROGRAM_REL => array(
			self::CLASSMODEL_PREFIX => CLS_ID_PROGRAM,
			self::PROGRAM_PREFIX => PGR_ID_PROGRAM
			)
	);
	/**
	 * Relação de todos os Módulos que podem ser utilizados no relatório
	 */
	public static $allModules = array(
			CLS_ASSISTANCE,
			CLS_ESPECIALASSISTANCE,
			CLS_GENERALASSISTANCE,
			CLS_EXPENSE,
			CLS_LEVELINSTRUCTION,
			CLS_ADDRESS,
			CLS_ENTITY,
			CLS_FAMILY,
			CLS_PERSON,
			CLS_PROGRAM,
			CLS_SOCIALPROGRAM,
			CLS_CLASSASSISTANCE,
			CLS_INCOME,
			CLS_RESIDENCE,
//			CLS_HEALTH,  //não deve ser exibido no primeiro nível
			CLS_TELEPHONENUMBER,
			CLS_CLASSMODEL
		);
	/**
	 * Mapeamento dos módulos que possuem relacionamento
	 */
	public static $modulesRelationship = array(
			CLS_PERSON => array(
				CLS_HEALTH,CLS_PREGNANCY,CLS_LEVELINSTRUCTION,CLS_FAMILY,CLS_SOCIALPROGRAM,
				CLS_INCOME,CLS_ASSISTANCE,CLS_ADDRESS,CLS_TELEPHONENUMBER
				),
			CLS_HEALTH => array(CLS_PERSON),
			CLS_PREGNANCY => array(CLS_PERSON),
			CLS_LEVELINSTRUCTION => array(CLS_PERSON),
			CLS_RESIDENCE => array(CLS_ADDRESS),
			CLS_ADDRESS => array(),
			CLS_FAMILY => array(CLS_PERSON, CLS_RESIDENCE, CLS_EXPENSE),
			CLS_EXPENSE => array(),
			CLS_INCOME => array(CLS_PERSON, CLS_TELEPHONENUMBER),
			CLS_TELEPHONENUMBER => array(),
			CLS_ASSISTANCE => array(CLS_ESPECIALASSISTANCE, CLS_GENERALASSISTANCE, CLS_PROGRAM,
				CLS_CLASSASSISTANCE, CLS_PERSON
				),
			CLS_SOCIALPROGRAM => array(CLS_PERSON),
			CLS_ESPECIALASSISTANCE => array(CLS_ASSISTANCE),
			CLS_GENERALASSISTANCE => array(CLS_ASSISTANCE),
			CLS_ENTITY => array(CLS_PROGRAM, CLS_TELEPHONENUMBER),
			CLS_CLASSMODEL => array(CLS_PROGRAM),
			CLS_CLASSASSISTANCE => array(CLS_ASSISTANCE),
			CLS_PROGRAM => array(CLS_ASSISTANCE,CLS_CLASSMODEL,CLS_ENTITY)
	);

	protected abstract static function getInstance();
	protected abstract function getTableMap();
	/**
	 * Retorna um array contendo as partes que compõe o script SQL
	 * @return Array Map contendo as partes do script
	 */
	protected abstract function build();
	
	protected function getColumns()
	{
		$query = null;
		foreach($this->getTableMap() as $class => $value)
		{
			$table = new $class();
			$info = $table->info();
			/* Campos da clausula SELECT */
			$cols = $info[self::COLS_KEY];
			self::setAlias($cols, $value[self::_PREFIX]);
			if(!empty($query))
				$query = $query .', '.implode(', ',$cols);
			else
				$query = implode(', ',$cols);
			
		}
		unset($table);
		return $query;
	}

	protected function getFrom()
	{
		$query = null;
		$tableMap = $this->getTableMap();
		foreach($tableMap as $class => $value)
		{
			$table = new $class();
			$info = $table->info();
			$tableName = $info[self::TABLE_NAME_KEY];
			self::setFromAlias($tableName, $value[self::_PREFIX]);
			if($value[self::_MAIN_TABLE_KEY])
				$query[self::_FROM_TABLES_KEY] = $tableName;
			
			if(!empty($value[self::_REFERENCE_FIELDS_KEY]))
			{
				foreach($value[self::_REFERENCE_FIELDS_KEY] as $refClass => $refFields)
				{
					$table = new $refClass();
					$info = $table->info();					
					$tableName = $info[self::TABLE_NAME_KEY];
					self::setFromAlias($tableName, $tableMap[$refClass][self::_PREFIX]);
					// Tables
					$query[self::_OUTER_JOIN_KEY][] = $tableName;
					$join = $this->getJoin($class, $refClass);
					// Fields
					$query[self::_OUTER_JOIN_FIELDS_KEY][] = $join;
					unset($table);
				}
			}
		}
		return $query;
	}
	protected function getBuildQuery()
	{
		$sql[self::_COLUMNS_KEY] = $this->getColumns();
		$sql[self::_FROM_KEY] = $this->getFrom(CLS_PERSON);
		return $sql;
	}

	protected function getFromTables($tablesMap)
	{
		if(!empty($tablesMap))
		{
			$query = null;
			foreach($tablesMap as $class => $value)
			{
				try
				{
					Zend_Loader::loadClass($class);
					$table = new $class();
					$info = $table->info();
					$cols = $info[self::COLS_KEY];
					self::setAlias($cols, $value[self::_PREFIX]);
					if($query != null)
						$query = $query .', '.implode(', ',$cols);
					else
						$query = implode(', ',$cols);
				}
				catch(Zend_Exception $e)
				{
					Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);					
					trigger_error($e->getMessage(), E_USER_ERROR);
				}
			}
		}
		return $query;
	}

	public function getModulesJoin($previousClass, $currentClass)
	{
		if(!empty($previousClass) && !empty($currentClass))
		{
			// Verifica se existe relacionamento entre os módulos 
			if(!empty($this->joinModules[$previousClass.'_'.$currentClass]))
			{
				$moduleKey = $this->joinModules[$previousClass.'_'.$currentClass];
			}
			elseif(!empty($this->joinModules[$currentClass.'_'.$previousClass]))
			{
				$moduleKey = $this->joinModules[$currentClass.'_'.$previousClass];
			}
			if(empty($moduleKey))
			{
				$msg = 'Não existe relacionamento entre os módulos selecionados.['.
				$previousClass.' e '.$currentClass.']';
				// Não há relacionamento entre os módulos
				Logger::loggerError("Caught exception: ".get_class($this)."\nMessage: ".$msg);
				
				trigger_error($msg , E_USER_ERROR);
			}
			else
			{
				$sql = null;
				if(is_array($moduleKey) && count($moduleKey) > 0)
				foreach($moduleKey as $prefix => $column)
				{
					if(!empty($column))
					{
						if(!is_array($column))
							$sql[]= $prefix.'.'.$column;
					}
				}
				if(is_array($sql))
					return implode(' = ', $sql);
			}
		}
		return null;
	}

	public function getFilterFields($filters)
	{
		if(!empty($filters) && count($filters) > 0)
		{
			if(is_array($filters) && count($filters) > 0)
				return implode(' AND ', $filters);
		}
		return null;
	}

	public function getOrderFields($order)
	{
		if(!empty($order))
		{
			if(is_array($order) && count($order) > 0)
				return '('.implode(', ', $order).')';
			else
				return '('.$order.')';
		}
		return null;
	}

	private function getJoin($class, $refClass)
	{
		if(!empty($class) && !empty($refClass))
		{
			$tableMap = $this->getTableMap();
			$current = null;
			foreach($tableMap[$class][self::_REFERENCE_FIELDS_KEY][$refClass] as $prefix => $column)
			{
				$current[] = $prefix.'.'.$column;
			}
			if($current != null)
				$ref[] = implode(' = ', $current);
		}
		return $ref;
	}

//	private function getOuterFields($references)
//	{
//		if(!empty($references) && count($references) > 0)
//		{
//			$ref = array();
//			foreach($references as $class => $reference)
//			{
//				if(!empty($reference) && count($reference) >0)
//				{
//					$current = null;
//					foreach($reference as $prefix => $column)
//					{
//						// Se estiver configurado como OUTER, adiciona no array
//						if($this->isOuter($class))
//							$current[] = $prefix.'.'.$column;
//					}
//					if($current != null)
//						$ref[] = implode(' = ', $current);
//				}
//			}
//			return $ref;
//		}
//		return null;
//	}
//
//	private function isOuter($className)
//	{
//		if(!empty($className))
//		{
//			$tableMap = $this->getTableMap();
//			return $tableMap[$className][self::_IS_OUTER_KEY];
//		}
//		return FALSE;
//	}
	/**
	 * Retorna um array com os campos e seus prefixos dadas as tabelas desejadas
	 * Caso nenhum valor seja passado, retorna colunas do módulo(conjunto de tabelas) todo
	 */
	public function getFields($classesName=null)
	{
		$tableMap = $this->getTableMap();
		if(!empty($classesName))
		{
			if(is_array($classesName))
			{
				if(count($classesName) > 0)
				{
					foreach($classesName as $class)
					{
						$res = $tableMap[$class];
						if(empty($res))
							return null;
						
						$cols = $this->getTableColumns($classesName);
						self::setPrefix($cols, $res[self::_PREFIX]);
						return $cols;
					}
				}
			}
			else
			{
				$res = $tableMap[$classesName];
				if(empty($res))
					return null;
				
				$cols = $this->getTableColumns($classesName);
				self::setPrefix($cols, $res[self::_PREFIX]);
				return $cols;
			}
		}
		$previous = null;
		foreach($tableMap as $class => $value)
		{
			$cols = $this->getTableColumns($class);
			if(UserLogged::isCoordinator())
			{	
				foreach($cols as $k=>$v)
				{
					if($v == PGR_ID_ENTITY)
					{
						unset($cols[$k]);
					}
				}
			}
			
			self::setPrefix($cols, $value[self::_PREFIX]);			
			$previous = array_merge((array)$previous, (array)$cols);			
		}
		
		return $previous;
	}

	private function getTableColumns($class)
	{
		if(!empty($class))
		{
			$table = new $class();
			$info = $table->info();
			return $info[self::COLS_KEY];
		}
		return null;
	}
	/**
	 * Seta um prefixo e alias para $columns. Altera a coluna e a devolve 
	 * juntamente com seu alias
	 * @param String/Array $columns - Coluna que deve receber o alias
 	 */
	protected function setAlias(&$columns, $prefix)
	{
		if(is_array($columns) && count($columns) > 0)
		{
			foreach($columns as $k => &$v)
				$v = $prefix.".$v as $prefix".'_'.$v;
		}
		else
		{
			if(!empty($columns))
				$columns = $prefix.".$columns as $prefix".'_'.$columns;
		}
	}
	/**
	 * Seta somente o prefixo dado um array de valores(colunas)
	 */
	protected function setPrefix(&$columns, $prefix)
	{
		if(is_array($columns) && count($columns) > 0)
		{
			foreach($columns as $k => &$v)
				$v = $prefix.'.'.$v;
		}
		else
		{
			if(!empty($columns))
				$columns = $prefix.'.'.$columns;
		}
	}

	/**
	 * Seta um alias para $columns. Altera a coluna e a devolve 
	 * juntamente com seu alias
	 * @param String/Array $columns - Coluna que deve receber o alias
 	 */
	protected function setFromAlias(&$table, $prefix=null)
	{
		if(is_array($table) && count($table) > 0)
		{
			foreach($table as $k => &$v)
				$v = $v .' '. $prefix;
		}
		else
		{
			if(!empty($table))
				$table = "$table $prefix";
		}
	}

}
