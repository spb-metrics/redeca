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
 * Saulo Esteves Rodrigues  - W3S         			30/01/2008                         Create file 
 * 
 */
?>
<?php
class AccessControl extends Zend_Acl
{
	public function __construct(Zend_Auth $auth)
	{
		Zend_Loader::loadClass('Zend_Acl_Resource');
		Zend_Loader::loadClass('Zend_Acl_Role');
		
		try
		{
			//para a api, só será considerado o usuário autenticado
			$this->addRole(new Zend_Acl_Role(Constants::ZEND_ACL_ROLE));
	
			//controllers a serem gerenciados
			$this->add(new Zend_Acl_Resource(controller_name(AUTH_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(AREA_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(PERSON_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(ACTIVITY_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(ATTENDANCE_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(EDUCATION_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(FAMILYRELATIONSHIP_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(BIOLOGICALRELATIONSHIP_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(HEALTH_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(INCOME_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(RESIDENCE_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(SEARCH_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(SEARCHADDRESS_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(BENEFIT_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(HISTORY_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(ENTITY_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(USER_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(CLASS_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(ASSOCIATE_ENTITY_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(CLASSIFICATION_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(PROGRAM_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(REPORT_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(EXPORT_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(NETWORK_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(REGION_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(IMPORT_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(PROFILE_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(FAMILYEXPENSE_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(GROUP_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(ADDITIONALINFORMATION_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(INDEX_CONTROLLER)));		
			$this->add(new Zend_Acl_Resource(controller_name(ENTITYINITIAL_CONTROLLER)));
			$this->add(new Zend_Acl_Resource(controller_name(ACCESSDENIED_CONTROLLER)));	
			$this->add(new Zend_Acl_Resource(controller_name(ACTIVITY_DETAIL_CONTROLLER)));	
			$this->add(new Zend_Acl_Resource(controller_name(PERSON_LOG_CONTROLLER)));
			
			$this->allow(Constants::ZEND_ACL_ROLE, controller_name(AUTH_CONTROLLER));
			$this->allow(Constants::ZEND_ACL_ROLE, controller_name(ACCESSDENIED_CONTROLLER));
		
			//usuário logado?
			try
			{
				$userId 	= AuthBusiness::getUserId();
				$entityId 	= AuthBusiness::getEntityId();
			}
			catch(UserNotLoggedException $e)
			{
				$userId 	= null;
				$entityId	= null;
			}
			
			if($userId != null)
			{
				$activeUser = false;
				if(!AuthBusiness::isAdministrator())
				{					
					//senão for adm, verificar se está ativo
					if($entityId != null && $userId != null)
					{
						try
						{
							$typeE 		= new Entity();
							$typeU 		= new User();
							$entity 	= $typeE->find($entityId)->current();
							$user 		= $typeU->find($userId)->current();
							
							if($entity != null && $user != null)
							{
								$config 		= Zend_Registry::get(CONFIG);
								if($entity->{ENT_STATUS} && $user->{AUTH_ACTIVE_USER})
								{
									$activeUser = true;
								}
								else if(!$entity->{ENT_STATUS} && ($user->{AUTH_ID_ROLE_USER} == $config->user->role->coordinator))
								{
									if($user->{AUTH_PERMISSION})
									{
										$activeUser = true;
									}
								}
							}
						}
						catch(Zend_Exception $e)
						{	
							Logger::loggerError("AccessControl->__construct fail.");
							Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
							$resources = Zend_Registry::get(LABEL_RESOURCES);
							trigger_error($resources->permission->proccess->fail, E_USER_ERROR);
						}
					}
				}
				else
					$activeUser = true;
				
				if($activeUser)
				{
					$allowResources = AuthBusiness::getResources();
					if(count($allowResources) > 0)
					{
						foreach($allowResources as $k=>$v)
						{
							if($v != null)
								$this->allow(Constants::ZEND_ACL_ROLE, controller_name($v->{ARC_CONTROLLER_NAME}));
						}
					}
				}
			}
			
		}
		catch(Zend_Acl_Exception $e)
		{
			Logger::loggerError("AccessControl->__construct fail. Truncate Database [invalid resource name]");
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			$resources = Zend_Registry::get(LABEL_RESOURCES);
			trigger_error($resources->permission->proccess->fail, E_USER_ERROR);
		}
	}
}

/**
 * Retorna o nome do controller sem o / inicial
 * 
 */
function controller_name($str)
{
	if(substr($str, 0, 1) == "/")
		return substr($str, 1);
	else
		return $str;
}
