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
define('REGION', 		'region');
define('ID_REGION', 	'idRegion');
define('STATE', 		'state');
define('CITY', 			'city');
define('NEIGHBORHOOD', 	'neighborhood');

class RegionForm extends BasicForm
{
	private $region;
	private $idRegion;
	private $state;
	private $city;
	private $neighborhood;
	
	public static function region(){return REGION;}
	public static function idRegion(){return ID_REGION;}
	public static function state(){return STATE;}
	public static function city(){return CITY;}
	public static function neighborhood(){return NEIGHBORHOOD;}

	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);

		if($_request->isPost())
		{
			$filter				= BasicForm::getFilterStripTags();
			$this->region		= trim($filter->filter( $_request->getPost(RegionForm::region()) ));
			$this->idRegion		= $_request->getPost(RegionForm::idRegion());
			$this->state		= $_request->getPost(RegionForm::state());
			$this->city			= $_request->getPost(RegionForm::city());
			$this->neighborhood	= $_request->getPost(RegionForm::neighborhood());
		}
		else
		{
			$this->idRegion		= $_request->getParam(RegionForm::idRegion());
		}
	}
	
	public function getRegion()
	{
		return $this->region;
	}
	public function getIdRegion()
	{
		return $this->idRegion;
	}
	public function getNeighborhood()
	{
		return $this->neighborhood;
	}
	public function getState()
	{
		return $this->state;
	}
	public function getCity()
	{
		return $this->city;
	}

	public function setRegion($region)
	{
		$this->region = $region;
	}
	public function setIdRegion($idRegion)
	{
		$this->idRegion = $idRegion;
	}
	public function setNeighborhood($neighborhood)	
	{
		$this->neighborhood = $neighborhood;
	}
	public function setState($state)
	{
		$this->state = $state;
	}
	public function setCity($city)
	{
		$this->city = $city;
	}
}
