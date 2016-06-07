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
 * Jefferson Barros Lima  - W3S		   				22/02/2008	                       Create file 
 * 
 */

require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('ADDRESS_FILE', 		'addressFile');
define('FOLDER_NAME', 		'folder');
define('RADIO_NAME', 		'option');
define('SCHOOL_SUCCESS',	'schoolSuccess');

class ImportForm extends BasicForm
{
	/* Flags de exibição */
	const ZIPCODE_DIV_KEY = 'zipcode';
	const SINGLEREGISTER_DIV_KEY = 'single';
	const SCHOOL_DIV_KEY = 'school';

	private $addressFile;
	private $folder;
	/**
	 * Variavel para controle de exibição
	 */
	private $radioButton;
	private $schoolSuccess;
	
	public static function addressFile(){return ADDRESS_FILE; }
	public static function folder(){return FOLDER_NAME; }
	public static function radioButton(){return RADIO_NAME; }
	public static function schoolSuccess(){return SCHOOL_SUCCESS; }
	
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);

		if($_request->isPost())
		{
			if(isset($_FILES[ImportForm::addressFile()]))
			{
				$this->addressFile		= $_FILES[ImportForm::addressFile()];
			}

			$filter					= BasicForm::getFilterStripTags();
			$this->folder			= trim($filter->filter($_request->getPost(ImportForm::folder())));
			$this->radioButton		= trim($filter->filter($_request->getPost(ImportForm::radioButton())));
			$this->schoolSuccess		= trim($filter->filter($_request->getPost(ImportForm::schoolSuccess())));
		}
		else
		{
			$this->radioButton		= $_request->getParam(ImportForm::radioButton());
			$this->schoolSuccess		= $_request->getParam(ImportForm::schoolSuccess());
		}
	}
	public function getAddressFile()
	{
		return $this->addressFile;
	}
	public function getFolder()
	{
		return $this->folder;
	}
	public function getRadioButton()
	{
		return $this->radioButton;
	}
	public function getSchoolSuccess()
	{
		return $this->schoolSuccess;
	}
	
	public function setAddressFile($addressFile)
	{
		$this->addressFile = $addressFile;
	}
	public function setFolder($folder)
	{
		$this->folder = $folder;
	}
	public function setRadioButton($radioButton)
	{
		$this->radioButton = $radioButton;
	}
	public function setSchoolSuccess($schoolSuccess)
	{
		$this->schoolSuccess = $schoolSuccess;
	}
}
