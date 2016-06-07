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
 
require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('F_ID_AREA','area_id');
define('F_NAME_AREA','nameName');
define('F_STATUS','status');

class AreaForm extends BasicForm
{
	/**
	 * Campos
	 * 
	 */
	private $id;
	private $areaName;
	private $status;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function id() { return F_ID_AREA; }
	public static function areaName() { return F_NAME_AREA; }
	public static function status() { return F_STATUS; }
	
	/**
	 * Preenche os valores vindos do request
	 * 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->id = $_request->getParam(AreaForm::id());
		$this->areaName = $_request->getParam(AreaForm::areaName());
		if($_request->isPost())
		{
			$filter				= BasicForm::getFilterStripTags();
			$this->id 			= $_request->getPost(AreaForm::id());
			$this->areaName 	= trim($filter->filter($_request->getPost(AreaForm::areaName())));
			$this->status 	= trim($filter->filter($_request->getPost(AreaForm::status())));
		}
	}
	
	/**
	 * Recupera as informações do form e retorna no objeto Group
	 * 
	 */
	function assembleFormToArea(AreaForm $frm)
	{
		$area 				= new EntityAreaType();
		$area->id 		 	= $frm->getId();
		$area->areaName 	= $frm->getAreaName();
		$area->status 		= $frm->getStatus();
		
		return $area;
	}
	
	/**
	 * Recupera as informações do objeto e insere no form
	 * 
	 */
	function assembleAreaToForm($area)
	{
		if(!empty($area))
		{
			foreach($area as $frm)
			{
				$this->view->form->setId($frm->{EAT_ID_ENTITY_AREA});
				$this->view->form->setAreaName($frm->{EAT_ENTITY_AREA});
				$this->view->form->setStatus($frm->{EAT_STATUS});
			}
		}
	}
	
	/**
	 * Getters and Setters
	 * 
	 */
	 public function getId(){return $this->id;}
	 public function getAreaName(){return $this->areaName;}
	 public function getStatus(){return $this->status;}
	 
	 public function setId($id){$this->id = $id;}
	 public function setAreaName($areaName){$this->areaName = $areaName;}
	 public function setStatus($status){$this->status = $status;}
}