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
 * Saulo Esteves Rodrigues  - W3S		   			31/01/2008	                       Create file 
 * 
 */


require_once('BasicForm.php');

define('NET_ID_PERSON',		'id_person');
define('NET_ENTITY',		'entity');
define('NET_ID_ACTIVITY',	'id_activity');

class NetworkForm extends BasicForm
{
	/**
	 * Campos
	 * 
	 */
	private $id;
	private $entity;
	private $idActivity;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function id() 			{ return NET_ID_PERSON; }
	public static function entity() 		{ return NET_ENTITY; }
	public static function idActivity()		{ return NET_ID_ACTIVITY; }
	
	/**
	 * Preenche os valores vindos do request
	 * 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
	
		$this->id 			= $_request->getParam(NetworkForm::id());
		$this->entity 		= $_request->getParam(NetworkForm::entity());
		$this->idActivity	= $_request->getParam(NetworkForm::idActivity());
		
		if($_request->isPost())
		{
			$filter				= BasicForm::getFilterStripTags();
			$this->id 			= $_request->getPost(NetworkForm::id());
			$this->entity 		= trim($filter->filter($_request->getPost(NetworkForm::entity())));
			$this->idActivity	= $_request->getPost(NetworkForm::idActivity());
		}
	}
		
	/**
	 * Getters and Setters
	 * 
	 */
	 public function getId()			{return $this->id;}
	 public function getEntity()		{return $this->entity;}
	 public function getIdActivity()			{return $this->idActivity;}
	
	 public function setId($id){$this->id = $id;}
	 public function setEntity($entity){$this->entity = $entity;}
	 public function setIdActivity($idActivity){$this->idActivity = $idActivity;}
}