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
 * Lucas dos Santos Borges Corrêa  - W3S		   	05/05/2008	                       Create file 
 * 
 */
require_once 'BasicValidator.php';

abstract class ReportValidator extends BasicValidator
{
	const META_DATA = 'metadata';
	const DATA_TYPE = 'DATA_TYPE';
	const LENGTH 	= 'LENGTH';
	/**
	 * Validador de Identificação (genérico)
	 */
	public static function validateRequiredId($value, $constant, &$errorMessages = null)
	{
		if(is_array($value))
		{
			if(sizeof($value) > 0)
				foreach($value as $current)
				{
					ReportValidator::validateRequired($current, $constant, $errorMessages);
					ReportValidator::validateInt($current, $constant, $errorMessages);
				}
			else
				ReportValidator::validateRequired(null, $constant, $errorMessages);
		}
		else
		{	
			ReportValidator::validateRequired($value, $constant, $errorMessages);
			ReportValidator::validateInt($value, $constant, $errorMessages);
		}
		return $errorMessages;
	}
	/**
	 * Validação de valor requerido
	 */
	public static function validateRequired($value, $constant, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		if (!$notEmpty->isValid($value))
			$errorMessages[$constant][] = $notEmpty->getMessages();
	
		return $errorMessages;
	}
	
	/**
	 * Validação para inteiro
	 */
	public static function validateInt($value, $constant, &$errorMessages = null)
	{
		$validator = parent::validatorInt();
		if(strlen($value) > 0 && !$validator->isValid($value))
			$errorMessages[$constant][] = $validator->getMessages();

		return $errorMessages;
	}
	/**
	 * Validação para Booleano
	 */
	public static function validateBoolean($value, $constant, &$errorMessages = null)
	{
		$resources = parent::getValidatorResources();
		$validator = parent::validatorInt();
		if(strlen($value) > 0 && !$validator->isValid($value))
			$errorMessages[$constant][] = $validator->getMessages();
		elseif($value !== 0 && $value !=='0' && $value !== 1 && $value !== '1')
			$errorMessages[$constant][][][] = $resources->boolean->invalid;

		return $errorMessages;
	}
	/**
	 * Validação de Tamanho de string
	 */
	public static function validateStringLength($value, $constant, $min=0, $max=null, &$errorMessages = null)
	{
		$stringLenght = parent::validatorStringLength($min, $max);
		if (!$stringLenght->isValid($value))
			$errorMessages[$constant][] = $stringLenght->getMessages();
		return $errorMessages;
	}
	/**
	 * Validação de Data
	 */
	public static function validateDate($value, $constant, &$errorMessages = null)
	{
		$validator = parent::validatorDate();
//		$dateFormat = BasicForm::dateFormat($value);
		$dateFormat = $value;
		if (!$validator->isValid($dateFormat))
			$errorMessages[$constant][] = $validator->getMessages();

		return $errorMessages;
	}
	/**
	 * Validação de Módulo Válido
	 */
	public static function validateModuleIn($value, $constant, $allowedModules, &$errorMessages = null)
	{
		$resources = parent::getValidatorResources();
		if(strlen($value) > 0 && !in_array($value, $allowedModules))
			$errorMessages[$constant][][] = $resources->report->module->invalid;

		return $errorMessages;
	}

	/**
	 * Validação de seleção de Módulos
	 * Não permite que um nível tenha mais de um módulo selecionado caso seja selecionado 
	 * algum módulo no nível seguinte
	 * @param String/Array $currentModule Valor do módulo atual
	 * @param Array $nextModule Array contendo o valor do módulo seguinte(se houver)
	 * @param String $constant chave da mensagem de erro
	 * @param Array $errorMessages Array de mensagem de erro
	 */
	public static function validateModuleAmount($value, $constant, $max, &$errorMessages = null)
	{
		$resources = parent::getValidatorResources();
		if($value > 0 && $value > $max)
			$errorMessages[$constant][][] = $resources->report->module->amount->invalid;

		return $errorMessages;
	}

