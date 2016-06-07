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
 
require_once('BasicController.php');

class ImportController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Import');
		parent::setControllerHelp('Import');
		
		Zend_Loader::loadClass('ImportForm');
		Zend_Loader::loadClass('ImportBusiness');
		Zend_Loader::loadClass('SchoolBusiness');
		Zend_Loader::loadClass('UFBusiness');
		Zend_Loader::loadClass('Constants');
		Zend_Loader::loadClass('FileHelper');
		Zend_Loader::loadClass('ImportValidator');
		Zend_Loader::loadClass('Utils');

		$frm				= new ImportForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
		
		$this->view->maxFileSize = get_cfg_var('upload_max_filesize');
	}

	/**
	 * Exibe formulário de upload
	 */
	function indexAction()
	{
		
		$errorMessages = ImportValidator::validatePermission();
		
		if(count($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
		}
		else
		{
			$singleStatus = ImportBusiness::singleRegisterStatus();
			$this->setImportStep($singleStatus, ImportForm::SINGLEREGISTER_DIV_KEY);
			
			//Verifica a pasta
			$path = PATH_ROOT. Constants::FOLDER_ROOT_SCHOOL;
			// Verifica o status
			$res = FileHelper::verifyFolderStatusForSchool($path, 10);
			if($res['parsed_lines'] > 1)
			{
				$this->view->processSchool = true;			
			}
			
			$errorMessages = ImportValidator::validateSchoolProcess();
			if(sizeof($errorMessages) > 0)
				$this->view->errorSchoolEmptyMessages = $errorMessages;
							
			$status = ImportBusiness::zipCodeStepStatus();
			$this->setImportStep($status, ImportForm::ZIPCODE_DIV_KEY);
					
			// Se já foi importado, seta flag
			if(ImportValidator::isZipCodeImported())
				$this->view->importedOnce = TRUE;
			
			$this->view->form->setSchoolSuccess(null);
			$schoolSuccess = $this->_request->getParam(ImportForm::schoolSuccess());
			if($schoolSuccess)
				$this->view->form->setSchoolSuccess($schoolSuccess);
				
			$this->view->schoolUpload = Constants::FOLDER_ROOT_SCHOOL;
		}
	}

	/**
	 * Faz upload e salva os dados do arquivo de cadastro único
	 */
	function singleRegisterAction()
	{
		$frm = $this->view->form;
	 	$status = ImportBusiness::singleRegisterStatus();
		$this->setImportStep($status, ImportForm::SINGLEREGISTER_DIV_KEY);		 

		$errorMessages = ImportValidator::validateSingleRegisterData($frm);
	 	if(sizeof($errorMessages) > 0)
		{
			$this->view->errorSingleMessages = $errorMessages;
			$this->view->form->setRadioButton(ImportForm::SINGLEREGISTER_DIV_KEY);			
			return;
		}
		// Se passou na validação, salva o arquivo
		FileHelper::saveUploadForSingleRegister($frm->getAddressFile(), $frm->getFolder());
		
		$this->_redirect(IMPORT_CONTROLLER.'/'.DEFAULT_INDEX_ACTION.'/'.ImportForm::radioButton().'/'.$frm->getRadioButton());
	}

	/**
	  * Processa a importação
	  */
	function processSingleRegisterAction()
	{
		$errorMessages = ImportValidator::validateSingleRegisterProcess();
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorSingleMessages = $errorMessages;
			return;
		}
		else
		{
			$processMsg = ImportBusiness::processSingleRegister();
			if($processMsg)
				$this->_redirect(IMPORT_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
			else
				$this->view->processMsg = $processMsg;
		}
	}

	/**
	 * Faz upload e salva os dados do arquivo de escolas
	 */
	 function schoolAction()
	 {	 	
	 	$frm = $this->view->form;
	 	
	 	$status = ImportBusiness::schoolStatus(); 	
		$this->setImportStep($status, ImportForm::SCHOOL_DIV_KEY);
		
		$errorMessages = ImportValidator::validateSchoolData($frm);
	 	if(sizeof($errorMessages) > 0)
		{
			$this->view->errorSchoolMessages = $errorMessages;
			$this->view->form->setRadioButton(ImportForm::SCHOOL_DIV_KEY);	
			return;
		}
		$errorMessages = ImportValidator::validateSchoolProcess();
		if(sizeof($errorMessages) > 0)
			$this->view->errorSchoolEmptyMessages = $errorMessages;
		
		FileHelper::removeSchoolFiles();
			
		FileHelper::saveUploadForSchool($frm->getAddressFile(), $frm->getFolder());
		
		$status = ImportBusiness::schoolStatus();	
	 	if($status[0]->getMessage())
	 	{
	 		$this->view->errorSchoolMessages[] = $status[0]->getMessage();
	 		$this->view->processSchool == false; 
	 		$this->view->form->setSchoolSuccess(null);
	 		FileHelper::removeSchoolFiles();
	 		return;	 		
	 	} 	
	 	
		$this->_redirect(IMPORT_CONTROLLER.'/'.DEFAULT_INDEX_ACTION.'/'.ImportForm::radioButton().'/'.$frm->getRadioButton().'/'.ImportForm::schoolSuccess().'/'.DEFAULT_SUCCESS_ACTION);		
	 }
	
	/**
	 * Processa a importação de escolas
	 */
	function processSchoolAction()
	{
		
		$errorMessages = ImportValidator::validateSchoolProcess();
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorSchoolEmptyMessages = $errorMessages;
		}			
		$processMsg = ImportBusiness::processSchool();
		
		if($processMsg)
		{
			FileHelper::removeSchoolFiles();
			$this->_redirect(IMPORT_CONTROLLER.'/'.DEFAULT_SCHOOLSUCCESS_ACTION);			
		}
		else
		{
			$this->view->processMsg = $processMsg;
		}		
		
	}
	
	/**
	 * Faz upload e salva os arquivos relacionados a CEP
	 */
	 function zipcodeAction()
	 {	
	 	$frm = $this->view->form;
	 	$status = ImportBusiness::zipCodeStepStatus();
		$this->setImportStep($status, ImportForm::ZIPCODE_DIV_KEY);
				
		$errorMessages = ImportValidator::validateZipCodeData($frm);
	 	if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->view->form->setRadioButton(ImportForm::ZIPCODE_DIV_KEY);
			return;
		}
		// Se passou na validação, salva o arquivo
	 	FileHelper::saveUpload($frm->getAddressFile(), $frm->getFolder());

	 	$this->_redirect(IMPORT_CONTROLLER.'/'.DEFAULT_INDEX_ACTION.'/'.ImportForm::radioButton().'/'.$frm->getRadioButton());
	 }
	 /**
	  * Processa a importação
	  */
	 function processAction()
	 {
		$errorMessages = ImportValidator::validateZipCodeProcess();
	 	if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
		}
		else
		{
		 	$processMsg = ImportBusiness::process();

			if(!empty($processMsg) && Utils::array_is_empty($processMsg))
				$this->_redirect(IMPORT_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
		 	
			$this->view->processMsg = $processMsg;
		}
	 }

	public function successAction()
	{
		;
	}
	
	public function schoolSuccessAction()
	{
		;
	}
	 
	 /**
	  * Seta o status do processo de importação na camada de view
	  */
	 private function setImportStep($status, $option)
	 {
		// Flag que libera o processamento
		$process = TRUE;
		
	 	if(!empty($status))
	 	{
			if($option == ImportForm::SINGLEREGISTER_DIV_KEY)
			{
				$this->view->singleInfos = $status;
				
				foreach($status as $curr)
				{
					if($curr->hasImported()=== FALSE)
					{
						// Seta informações de indicação do upload necessário
						$this->view->singleUpload = $curr;
						$process = FALSE;
						break;
					}
				}
				$this->view->processSingleRegister = $process;
				$errorMessages = ImportValidator::validateSingleRegisterProcess();
				if(sizeof($errorMessages) > 0)
				{
					$this->view->errorSingleMessages = $errorMessages;
				}
			}
			else if($option == ImportForm::SCHOOL_DIV_KEY)
			{				
				$this->view->schoolInfos = $status;	
				
				foreach($status as $curr)
				{					
					if($curr->hasImported() === FALSE)
					{						
						// Seta informações de indicação do upload necessário
						$this->view->schoolUpload = Constants::FOLDER_ROOT_SCHOOL;
						$process = FALSE;
						break;
					}
				}				
				$this->view->processSchool = $process;
				$errorMessages = ImportValidator::validateSchoolProcess();
				if(sizeof($errorMessages) > 0)
					$this->view->errorSchoolEmptyMessages = $errorMessages;
			}
			else
			{
				// Seta informações do upload
				$this->view->infos = $status; 
				foreach($status as $curr)
				{
					if($curr->hasImported()=== FALSE)
					{
						// Seta informações de indicação do upload necessário
						$this->view->upload = $curr;
						$process = FALSE;
						break;
					}
				}
				$this->view->process = $process;
			}
	 	}
	 	else
	 	{
	 		$process = FALSE;
	 		$this->view->process = $process;
	 		$this->view->processSingleRegister = $process;
	 		$this->view->processSchool = $process;
	 	}
	 	
	 }
}