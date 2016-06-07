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
 * Fabricio Meireles Monteiro  - W3S		   		29/02/2008	                       Create file 
 * 
 */

	require_once 'BasicValidator.php';
	
	abstract class ClassValidator extends BasicValidator
	{
		/**
		 * Verifica se a turma a ser excluída tem um ou mais alunos matriculados 
		 */
		public static function validatePersonInClass(ClassForm &$frm, &$errorMessages = null)
		{	
			$validator = parent::validatorPersonInClass();
			if(!$validator->isValid($frm->getIdClass()))
			{
				$errorMessages[ClassForm::idClass()][] = $validator->getMessages();
			}
			
			return $errorMessages;
		}
		
		/*
		 * valida o "id" da turma que recebe a migração
		 */ 
		public static function validateNewClassId(ClassForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(!$validatorRequired->isValid($frm->getIdNewClass()))
			{
				$errorMessages[ClassForm::idNewClass()][] = $validatorRequired->getMessages();
			}
			else
			{
				if(!$validator->isValid($frm->getIdNewClass()))
				{
					$errorMessages[ClassForm::idNewClass()][] = $validator->getMessages();	
				}
			}	
						
			return $errorMessages;		
		}
		
		/*
		 * valida a flag de migração da turma
		 */
		public static function validateFlagMigrate(ClassForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			$flagMigrate = parent::validatorBetween(1, 1);
			
			if(!$validatorRequired->isValid($frm->getFlagMigrate()))
			{
				$errorMessages[ClassForm::flagMigrate()][] = $validatorRequired->getMessages();
			}
			elseif(!$validator->isValid($frm->getFlagMigrate()))
			{	
				$errorMessages[ClassForm::flagMigrate()][] = $validator->getMessages();
			}	
			else
			{
				if(!$flagMigrate->isValid($frm->getFlagMigrate()))
				{
					$errorMessages[ClassForm::flagMigrate()][] = $flagMigrate->getMessages();
				}
			} 
						
			return $errorMessages;		
		}
		
		/*
		 * valida a confidencialidade do atendimento
		 */
		public static function validateConfidentiality(ClassForm &$frm, &$errorMessages = null)
		{	
			$validator = parent::validatorInt();
			$valueConfidentiality = parent::validatorBetween(0, 1);
			
			if(!$validator->isValid($frm->getConfidentiality()))
			{	
				$errorMessages[ClassForm::confidentiality()][] = $validator->getMessages();
			}	
			else
			{
				if(!$valueConfidentiality->isValid($frm->getConfidentiality()))
				{
					$errorMessages[ClassForm::confidentiality()][] = $valueConfidentiality->getMessages();
				}
			} 
						
			return $errorMessages;		
		}
		
		/*
		 * valida a data prevista para o término da atividade
		 */
		public static function validateEndDate(ClassForm &$frm, &$errorMessages = null)
		{	
			$validator = parent::validatorDate();
			
			//formata a data inserida pelo usuário
			$formatedDate = BasicForm::dateFormat($frm->getEndDate());
			
			if((strlen($frm->getEndDate()) > 0) && !$validator->isValid($formatedDate))
			{
				$errorMessages[ClassForm::endDate()][] = $validator->getMessages();
			}
			
			return $errorMessages;
		}
		
		
		/*
		 * valida o "id" da turma
		 */ 
		public static function validateClassId(ClassForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(!$validatorRequired->isValid($frm->getIdClass()))
			{
				$errorMessages[ClassForm::idClass()][] = $validatorRequired->getMessages();
			}
			else
			{
				if(!$validator->isValid($frm->getIdClass()))
				{
					$errorMessages[ClassForm::idClass()][] = $validator->getMessages();	
				}
			}	
						
			return $errorMessages;		
		}
		
		
		/*
		 * valida o "id" do programa
		 */ 
		public static function validateProgramId(ClassForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(!$validatorRequired->isValid($frm->getIdProgram()))
			{
				$errorMessages[ClassForm::idProgram()][] = $validatorRequired->getMessages();
			}
			else if(!$validator->isValid($frm->getIdProgram()))
			{
				$errorMessages[ClassForm::idProgram()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('ProgramBusiness');
				$row = ProgramBusiness::loadProgram(UserLogged::getEntityId(), $frm->getIdProgram());
				
				if(count($row) == 0)
				{	
					$errorMessages[ClassForm::idProgram()][][] = parent::getValidatorResources()->programEntity->notfound;
				}
			}				
				
			return $errorMessages;		
		}
		
		/*
		 * valida o "id" do detalhamento da atividade
		 */
		public static function validateActivityDetailId(ClassForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(!$validatorRequired->isValid($frm->getIdActivityDetail()))
			{	
				if(strlen($frm->getExistActDetail()) <= 0)
				{
					$errorMessages[ClassForm::idActivityDetail()][] = $validatorRequired->getMessages();
				}
			}
			else
			{
				if(is_array($frm->getIdActivityDetail()))
				{
					foreach($frm->getIdActivityDetail() as $id)
					{
						if(!$validatorRequired->isValid($id))
						{
							$errorMessages[ClassForm::idActivityDetail()][] = $validatorRequired->getMessages();
						}
						else
						{
							if(!$validator->isValid($id))
							{
								$errorMessages[ClassForm::idActivityDetail()][] = $validator->getMessages();
							}
							else
							{
								Zend_Loader::loadClass('ActivityBusiness');
								$row = ActivityBusiness::load($id);								
								if(count($row) == 0)
								{
									$errorMessages[ClassForm::idActivityDetail()][][] = parent::getValidatorResources()->activity->notfound;
								}
							}
						}
					}
				}	
				else
				{
					if((!$validator->isValid($frm->getIdActivityDetail())))
					{
						$errorMessages[ClassForm::idActivityDetail()][] = $validator->getMessages();	
					}
					else
					{
						Zend_Loader::loadClass('ActivityBusiness');
						$row = ActivityBusiness::load($frm->getIdActivityDetail());
						if(count($row) == 0)
						{
							$errorMessages[ClassForm::idActivityDetail()][][] = parent::getValidatorResources()->activity->notfound;
						}
					}
				}
			}
									
			return $errorMessages;		
		}
		
		/*
		 * valida o "id" do relacionamento entre turma e atividade
		 */
		public static function validateActivityClassId(ClassForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(!$validatorRequired->isValid($frm->getIdActivityClass()))
			{
				$errorMessages[ClassForm::idActivityClass()][] = $validatorRequired->getMessages();
			}
			else
			{
				if((!$validator->isValid($frm->getIdActivityClass())))
				{
					$errorMessages[ClassForm::idActivityClass()][] = $validator->getMessages();	
				}
			}
									
			return $errorMessages;		
		}
		
		public static function validateClassHaveEntity(ClassForm &$frm, &$errorMessages = null)
		{	
			if(UserLogged::getEntityId() != $frm->getIdEntity())
			{	
				$errorMessages[ClassForm::idEntity()][][] = parent::getValidatorResources()->required->user->entity;
			}
			
			return $errorMessages;
		}
		
		public static function validateClassEqualName(ClassForm &$frm, &$errorMessages = null)
		{
			Zend_Loader::loadClass('ClassBusiness');
			$row = ClassBusiness::loadByName($frm->getClassName());
			
			if(count($row) > 0 && is_null($row->{CLS_END_DATE}))
			{
				$errorMessages[ClassForm::className()][][] = parent::getValidatorResources()->class->equal;
			}
		}
		
		public static function validateClassName(ClassForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorStringLength(3, 80);
			
			if(!$validatorRequired->isValid($frm->getClassName()))
			{
				$errorMessages[ClassForm::className()][] = $validatorRequired->getMessages();
			}
			else if(strlen($frm->getClassName()) > 80)
			{
				$name = Utils::abbreviate($frm->getClassName(), 31);
				$errorMessages[ClassForm::className()][][] = parent::getValidatorResources()->text->long1.$name.parent::getValidatorResources()->text->long2.'80'.parent::getValidatorResources()->text->long3;
			}
			else if (!$validator->isValid($frm->getClassName()))
			{
				$errorMessages[ClassForm::className()][] = $validator->getMessages();
			}
			else
			{
				$validatorWord = parent::validatorWords($frm->getClassName());
				if(!$validatorWord->isValid($frm->getClassName()))
				{
					$errorMessages[ClassForm::className()][] = $validatorWord->getMessages();
				}
			}					
						
			return $errorMessages;		
		}
		
		public static function validateDate(ClassForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorDate();
			
			if(!$validatorRequired->isValid($frm->getDayStart()))
			{
				$errorMessages[ClassForm::dayStart()][] = $validatorRequired->getMessages();
			}
			elseif(!$validator->isValid($frm->getDayStart()))
			{		
				$errorMessages[ClassForm::dayStart()][] = $validator->getMessages();	
			}
			
			if(!$validatorRequired->isValid($frm->getDayEnd()))
			{
				$errorMessages[ClassForm::dayEnd()][] = $validatorRequired->getMessages();
			}
			elseif(!$validator->isValid($frm->getDayEnd()))
			{
				$errorMessages[ClassForm::dayEnd()][] = $validator->getMessages();
			}				
						
			return $errorMessages;		
		}
		
		public static function validatePeriod(ClassForm &$frm, &$errorMessages = null)
		{
			$validatorRequired = parent::validatorNotEmpty();
			$validatorInt = parent::validatorInt();
			
			if(!$validatorRequired->isValid($frm->getPeriod()))
			{
				$errorMessages[ClassForm::period()][] = $validatorRequired->getMessages();
			}
			else
			{
				if(!$validatorInt->isValid($frm->getPeriod()))
				{
					$errorMessages[ClassForm::period()][] = $validatorInt->getMessages();
				}	
			}
			
			return $errorMessages;		
		}
		
		public static function validateVacancy(ClassForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(!$validatorRequired->isValid($frm->getVacancy()))
			{
				$errorMessages[ClassForm::vacancy()][] = $validatorRequired->getMessages();
			}
			else if($frm->getVacancy() < 0)
			{
				$errorMessages[ClassForm::vacancy()][][] = parent::getValidatorResources()->value->negative;
			}
			else if(strlen($frm->getVacancy()) > 20)
			{
				$vacancy = Utils::abbreviate($frm->getVacancy(), 20);
				if(!$validator->isValid($vacancy))
				{
					$errorMessages[ClassForm::vacancy()][] = $validator->getMessages();			
				}
			}
			else if(!$validator->isValid($frm->getVacancy()))
			{
				$errorMessages[ClassForm::vacancy()][] = $validator->getMessages();			
			}
			else if($frm->getVacancy() == 0)
			{
				$errorMessages[ClassForm::vacancy()][][] = parent::getValidatorResources()->value->zero;
			}	
						
			return $errorMessages;		
		}
		
		public static function validateTime(ClassForm &$frm, &$errorMessages = null)
		{	
			$validator = parent::validatorStringLength(0, 50);
			
			if(strlen($frm->getTimeClass()) > 50)
			{
				$time = Utils::abbreviate($frm->getTimeClass(), 31);
				$errorMessages[ClassForm::timeClass()][][] = parent::getValidatorResources()->text->long1.$time.parent::getValidatorResources()->text->long2.'50'.parent::getValidatorResources()->text->long3;
			}
			else if (!$validator->isValid($frm->getTimeClass()))
			{
				$errorMessages[ClassForm::timeClass()][] = $validator->getMessages();
			}				
			else
			{
				$validatorWord = parent::validatorWords($frm->getTimeClass());
				if(!$validatorWord->isValid($frm->getTimeClass()))
				{
					$errorMessages[ClassForm::timeClass()][] = $validatorWord->getMessages();
				}
			}	
					
			return $errorMessages;		
		}
		
		public static function validateClass(ClassForm &$frm)
		{	
			$errorMessages = null;
//			ClassValidator::validateEntityId($frm, $errorMessages);
			ClassValidator::validateProgramId($frm, $errorMessages);
			ClassValidator::validateActivityDetailId($frm, $errorMessages);
			ClassValidator::validateClassName($frm, $errorMessages);
			ClassValidator::validateVacancy($frm, $errorMessages);
			ClassValidator::validatePeriod($frm, $errorMessages);
			ClassValidator::validateTime($frm, $errorMessages);
			
			return $errorMessages;
		}
	}