	/**
	 * Validação se Módulos tem relacionamento
	 */
	public static function validateModuleRelationship($module1, $module2, $constant, &$errorMessages = null)
	{
		$resources = parent::getValidatorResources();
		$msg = null;
		if(!ReportBusiness::hasRelationship($module1, $module2, $msg))
			$errorMessages[$constant][][] = 
				$resources->report->module->relationship->invalid.'['.implode('] [',$msg).']';
		return $errorMessages;
	}
	/**
	 * Validação de requerido para os módulos
	 */
	public static function validateRequiredModule(ReportForm &$frm, &$errorMessages = null, $validateFirstModule = false)
	{
		$resources = parent::getValidatorResources();
		// Flag que indica se o nivel mais alto foi selecionado
		$wasSelected = FALSE;
		if(!empty($frm))
		{
			foreach(range(4,1) as $number)
			{
				$module 	= 'getModule'.$number;
				$module 	= $frm->$module();
				$moduleKey	= 'module'.$number;
				$moduleKey	= $frm->$moduleKey();

				if(is_array($module))
				{
					if(Utils::arrayEmpty($module))
					{
						if($wasSelected || ($validateFirstModule && $number == 1))
						{
							$errorMessages[$moduleKey] = array(array($resources->notEmpty->isEmpty));
							$wasSelected = FALSE;
						}
					}
					else
					{
						$wasSelected = TRUE;
					}
				}
				else
				{	
					if(empty($module))
					{
						if($wasSelected || ($validateFirstModule && $number == 1))
						{
							$errorMessages[$moduleKey] = array(array($resources->notEmpty->isEmpty));
							$wasSelected = FALSE;
						}
					}
					else
						$wasSelected = TRUE;
				}
			}			
		}
		return $errorMessages;
	}
	/**
	 * Retorna informações sobre a coluna selecionada
	 */
	private static function getFieldInfo($module, $field)
	{
		if(is_array($module))
		{
			if(count($module) > 0)
			{
				foreach($module as $class)
				{
					if(class_exists($class, false))
					{
						Zend_Loader::loadClass($class);
						$builder = FactoryBuilderQuery::factory($class);
						$builder->getTableMap();
						foreach($builder->getTableMap() as $className => $values)
						{
							$model = new $className();
							$modelMap = $model->info();
							if(is_array($field))
							{
								if(count($field) > 0)
								{
									foreach($field as $column)
									{
										$var = split('\.', $column, 2);
										$columnInfo = $modelMap[self::META_DATA][$var[1]];
										if(!empty($columnInfo))
											return $columnInfo; 
									}
								}
							}
							else
							{
								$var = split('\.', $field, 2);
								$columnInfo = $modelMap[self::META_DATA][$var[1]];
								if(!empty($columnInfo))
									return $columnInfo; 
							}
						}
					}
				}
			}
		}
		else
		{
			if(!empty($module))
			{
				$class = $module;
				if(class_exists($class, false))
				{
					Zend_Loader::loadClass($class);
					Zend_Loader::loadClass('FactoryBuilderQuery');
					$builder = FactoryBuilderQuery::factory($class);
					$builder->getTableMap();
					foreach($builder->getTableMap() as $className => $values)
					{
						$model = new $className();
						$modelMap = $model->info();
						if(is_array($field))
						{
							if(count($field) > 0)
							{
								foreach($field as $column)
								{
									$var = split('\.', $column, 2);
									$columnInfo = $modelMap[self::META_DATA][$var[1]];
									if(!empty($columnInfo))
										return $columnInfo; 
								}
							}
						}
						else
						{
							$var = split('\.', $field, 2);
							$columnInfo = $modelMap[self::META_DATA][$var[1]];
							if(!empty($columnInfo))
								return $columnInfo; 
						}
					}
				}
			}
		}
	}
	//------------------------------ Validação 
	/**
	 * Valida Modulos
	 */
	public static function validateModule(ReportForm &$frm, &$errorMessages = null, $validateFirstModule)
	{		
		$priorModule = null;
		ReportValidator::validateRequiredModule($frm, $errorMessages, $validateFirstModule);
		foreach(range(1,4) as $number)
		{
			Zend_Loader::loadClass('BuilderQueryAbstract');
			$allowedModules = ReportBusiness::getModuleRelationship($priorModule);
			
			$module 	= 'getModule'.$number;
			$moduleKey	= 'module'.$number;
			$moduleKey	= $frm->$moduleKey();
			$moduleValue = $frm->$module();
			
			$priorModule = $frm->$module();
			$nextModule 	= 'getModule'.($number+1);
			/* Nome do método para verificação da existencia */
			$methodName = 'ReportForm::'.$nextModule;
			
			if(is_array($moduleValue))
			{
				if(array_values($moduleValue) > 0)
				{
					foreach($moduleValue as $current)
					{
						
						ReportValidator::validateModuleIn($current, $moduleKey, $allowedModules, $errorMessages);
					}
					/* Verifica se é um método existente e somente em caso positivo faz a validação */
					if( is_callable(array('ReportForm',$nextModule)))
					{
						self::validateModuleRelationship($priorModule, $frm->$nextModule(),$moduleKey, $errorMessages);
						if(count($frm->$nextModule()) > 0)
							ReportValidator::validateModuleAmount(count($moduleValue), $moduleKey, 1, $errorMessages);
					}
				}
			}
			else
			{
				if(strlen($moduleValue) > 0)
				{
					ReportValidator::validateModuleIn($moduleValue, $moduleKey, $allowedModules, $errorMessages);
					/* Verifica se é um método existente e somente em caso positivo faz a validação */
					if( is_callable(array('ReportForm',$nextModule)) )
					{
						self::validateModuleRelationship($priorModule, $frm->$nextModule(),$moduleKey, $errorMessages);
						if(count($frm->$nextModule()) > 0)
							ReportValidator::validateModuleAmount(count($moduleValue), $moduleKey, 1, $errorMessages);
					}
				}
			}
		}
		
		return $errorMessages;
	}

