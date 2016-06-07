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
 * Jefferson Barros Lima  - W3S		   				18/02/2008	                       Create file 
 * 
 */

require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('ID_ACTIVITY', 		'idActivity');
define('ID_MACROACTIVITY', 	'idMacroActivity');
define('ACTIVITY_NAME', 	'activity');
define('ACTIVITY_TYPE', 	'activityType');
define('STATUS', 			'status');

/**
 * Utilizados na criacao de detalhamento de atividade em entidade
 */
define('ID_ACTIVITY_DETAIL',	'idActivityDetail');
define('ID_PROGRAM', 			'idProgram');
define('ID_CATEGORY', 			'idCategory');
define('NAME_DETAIL_ACTIVITY',	'nameDetailActivity');
define('WORKING_HOUR',			'workingHour');

class ActivityForm extends BasicForm
{
	/* Flags para exibição das tab's no template */
	const CADASTRE_FLAG 	= 'cadastre';
	const ASSOCIATION_FLAG 	= 'association';

	private $idActivityDetail;
	private $workingHour;
	private $nameDetailActivity;
	private $idCategory;
	private $idProgram;
	
	private $idActivity;
	private $idMacroActivity;
	private $activityName;
	private $activityType;
	private $status;
	
	public static function idActivityDetail(){return ID_ACTIVITY_DETAIL;}
	public static function workingHour(){return WORKING_HOUR;}
	public static function nameDetailActivity(){return NAME_DETAIL_ACTIVITY;}
	public static function idCategory(){return ID_CATEGORY;}
	public static function idProgram(){return ID_PROGRAM;}
	
	public static function idActivity(){return ID_ACTIVITY;}
	public static function idMacroActivity(){return ID_MACROACTIVITY;}
	public static function activityName(){return ACTIVITY_NAME;}
	public static function activityType(){return ACTIVITY_TYPE;}
	public static function status(){return STATUS;}
	
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->idActivityDetail		= $_request->getParam(ActivityForm::idActivityDetail());
		
		if($_request->isPost())
		{
			$filter						= BasicForm::getFilterStripTags();
			$this->workingHour	 		= trim($filter->filter($_request->getPost(ActivityForm::workingHour())));
			$this->nameDetailActivity 	= trim($filter->filter($_request->getPost(ActivityForm::nameDetailActivity())));
			$this->idCategory			= $_request->getPost(ActivityForm::idCategory());
			$this->idProgram			= $_request->getPost(ActivityForm::idProgram());
			$this->idActivity			= $_request->getPost(ActivityForm::idActivity());
			$this->idMacroActivity		= $_request->getPost(ActivityForm::idMacroActivity());
			$this->activityName 		= trim($filter->filter($_request->getPost(ActivityForm::activityName())));
			$this->activityType 		= $_request->getPost(ActivityForm::activityType());
			$this->status 				= trim($filter->filter($_request->getPost(ActivityForm::status())));
		}
	}
	
	public function getIdActivityDetail()
	{
		return $this->idActivityDetail;
	}
	public function getWorkingHour()
	{
		return $this->workingHour;
	}
	public function getNameDetailActivity()
	{
		return $this->nameDetailActivity;
	}
	public function getIdCategory()
	{
		return $this->idCategory;
	}
	public function getIdProgram()
	{
		return $this->idProgram;
	}
	public function getIdActivity()
	{
		return $this->idActivity;
	}
	public function getIdMacroActivity()
	{
		return $this->idMacroActivity;
	}
	public function getActivityName()
	{
		return $this->activityName;
	}
	public function getActivityType()
	{
		return $this->activityType;
	}
	public function getStatus()
	{
		return $this->status;
	}
	
	
	public function setIdActivityDetail($idActivityDetail)
	{
		return $this->idActivityDetail = $idActivityDetail;
	}
	public function setWorkingHour($workingHour)
	{
		return $this->workingHour = $workingHour;
	}
	public function setNameDetailActivity($nameDetailActivity)
	{
		return $this->nameDetailActivity = $nameDetailActivity;
	}
	public function setIdCategory($idCategory)
	{
		$this->idCategory = $idCategory;
	}
	public function setIdProgram($idProgram)
	{
		$this->idProgram = $idProgram;
	}
	public function setIdActivity($idActivity)
	{
		$this->idActivity = $idActivity;
	}
	public function setIdMacroActivity($idMacroActivity)
	{
		$this->idMacroActivity = $idMacroActivity;
	}
	public function setActivityName($activityName)
	{
		$this->activityName = $activityName;
	}
	public function setActivityType($activityType)
	{
		$this->activityType = $activityType;
	}
	public function setStatus($status)
	{
		$this->status = $status;
	}	
}
