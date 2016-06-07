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
 * Fabricio Meireles Monteiro  - W3S		    	28/03/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class AssistanceBusiness extends BasicBusiness
{
	
	/**
	 * Exclui registros da tabela "ast_assistance" dado o Id do programa
	 */
	 
//	método não mais utilizado porque registros não serão excluídos
//	public static function dropAssistanceByProgram($idProgram, &$db=null)
//	{	
//		if($db == null)
//		{
//			$db = Zend_Registry::get(DB_CONNECTION);
//			$db->beginTransaction();
//			$mt = true;
//		}
//
//		try
//		{
//			$obj = new Assistance();
//			if($idProgram)
//			{ 
//				$where = $obj->getAdapter()->quoteInto(AST_ID_PROGRAM.' in (?)', $idProgram);
//				$obj->delete($where);
//				
//				(is_array($idProgram)) ? $ids = implode(',', $idProgram) : $ids = $idProgram; 
//				Logger::loggerOperation('Registro excluído na tabela '.TBL_ASSISTANCE.
//					' ['.AST_ID_PROGRAM.'='.$ids.']');
//			}
//
//			if($mt) $db->commit();
//		}
//		catch(Zend_Exception $e)
//		{
//			$db->rollback();
//			$db->closeConnection();
//			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
//			trigger_error(parent::getLabelResources()->assistance->drop->fail, E_USER_ERROR);
//		}
//	}
	
	/**
	 * Popula um array contendo informações necessárias para 
	 * exibição de resumo de atendimentos do usuário 
	 */
	public static function buildAssistanceSummary($assistances)
	{
		$data = NULL;
		if(!empty($assistances))
		{
			if(is_array($assistances))
			{
				foreach($assistances as $assistance)
				{
					$ast = self::buildAssistance($assistance);
					if($ast !== null)
						$data[] = $ast;
				}
			}
		}
		return $data;
	}
	/**
	 * Popula um array com informações de um atendimento dependendo do tipo(geral, especial ou de grupo)
	 */ 
	public static function buildAssistance($assistance)
	{
		if(!empty($assistance))
		{			
			$assistance = AssistanceBusiness::load($assistance->{AST_ID_ASSISTANCE});
			$user = Utils::abbreviate($assistance->findParentRow(CLS_AUTH_USER)->{AUTH_NAME_USER}, 10);			
			$program = $assistance->findParentRow(CLS_PROGRAM);
			$assistType = Utils::getAssistanceClassification($program->{PGR_ID_PROGRAM_TYPE});
			/* Atendimento geral */
			if($assistType == Constants::GENERAL)
			{				
				$data = null;
				$general = $assistance->findDependentRowset(CLS_GENERALASSISTANCE);				
				$descMap = NULL;
				$i = 0;
				foreach($general as $current)
				{					
					if($current->{GAS_CONFIDENTIALITY} != Constants::VISIBILITY_PROFILE)
					{						
						$benefits = $current->findManyToManyRowset(CLS_ASSISTANCEBENEFITTYPE, CLS_ASSISTANCEBENEFIT);					
						foreach($benefits as $benefit)
						{
							$desc = Utils::abbreviate($benefit->{ABT_DESCRIPTION}, 8);
							// impede de repetir várias vezes o mesmo tipo na descrição
							if($i++ >= 3)
								break;
	
							$descMap[$desc]=$desc;
						}
					}
					else
					{
						$generalProfile = $current->findDependentRowset(CLS_ASSISTANCEPROFILE);						
						$userProfile = UserLogged::getProfiles();																						
						if((count($userProfile) > 0) && (count($generalProfile) > 0))
						{							
							foreach($userProfile as $profile)
							{
								foreach($generalProfile as $gnp)
								{	
									if($gnp->{AUTH_ID_PROFILE} == $profile->{AUTH_ID_PROFILE})
									{
										$benefit = $current->findManyToManyRowset(CLS_ASSISTANCEBENEFITTYPE, CLS_ASSISTANCEBENEFIT)->current();
										$desc = Utils::abbreviate($benefit->{ABT_DESCRIPTION}, 8);
										// impede de repetir várias vezes o mesmo tipo na descrição
										if($i++ >= 3)
											break;
				
										$descMap[$desc]=$desc;
									}
								}
							}
						}
						else if(UserLogged::isCoordinator())
						{
							$benefits = $current->findManyToManyRowset(CLS_ASSISTANCEBENEFITTYPE, CLS_ASSISTANCEBENEFIT);
							foreach($benefits as $benefit)
							{
								$desc = Utils::abbreviate($benefit->{ABT_DESCRIPTION}, 8);
								// impede de repetir várias vezes o mesmo tipo na descrição
								if($i++ >= 3)
									break;
		
								$descMap[$desc]=$desc;
							}
						}
					}
					
				}
				$assist[Constants::AST_SUMMARY_ID] = $assistance->{AST_ID_ASSISTANCE};
				if(is_array($descMap))
					$assist[Constants::AST_SUMMARY_DESC] = implode(', ',$descMap);
				else
					$assist[Constants::AST_SUMMARY_DESC] = $descMap;
					
				$assist[Constants::AST_SUMMARY_DESC] = $assist[Constants::AST_SUMMARY_DESC];
				$assist[Constants::AST_SUMMARY_PROGRAM_ID] = $program->{PGR_ID_PROGRAM_TYPE};
				$assist[Constants::AST_SUMMARY_TYPE] = Constants::GENERAL;
				$assist[Constants::AST_USER_NAME] = $user;
				return $assist;
			}
			/* Atendimento especial */
			if($assistType == Constants::ESPECIAL)
			{
				$especial = $assistance->findParentRow(CLS_ESPECIALASSISTANCE);
				$assist[Constants::AST_SUMMARY_ID] = $assistance->{AST_ID_ASSISTANCE};
				$assist[Constants::AST_SUMMARY_DESC] = null;
				$assist[Constants::AST_SUMMARY_PROGRAM_ID] = $program->{PGR_ID_PROGRAM_TYPE};
				$assist[Constants::AST_SUMMARY_TYPE] = Constants::ESPECIAL;
				$assist[Constants::AST_USER_NAME] = $user;
				return $assist;
			}
			/* Atendimento de grupo */
			if($assistType == Constants::GROUP)
			{
				$groups = $assistance->findManyToManyRowset(CLS_CLASSMODEL, CLS_CLASSASSISTANCE);
				$class = $groups->current();
				
				$assist[Constants::AST_SUMMARY_ID] = $assistance->{AST_ID_ASSISTANCE};
				$assist[Constants::AST_SUMMARY_DESC] = $class->{CLS_NAME};
				$assist[Constants::AST_SUMMARY_PROGRAM_ID] = $program->{PGR_ID_PROGRAM_TYPE};
				$assist[Constants::AST_SUMMARY_TYPE] = Constants::GROUP;
				$assist[Constants::AST_USER_NAME] = $user;
				return $assist;
			}
		}
		return NULL;
	}
	
	public static function getClassesAsArray(&$assistance)
	{
		$data = null;		
		if(!empty($assistance))
		{
			if($assistance instanceof Zend_Db_Table_Rowset)
			{
				foreach($assistance as $current)
				{					
					$class = self::getClasses($current);
					if(!empty($class))
						$data[] = $class; 
				}
			}
			else
			{
				$data[] = self::getClasses($assistance);
			}
		}
		return $data;
	}
	private static function getClasses(&$assistance)
	{
		if(!empty($assistance))
		{
			$classes = $assistance->findManyToManyRowset(CLS_CLASSMODEL, CLS_CLASSASSISTANCE);
			if(count($classes) == 1)
			{
				foreach($classes as $class)
				{
					$class->{CLS_VACANCY} = ClassBusiness::getVacancyByClassId($class->{CLS_ID_CLASS},$class->{CLS_VACANCY});
				}			
				$arrClasses = $classes->toArray();
				$status = $assistance->findDependentRowset(CLS_CLASSASSISTANCE);
				foreach($status as $st)
				{
					if($st->{CLA_ID_CLASS} == $arrClasses[0][CLA_ID_CLASS])
					{
						$arrClasses[0][CLA_ID_STATUS] = $st->{CLA_ID_STATUS};
					}
				}
			}
			else
			{
				foreach($classes as $class)
				{
					$class->{CLS_VACANCY} = ClassBusiness::getVacancyByClassId($class->{CLS_ID_CLASS},$class->{CLS_VACANCY});
					$arrClasses = $classes->toArray();
				}			
								
				$status = $assistance->findDependentRowset(CLS_CLASSASSISTANCE);
				foreach($status as $st)
				{
					foreach($arrClasses as $k=>$arr)
					{
						if($st->{CLA_ID_CLASS} == $arr[CLA_ID_CLASS])
						{
							$arrClasses[$k][CLA_ID_STATUS] = $st->{CLA_ID_STATUS};
						}
					}
				}				
			}
			
			return $arrClasses;
		}
		return null;
	}
	
	/**
	 * Popula um array com informações de um atendimento dependendo do tipo para detalhamento(geral, especial ou de grupo)
	 */ 
	public static function buildAssistanceDetail($id, $profile=NULL, $start=NULL, $limit=NULL)
	{
		if(!empty($id))
		{
			$assistance = AssistanceBusiness::load($id);
			$program = $assistance->findParentRow(CLS_PROGRAM);
			$entity = $program->findParentRow(CLS_ENTITY);
			$assistType = Utils::getAssistanceClassification($program->{PGR_ID_PROGRAM_TYPE});
			
			$assist[AST_ID_ASSISTANCE] = $assistance->{AST_ID_ASSISTANCE};
			$assist[AST_ID_PERSON] = $assistance->{AST_ID_PERSON};
			$assist[AST_BEGINNING_DATE] = $assistance->{AST_BEGINNING_DATE};
			$assist[AST_END_DATE_PREVISION] = $assistance->{AST_END_DATE_PREVISION};
			$assist[AST_REAL_END_DATE] = $assistance->{AST_REAL_END_DATE};
			$assist[AST_CONFIDENTIALITY] = $assistance->{AST_CONFIDENTIALITY};
			$assist[AST_ID_PROGRAM] = $program->{PGR_ID_PROGRAM_TYPE};
			$assist[CLS_ENTITY.ENT_NAME] = $entity->{ENT_NAME};
			
			/* Atendimento geral */
			if($assistType == Constants::GENERAL)
			{
				$assist[Constants::AST_SUMMARY_TYPE] = Constants::GENERAL;
				
				$general = self::loadAllGeneralByAssistanceId($assistance->{AST_ID_ASSISTANCE}, $profile, $start, $limit);
				$assist[Constants::GENERAL_ASSISTANCE_OBJECT] = $general;

				return $assist;
			}
			/* Atendimento especial */
			if($assistType == Constants::ESPECIAL)
			{
				$assist[Constants::AST_SUMMARY_TYPE] = Constants::ESPECIAL;
				
				$especial = $assistance->findParentRow(CLS_ESPECIALASSISTANCE);
				if(count($especial) > 0)
				{
					$officialOrigin = $especial->findParentRow(CLS_OFFICIALLETTERORIGIN);
					$lawsuitOrigin = $especial->findParentRow(CLS_LAWSUITORIGIN);
								
					$assist[EAS_OFFICIAL_LETTER_NUMBER] = $especial->{EAS_OFFICIAL_LETTER_NUMBER};
					$assist[EAS_OFFICIAL_LETTER_YEAR] = $especial->{EAS_OFFICIAL_LETTER_YEAR};
					$assist[EAS_ID_OFFICIAL_LETTER_ORIGIN] = $officialOrigin->{OLO_OFFICIAL_LETTER_ORIGIN};
					$assist[EAS_LAWSUIT_NUMBER] = $especial->{EAS_LAWSUIT_NUMBER};
					$assist[EAS_LAWSUIT_YEAR] = $especial->{EAS_LAWSUIT_YEAR};
					$assist[EAS_LAWSUIT_DETAIL] = $especial->{EAS_LAWSUIT_DETAIL};
					$assist[EAS_ID_LAWSUIT_ORIGIN] = $lawsuitOrigin->{LWO_LAWSUIT_ORIGIN};
					$assist[EAS_RULING] = $especial->{EAS_RULING};
				}
				return $assist;
			}
			/* Atendimento de grupo */
			if($assistType == Constants::GROUP)
			{
				$assist[Constants::AST_SUMMARY_TYPE] = Constants::GROUP;
				
				$groups = $assistance->findManyToManyRowset(CLS_CLASSMODEL, CLS_CLASSASSISTANCE);	
				
				foreach($groups as $group){
					$class = $group->findManyToManyRowset(CLS_ACTIVITYDETAIL, CLS_ACTIVITYCLASS);
					foreach($class as $cls){
						$category = $cls->findParentRow(CLS_CATEGORY);
						
						$assist[CLS_VACANCY][] = $group->{CLS_VACANCY};
						$assist[CLS_SCHEDULE][] = $group->{CLS_SCHEDULE};
						$assist[CLS_PERIOD][] = $group->{CLS_PERIOD};
						$assist[CLS_NAME][] = $group->{CLS_NAME};
						$assist[CLS_PERIOD][]= $group->{CLS_PERIOD};
						$assist[ACD_ACTIVITY_DETAIL][] = $cls->{ACD_ACTIVITY_DETAIL};
						$assist[ACD_HOURLY_LOAD][] = $cls->{ACD_HOURLY_LOAD};
						$assist[CAT_CATEGORY][] = $category->{CAT_CATEGORY};
					}
				}			
				return $assist;
			}
		}
		return NULL;
	}
	
	public static function buildAssistanceDetailGeneral($id, $profile=NULL, $start=NULL, $limit=NULL)
	{
		if(!empty($id))
		{
			$assistance = AssistanceBusiness::load($id);
			$program = $assistance->findParentRow(CLS_PROGRAM);
			$entity = $program->findParentRow(CLS_ENTITY);
//			$assistType = Utils::getAssistanceClassification($program->{PGR_ID_PROGRAM_TYPE});?
			
			$assist[AST_ID_ASSISTANCE] = $assistance->{AST_ID_ASSISTANCE};
			$assist[AST_ID_PERSON] = $assistance->{AST_ID_PERSON};
			$assist[AST_BEGINNING_DATE] = $assistance->{AST_BEGINNING_DATE};
			$assist[AST_END_DATE_PREVISION] = $assistance->{AST_END_DATE_PREVISION};
			$assist[AST_REAL_END_DATE] = $assistance->{AST_REAL_END_DATE};
			$assist[AST_CONFIDENTIALITY] = $assistance->{AST_CONFIDENTIALITY};
			$assist[AST_ID_PROGRAM] = $program->{PGR_ID_PROGRAM_TYPE};
			$assist[CLS_ENTITY.ENT_NAME] = $entity->{ENT_NAME};
			
			$assist[Constants::AST_SUMMARY_TYPE] = Constants::GENERAL;
			
			$general = self::loadAllGeneralByAssistanceId($assistance->{AST_ID_ASSISTANCE}, $profile, $start, $limit);
			$assist[Constants::GENERAL_ASSISTANCE_OBJECT] = $general;

			return $assist;
		}
		return NULL;
	}
	
	/**
	 * Monta as condições para buscar por todos os atendimentos de uma pessoa
	 */
	public static function createConditionsToLoadAssistences($id, $entity=NULL, $endDate=NULL)
	{
		$obj = new Assistance();
			
		try
		{			
			$idAssistance =	array();		
				
			$where[] = $obj->getAdapter()->quoteInto(AST_ID_PERSON.' = ?', $id);
			
			if($endDate == Constants::ONE)
				$where[] = $obj->getAdapter()->quoteInto(AST_REAL_END_DATE.' is null');
			elseif($endDate == Constants::TWO)
				$where[] = $obj->getAdapter()->quoteInto(AST_REAL_END_DATE.' is not null');
			
			$resAssistance = $obj->fetchAll($where);
			
			foreach($resAssistance as $assistance)
			{
				$program = $assistance->findParentRow(CLS_PROGRAM);
				if($assistance->{AST_CONFIDENTIALITY})
				{					
					if($program->{PGR_ID_ENTITY} == UserLogged::getEntityId())
					{						
						if($entity)
						{							
							if($program->{PGR_ID_ENTITY} == $entity)
							{								
								if(!array_search($assistance->{AST_ID_ASSISTANCE}, $idAssistance))
								{
									$idAssistance[] = $assistance->{AST_ID_ASSISTANCE};
								}
							}
						}
						else
						{
							if(!array_search($assistance->{AST_ID_ASSISTANCE}, $idAssistance))
							{
								$idAssistance[] = $assistance->{AST_ID_ASSISTANCE};
							}
						}
					}
				}
				else
				{					
					if($entity)
					{							
						if($program->{PGR_ID_ENTITY} == $entity)
						{
							if(!array_search($assistance->{AST_ID_ASSISTANCE}, $idAssistance))
							{
								$idAssistance[] = $assistance->{AST_ID_ASSISTANCE};
							}
						}
					}
					else
					{
						if(!array_search($assistance->{AST_ID_ASSISTANCE}, $idAssistance))
						{
							$idAssistance[] = $assistance->{AST_ID_ASSISTANCE};
						}
					}			
				}
			}			
			$idAssistance = array_unique($idAssistance);			
			
			$whereId[AST_ID_ASSISTANCE.' in (?)'] = $idAssistance;
			
			return $whereId;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * resgata atendimentos de uma pessoa
	 */
	public static function loadAllAssistancePerson($where, $start=NULL, $limit=NULL)
	{
		$obj = new Assistance();
		try
		{											
			foreach($where as $k => $v)
				$whereAssist[] = $obj->getAdapter()->quoteInto($k, $v);
			
			$order = AST_BEGINNING_DATE.' desc';
			
			$resAssistance = $obj->fetchAll($whereAssist, $order, $limit, $start);
			$arrAssistance = array();			
			if(count($resAssistance) > 0)
			{
				foreach($resAssistance as $assistance)
				{
					$program = $assistance->findParentRow(CLS_PROGRAM);
					$programType = $program->findParentRow(CLS_PROGRAMTYPE);
					$entity = $program->findParentRow(CLS_ENTITY);
					$assistType = Utils::getAssistanceClassification($program->{PGR_ID_PROGRAM_TYPE});
					
					if($assistType == Constants::GENERAL)
					{
						$profile = Utils::getProfileIdAsArray();
						if(is_null($profile))
						{
							$general = self::loadAllGeneralByAssistanceId($assistance->{AST_ID_ASSISTANCE}, null, 0, 3);
						}
						else
							$general = self::loadAllGeneralByAssistanceId($assistance->{AST_ID_ASSISTANCE}, $profile, 0, 3);
						
						$arrGeneral[AST_ID_ASSISTANCE] 	= $assistance->{AST_ID_ASSISTANCE};
						$arrGeneral[AST_BEGINNING_DATE] = $assistance->{AST_BEGINNING_DATE};										
						$arrGeneral[PGT_PROGRAM_TYPE] 	= $programType->{PGT_PROGRAM_TYPE};
						$arrGeneral[ENT_NAME] 			= $entity->{ENT_NAME};
						$arrGeneral[ABT_DESCRIPTION] = null;
						if(count($general) > 0)
						{
							$i=0;
							$descMap=null;
							foreach($general as $gen)
							{
								$benefits 	= $gen->findManyToManyRowset(CLS_ASSISTANCEBENEFITTYPE, CLS_ASSISTANCEBENEFIT);
								foreach($benefits as $benefit)
								{
									$desc = Utils::abbreviate($benefit->{ABT_DESCRIPTION}, 8);
									// impede de repetir várias vezes o mesmo tipo na descrição
									if($i++ >= 3)
										break;
			
									$descMap[$desc]=$desc;
								}
							}
						}
						if(is_array($descMap))
							$arrGeneral[ABT_DESCRIPTION] = implode(', ',$descMap);
						else
							$arrGeneral[ABT_DESCRIPTION] = $descMap;
						$arrAssistance[] = $arrGeneral;
					}
					else if($assistType == Constants::ESPECIAL)
					{
						$arrEspecial[AST_ID_ASSISTANCE] 	= $assistance->{AST_ID_ASSISTANCE};
						$arrEspecial[AST_BEGINNING_DATE] 	= $assistance->{AST_BEGINNING_DATE};										
						$arrEspecial[PGT_PROGRAM_TYPE] 		= $programType->{PGT_PROGRAM_TYPE};
						$arrEspecial[ENT_NAME] 				= $entity->{ENT_NAME};	
						$arrEspecial[EAS_RULING]			= Constants::ESPECIAL;
						
						$arrAssistance[] = $arrEspecial;
					}
					else
					{
						$class = $assistance->findManyToManyRowset(CLS_CLASSMODEL, CLS_CLASSASSISTANCE);
						
						$arrGroup[AST_ID_ASSISTANCE] 	= $assistance->{AST_ID_ASSISTANCE};
						$arrGroup[AST_BEGINNING_DATE] 	= $assistance->{AST_BEGINNING_DATE};										
						$arrGroup[PGT_PROGRAM_TYPE] 	= $programType->{PGT_PROGRAM_TYPE};
						$arrGroup[ENT_NAME] 			= $entity->{ENT_NAME};	
						
						if(count($class) > 0)
						{	
							foreach($class as $cls)
								$arrGroup[CLS_CLASSMODEL.CLS_NAME]		= $cls->{CLS_NAME};
						}
						else
						{
							$arrGroup[CLS_CLASSMODEL.CLS_NAME]			= Constants::GROUP;
						}							
						$arrAssistance[] = $arrGroup;
					}																											
				}																
			}
			return $arrAssistance;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega os dados de atendimento da entidade do usuário logado
	 * @param Integer $personId ID da pessoa
	 * @param Integer/Array $idProgram ID do tipo de programa
	 */
	public static function loadAllInProcessAssistance($personId = NULL, $idProgram = NULL)
	{
	 	$entityId = UserLogged::getEntityId();
	 	if(!empty($personId))
	 	{
			try
			{
				Zend_Loader::loadClass(CLS_ASSISTANCE);
				Zend_Loader::loadClass(CLS_PROGRAM);

				$db = Zend_Registry::get(DB_CONNECTION);
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$select = $db->select()
		    		->from(array('ast' => TBL_ASSISTANCE))
		    		->joinInner(array('prg' => TBL_PROGRAM),
		        		'prg.'. PGR_ID_PROGRAM .' = ast.'. AST_ID_PROGRAM);
				// filtra por entidade se o usuário está associado a uma entidade
				if(!empty($entityId))
					$select->where('prg.'. PGR_ID_ENTITY .' = ?', $entityId );
					
				$select->where('ast.'. AST_REAL_END_DATE .' is null', null )
						->where('ast.'. AST_ID_PERSON .' = ?', $personId );
						
				if($idProgram !== NULL)
					$select->where('prg.'. PGR_ID_PROGRAM_TYPE .' in (?)', $idProgram );
				
				$select->order('ast.'.AST_ID_ASSISTANCE.' DESC');

				$res = $db->fetchAll($select);
			}
			catch(Zend_Exception $e)
			{
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
				trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
			}
	 	}
	 	return $res;
	}
	/**
	 * Retorna um array com os identificadores de turma dado uo objeto de Atendimento
	 * @param Object $assistance - Objeto de banco representando atendimentos
	 * @return Array Id's de turma
	 */
	public static function getClassesIdAsArray($assistance)
	{
		$array = null;
		if(!empty($assistance))
		{
			foreach($assistance as $current)
			{
				$classes = $current->findManyToManyRowset(CLS_CLASSMODEL, CLS_CLASSASSISTANCE);
				foreach($classes as $class)
				{
					$array[$class->{CLS_ID_CLASS}] = $class->{CLS_ID_CLASS};
				}
			}
		}
		return $array;
	}
	/**
	 * Verifica se um atendimento pode ser exibido ao usuário logado
	 */
	public static function isVisibleAssistance($assistanceId)
	{
		$entityId = UserLogged::getEntityId();
		if(!empty($entityId) && !empty($assistanceId))
		{
			try
			{
				$row = self::load($assistanceId);
				if(!empty($row) && count($row) > 0)
				{
					$endDate = $row->{AST_REAL_END_DATE};
					if(!empty($endDate))
					{
						return FALSE;
					}
					$program  = $row->findParentRow(CLS_PROGRAM);
					if(!empty($program) && count($program) > 0)
					{
						if($program->{PGR_ID_ENTITY} == $entityId)
							return TRUE;
					}
				}
			}
			catch(Zend_Exception $e)
			{
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '. $e);
				trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
			}
		}
		return FALSE;
	}
	/**
	 * Encerra um atendimento dado o Identificador do atendimento
	 * @param Integer $assistanceId ID do atendimento
	 * @return Integer Quantidade de registros afetados
	 */
	public static function closeAssistance($assistanceId, $classId=null)
	{
		if(!empty($assistanceId))
		{
			$assistance = AssistanceBusiness::load($assistanceId);
			if(!empty($assistance))
			{
				if(is_null($classId))
				{
					Zend_Loader::loadClass('ClassBusiness');
					$program = $assistance->findParentRow(CLS_PROGRAM);
					
//					$type = Utils::getAssistanceClassification($program->{PGR_ID_PROGRAM_TYPE});
//					
//					if($type == Constants::GROUP)
//					{
//						Zend_Loader::loadClass(CLS_CLASSASSISTANCE);
//						$data[CLA_ID_ASSISTANCE. ' = ?'] = $assistanceId;
//						$del = ClassBusiness::deleteByQuery($data);
//					}	
//					unset($data);
					$data[AST_ID_ASSISTANCE] = $assistanceId;
					$data[AST_REAL_END_DATE] = date('c', time());
					$up = AssistanceBusiness::save( $data );
					
					// Retorna a quantidade de registros afetados
					return ($up);
				}
				else
				{
					Zend_Loader::loadClass(CLS_CLASSASSISTANCE);
					
					$objClass = new ClassAssistance();
					$row = $objClass->find($classId, $assistanceId)->current();
					
					if($row->{CLA_ID_STATUS} != Constants::FINISHED)
					{
						$data = array(
							CLA_ID_STATUS => Constants::FINISHED
						);
						
						$where[] = $objClass->getAdapter()->quoteInto(CLA_ID_ASSISTANCE.' = ?',$assistanceId);
						$where[] = $objClass->getAdapter()->quoteInto(CLA_ID_CLASS.' = ?',$classId);
						
						$up = $objClass->update($data, $where);
					}
					
					if(count($assistance->findDependentRowset(CLS_CLASSASSISTANCE)) == 1)
					{
						Zend_Loader::loadClass('ClassBusiness');
						$program = $assistance->findParentRow(CLS_PROGRAM);
						$data[AST_ID_ASSISTANCE] = $assistanceId;
						$data[AST_REAL_END_DATE] = date('c', time());
						$up = AssistanceBusiness::save( $data );												
					}
					
					return $up;				
				}
			}
		}
	}

	/**
	 * Carrega um registro
	 */
	public static function load($id)
	{
		Zend_Loader::loadClass(CLS_ASSISTANCE);
		$table = new Assistance();

		try
		{
			if(!empty($id))
			{
				return $table->find($id)->current();
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.AST_ID_ASSISTANCE.' = '.$id .' na tabela '.TBL_ASSISTANCE);
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega um registro da tabela GeneralAssistance
	 * @param Integer $id - Id (id_general_assistance) ou Array (id_assistance, id_general_assistance)
	 * @return Object Retorna o registro solicitado 
	 */
	public static function loadGeneralAssistance($id)
	{
		$table = new GeneralAssistance();

		try
		{
			if(!empty($id))
			{
				return $table->find($id)->current();
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.GAS_ID_GENERAL_ASSISTANCE.' = '.$id .' na tabela '.TBL_ASSISTANCE);
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Retorna a quantidade de registros(Usado juntamente com o método loadAllGeneralByAssistanceId)
	 * @param Integer $id - Id (id_assistance)
	 * @return Integer Retorna a quantidade de registros encontrados
	 **/
	public static function countGeneralAssistance($id, $profile=null)
	{
		if(!empty($id))
		{
			try
			{
				Zend_Loader::loadClass(CLS_ASSISTANCE);
				Zend_Loader::loadClass(CLS_PROGRAM);
				Zend_Loader::loadClass(CLS_ASSISTANCEPROFILE);

				$db = Zend_Registry::get(DB_CONNECTION);
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				if(UserLogged::isTechnician())
				{
					if($profile == NULL)
					throw new Zend_Exception(parent::getLabelResources()->profile->required->fail);
				}
				$query = self::buildGeneralQuery($id, $profile, NULL, NULL, TRUE);

				$stmt = $db->query($query);
				$rows = $stmt->fetchAll();
				foreach($rows as $row)
				{
					return $row->{'count'};
				}
				
				$res = count($rows);
			}
			catch(Zend_Exception $e)
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
				trigger_error($e->getMessage().' - '.parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
			}
		}
		return $res;
	}

	/**
	 * Carrega todos os registros da tabela GeneralAssistance dado ID do Atendimento
	 * @param Integer $id - Id (id_assistance)
	 * @return Object Retorna o registro solicitado 
	 **/
	public static function loadAllGeneralByAssistanceId($id, $profile=NULL, $start=NULL, $limit=NULL)
	{
		if(!empty($id))
		{
			try
			{
				Zend_Loader::loadClass(CLS_ASSISTANCE);
				Zend_Loader::loadClass(CLS_PROGRAM);
				Zend_Loader::loadClass(CLS_ASSISTANCEPROFILE);

				$db = Zend_Registry::get(DB_CONNECTION);
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
//				if(UserLogged::isTechnician())
//				{
//					if($profile == NULL)
//					throw new Zend_Exception(parent::getLabelResources()->profile->required->fail);
//				}
				$query = self::buildGeneralQuery($id, $profile, $start, $limit);
//				$query = self::buildGeneralQuery($id, Utils::getProfileIdAsArray(), $start, $limit);
				
				$stmt = $db->query($query);
				$rows = $stmt->fetchAll();
				
				foreach($rows as $row)
					$idGeneralAssistance[]=$row->{GAS_ID_GENERAL_ASSISTANCE};

				$general = new GeneralAssistance();
				$where = $general->getAdapter()->quoteInto(GAS_ID_GENERAL_ASSISTANCE . ' in (?)', $idGeneralAssistance);
				$order = GAS_ID_GENERAL_ASSISTANCE . ' DESC'; 
				$res = $general->fetchAll($where, $order);
			}
			catch(Zend_Exception $e)
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
				trigger_error($e->getMessage(), E_USER_ERROR);
			}
		}
		return $res;
	}

	/**
	 * Constrói o script sql para busca de 
	 */
	public static function buildGeneralQuery($assistanceId, $profile=null, $start=NULL, $limit=NULL, $isCount=false)
	{
		if(!empty($assistanceId))
		{
			$entityId = UserLogged::getEntityId();
			if($profile != null)
			{
				if(is_array($profile))
					$profiles = implode(',',$profile);
				else
					$profiles = $profile;
			}
			
//			if(UserLogged::isCoordinator())
//			{
//				Zend_Loader::loadClass('ProfileBusiness');
//				$rows = ProfileBusiness::
//			}
			Zend_Loader::loadClass(CLS_ASSISTANCE);
			Zend_Loader::loadClass(CLS_PROGRAM);
			Zend_Loader::loadClass(CLS_ASSISTANCEPROFILE);
			Zend_Loader::loadClass(CLS_GENERALASSISTANCE);

			/*
			 * Produz o script

				SELECT g.id_general_assistance 
				FROM ast_assistance ast, ast_program prog, gas_general_assistance g 
				left join ast_assistance_profile p ON g.id_general_assistance = p.id_general_assistance 
				WHERE ast.id_assistance = $assistanceId 
				AND ast.id_program = prog.id_program 
				AND (ast.confidentiality = 0 
					OR (ast.confidentiality = 1 
						AND prog.id_entity = $entityId)) 
				AND g.id_assistance = ast.id_assistance 
				AND (g.confidentiality = 1 
					OR (g.confidentiality = 2 
						AND prog.id_entity = $entityId) 
					OR (g.confidentiality = 3 
					AND p.id_profile IN ( $profiles )) ) 
				ORDER BY g.id_general_assistance DESC 
				LIMIT $start,$limit
			 */

			if($isCount === true)
				$query = 'SELECT COUNT(g.'.GAS_ID_GENERAL_ASSISTANCE.') as count';
			else
				$query = 'SELECT g.'.GAS_ID_GENERAL_ASSISTANCE;
			
			$query = $query .
				' FROM '.TBL_ASSISTANCE.' ast, '.TBL_PROGRAM.' prog, '.
						TBL_GENERAL_ASSISTANCE .' g left join '. TBL_ASSISTANCE_PROFILE.' p'.
						' ON g.'.GAS_ID_GENERAL_ASSISTANCE.' = p.'.AAP_ID_ASSISTANCE.
				' WHERE ast.'.AST_ID_ASSISTANCE.' = '.$assistanceId.
				' AND ast.'.AST_ID_PROGRAM.' = prog.'.PGR_ID_PROGRAM.
				' AND (ast.'.AST_CONFIDENTIALITY.' = '. Constants::CONFIDENTIALITY_PUBLIC.
				' OR (ast.'.AST_CONFIDENTIALITY.' = '. Constants::CONFIDENTIALITY_ENTITY;
			// Caso o usuário possua entidade associada, utiliza o $entityId no filtro
			if(!empty($entityId))
			{
				$query = $query .
					' AND prog.'.PGR_ID_ENTITY .' = '.$entityId;
			}
			
			$query = $query .
					'))'.
				' AND g.'.GAS_ID_ASSISTANCE.' = ast.'.AST_ID_ASSISTANCE.
				' AND (g.'.GAS_CONFIDENTIALITY.' = '. Constants::VISIBILITY_PUBLIC.
				' OR (g.'.GAS_CONFIDENTIALITY.' = '. Constants::VISIBILITY_ENTITY;
			// Caso o usuário possua entidade associada, utiliza o $entityId no filtro
			if(!empty($entityId))
			{
				$query = $query .
					' AND prog.'.PGR_ID_ENTITY .' = '.$entityId;
			}
			
			$query = $query .')';
			if($profiles != NULL)
			{
				$query = $query .
					' OR (g.'.GAS_CONFIDENTIALITY.' = '. Constants::VISIBILITY_PROFILE .
					' AND p.'.AAP_ID_PROFILE.' IN ('.$profiles.')) ';
			}
			else if(UserLogged::isCoordinator() || UserLogged::isManager() || UserLogged::isAdministrator())
			{
				$query = $query .
					' OR (g.'.GAS_CONFIDENTIALITY.' = '. Constants::VISIBILITY_PROFILE .') ';
			}
			$query = $query . ') ORDER BY g.'.GAS_ID_GENERAL_ASSISTANCE.' DESC';
				
			if($start !== null && $limit !== null)
				$query = $query . ' LIMIT '.$start .','. $limit;
		}
		return $query;
	}

	/**
	 * Carrega todos os registros da tabela gas_assistance_benefit_type
	 */
	public static function loadAllBenefitType()
	{
		Zend_Loader::loadClass('AssistanceBenefitType');
		$table = new AssistanceBenefitType();

		try
		{
			$where = $table->getAdapter()->quoteInto(ABT_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadBenefitType($id)
	{
		Zend_Loader::loadClass('AssistanceBenefitType');
		$table = new AssistanceBenefitType();

		try
		{
			$where[] = $table->getAdapter()->quoteInto(ABT_ID_ASSISTANCE_BENEFIT_TYPE.' in (?)', $id);
			$where[] = $table->getAdapter()->quoteInto(ABT_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchRow($where);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega todos os registros de OfficialLetterOrigin
	 */
	public static function loadAllOfficialLetter()
	{
		Zend_Loader::loadClass('OfficialLetterOrigin');
		$table = new OfficialLetterOrigin();

		try
		{
			return $table->fetchAll();
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}

	public static function loadOfficialLetter($id)
	{
		Zend_Loader::loadClass('OfficialLetterOrigin');
		$table = new OfficialLetterOrigin();

		try
		{
			$where = $table->getAdapter()->quoteInto(OLO_ID_OFFICIAL_LETTER_ORIGIN.' in (?)', $id);
			return $table->fetchRow($where);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega todos os registros de LawsuitOrigin
	 */
	public static function loadAllLawsuit()
	{
		Zend_Loader::loadClass('LawsuitOrigin');
		$table = new LawsuitOrigin();

		try
		{
			return $table->fetchAll();
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadLawsuit($id)
	{
		Zend_Loader::loadClass('LawsuitOrigin');
		$table = new LawsuitOrigin();

		try
		{
			$where = $table->getAdapter()->quoteInto(LWO_ID_LAWSUIT_ORIGIN.' in (?)', $id);
			return $table->fetchRow($where);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega dados de atendimento dado um array contendo filtro
	 * $query[nome_da_coluna . ' clausula ' . ' ? '] = 'valor_da_coluna'
	 * EX: $query[nome_da_coluna . ' in ' . ' (?) '] = 'valor_da_coluna'
	 */
	public static function loadByQuery($query)
	{
		$table = new Assistance();

		try
		{
			if(!empty($query) && is_array($query))
			{
				$where = NULL;
				foreach($query as $k => $v)
					$where[] = $table->getAdapter()->quoteInto($k, $v);
				
				return $table->fetchAll($where);
			}
			return $table->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Insere ou edita um atendimento
	 * @param Assistance $assistance - objeto assistance
	 * @param Connection $db - objeto contendo a conexão
	 */
	public static function save($assistance, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$assistanceObj = new Assistance();

			if(Utils::isEmpty($assistance[AST_ID_ASSISTANCE]))
			{
				$res = $assistanceObj->insert($assistance);
				Logger::loggerOperation('Novo atendimento adicionado. [id='.$res.']');
			}
			else
			{
				$where = AST_ID_ASSISTANCE . ' = ' . $assistance[AST_ID_ASSISTANCE];
				$res = $assistanceObj->update($assistance, $where);
				Logger::loggerOperation('Atendimento modificado. [id='.$assistance[AST_ID_ASSISTANCE].']');
			}
			if($mt) $db->commit();
			return $res;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage(). ' - '.$e);
			trigger_error(parent::getLabelResources()->assistance->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Insere ou edita um atendimento
	 * @param Assistance $assistance - objeto assistance
	 * @param Connection $db - objeto contendo a conexão
	 */
	public static function saveEspecialAssistance($assistance, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new EspecialAssistance();

			$rows = count($obj->find($assistance[EAS_ID_ASSISTANCE]));
			if($rows == 0)
			{
				$res = $obj->insert($assistance);
				Logger::loggerOperation('Novo atendimento adicionado. [id='.$res.']');
			}
			else
			{
				$where = $obj->getAdapter()->quoteInto(EAS_ID_ASSISTANCE.' = ?', $assistance[EAS_ID_ASSISTANCE]);
				$res = $obj->update($assistance, $where);
				Logger::loggerOperation('Atendimento Especial modificado. [id='.$assistance[EAS_ID_ASSISTANCE].']');
			}
			if($mt) $db->commit();
			
			return $res;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->assistance->save->fail, E_USER_ERROR);
		}
	}

	/**
	 * Insere ou edita um atendimento geral
	 * @param Assistance $assistance - objeto assistance
	 * @param Connection $db - objeto contendo a conexão
	 */
	public static function saveGeneralAssistance($assistance, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			Zend_Loader::loadClass('GeneralAssistance');
			$obj = new GeneralAssistance();
			$rows = count($obj->find($assistance[GAS_ID_ASSISTANCE], $assistance[GAS_ID_GENERAL_ASSISTANCE]));
			if($rows == 0)
			{
				$obj->insert($assistance);
				$res = $obj->getAdapter()->lastInsertId();
				Logger::loggerOperation('Novo atendimento adicionado. [id='.implode(',',$res).']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(GAS_ID_ASSISTANCE.' = ?', $assistance[GAS_ID_ASSISTANCE]);
				$where[] = $obj->getAdapter()->quoteInto(GAS_ID_GENERAL_ASSISTANCE.' = ?', $assistance[GAS_ID_GENERAL_ASSISTANCE]);
				
				$obj->update($assistance, $where);
				$res = $assistance[GAS_ID_GENERAL_ASSISTANCE];
				Logger::loggerOperation('Registro modificado na tabela '.TBL_GENERAL_ASSISTANCE.
					' ['.GAS_ID_ASSISTANCE.'='.$assistance[GAS_ID_ASSISTANCE].']'.
					' ['.GAS_ID_GENERAL_ASSISTANCE.'='.$assistance[GAS_ID_GENERAL_ASSISTANCE].']');
			}
			if($mt) $db->commit();

			return $res;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->assistance->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Insere ou edita um atendimento geral
	 * @param Assistance $assistance - objeto assistance
	 * @param Connection $db - objeto contendo a conexão
	 */
	public static function saveAssistanceBenefit($assistance, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			Zend_Loader::loadClass('AssistanceBenefit');
			$obj = new AssistanceBenefit();
			$rows = count(
				$obj->find($assistance[ABF_ID_ASSISTANCE], 
							$assistance[ABF_ID_GENERAL_ASSISTANCE],
							$assistance[ABF_ID_ASSISTANCE_BENEFIT_TYPE])
				);
			if($rows == 0)
			{
				$res = $obj->insert($assistance);
				Logger::loggerOperation('Novo atendimento adicionado. [id='.$res.']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(ABF_ID_ASSISTANCE.' = ?', $assistance[ABF_ID_ASSISTANCE]);
				$where[] = $obj->getAdapter()->quoteInto(ABF_ID_GENERAL_ASSISTANCE.' = ?', $assistance[ABF_ID_GENERAL_ASSISTANCE]);
				$where[] = $obj->getAdapter()->quoteInto(ABF_ID_ASSISTANCE_BENEFIT_TYPE.' = ?', $assistance[ABF_ID_ASSISTANCE_BENEFIT_TYPE]);

				$res = $obj->update($assistance, $where);
				Logger::loggerOperation('Registro modificado na tabela '.TBL_GENERAL_ASSISTANCE.
					' ['.ABF_ID_ASSISTANCE.'='.$assistance[ABF_ID_ASSISTANCE].']'.
					' ['.ABF_ID_GENERAL_ASSISTANCE.'='.$assistance[ABF_ID_GENERAL_ASSISTANCE].']'.
					' ['.ABF_ID_ASSISTANCE_BENEFIT_TYPE.'='.$assistance[ABF_ID_ASSISTANCE_BENEFIT_TYPE].']');
			}
			if($mt) $db->commit();
			
			return $res;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->assistance->save->fail, E_USER_ERROR);
		}
	}
	public static function saveAllAssistanceProfile($arrayAssistanceProfile, $db=null)
	{
		try
		{
			$i = 0;
			foreach($arrayAssistanceProfile as $current)
			{
				$res = AssistanceBusiness::saveAssistanceProfile($current, $db);
				if(is_array($res) && count($res)> 0)$i++; 
				elseif($res > 0)$i++;
			}
			if($i == 0)
				throw new Zend_Exception(parent::getLabelResources()->assistanceprofile->save->fail .' Table: '. TBL_ASSISTANCE_PROFILE);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
			trigger_error(parent::getLabelResources()->assistanceprofile->save->fail, E_USER_ERROR);
		}
	}
	/**
	 * Insere ou edita um perfil de atendimento
	 * @param Assistance $assistance - objeto assistance
	 * @param Connection $db - objeto contendo a conexão
	 */
	public static function saveAssistanceProfile($assistance, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			Zend_Loader::loadClass(CLS_ASSISTANCEPROFILE);
			$obj = new AssistanceProfile();
			$rows = count($obj->find($assistance[AAP_ID_ASSISTANCE], $assistance[AAP_ID_PROFILE]));
			if($rows == 0)
			{
				$res = $obj->insert($assistance);
				Logger::loggerOperation('Registro adicionado na tabela '.TBL_ASSISTANCE_PROFILE.' [id='.implode(',',$res).']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(AAP_ID_ASSISTANCE.' = ?', $assistance[AAP_ID_ASSISTANCE]);
				$where[] = $obj->getAdapter()->quoteInto(AAP_ID_PROFILE.' = ?', $assistance[AAP_ID_PROFILE]);
				
				$obj->update($assistance, $where);
				$res = array($assistance[AAP_ID_ASSISTANCE], $assistance[AAP_ID_PROFILE]);
				Logger::loggerOperation('Registro modificado na tabela '.TBL_GENERAL_ASSISTANCE.
					' ['.AAP_ID_ASSISTANCE.'='.$assistance[AAP_ID_ASSISTANCE].']'.
					' ['.AAP_ID_PROFILE.'='.$assistance[AAP_ID_PROFILE].']');
			}
			if($mt) $db->commit();

			return $res;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
			trigger_error(parent::getLabelResources()->assistance->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Verifica se pessoa possui o tipo de atendimento de determinada entidade
	 */
	public static function verifyPersonProgramTypeByEntity($idProgramType, $idPerson, $idEntity)
	{
		try
		{
			Zend_Loader::loadClass(CLS_PROGRAM);
			$progObj = new Program();
			$where = $progObj->getAdapter()->quoteInto(PGR_ID_ENTITY.' = '.$idEntity.' AND '.PGR_ID_PROGRAM_TYPE.' = '.$idProgramType);
			$progRow = $progObj->fetchRow($where);
						
			Zend_Loader::loadClass(CLS_ASSISTANCE);
			$obj = new Assistance();			
			$where = $obj->getAdapter()->quoteInto(AST_ID_PERSON.' = '.$idPerson.' AND '.AST_ID_PROGRAM.' = '.$progRow->{PGR_ID_PROGRAM});
			$rows = $obj->fetchAll($where);
			
			if(count($rows) == 0)
			{
				return null;
			}
			else
			{
				$flag = null;
				foreach($rows as $assistance)
				{
					if(is_null($assistance->{AST_REAL_END_DATE}))
					{
						$flag = $assistance;
					}
				}
				
				return $flag;
			} 
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
	}
}