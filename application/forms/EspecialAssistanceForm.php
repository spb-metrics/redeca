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
 * Jefferson Barros Lima  - W3S		   				05/05/2008	                       Create file 
 * 
 */

require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('F_AST_ASSISTANCE_ID',			'assistance_id');
define('F_AST_OFFICIAL_LETTER',			'official_letter');
define('F_AST_OFFICIAL_LETTER_NUMBER',	'oficial_letter_number');
define('F_AST_OFFICIAL_LETTER_YEAR',	'oficial_letter_year');
define('F_AST_LAWSUIT',					'lawsuit');
define('F_AST_LAWSUIT_NUMBER',			'lawsuit_number');
define('F_AST_LAWSUIT_YEAR',			'lawsuit_year');
define('F_AST_LAWSUIT_DETAIL',			'lawsuit_detail');
define('F_AST_RULING',					'ruling');

class EspecialAssistanceForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $assistanceId;
	private $officialLetterOrigin;
	private $lawsuitOrigin;
	private $lawsuitNumber;
	private $lawsuitYear;
	private $officialLetterNumber;
	private $officialLetterYear;
	private $lawsuitDetail;
	private $ruling;

	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function assistanceId()		{return F_AST_ASSISTANCE_ID;}
	public static function officialLetter(){return F_AST_OFFICIAL_LETTER;}
	public static function lawsuit()		{return F_AST_LAWSUIT;}
	public static function lawsuitNumber()		{return F_AST_LAWSUIT_NUMBER;}
	public static function lawsuitYear()		{return F_AST_LAWSUIT_YEAR;}
	public static function officialLetterNumber(){return F_AST_OFFICIAL_LETTER_NUMBER;}
	public static function officialLetterYear()	{return F_AST_OFFICIAL_LETTER_YEAR;}
	public static function lawsuitDetail()		{return F_AST_LAWSUIT_DETAIL;}
	public static function ruling()				{return F_AST_RULING;}
	
	/**
	 * Preenche os valores vindos do request
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);

		$this->assistanceId			= $_request->getParam(EspecialAssistanceForm::assistanceId());
		$this->officialLetterOrigin	= $_request->getParam(EspecialAssistanceForm::officialLetter());
		$this->lawsuitOrigin		= $_request->getParam(EspecialAssistanceForm::lawsuit());
		$this->lawsuitNumber		= $_request->getParam(EspecialAssistanceForm::lawsuitNumber());
		$this->lawsuitYear			= $_request->getParam(EspecialAssistanceForm::lawsuitYear());
		$this->officialLetterNumber	= $_request->getParam(EspecialAssistanceForm::officialLetterNumber());
		$this->officialLetterYear	= $_request->getParam(EspecialAssistanceForm::officialLetterYear());
		$this->lawsuitDetail		= $_request->getParam(EspecialAssistanceForm::lawsuitDetail());
		$this->ruling				= $_request->getParam(EspecialAssistanceForm::ruling());
	}

	/**
	 * Getters and Setters
	 */
	 public function getAssistanceId(){ return $this->assistanceId;}
	 public function getOfficialLetter(){ return $this->officialLetterOrigin;}
	 public function getOfficialLetterNumber(){ return $this->officialLetterNumber;}
	 public function getOfficialLetterYear(){ return $this->officialLetterYear;}
	 public function getLawsuit(){ return $this->lawsuitOrigin;}
	 public function getLawsuitNumber(){ return $this->lawsuitNumber;}
	 public function getLawsuitYear(){ return $this->lawsuitYear;}
	 public function getLawsuitDetail(){ return $this->lawsuitDetail;}
	 public function getRuling(){ return $this->ruling;}

	 public function setAssistanceId($assistanceId){ $this->assistanceId = $assistanceId;}
}