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
 * @copyright Funda��o Telef�nica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Ara�atuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guaruj� - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de S�o Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de V�rzea Paulista - http://www.varzeapaulista.sp.gov.br 
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
 * Fabricio Meireles Monteiro  - W3S		   		19/02/2008	                       Create file 
 * 
 */
 
require_once('BasicForm.php');

/**
 * Nome das vari�veis no template
 * 
 */
define('F_PROGRAM_ID', 		'id');
define('F_PROGRAM_NAME',	'programName');
define('F_TARGET_PUBLIC_ID','idTargetPublic');
define('F_STATUS','status');

class ProgramForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $id;
	private $programName;
	private $idTargetPublic;
	private $status;
	
	/**
	 * Nomes dos campos vindos do formul�rio html
	 * 
	 */
	public static function id()
	{
		return F_PROGRAM_ID;
	} 
	
	public static function programName() 
	{
		 return F_PROGRAM_NAME;
	}
	
	public static function idTargetPublic()
	{
		return F_TARGET_PUBLIC_ID;
	}
	
	public static function status()
	{
		return F_STATUS;
	}
	
	/**
	 * Preenche os valores vindos do request
	 * "&" : pega a mesma inst�ncia da mem�ria. 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->id = $_request->getParam(ProgramForm::id());
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->id 				= $_request->getPost(ProgramForm::id());
			$this->programName 		= trim($filter->filter($_request->getPost(ProgramForm::programName())));
			$this->idTargetPublic 	= $_request->getPost(ProgramForm::idTargetPublic());
			$this->status 			= trim($filter->filter($_request->getPost(ProgramForm::status())));
		}
	}	
	
	/**
	 * Getters and Setters
	 */
	 public function getId()
	 {
	 	return $this->id;	
	 }
	 
	 public function getProgramName()
	 {
	 	return $this->programName;	
	 }
	 
	 public function getIdTargetPublic()
	 {
	 	return $this->idTargetPublic;	
	 }
	 
	 public function getStatus()
	 {
	 	return $this->status;	
	 }
	 
	 	 
	 public function setId($id)
	 {
	 	$this->id = $id;	
	 }
	 
	 public function setProgramName($programName)
	 {
	 	$this->programName = $programName;	
	 }
	 
	 public function setIdTargetPublic($idTargetPublic)
	 {
	 	$this->idTargetPublic = $idTargetPublic;	
	 }
	 
	 public function setStatus($status)
	 {
	 	$this->status = $status;	
	 }
}