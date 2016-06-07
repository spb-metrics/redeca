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
define('F_AST_ASSISTANCE_ID',	'assistance_id');
define('F_AST_GENERAL_AST_ID',	'general_ast_id');
define('F_AST_COMMENTS',		'comments');
define('F_AST_CONFIDENTIALITY',	'confidentiality_level');
define('F_AST_BENEFIT_TYPE',	'benefit_type');
define('F_AST_PROFILE_ID',		'profile_id');

class GeneralAssistanceForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $assistanceId;
	private $generalAstId;
	private $comments;
	private $confidentialityLevel;
	private $benefitType;
	private $profileId;

	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function assistanceId(){return F_AST_ASSISTANCE_ID;}
	public static function generalAstId(){return F_AST_GENERAL_AST_ID;}
	public static function comments(){return F_AST_COMMENTS;}
	public static function confidentialityLevel(){return F_AST_CONFIDENTIALITY;}
	public static function benefitType(){return F_AST_BENEFIT_TYPE;}
	public static function profileId(){return F_AST_PROFILE_ID;}
	
	/**
	 * Preenche os valores vindos do request
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		if($_request->isPost())
		{
			$filter						= BasicForm::getFilterStripTags();
			$this->assistanceId			= $_request->getPost(GeneralAssistanceForm::assistanceId());
			$this->generalAstId			= $_request->getPost(GeneralAssistanceForm::generalAstId());
			$this->comments				= trim($filter->filter($_request->getPost(GeneralAssistanceForm::comments())));
			$this->confidentialityLevel	= $_request->getPost(GeneralAssistanceForm::confidentialityLevel());
			$this->benefitType			= $_request->getPost(GeneralAssistanceForm::benefitType());
			$this->profileId			= $_request->getPost(GeneralAssistanceForm::profileId());
		}
	}

	/**
	 * Getters and Setters
	 */
	 public function getAssistanceId(){ return $this->assistanceId;}
	 public function getGeneralAstId(){ return $this->generalAstId;}
	 public function getComments(){ return $this->comments;}
	 public function getConfidentialityLevel(){ return $this->confidentialityLevel;}
	 public function getBenefitType(){ return $this->benefitType;}
	 public function getProfileId(){ return $this->profileId;}

	 public function setAssistanceId($assistanceId){ $this->assistanceId = $assistanceId;}
}