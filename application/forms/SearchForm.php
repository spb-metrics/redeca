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
define('DOC_TYPE', 			'docType');
define('DOC_NUMBER', 		'docNumber');
define('PERSON_NAME',		'prsName');
define('PERSON_NICKNAME',	'prsNickname');
define('PERSON_SEX',		'prsSex');
define('PERSON_FIRST_AGE',	'prsFirstAge');
define('PERSON_SECOND_AGE', 'prsSecondAge');
define('PERSON_REGION',		'prsRegion');

//utilizado apenas na busca de relação familiar
define('PERSON_PARENT_ID',	'idParent');
define('ID_FAMILY',			'idFamily');
define('ID_KINSHIP',		'idKinship');


class SearchForm extends BasicForm
{
	private $docType;
	private $docNumber;
	private $prsName;
	private $prsNickname;
	private $prsSex;
	private $prsFirstAge;
	private $prsSecondAge;
	private $prsRegion;

	//utilizado apenas na busca de relação familiar
	private $idPerson;
	private $idParent;
	private $idFamily;
	private $idKinship;
	
	public static function docType(){return DOC_TYPE;}
	public static function docNumber(){return DOC_NUMBER;}
	public static function prsName(){return PERSON_NAME;}
	public static function prsNickname(){return PERSON_NICKNAME;}
	public static function prsSex(){return PERSON_SEX;}
	public static function prsFirstAge(){return PERSON_FIRST_AGE;}
	public static function prsSecondAge(){return PERSON_SECOND_AGE;}
	public static function prsRegion(){return PERSON_REGION;}

	//utilizado apenas na busca de relação familiar
	public static function idPerson(){return F_PERSON_ID;}
	public static function idParent(){return PERSON_PARENT_ID;}
	public static function idFamily(){return ID_FAMILY;}
	public static function idKinship(){return ID_KINSHIP;}
	
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);

		//utilizado apenas na busca de relação familiar
		$this->idPerson			= $_request->getParam(self::idPerson());
		
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->docType			= trim($filter->filter($_request->getPost(SearchForm::docType())));
			$this->docNumber		= trim($filter->filter($_request->getPost(SearchForm::docNumber())));
			$this->prsName			= trim($filter->filter($_request->getPost(SearchForm::prsName())));
			$this->prsNickname		= trim($filter->filter($_request->getPost(SearchForm::prsNickname())));
			$this->prsSex			= trim($filter->filter($_request->getPost(SearchForm::prsSex())));
			$this->prsFirstAge		= trim($filter->filter($_request->getPost(SearchForm::prsFirstAge())));
			$this->prsSecondAge		= trim($filter->filter($_request->getPost(SearchForm::prsSecondAge())));
			$this->prsRegion		= trim($filter->filter($_request->getPost(SearchForm::prsRegion())));
			
			//utilizado apenas na busca de relação familiar
			$this->idParent	= $_request->getPost(SearchForm::idParent());
			$this->idFamily	= $_request->getPost(SearchForm::idFamily());
			$this->idKinship	= $_request->getPost(SearchForm::idKinship());
		}
	}
	
	public function getDocType(){return $this->docType;	}
	public function getDocNumber(){	return $this->docNumber;}
	public function getPrsName(){return $this->prsName;}
	public function getPrsNickname(){return $this->prsNickname;}
	public function getPrsSex(){return $this->prsSex;}
	public function getPrsFirstAge(){return $this->prsFirstAge;}
	public function getPrsSecondAge(){return $this->prsSecondAge;}
	public function getPrsRegion(){return $this->prsRegion;}
	
	//utilizado apenas na busca de relação familiar
	public function getIdPerson(){return $this->idPerson;}
	public function getIdParent(){return $this->idParent;}
	public function getIdFamily(){return $this->idFamily;}
	public function getIdKinship(){return $this->idKinship;}
	
	public function setDocType($docType){$this->docType = $docType;	}
	public function setDocNumber($docNumber){$this->docNumber = $docNumber;	}
	public function setPrsName($prsName){$this->prsName = $prsName;	}
	public function setPrsNickname($prsNickname){$this->prsNickname = $prsNickname;	}
	public function setPrsSex($prsSex){$this->prsSex = $prsSex;	}
	public function setPrsFirstAge($prsFirstAge){$this->prsFirstAge = $prsFirstAge;	}
	public function setPrsSecondAge($prsSecondAge){$this->prsSecondAge = $prsSecondAge;	}
	public function setPrsRegion($prsRegion){$this->prsRegion = $prsRegion;	}
	
	//utilizado apenas na busca de relação familiar
	public function setIdPerson($idPerson){$this->idPerson = $idPerson;	}
	public function setIdParent($idParent){$this->idParent = $idParent;	}
	public function setIdFamily($idFamily){$this->idFamily = $idFamily;	}
	public function setIdKinship($idKinship){$this->idKinship = $idKinship;	}
}
