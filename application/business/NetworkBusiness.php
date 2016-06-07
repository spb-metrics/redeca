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
 * Jefferson Barros Lima  - W3S		    			18/02/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class NetworkBusiness extends BasicBusiness
{
	/**
	 * Carrega todas as subcategorias
	 */
	public static function loadActivities()
	{
		$obj = new Category();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(CAT_ID_CATEGORY_FATHER.' is not null');
			$where[] = $obj->getAdapter()->quoteInto(CAT_STATUS.' not in (?)', Constants::DISABLE);
			
			$res = $obj->fetchAll($where, CAT_CATEGORY);
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activity->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega tipos de atividades que contem pelo menos um entidade
	 */
	public static function loadAllActivitiesEntity($idCategory=NULL, $start=NULL, $limit=NULL)
	{
		$obj = new ActivityDetail();
		try
		{
			if($idCategory)			
				$where = $obj->getAdapter()->quoteInto(ACD_ID_CATEGORY.' = ?', $idCategory);
			else
				$where = null;
			
			$resDetail = $obj->fetchAll($where, ACD_ACTIVITY_DETAIL, $limit, $start);
			
			$arrActivities = array();
			foreach($resDetail as $detail)
			{								
				if(count($detail) > 0)
				{	
					$program 		= $detail->findParentRow(CLS_PROGRAM);							
					$entity 		= $program->findParentRow(CLS_ENTITY);
					$class 			= $detail->findManyToManyRowset(CLS_CLASSMODEL, CLS_ACTIVITYCLASS);
					$programType 	= $program->findParentRow(CLS_PROGRAMTYPE);
					$category		= $detail->findParentRow(CLS_CATEGORY);					
					
					$arrList[CAT_CATEGORY] 		= $category->{CAT_CATEGORY};
					$arrList[ENT_NAME] 			= $entity->{ENT_NAME};
					$arrList[PGT_PROGRAM_TYPE]	= $programType->{PGT_PROGRAM_TYPE};
					foreach($class as $cls)
					{
						$arrList[CLS_VACANCY]	= $cls->{CLS_VACANCY};
						$arrList[CLS_SCHEDULE] 	= $classAssistance = count($cls->findDependentRowset(CLS_CLASSASSISTANCE));						
					}
					$arrActivities[] = $arrList;																
				}
			}				
			return $arrActivities;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activity->load->fail, E_USER_ERROR);
		}
	}
}