	/**
	 * Valida Filtros
	 */
	public static function validateFilter(&$frm, &$errorMessages = null)
	{
		$resources = parent::getValidatorResources();

		static $dateType 		= 'date';
		static $varcharType 	= 'varchar';
		static $charType 		= 'char';
		static $intType 		= 'int';
		static $floatType 		= 'float';
		static $tinyintType 	= 'tinyint';
		static $timestampType 	= 'timestamp';

		/*
		 * Faz a validação dos campos
		 */
		foreach(range(1,4) as $number)
		{
			// Módulo
			$module 		= 'getModule'.$number;
			// Campo
			$filter 		= 'getFilter'.$number;
			$filterKey 		= 'filter'.$number;
			// Valor
			$filterValue 	= 'getFilterValue'.$number;
			$filterValueKey = 'filterValue'.$number;
			// Operador
			$operator 		= 'getOperator'.$number;
			$operator 		= $frm->$operator();
			$operatorKey 	= 'operator'.$number;
			
			/* Coluna para o filtro */
			$valFilter = $frm->$filter();
			if( (is_array($valFilter) && !Utils::arrayEmpty($valFilter)) ||
				(!is_array($valFilter) && !empty($valFilter)) )
			{
				// Se não for array, coloca o valor no array para ser tratado por um único código abaixo
				if(!is_array($frm->$filter()))
					$fields = array($valFilter);
				else
					$fields = $valFilter;
				if(!is_array($frm->$filterValue()))
					$values = array($frm->$filterValue());
				else
					$values = &$frm->$filterValue();
				if(!is_array($operator))
					$operators = array($operator);
				else
					$operators = $operator;
								
				// Flag que indica se um campo válido foi encontrado
				$found = FALSE;
				foreach($valFilter as $key => $field)
				{
					$columnInfo = self::getFieldInfo($frm->$module(), $field);
					if(!empty($columnInfo))
					{
						$found=TRUE;
						$type = $columnInfo[self::DATA_TYPE];
						if($type==$varcharType || $type==$charType)
						{
							/* Operador inválido para dados do tipo texto*/
							if($operators[$key] != Constants::EQUAL_KEY && $operators[$key] != Constants::DIFFERENT_KEY)
								$errorMessages[$operatorKey] = array(array($resources->report->operator->invalid));
							/* valida o campo */
							self::validateStringLength($values[$key], $frm->$filterValueKey(),0,$columnInfo[self::LENGTH], $errorMessages);
						}
						elseif($type==$dateType || $type==$timestampType)
						{
							/* valida o campo */							
							$values[$key] = BasicForm::dateFormat($values[$key]);
							self::validateDate($values[$key], $frm->$filterValueKey(), $errorMessages);		
							$set = 'setFilterValue'.$number;
							$frm->$set($values[$key]);										
						}
						elseif($type==$intType || $type==$floatType || $type==$tinyintType)
						{
							/* valida o campo */
							self::validateInt($values[$key], $frm->$filterValueKey(), $errorMessages);
						}
					}
				}
				if(!$found)
				{
					/* O campo não foi encontrado */
					$errorMessages[$frm->$filterKey()] = array(array($resources->notEmpty->isEmpty));
				}
			}
		}
		return $errorMessages;
	}
	/**
	 * Valida o campo OrderBy
	 */
	public static function validateOrderBy(ReportForm &$frm, &$errorMessages = null)
	{
		foreach(range(1,4) as $number)
		{
			$order 		= 'getOrder'.$number;
			$orderKey 	= 'order'.$number;
			$orderBy = $frm->$order();
			if(is_array($orderBy))
			{
				if(array_values($orderBy) > 0)
				{
					foreach($orderBy as $current)
					{
						self::validateStringLength($orderBy, $orderKey,0,50, $errorMessages);
					}
				}
			}
			else
			{
				if(!empty($orderBy))
				{
					self::validateStringLength($orderBy, $orderKey,0,50, $errorMessages);
				}
			}
		}
		return $errorMessages;
	}

	/**
	 * Valida exibição de atendimento do tipo grupo
	 * @param AssistanceForm $frm
	 * @param String $errorMessages
	 * @param Boolean $validateFirstModule - Indica se deve aplicar validação no primeiro nível
	 */
	public static function validateData(AssistanceForm &$frm, &$errorMessages = null, $validateFirstModule)
	{
		/* Valida Id da pessoa */
		ReportValidator::validateModule($frm, $errorMessages, $validateFirstModule);
		ReportValidator::validateFilter($frm, $errorMessages);
		ReportValidator::validateOrderBy($frm, $errorMessages);
	}
}