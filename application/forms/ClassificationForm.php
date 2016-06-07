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
 * Fabricio Meireles Monteiro  - W3S		   		18/02/2008	                       Create file 
 * 
 */


require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('F_ID', 					'classification_id');
define('F_CLASSIFICATION_NAME',	'classificationName');
define('F_STATUS',	'status');

class ClassificationForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $id;
	private $classificationName;
	private $status;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function id()
	{
		return F_ID;
	} 
	
	public static function classificationName() 
	{
		 return F_CLASSIFICATION_NAME;
	}
	
	public static function status() 
	{
		 return F_STATUS;
	}
	
	/**
	 * Preenche os valores vindos do request
	 * "&" : pega a mesma instância da memória. 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->id = $_request->getParam(ClassificationForm::id());
		if($_request->isPost())
		{
			$filter						= BasicForm::getFilterStripTags();
			$this->id 					= $_request->getPost(ClassificationForm::id());
			$this->classificationName 	= trim($filter->filter($_request->getPost(ClassificationForm::classificationName())));
			$this->status			 	= trim($filter->filter($_request->getPost(ClassificationForm::status())));
		}
	}	
	
	/**
	 * Getters and Setters
	 */
	 public function getId()
	 {
	 	return $this->id;	
	 }
	 
	 public function getClassificationName()
	 {
	 	return $this->classificationName;	
	 }
	 
	 public function getStatus()
	 {
	 	return $this->status;	
	 }
	 
	 public function setId($id)
	 {
	 	$this->id = $id;	
	 }
	 
	 public function setClassificationName($classificationName)
	 {
	 	$this->classificationName = $classificationName;	
	 }
	 
	 public function setStatus($status)
	 {
	 	$this->status = $status;	
	 }
}