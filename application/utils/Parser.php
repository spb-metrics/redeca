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
 * Jefferson Barros Lima  - W3S		   				26/02/2008	                       Create file 
 * 
 */

class Parser
{
	static function is_numeric(){ return 'is_numeric'; }
	static function is_string(){ return 'is_string'; }

	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela UF
	 * @param String $fullFilePath - path para o arquivo incluindo o nome e extensão
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param flag - true para persistir em banco as informações do parse
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseSingleRegister($folder, $fileNames, $numberOfLines=0, &$flag=NULL)
	{
		Zend_Loader::loadClass('AddressBusiness');
		Zend_Loader::loadClass('ResidenceBusiness');
		Zend_Loader::loadClass('FamilyResidenceBusiness');
		Zend_Loader::loadClass('PersonBusiness');
		Zend_Loader::loadClass('FamilyBusiness');
		Zend_Loader::loadClass('RepresentativeBusiness');
		Zend_Loader::loadClass('DeficiencyBusiness');
		Zend_Loader::loadClass('UfBusiness');
		Zend_Loader::loadClass('DocumentBusiness');
		Zend_Loader::loadClass('CtpsBusiness');
		Zend_Loader::loadClass('CivilCertificateBusiness');
		Zend_Loader::loadClass('IncomeBusiness');
		Zend_Loader::loadClass('ExpenseBusiness');
		Zend_Loader::loadClass('SocialProgramBusiness');
		Zend_Loader::loadClass('LevelInstructionBusiness');
		Zend_Loader::loadClass('SchoolBusiness');
		Zend_Loader::loadClass('RegistrationBusiness');
		Zend_Loader::loadClass('EmploymentBusiness');
		Zend_Loader::loadClass(CLS_FAMILYRESIDENCE);
		Zend_Loader::loadClass(CLS_RESIDENCE);
		Zend_Loader::loadClass(CLS_ADDRESS);
		Zend_Loader::loadClass(CLS_PERSON);
		Zend_Loader::loadClass(CLS_DOCUMENT);
		Zend_Loader::loadClass(CLS_CTPS);
		Zend_Loader::loadClass(CLS_DEFICIENCY);
		Zend_Loader::loadClass(CLS_INCOME);
		Zend_Loader::loadClass(CLS_CIVILCERTIFICATE);
		Zend_Loader::loadClass(CLS_EMPLOYMENT);
		Zend_Loader::loadClass(CLS_EXPENSE);
		Zend_Loader::loadClass(CLS_CONSANGUINE);
		Zend_Loader::loadClass(CLS_SOCIALPROGRAM);
		Zend_Loader::loadClass(CLS_LEVELINSTRUCTION);
		Zend_Loader::loadClass(CLS_SCHOOL);
		Zend_Loader::loadClass(CLS_REGISTRATION);
		Zend_Loader::loadClass(CLS_FAMILY);
		Zend_Loader::loadClass(CLS_FAMILY_ID);
		Zend_Loader::loadClass(CLS_REPRESENTATIVE);
		Zend_Loader::loadClass('MetaPhoneClass');
		
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			$uniqueRegisterArray = NULL;
			foreach($fileNames as $currentFile)
			{
				if($currentFile !== NULL && !is_dir($folder.'/'.$currentFile))
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					Logger::loggerImport("Arquivo aberto com sucesso. [nome=$folder/$currentFile]");
									
					$i = 0;
					$rowCounter = 1;
					while(!feof($handle) && $i <= $numberOfLines)
					{	
						$object = self::processLine(fgets($handle));
						Logger::loggerImport("Arquivo '$folder/$currentFile' - linha número: $rowCounter");
						$rowCounter++;
						
						if($object !== NULL)
						{
							// Valida a quantidade o tipo
							if(count($object) == 30)
							{
								$familyResidence = array();
								$familyResidence[FRS_ID_FAMILY]					= null;
								$familyResidence[FRS_ID_RESIDENCE]				= null;
								$familyResidence[FRS_LIVE_SINCE] 				= self::dateFormat(self::validateValue(trim($object['_103_DtaPesquisaDomic']), Parser::is_numeric()));
								
								$familyRelation = array();
								$familyRelation[FAM_ID_FAMILY]					= null;
								$familyRelation[FAM_ID_PERSON]					= null;
								$familyRelation[FAM_ID_KINSHIP]					= null;
								
								$familyId = array();
								$familyId[FID_ID_FAMILY]						= null;
								
								$representative	= array();
								$representative[REP_ID_REPRESENTATIVE]			= null;
								$representative[REP_ID_FAMILY]					= null;
								$representative[REP_ID_PERSON]					= null;
								
								$residence = array();
								$residence[RES_ID_RESIDENCE]					= null;
								$residence[RES_ID_ADDRESS]						= null;
								if(is_string($object['_204_NumResidenciaDomic']))
									$residence[RES_NUMBER]						= null;
								else
									$residence[RES_NUMBER]						= (int)self::validateValue(trim($object['_204_NumResidenciaDomic']), Parser::is_numeric());	
								$residence[RES_COMPLEMENT] 						= self::validateValue(trim($object['_205_NomComplResidenciaDomic']), Parser::is_string(), TRUE);
								$residence[RES_REFERENCE_POINT]					= null;
								$residence[RES_ID_STATUS]		 				= (int)self::validateValue(trim($object['_213_SitDomicilioDomic']), Parser::is_numeric());
								$residence[RES_ID_MORADA]		 				= (int)self::validateValue(trim($object['_214_TipDomicilioDomic']), Parser::is_numeric());
								$residence[RES_ACCOMMODATION]		 			= (int)self::validateValue(trim($object['_215_NumComodosDomic']), Parser::is_numeric());
								$residence[RES_ID_BUILDING]		 				= (int)self::validateValue(trim($object['_216_TipConstrucaoDomic']), Parser::is_numeric());
								$residence[RES_ID_SUPPLY]						= (int)self::validateValue(trim($object['_217_TipAbastecimentoAguaDomic']), Parser::is_numeric());
								$residence[RES_ID_WATER]						= (int)self::validateValue(trim($object['_218_TipTratamentoAguaDomic']), Parser::is_numeric());
								$residence[RES_ID_ILLUMINATION]					= (int)self::validateValue(trim($object['_219_TipIluminacaoAguaDomic']), Parser::is_numeric());
								$residence[RES_ID_SANITARY]						= (int)self::validateValue(trim($object['_220_TipEscoamentoSanitarioDomic']), Parser::is_numeric());
								$residence[RES_ID_TRASH]						= (int)self::validateValue(trim($object['_221_TipDestinoLixoDomic']), Parser::is_numeric());
								$residence[RES_ID_LOCALITY] 					= (int)self::validateValue(trim($object['_211_TipLocalDomic']), Parser::is_numeric());								
								if($residence[RES_ID_BUILDING] == 0)
									$residence[RES_ID_BUILDING] = null;
								if($residence[RES_ID_ILLUMINATION] == 0)
									$residence[RES_ID_ILLUMINATION] = null;
								if($residence[RES_ID_LOCALITY] == 0)
									$residence[RES_ID_LOCALITY] = null;
								if($residence[RES_ID_MORADA] == 0)
									$residence[RES_ID_MORADA] = null;
								if($residence[RES_ID_SANITARY] == 0)
									$residence[RES_ID_SANITARY] = null;
								if($residence[RES_ID_STATUS] == 0)
									$residence[RES_ID_STATUS] = null;
								if($residence[RES_ID_SUPPLY] == 0)
									$residence[RES_ID_SUPPLY] = null;
								if($residence[RES_ID_TRASH] == 0)
									$residence[RES_ID_TRASH] = null;
								if($residence[RES_ID_WATER] == 0)
									$residence[RES_ID_WATER] = null;
								
								$address										= array();
								$address[ADR_ZIP_CODE]							= (int)self::validateValue(trim($object['_201_CepResidenciaDomic']), Parser::is_numeric());
								$address[ADR_ADDRESS]							= self::validateValue(trim($object['_203_NomLogradouroDomic']), Parser::is_string(), TRUE);
								$address[ADR_ADDRESS_METAFONE]					= MetaPhoneClass::getMetaPhone($address[ADR_ADDRESS]);
								
								$family 										= array();
								$family[Constants::ARRAY_TYPE]					= Constants::ROWTYPE_FAMILY;
								$family[CLS_FAMILYRESIDENCE]					= $familyResidence;
								$family[CLS_RESIDENCE]							= $residence;
								$family[CLS_ADDRESS]							= $address;
								$family[CLS_FAMILY]								= $familyRelation;
								$family[CLS_REPRESENTATIVE]						= $representative;
								$family[CLS_FAMILY_ID]							= $familyId;
																
								$uniqueRegisterArray[$i] = $family;
								
								unset($familyResidence);
								unset($residence);
								unset($address);
								unset($familyRelation);
								unset($representative);
								
//								self::validateValue(trim($object['_104_NumNisEntrevistadorDomic']), Parser::is_numeric());
//								self::validateValue(trim($object['_Sis_CodControleDomic']), Parser::is_numeric());
//								self::validateValue(trim($object['_Sis_CodDomiciliarDomic']), Parser::is_numeric());
//								self::validateValue(trim($object['_105_NomEntrevistadorDomic']), Parser::is_string(), TRUE);
//								self::validateValue(trim($object['_Sis_NumModalidadeDomic']), Parser::is_numeric());
//								self::validateValue(trim($object['_108_NomEstabAssitSaudeDomic']), Parser::is_string(), TRUE);
//								self::validateValue(trim($object['_109_CodEasMsDomic']), Parser::is_numeric(), TRUE);
//								self::validateValue(trim($object['_202_TipLogradouroDomic']), Parser::is_numeric(), TRUE);
//								self::validateValue(trim($object['_206_NomBairroResidenciaDomic']), Parser::is_string(), TRUE);
//								self::validateValue(trim($object['_207_SigUfResidenciaDomic']), Parser::is_string());
//								self::validateValue(trim($object['_208_NomLocalidadeDomic']), Parser::is_string());
//								self::validateValue(trim($object['_209_CodDddResidenciaDomic']), Parser::is_numeric());
//								self::validateValue(trim($object['_210_NumTelContatoDomic']), Parser::is_numeric());
//								self::validateValue(trim($object['_212_NumDomicilioCobertoDomic']), Parser::is_numeric());
							}
							
							if(count($object) == 94)
							{	
								$person 											= array();
								$person[PRS_ID_PERSON]								= null;
								$person[PRS_NAME] 									= self::validateValue(trim($object['_201_NomPessoa']), Parser::is_string());
								$person[PRS_METANAME]								= MetaPhoneClass::getMetaPhone($person[PRS_NAME]);
								$person[PRS_NICKNAME]								= null;
								$person[PRS_METANICKNAME]							= MetaPhoneClass::getMetaPhone($person[PRS_NICKNAME]);
								$person[PRS_TATTOO]									= null;
								$person[PRS_DEATH_DATE]								= null;
								$person[PRS_BIRTH_DATE] 							= self::dateFormat(self::validateValue(trim($object['_202_DtaNascPessoa']), Parser::is_numeric()));
								$person[PRS_SEX] 									= self::validateValue(trim($object['_203_CodSexoPessoa']), Parser::is_numeric());
								if($person[PRS_SEX] == 1)
									$person[PRS_SEX] = 'm';
								if($person[PRS_SEX] == 2)
									$person[PRS_SEX] = 'f';
								$person[PRS_ID_NATIONALITY] 						= (int)self::validateValue(trim($object['_204_CodNacionalidadePessoa']), Parser::is_numeric());
								
								$person[PRS_NATIVE_COUNTRY] 						= self::validateValue(trim($object['_205_CodPaisOrigemPessoa']), Parser::is_numeric());
								$person[PRS_ARRIVAL_DATE]							= self::dateFormat(self::validateValue(trim($object['_206_CodDtaChegadaPaisPessoa']), Parser::is_numeric()));
								$person[PRS_ID_MARITAL_STATUS]						= (int)self::validateValue(trim($object['_212_CodEstadoCivilPessoa']), Parser::is_numeric());
								$person[PRS_ID_RACE]								= (int)self::validateValue(trim($object['_215_CodRacaCorPessoa']), Parser::is_numeric());
								if($person[PRS_ID_RACE] == 0)
									$person[PRS_ID_RACE] = null;
								if($person[PRS_ID_MARITAL_STATUS] == 0)
									$person[PRS_ID_MARITAL_STATUS] = null;
								if($person[PRS_ID_NATIONALITY] == 0)
									$person[PRS_ID_NATIONALITY] = null;
								
								
								$idDeficiency										= array();
								$idDeficiency[1]									= (int)self::validateValue(trim($object['_214_CodDefiCegueiraPessoa']), Parser::is_numeric());
								$idDeficiency[2]									= (int)self::validateValue(trim($object['_214_CodDefiMudezPessoa']), Parser::is_numeric());
								$idDeficiency[3]									= (int)self::validateValue(trim($object['_214_CodDefiSurdezPessoa']), Parser::is_numeric());
								$idDeficiency[4]									= (int)self::validateValue(trim($object['_214_CodDefiMentalPessoa']), Parser::is_numeric());
								$idDeficiency[5]									= (int)self::validateValue(trim($object['_214_CodDefiFisicaPessoa']), Parser::is_numeric());
								$idDeficiency[6]									= (int)self::validateValue(trim($object['_214_CodOutrasDefiPessoa']), Parser::is_numeric());
								$haveDef = false;
								foreach($idDeficiency as $def):
									if($def != 0) $haveDef = true; 
								endforeach;
								if($haveDef == true){
									$deficiency										= array();
									$deficiency[DFY_ID_PERSON]						= null;
									$deficiency[DFY_ID_DEFICIENCY]					= $idDeficiency;
								}
								else
								{
									$deficiency										= null;
								}
								
								$document											= array();
								$document[DOC_CPF]									= self::validateValue(trim($object['_233_NumCpfPessoa']), Parser::is_numeric(), TRUE);
								$document[DOC_NIS]									= self::validateValue(trim($object['_216_NumNisPessoa']), Parser::is_numeric(), TRUE);
								$document[DOC_SUS_CARD] 							= null;
								$document[DOC_RA] 									= null;
								$document[DOC_RG_NUMBER]							= (int)self::validateValue(trim($object['_224_NumIndentidadePessoa']), Parser::is_string(), TRUE);
								$document[DOC_RG_COMPLEMENT]						= self::validateValue(trim($object['_225_TxtComplementoPessoa']), Parser::is_string(), TRUE);
								$document[DOC_RG_EMISSION_DATE]						= self::dateFormat(self::validateValue(trim($object['_226_DtaEmissaoIdentPessoa']), Parser::is_numeric()), TRUE);
								$document[DOC_RG_SENDER]							= self::validateValue(trim($object['_228_SigOrgaoEmissaoPessoa']), Parser::is_string(), TRUE);
								$document[DOC_ID_RG_UF]								= self::validateValue(trim($object['_227_SigUfIdentPessoa']), Parser::is_string(), TRUE);
								if($document[DOC_RG_NUMBER] == 0)
								{
									$document[DOC_RG_COMPLEMENT] = null;
									$document[DOC_RG_EMISSION_DATE] = null;
									$document[DOC_RG_SENDER] = null;
									$document[DOC_ID_RG_UF]	= null;				
								}
								$document[DOC_TITLE_NUMBER]							= self::validateValue(trim($object['_234_NumTituloEleitorPessoa']), Parser::is_numeric(), TRUE);
								$document[DOC_TITLE_ZONE]							= self::validateValue(trim($object['_235_NumZonaTitEleitorPessoa']), Parser::is_string(), TRUE);
								$document[DOC_TITLE_SECTION]						= self::validateValue(trim($object['_236_NumSecaoTitEleitorPessoa']), Parser::is_string(), TRUE);
								if($document[DOC_TITLE_NUMBER] == 0)
								{
									$document[DOC_TITLE_NUMBER] = null;
									$document[DOC_TITLE_SECTION] = null;
									$document[DOC_TITLE_ZONE] = null;
								}
								
								$ctps												= array();
								$ctps[CTS_ID_UF]									= self::validateValue(trim($object['_232_SigUfCartTrabPessoa']), Parser::is_string(), TRUE);
								$ctps[CTS_NUMBER]									= self::validateValue(trim($object['_229_NumCartTrabPrevSocPessoa']), Parser::is_numeric(), TRUE);
								$ctps[CTS_SERIES]									= self::validateValue(trim($object['_230_NumSerieTrabPrevSocPessoa']), Parser::is_numeric(), TRUE);
								$ctps[CTS_EMISSION]									= self::dateFormat(self::validateValue(trim($object['_231_DtaEmissaoCartTrabPessoa']), Parser::is_numeric()), TRUE);
								
								$civilCertificate									= array();
								$civilCertificate[CCF_ID_UF]						= self::validateValue(trim($object['_222_SigUfCertidPessoa']), Parser::is_string(), TRUE);
								$civilCertificate[CCF_CERTIFICATE_TYPE]				= self::validateValue(trim($object['_217_CodCertidCivilPessoa']), Parser::is_numeric(), TRUE);
								$civilCertificate[CCF_TERM]							= self::validateValue(trim($object['_218_CodTermoCertidPessoa']), Parser::is_string(), TRUE);
								$civilCertificate[CCF_BOOK]							= self::validateValue(trim($object['_219_CodLivroTermoCertidPessoa']), Parser::is_string(), TRUE);
								$civilCertificate[CCF_LEAF]							= self::validateValue(trim($object['_220_CodFolhaTermoCertidPessoa']), Parser::is_string(), TRUE);
								$civilCertificate[CCF_EMISSION_DATE]				= self::dateFormat(self::validateValue(trim($object['_221_DtaEmissaoCertidPessoa']), Parser::is_numeric()), TRUE); 
								$civilCertificate[CCF_REGISTRY_OFFICE_NAME]			= self::validateValue(trim($object['_223_NomCartorioPessoa']), Parser::is_string(), TRUE);
								
								$valueIncome										= array();
								$valueIncome[1]										= (int)self::validateValue(trim($object['_247_ValRemunerEmpregoPessoa']), Parser::is_numeric());
								$valueIncome[2]										= (int)self::validateValue(trim($object['_248_ValRendaAposentPessoa']), Parser::is_numeric());		
								$valueIncome[3]										= (int)self::validateValue(trim($object['_249_ValRendaSeguroDesempPessoa']), Parser::is_numeric());			
								$valueIncome[4]										= (int)self::validateValue(trim($object['_250_ValRendaPensaoAlimenPessoa']), Parser::is_numeric());			
								$valueIncome[5]										= (int)self::validateValue(trim($object['_251_ValOutrasRendasPessoa']), Parser::is_numeric());
								$haveInc = false;	
								foreach($valueIncome as $inc):
									if($inc != 0) $haveInc = true; 
								endforeach;
								if($haveInc == true){
									$income											= array();
									$income[ICM_VALUE]								= $valueIncome;
									$income[ICM_REGISTER_DATE]						= date("Y-m-d");										
								}
								else
								{
									$income											= null;
								}	
								
								$employment											= array();
								$employment[EMP_ID_EMPLOYMENT_STATUS]				= self::validateValue(trim($object['_242_SitMercadoTrabPessoa']), Parser::is_numeric());
								$employment[EMP_COMPANY_NAME]						= self::validateValue(trim($object['_243_NomEmpresaTrabPessoa']), Parser::is_string(), TRUE);
								if($employment[EMP_COMPANY_NAME] != null){
									$employment[EMP_ID_EMPLOYMENT]					= null; 
									$employment[EMP_ID_ADDRESS]						= null;	
									$employment[EMP_START_DATE]						= self::dateFormat(self::validateValue(trim($object['_245_DtaAdmisEmpresaPessoa']), Parser::is_numeric()), TRUE);			
									$employment[EMP_END_DATE]						= null;
									$employment[EMP_NUMBER]							= null;
									$employment[EMP_COMPLEMENT]						= null;			
									$employment[EMP_REFERENCE_POINT]				= null;
									$employment[EMP_ID_INCOME]						= null;
									
								}
								else
								{
									$employment 									= null;
								}
								
								$valueExpense										= array();
								$valueExpense[1]									= (int)self::validateValue(trim($object['_253_ValDespMesaisPessoa']), Parser::is_numeric());			
								$valueExpense[2]									= (int)self::validateValue(trim($object['_254_ValDespPrestHabPessoa']), Parser::is_numeric());					
								$valueExpense[3]									= (int)self::validateValue(trim($object['_255_ValDespAlimentPessoa']), Parser::is_numeric());				
								$valueExpense[4]									= (int)self::validateValue(trim($object['_256_ValDespAguaPessoa']), Parser::is_numeric());		
								$valueExpense[5]									= (int)self::validateValue(trim($object['_257_ValDespLuzPessoa']), Parser::is_numeric());		
								$valueExpense[6]									= (int)self::validateValue(trim($object['_258_ValDespTransporPessoa']), Parser::is_numeric());			
								$valueExpense[7]									= (int)self::validateValue(trim($object['_259_ValDespMedicamentosPessoa']), Parser::is_numeric());				
								$valueExpense[8]									= (int)self::validateValue(trim($object['_260_ValDespGazPessoa']), Parser::is_numeric());
								$valueExpense[9]									= (int)self::validateValue(trim($object['_261_ValOutrasDespPessoa']), Parser::is_numeric());
								$haveExp = false;
								foreach($valueExpense as $exp):
									if($exp != 0) $haveExp = true; 
								endforeach;
								if($haveExp == true){
									$expense										= array();
									$expense[EXP_ID_EXPENSE_TYPE]					= null;		
									$expense[EXP_EXPENSE_VALUE]						= $valueExpense;				
									$expense[EXP_ID_FAMILY]							= null;													
								}
								else
								{
									$expense 										= null;
								}
								
								$consanguine										= array();
								$consanguine[CSG_ID_PERSON_FROM]					= (int)self::validateValue(trim($object['_263_NumOrdemRespPessoa']), Parser::is_numeric());
								$consanguine[CSG_ID_CONSANGUINE_TYPE]				= (int)self::validateValue(trim($object['_264_CodParentRelMaePessoa']), Parser::is_numeric());
								$consanguine[CSG_ID_PERSON_TO]						= (int)self::validateValue(trim($object['_102_NumOrdemPessoa']), Parser::is_numeric());
								
								$idSocialProgram									= array();
								$idSocialProgram[1]									= self::validateValue(trim($object['_270_IndBenefPetiPessoa']), Parser::is_string(), TRUE);
								$idSocialProgram[2]									= self::validateValue(trim($object['_270_IndBenefLoasBpcPessoa']), Parser::is_string(), TRUE);
								$idSocialProgram[3]									= null;
								$idSocialProgram[4]									= null;
								$idSocialProgram[5]									= null;
								$idSocialProgram[6]									= null;
								$idSocialProgram[7]									= null;
								$idSocialProgram[8]									= self::validateValue(trim($object['_270_IndOutrosBeneficiosPessoa']), Parser::is_string(), TRUE);
								$haveSop = false;
								foreach($idSocialProgram as $sop):
									if($sop != 0) $haveSop = true; 
								endforeach;
								if($haveSop == true){
									$socialProgram									= array();
									$registerDate									= array();
									$registerDate[1]								= self::dateFormat(self::validateValue(trim($object['_270_DtaIncPetiPessoa']), Parser::is_numeric()));
									$registerDate[2]								= null;
									$registerDate[3]								= self::dateFormat(self::validateValue(trim($object['_270_DtaIncAgjPessoa']), Parser::is_numeric()));
									$registerDate[4]								= null;
									$registerDate[5]								= null;
									$registerDate[6]								= self::dateFormat(self::validateValue(trim($object['_270_DtaIncProgerPessoa']), Parser::is_numeric()));
									$registerDate[7]								= null;
									$registerDate[8]								= null;
									$socialProgram[SPG_ID_PERSON]					= null;
									$socialProgram[SPG_ID_SOCIAL_PROGRAM]			= $idSocialProgram;
									$socialProgram[SPG_REGISTER_DATE]				= $registerDate;
								}
								else
								{
									$socialProgram									= null;
								}	
																					
								$levelInstruction									= array();
								$levelInstruction[LIT_ID_PERSON]					= null;
								$levelInstruction[LIT_ID_DEGREE]					= (int)self::validateValue(trim($object['_238_CodGrauInstrucaoPessoa']), Parser::is_numeric());
								$levelInstruction[LIT_LAST_YEAR_STUDIED]			= null;
								
								
								$school 											= array();
								$school[SCH_ID_SCHOOL]								= null;
								$school[SCH_NAME]									= self::validateValue(trim($object['_240_NomEscolaPessoa']), Parser::is_string(), TRUE);
								if($school[SCH_NAME] == null){
									$school[SCH_INEP]								= null;
									Logger::loggerImport('Não há nome para escola, foi atribuido '.$school[SCH_NAME].' . [Line]: '.implode(' | ',$object));
								}
								else
								{
									$school[SCH_INEP]								= self::validateValue(trim($object['_241_CodCensoInepPessoa']), Parser::is_string(), TRUE);
								}
								$school[SCH_ID_SCHOOL_TYPE]	= (int)self::validateValue(trim($object['_237_CodQualifEscolarPessoa']), Parser::is_numeric());
								if($school[SCH_ID_SCHOOL_TYPE] == 0)
									$school[SCH_ID_SCHOOL_TYPE] = null;									
															
								if($school[SCH_NAME] == null || $school[SCH_INEP] == null)
								{
									$registration									= null;
								}
								else
								{
									$registration										= array();
									$registration[REG_ID_LEVEL_INSTRUCTION]				= null;
									$idSchoolYear										= (int)self::validateValue(trim($object['_239_NumSerieEscolarPessoa']), Parser::is_numeric(), TRUE);									
									if($idSchoolYear == null){ 
										$idSchoolYear = null;
										Logger::loggerImport('Pessoa não possui série, valor '. $idSchoolYear .' foi atribuido. [Line]: '.implode(' | ',$object));
									}
									$registration[REG_ID_SCHOOL_YEAR]					= $idSchoolYear;
									$registration[REG_ID_PERIOD]						= null;
									$registration[REG_ID_SCHOOL]						= null;
								}
								
								$personData 										= array();
								$personData[Constants::ARRAY_TYPE]									= Constants::ROWTYPE_PERSON;
								$personData[CLS_PERSON]								= $person;
								$personData[CLS_DEFICIENCY]							= $deficiency;
								$personData[CLS_DOCUMENT]							= $document;
								$personData[CLS_CTPS]								= $ctps;
								$personData[CLS_CIVILCERTIFICATE]					= $civilCertificate;
								$personData[CLS_INCOME]								= $income;
								$personData[CLS_EMPLOYMENT]							= $employment;
								$personData[CLS_EXPENSE]							= $expense;
								$personData[CLS_CONSANGUINE]						= $consanguine;
								$personData[CLS_SOCIALPROGRAM]						= $socialProgram;
								$personData[CLS_LEVELINSTRUCTION]					= $levelInstruction;
								$personData[CLS_SCHOOL]								= $school;
								$personData[CLS_REGISTRATION]						= $registration;
														
								$uniqueRegisterArray[$i] = $personData;
								
								unset($person);
								unset($deficiency);
								unset($document);
								unset($ctps);
								unset($civilCertificate);
								unset($income);
								unset($expense);
								unset($consanguine);
								unset($socialProgram);
								unset($school);
								unset($registration);
								unset($levelInstruction);
								unset($employment);
								
//								self::validateValue(trim($object['_103_TipoModalidadePessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_Sis_CodControlePessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_Sis_CodDomiciliarPessoa']), Parser::is_numeric());											
//								self::validateValue(trim($object['_210_NomCompletoPaiPessoa']), Parser::is_string(), TRUE);
//								self::validateValue(trim($object['_211_NomCompletoMaePessoa']), Parser::is_string());											
//								self::validateValue(trim($object['_213_NumOrdemEsposoCompanPessoa']), Parser::is_numeric());											
//								self::validateValue(trim($object['_244_NumCnpjEmpresaPessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_246_CodOcupacaoEmpresaPessoa']), Parser::is_string());											
//								self::validateValue(trim($object['_252_QtdTempoMorarAnosPessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_252_QtdTempoMorarMesesPessoa']), Parser::is_numeric());											
//								self::validateValue(trim($object['_262_NumPessoasRendaPessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_265_NumOrdemResidPaiPessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_266_NumOrdemResidMaePessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_267_CodCrianca06AnosPessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_268_CodGravidaPessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_269_CodAmamentandoPessoa']), Parser::is_string());											
//								self::validateValue(trim($object['_270_NomOutroBenefPessoa']), Parser::is_string(), TRUE);
//								self::validateValue(trim($object['_207_CodIbgeMunicNascPessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_270_TipBenefPetiPessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_270_ValBenefPetiPessoa']), Parser::is_string());
//								self::validateValue(trim($object['_270_IndBensocProgerPessoa']), Parser::is_string()); 
//								self::validateValue(trim($object['_270_IndBenPrioritBolsaAlimentacaoPessoa']), Parser::is_string());
//								self::validateValue(trim($object['_270_IndBenPrioritBolsaEscolaPessoa']), Parser::is_string());
//								self::validateValue(trim($object['_270_IndOcupacaoPetiPessoa']), Parser::is_numeric());
//								self::validateValue(trim($object['_270_TipOcupacaoPetiPessoa']), Parser::is_numeric());
								
							}
							if($flag !== NULL)
							{
								foreach($uniqueRegisterArray as $array)
								{
									
									if($array[Constants::ARRAY_TYPE] == Constants::ROWTYPE_FAMILY)
									{
										if(is_array($array[CLS_ADDRESS]))
										{
											$address = $array[CLS_ADDRESS];
											$metanameAddress = $address[ADR_ADDRESS_METAFONE];
											$res = AddressBusiness::searchByZipCode($address[ADR_ZIP_CODE]);
											if(count($res) == 0)
												$res = AddressBusiness::searchByMetafone($metanameAddress);
												
											if(count($res) > 0)
												foreach($res as $address)
												{
													$idAddress = $address->{ADR_ID_ADDRESS};
												}
											else{
												$idAddress = null;	
												Logger::loggerImport('id_address é nulo. [Line]: '.implode(' | ',$res));
											}
											
											unset($res);
											unset($address);						
										}								
										
										if(is_array($array[CLS_RESIDENCE]))
										{
											$residence = $array[CLS_RESIDENCE];
											
											$result = AddressBusiness::searchByMetafone($metanameAddress);
											
											if($result != null){ 
												foreach($result as $res)
													$resIdAddress[] = $res->{ADR_ID_ADDRESS};
																																			
												if($resIdAddress->{ADR_ID_ADDRESS}){
													foreach($resIdAddress as $resId)
														$resResidence[] = ResidenceBusiness::findByAddress($resId);
													
													if($resResidence->{RES_ID_RESIDENCE}){
														foreach($resResidence as $res)
															if(($res->{RES_NUMBER} == $residence->{RES_NUMBER}) && ($res->{RES_COMPLEMENT} == $residence->{RES_COMPLEMENT}))
															{
																$idResidence = $res->{RES_ID_RESIDENCE};
																break;
															}
													}else{
														$residence[RES_ID_ADDRESS] = $idAddress;
														$idResidence = ResidenceBusiness::save($residence);
													}
												}
												else
													$residence[RES_ID_ADDRESS] = $idAddress;
													$idResidence = ResidenceBusiness::save($residence);
											}
											else
												$residence[RES_ID_ADDRESS] = $idAddress;
												$idResidence = ResidenceBusiness::save($residence);
												
											unset($result);
											unset($resIdAddress);
											unset($resResidence);
											unset($residence);
										}		
										
										if(is_array($array[CLS_FAMILY_ID]))
										{
											$familyId = $array[CLS_FAMILY_ID];
											$idFamily = FamilyBusiness::saveFamilyId();
											unset($familyId);
										}
										
										if(is_array($array[CLS_FAMILYRESIDENCE]))
										{
											$familyResidence = $array[CLS_FAMILYRESIDENCE];
											$familyResidence[FRS_ID_RESIDENCE] = $idResidence;
											$familyResidence[FRS_ID_FAMILY] = $idFamily;
											FamilyResidenceBusiness::save($familyResidence);
											unset($familyResidence);
										}	
										
										if(is_array($array[CLS_FAMILY]) && is_array($array[CLS_REPRESENTATIVE]))
										{
											$famFamily = $array[CLS_FAMILY];																				
											$famRepresetation = $array[CLS_REPRESENTATIVE];						
										}
									}
									
									if($array[Constants::ARRAY_TYPE] == Constants::ROWTYPE_PERSON)
									{
										
										if(is_array($array[CLS_PERSON]))
										{
											$person = $array[CLS_PERSON];
											if($person[PRS_ID_NATIONALITY] == 0)
												$person[PRS_ID_NATIONALITY] = null;
											else if($person[PRS_ID_NATIONALITY] > 1)
												$person[PRS_ID_NATIONALITY] = 2;
											$idPerson = PersonBusiness::save($person);
											unset($person);
										}
										
										if(is_array($famFamily))
										{
											$famFamily[FAM_ID_PERSON] = $idPerson;										
											$famFamily[FAM_ID_FAMILY] = $idFamily;
											
											if(is_array($array[CLS_CONSANGUINE]))
											{
												$csg = $array[CLS_CONSANGUINE];
												$famFamily[FAM_ID_KINSHIP] = $csg[CSG_ID_CONSANGUINE_TYPE];
												if($famFamily[FAM_ID_KINSHIP] == 0) $famFamily[FAM_ID_KINSHIP] = 20;
												FamilyBusiness::save($famFamily);			
																								
												if($csg[CSG_ID_PERSON_FROM] == 1 && $csg[CSG_ID_PERSON_TO] == 1)
												{
													if(is_array($famRepresetation))
													{
														$famRepresetation[REP_ID_PERSON] = $idPerson;
														$famRepresetation[REP_ID_FAMILY] = $idFamily;
														RepresentativeBusiness::save($famRepresetation);
													}
												}
												
												if(is_array($array[CLS_EXPENSE]))
												{
													$expense = $array[CLS_EXPENSE];
													$arrExpense = array();
													$arrIdFamily[0] = 0;										
													$expense[EXP_ID_FAMILY] = $idFamily;
													$value = $expense[EXP_EXPENSE_VALUE];													
													foreach($value as $k=>$v)
													{
														if($v != 0)
														{
															if($k > 8)
																$expense[EXP_ID_EXPENSE_TYPE] = 8;
															else
																$expense[EXP_ID_EXPENSE_TYPE] = $k;
																
															$int = substr($v, 0, count($v)-3);
															$dec = substr($v, -2);
															$float = (float)$int.'.'.$dec;
															$expense[EXP_EXPENSE_VALUE] = $float;
															
															if(!array_search($expense[EXP_ID_FAMILY], $arrIdFamily))
															{
																if(count($arrExpense) > 0)
																{
																	$flagExp = false;
																	foreach($arrExpense as $arr)
																	{
																		if($arr[EXP_ID_EXPENSE_TYPE] == $expense[EXP_ID_EXPENSE_TYPE])
																		{
																			$arr[EXP_EXPENSE_VALUE] += $expense[EXP_EXPENSE_VALUE];
																			$flagExp = true;
																		}
																	}
																	
																	if($flagExp === false)
																	{
																		$arrExpense[] = $expense;
																	}
																}
																else
																{
																	$arrExpense[] = $expense;
																}
																$arrIdFamily[] = $expense[EXP_ID_FAMILY];
															}																												
														}													
													}
													if(count($arrExpense) > 0)
													{
														foreach($arrExpense as $arr)
														{
															if($arr)
															{
																ExpenseBusiness::save($arr);
															}
														}
													}
												}
											}											
											unset($csg);
											unset($expense);
											unset($arrExpense);
										}
																			
										if(is_array($array[CLS_DEFICIENCY]))
										{
											$def = $array[CLS_DEFICIENCY];
											$def[DFY_ID_PERSON] = $idPerson;
											
											$defTypes = $def[DFY_ID_DEFICIENCY];
											foreach($defTypes as $k=>$v){
												if($v != 0){
													$def[DFY_ID_DEFICIENCY] = $k;
													DeficiencyBusiness::save($def);
												}						
											}		
											unset($def);								
										}
										
										if(is_array($array[CLS_DOCUMENT]))
										{
											$docs = $array[CLS_DOCUMENT];
											$docs[DOC_ID_PERSON] = $idPerson;
											if($docs[DOC_RG_NUMBER] != 0)
											{
												if(is_string($docs[DOC_ID_RG_UF]))
												{
													$idUf = UFBusiness::findByUf($docs[DOC_ID_RG_UF]);
													$docs[DOC_ID_RG_UF] = $idUf->{UF_ID_UF};
												}
												else
												{
													$docs[DOC_ID_RG_UF] = null;
												}
												DocumentBusiness::save($docs);
											}
											unset($idUf);
											unset($docs);
										}
										
										if(is_array($array[CLS_CTPS]))
										{
											$ctps = $array[CLS_CTPS];
											$ctps[CTS_ID_PERSON] = $idPerson;
											if($ctps[CTS_NUMBER] != 0)
											{
												if(is_string($ctps[CTS_ID_UF]))
												{
													$idUf = UFBusiness::findByUf($ctps[CTS_ID_UF]);													
													$ctps[CTS_ID_UF] = $idUf->{UF_ID_UF};
												}
												else
												{
													$ctps[CTS_ID_UF] = null;
												}
												CtpsBusiness::save($ctps);
											}
											unset($idUf);
											unset($ctps);
										}
										
										if(is_array($array[CLS_CIVILCERTIFICATE]))
										{
											$civil = $array[CLS_CIVILCERTIFICATE];
											$civil[CCF_ID_PERSON] = $idPerson;
											if($civil[CCF_CERTIFICATE_TYPE] != 0)
											{
												if(is_string($civil[CCF_ID_UF]))
												{
													$idUf = UFBusiness::findByUf($civil[CCF_ID_UF]);
													$civil[CCF_ID_UF] = $idUf->{UF_ID_UF};
												}
												else
												{
													$civil[CCF_ID_UF] = null;
												}
												CivilCertificateBusiness::save($civil);
											}
											unset($idUf);
											unset($civil);
										}
										
										if(is_array($array[CLS_INCOME]))
										{
											$income = $array[CLS_INCOME];
											$income[ICM_ID_PERSON] = $idPerson;
											$income[ICM_REGISTER_DATE] = date("Y-m-d");
											$value = $income[ICM_VALUE];
											foreach($value as $k=>$v){
												if($v != 0)
												{
													$income[ICM_ID_INCOME] = $k;
													$int = substr($v, 0, count($v)-3);
													$dec = substr($v, -2);
													$float = (float)$int.'.'.$dec;
													$income[ICM_VALUE] = $float;
													$idIncome = IncomeBusiness::save($income);
																																					
													if(is_array($array[CLS_EMPLOYMENT]))
													{
														$emp = $array[CLS_EMPLOYMENT];
														$emp[EMP_ID_INCOME] = $idIncome;
														EmploymentBusiness::save($emp);
													}
												}
												unset($int);
												unset($dec);
												unset($float);
											}
											unset($income);
											unset($value);		
										}
										
										if(is_array($array[CLS_SOCIALPROGRAM]))
										{
											$social = $array[CLS_SOCIALPROGRAM];
											$social[SPG_ID_PERSON] = $idPerson;
											$benefit = $social[SPG_ID_SOCIAL_PROGRAM];
											$date = $social[SPG_REGISTER_DATE];
											$social[SPG_REGISTER_DATE] = null;
											foreach($benefit as $k=>$v)
											{
												if($v != 0 && $v != null)
												{
													$social[SPG_ID_SOCIAL_PROGRAM] = $k;
													foreach($date as $j=>$i){
														if($k == $j){
															$social[SPG_REGISTER_DATE] = $i;	
														}
													}
													SocialProgramBusiness::save($social);						
												}
											}
											unset($social);
											unset($benefit);
											unset($date);
										}
										
										if(is_array($array[CLS_LEVELINSTRUCTION]))
										{
											$level = $array[CLS_LEVELINSTRUCTION];											
											$level[LIT_ID_PERSON] = $idPerson;
											if($level[LIT_ID_DEGREE] > 0)
												$idLevel = LevelInstructionBusiness::save($level);
											unset($level);
										}
										
										if(is_array($array[CLS_SCHOOL]))
										{
											$school = $array[CLS_SCHOOL];
											$res = SchoolBusiness::findByInepCode($school[SCH_INEP]);
											if($res == null)
												$res = SchoolBusiness::findByName($school[SCH_NAME]);
												
											if($res == true)
											{
												$idSchool = $res->{SCH_ID_SCHOOL};
											}	
											
											if($idSchool)
											{
												if(is_array($array[CLS_REGISTRATION])){
													$registration = $array[CLS_REGISTRATION];
													$registration[REG_ID_LEVEL_INSTRUCTION] = $idLevel;
													$registration[REG_ID_SCHOOL] = $idSchool;
													RegistrationBusiness::save($registration);
												}
											}
											unset($res);
											unset($school);
											unset($registration);														
										}
									}
									unset($uniqueRegisterArray);
								}
							}
						}
						else
						{
							$fileNameError = $currentFile;
							Logger::loggerImport('Falha ao analisar o arquivo de Cad. Único: '. $object .' [Line]: '.implode(' | ',$object));
						}
						
						if($numberOfLines != 0) $i++;						
					}
					fclose($handle);
					if($flag != null) return;
				}
			}
			return $uniqueRegisterArray;
		}
		return NULL;
	}
	
	/**
	 * Retira do arquivo as informações utilizadas
	 */
	function processLine(&$line)
	{
		
		if(substr($line, 0, 2) == "50")
		{
			$obj['type']								= Constants::ROWTYPE_FAMILY;
			$obj['_103_DtaPesquisaDomic'] 				= self::getValue($line, 2, 8);			//date
			$obj['_104_NumNisEntrevistadorDomic'] 		= self::getValue($line, 10, 11);		//double
			$obj['_Sis_CodControleDomic'] 				= self::getValue($line, 21, 15);		//double
			$obj['_Sis_CodDomiciliarDomic'] 			= self::getValue($line, 36, 9);			//double
			$obj['_105_NomEntrevistadorDomic'] 			= self::getValue($line, 77, 70);		//varchar(70)
			$obj['_Sis_NumModalidadeDomic'] 			= self::getValue($line, 161, 1);		//byte
			$obj['_108_NomEstabAssitSaudeDomic'] 		= self::getValue($line, 162, 115);		//varchar(115)
			$obj['_109_CodEasMsDomic'] 					= self::getValue($line, 277, 12);		//varchar(12)
			$obj['_201_CepResidenciaDomic'] 			= self::getValue($line, 289, 8);		//double
			$obj['_202_TipLogradouroDomic'] 			= self::getValue($line, 297, 3);		//varchar(3)
			$obj['_203_NomLogradouroDomic'] 			= self::getValue($line, 300, 50);		//varchar(50)
			$obj['_204_NumResidenciaDomic'] 			= self::getValue($line, 350, 7);		//varchar(7)
			$obj['_205_NomComplResidenciaDomic'] 		= self::getValue($line, 357, 15);		//varchar(15)
			$obj['_206_NomBairroResidenciaDomic'] 		= self::getValue($line, 372, 40);		//varchar(40)
			$obj['_207_SigUfResidenciaDomic'] 			= self::getValue($line, 412, 2);		//varchar(2)
			$obj['_208_NomLocalidadeDomic'] 			= self::getValue($line, 418, 35);		//varchar(35)
			$obj['_209_CodDddResidenciaDomic'] 			= self::getValue($line, 453, 4);		//double
			$obj['_210_NumTelContatoDomic'] 			= self::getValue($line, 457, 10);		//double
			$obj['_211_TipLocalDomic'] 					= self::getValue($line, 467, 1);		//byte
			$obj['_212_NumDomicilioCobertoDomic'] 		= self::getValue($line, 468, 1);		//byte
			$obj['_213_SitDomicilioDomic']		 		= self::getValue($line, 469, 1);		//byte
			$obj['_214_TipDomicilioDomic']		 		= self::getValue($line, 470, 1);		//byte
			$obj['_215_NumComodosDomic']		 		= self::getValue($line, 471, 3);		//double
			$obj['_216_TipConstrucaoDomic']		 		= self::getValue($line, 474, 1);		//byte
			$obj['_217_TipAbastecimentoAguaDomic']		= self::getValue($line, 475, 1);		//byte
			$obj['_218_TipTratamentoAguaDomic']			= self::getValue($line, 476, 1);		//byte
			$obj['_219_TipIluminacaoAguaDomic']			= self::getValue($line, 477, 1);		//byte
			$obj['_220_TipEscoamentoSanitarioDomic']	= self::getValue($line, 478, 1);		//byte
			$obj['_221_TipDestinoLixoDomic']			= self::getValue($line, 479, 1);		//byte
			
		}
		
		if(substr($line, 0, 2) == "70")
		{
			
			
			$obj['type']								= Constants::ROWTYPE_PERSON;
			$obj['_102_NumOrdemPessoa'] 				= self::getValue($line, 2, 2);			//byte
			$obj['_103_TipoModalidadePessoa']			= self::getValue($line, 4, 1);			//byte
			$obj['_Sis_CodControlePessoa'] 				= self::getValue($line, 5, 15);			//double
			$obj['_Sis_CodDomiciliarPessoa']			= self::getValue($line, 20, 9);			//double
			$obj['_201_NomPessoa'] 						= self::getValue($line, 61, 70);		//varchar(70)
			$obj['_202_DtaNascPessoa'] 					= self::getValue($line, 131, 8);		//date
			$obj['_203_CodSexoPessoa'] 					= self::getValue($line, 139, 1);		//byte
			$obj['_204_CodNacionalidadePessoa'] 		= self::getValue($line, 140, 1);		//byte
			$obj['_205_CodPaisOrigemPessoa'] 			= self::getValue($line, 141, 3);		//integer(3)
			$obj['_206_CodDtaChegadaPaisPessoa']		= self::getValue($line, 144, 8);		//date
			$obj['_207_CodEstadoCivilPessoa']			= self::getValue($line, 333, 1);		//byte
			$obj['_208_CodRacaCorPessoa']				= self::getValue($line, 343, 1);		//byte
			$obj['_210_NomCompletoPaiPessoa']			= self::getValue($line, 193, 70);		//varchar(70)
			$obj['_211_NomCompletoMaePessoa']			= self::getValue($line, 263, 70);		//varchar(70)
			$obj['_212_CodEstadoCivilPessoa']			= self::getValue($line, 333, 1);		//byte
			$obj['_213_NumOrdemEsposoCompanPessoa']		= self::getValue($line, 334, 2);		//byte
			$obj['_214_CodDefiCegueiraPessoa']			= self::getValue($line, 336, 1);		//byte
			$obj['_214_CodDefiMudezPessoa']				= self::getValue($line, 337, 1);		//byte
			$obj['_214_CodDefiSurdezPessoa']			= self::getValue($line, 338, 1);		//byte
			$obj['_214_CodDefiMentalPessoa']			= self::getValue($line, 339, 1);		//byte
			$obj['_214_CodDefiFisicaPessoa']			= self::getValue($line, 340, 1);		//byte
			$obj['_214_CodOutrasDefiPessoa']			= self::getValue($line, 342, 1);		//byte
			$obj['_215_CodRacaCorPessoa']				= self::getValue($line, 343, 1);		//byte
			$obj['_216_NumNisPessoa']					= self::getValue($line, 344, 11);		//double
			$obj['_217_CodCertidCivilPessoa']			= self::getValue($line, 355, 2);		//byte
			$obj['_218_CodTermoCertidPessoa']			= self::getValue($line, 357, 8);		//varchar(8)
			$obj['_219_CodLivroTermoCertidPessoa']		= self::getValue($line, 365, 8);		//varchar(8)
			$obj['_220_CodFolhaTermoCertidPessoa']		= self::getValue($line, 373, 4);		//varchar(4)
			$obj['_221_DtaEmissaoCertidPessoa']			= self::getValue($line, 377, 8);		//date
			$obj['_222_SigUfCertidPessoa']				= self::getValue($line, 385, 2);		//varchar(2)
			$obj['_223_NomCartorioPessoa']				= self::getValue($line, 387, 48);		//varchar(48)
			$obj['_224_NumIndentidadePessoa']			= self::getValue($line, 435, 16);		//varchar(16)
			$obj['_225_TxtComplementoPessoa']			= self::getValue($line, 451, 5);		//varchar(5)
			$obj['_226_DtaEmissaoIdentPessoa']			= self::getValue($line, 456, 8);		//date
			$obj['_227_SigUfIdentPessoa']				= self::getValue($line, 464, 2);		//varchar(2)
			$obj['_228_SigOrgaoEmissaoPessoa']			= self::getValue($line, 466, 10);		//varchar(10)
			$obj['_229_NumCartTrabPrevSocPessoa']		= self::getValue($line, 476, 7);		//especial?
			$obj['_230_NumSerieTrabPrevSocPessoa']		= self::getValue($line, 483, 5);		//especial?
			$obj['_231_DtaEmissaoCartTrabPessoa']		= self::getValue($line, 488, 8);		//date
			$obj['_232_SigUfCartTrabPessoa']			= self::getValue($line, 496, 2);		//varchar(2)
			$obj['_233_NumCpfPessoa']					= self::getValue($line, 498, 11);		//double
			$obj['_234_NumTituloEleitorPessoa']			= self::getValue($line, 509, 13);		//double
			$obj['_235_NumZonaTitEleitorPessoa']		= self::getValue($line, 522, 4);		//varchar(4)
			$obj['_236_NumSecaoTitEleitorPessoa']		= self::getValue($line, 526, 4);		//varchar(4)
			$obj['_237_CodQualifEscolarPessoa']			= self::getValue($line, 530, 1);		//byte
			$obj['_238_CodGrauInstrucaoPessoa']			= self::getValue($line, 531, 2);		//byte
			$obj['_239_NumSerieEscolarPessoa']			= self::getValue($line, 534, 2);		//byte
			$obj['_240_NomEscolaPessoa']				= self::getValue($line, 535, 115);		//varchar(115)
			$obj['_241_CodCensoInepPessoa']				= self::getValue($line, 650, 8);		//varchar(8) double
			$obj['_242_SitMercadoTrabPessoa']			= self::getValue($line, 658, 2);		//byte
			$obj['_243_NomEmpresaTrabPessoa']			= self::getValue($line, 660, 115);		//varchar(115)
			$obj['_244_NumCnpjEmpresaPessoa']			= self::getValue($line, 775, 14);		//double
			$obj['_245_DtaAdmisEmpresaPessoa']			= self::getValue($line, 789, 8);		//date
			$obj['_246_CodOcupacaoEmpresaPessoa']		= self::getValue($line, 797, 5);		//varchar(5)
			$obj['_247_ValRemunerEmpregoPessoa']		= self::getValue($line, 802, 18);		//double
			$obj['_248_ValRendaAposentPessoa']			= self::getValue($line, 820, 18);		//double
			$obj['_249_ValRendaSeguroDesempPessoa']		= self::getValue($line, 838, 18);		//double
			$obj['_250_ValRendaPensaoAlimenPessoa']		= self::getValue($line, 856, 18);		//double
			$obj['_251_ValOutrasRendasPessoa']			= self::getValue($line, 874, 18);		//double
			$obj['_252_QtdTempoMorarAnosPessoa']		= self::getValue($line, 892, 3);		//double
			$obj['_252_QtdTempoMorarMesesPessoa']		= self::getValue($line, 892, 2);		//byte
			$obj['_253_ValDespMesaisPessoa']			= self::getValue($line, 897, 18);		//double
			$obj['_254_ValDespPrestHabPessoa']			= self::getValue($line, 915, 18);		//double
			$obj['_255_ValDespAlimentPessoa']			= self::getValue($line, 933, 18);		//double
			$obj['_256_ValDespAguaPessoa']				= self::getValue($line, 951, 18);		//double
			$obj['_257_ValDespLuzPessoa']				= self::getValue($line, 969, 18);		//double
			$obj['_258_ValDespTransporPessoa']			= self::getValue($line, 987, 18);		//double
			$obj['_259_ValDespMedicamentosPessoa']		= self::getValue($line, 1005, 18);		//double
			$obj['_260_ValDespGazPessoa']				= self::getValue($line, 1023, 18);		//double
			$obj['_261_ValOutrasDespPessoa']			= self::getValue($line, 1041, 18);		//double
			$obj['_262_NumPessoasRendaPessoa']			= self::getValue($line, 1059, 3);		//double
			$obj['_263_NumOrdemRespPessoa']				= self::getValue($line, 1062, 2);		//byte
			$obj['_264_CodParentRelMaePessoa']			= self::getValue($line, 1066, 2);		//byte
			$obj['_265_NumOrdemResidPaiPessoa']			= self::getValue($line, 1068, 2);		//byte
			$obj['_266_NumOrdemResidMaePessoa']			= self::getValue($line, 1070, 2);		//byte
			$obj['_267_CodCrianca06AnosPessoa']			= self::getValue($line, 1072, 1);		//byte
			$obj['_268_CodGravidaPessoa']				= self::getValue($line, 1073, 1);		//byte
			$obj['_269_CodAmamentandoPessoa']			= self::getValue($line, 1074, 1);		//varchar(1)
			$obj['_270_IndBenefPetiPessoa']				= self::getValue($line, 1075, 1);		//varchar(1)
			$obj['_270_IndBenefLoasBpcPessoa']			= self::getValue($line, 1076, 1);		//varchar(1)
			$obj['_270_IndOutrosBeneficiosPessoa']		= self::getValue($line, 1082, 1);		//varchar(1)
			$obj['_270_NomOutroBenefPessoa']			= self::getValue($line, 1083, 50);		//varchar(50)
			$obj['_207_CodIbgeMunicNascPessoa']			= self::getValue($line, 1135, 7);		//double
			$obj['_270_DtaIncPetiPessoa']			  	= self::getValue($line, 1147, 8);		//date
			$obj['_270_TipBenefPetiPessoa']			  	= self::getValue($line, 1155, 1);		//byte
			$obj['_270_ValBenefPetiPessoa']			  	= self::getValue($line, 1156, 5);		//varchar(5) double
			$obj['_270_DtaIncAgjPessoa']			  	= self::getValue($line, 1161, 8);		//date
			$obj['_270_IndBensocProgerPessoa']		  	= self::getValue($line, 1169, 1);		//varchar(1)
			$obj['_270_DtaIncProgerPessoa']		  		= self::getValue($line, 1170, 8);		//date
			$obj['_270_IndBenPrioritBolsaAlimentacaoPessoa']= self::getValue($line, 1178, 1);	//varchar(1)
			$obj['_270_IndBenPrioritBolsaEscolaPessoa']		= self::getValue($line, 1268, 1);	//varchar(1)
			$obj['_270_IndOcupacaoPetiPessoa']			  	= self::getValue($line, 1269, 1);	//byte
			$obj['_270_TipOcupacaoPetiPessoa']			  	= self::getValue($line, 1270, 5);	//double
			
		}
		return $obj;
	}
	
	function getValue(&$line, $start, $length)
	{
		return substr($line, $start, $length);
	}
	
	/**
	 * Faz leitura do arquivo e popula um array para persistencia na tabela edu_school
	 */
	public static function parseSchool($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$flag=FALSE)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('SchoolBusiness');
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;
			$fieldsep = ';';

			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($flag !== FALSE) Logger::loggerImportSchool('Abrindo o arquivo '.$currentFile);
					
					Zend_Loader::loadClass(CLS_SCHOOL);
					if($handle !== NULL)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$line = fgets($handle);
							
							if( strpos($line, $fieldsep) === FALSE ) {
								Logger::loggerImportSchool('Separador de campo \''.$fieldsep.'\' nao encontrado em '.$currentFile.': tentando o (,)');
								$fieldsep = ',';
								if( strpos($line, $fieldsep) === FALSE ) {
									Logger::loggerImportSchool('Separador de campo \''.$fieldsep.'\' nao encontrado em '.$currentFile.': tentando o (|)');
									$fieldsep = '|';
								}
								if( strpos($line, $fieldsep) === FALSE ) {
									Logger::loggerImportSchool('Separador de campo \''.$fieldsep.'\' nao encontrado em '.$currentFile.': desisto! Tente mudar o formato do arquivo, por favor');
									break;
								}
							}

							$row = self::parseLine($line, $fieldsep);
							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) != 4)
								{
									Logger::loggerImportSchool('Nro de colunas '.count($row).' diferente de 4, que eh o esperado');
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									break;
								}
								
								$school = array();
			
								try
								{									
									$ibge = self::validateValue(trim($row[0]), Parser::is_numeric());
									$school[SCH_INEP] = self::validateValue(trim($row[1]), Parser::is_numeric());
									$school[SCH_NAME] = self::validateValue(trim($row[2]), Parser::is_string());
									// achar um jeito  elegante igual o acima, mas não sei como interceptar exceção.
									try {
									$row[3] = trim($row[3]);
									$school[SCH_ID_SCHOOL_TYPE] = self::validateValue($row[3], Parser::is_numeric());
									} catch(Exception $e1) {
									// TODO consulting database for type.
										switch($row[3]) {
										case 'Estadual':  
											$school[SCH_ID_SCHOOL_TYPE] = 2; 
											break;
										case 'Municipal': 
											$school[SCH_ID_SCHOOL_TYPE] = 1; 
											break;
										default: throw $e1;
										}
									}
									$parsed++;									
									if($flag !== FALSE)
									{
										
										$resSchool = SchoolBusiness::findByInepCodeAndName($school[SCH_INEP],$school[SCH_NAME]);
						
										if(count($resSchool) > 0)
											foreach($resSchool as $result)
												$school[SCH_ID_SCHOOL] = $result->{SCH_ID_SCHOOL};
										
										SchoolBusiness::save($school);
										$lines++;
										unset($school);
									}
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportSchool('Falha ao analisar o arquivo de Escolas: '. $e .' [Line]: '.implode(' | ',$row));
									if($flag===FALSE)
										return FALSE;
									else
									{
										$errors++;
										continue;
									}
								}
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($flag !== FALSE) Logger::loggerImportSchool('Importação de Escolas: '.$lines.' registros.');
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela UF
	 * @param String $folder - path para o diretório onde estão os arquivos
	 * @param Array $fileNames - Array contendo o nome de todos os arquivos existentes em $folder
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param String $fileNameError - Armazena o nome do arquivo em caso de erro.
	 * @param Boolean $db - Flag que indica que os dados processados devem ou não ser persistidos
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseUf($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$db=NULL)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('UFBusiness');
			// Array que garante valor único para UF
			$unique = array();
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($db != NULL)Logger::loggerImportAddress('Abrindo o arquivo '.$currentFile);
					
					Zend_Loader::loadClass(CLS_UF);
					if($handle !== NULL)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$line = fgets($handle);
							$row = self::parseLine($line, '@');

							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) < 3)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									Logger::loggerImportAddress('Falha ao ler o arquivo de UF. O arquivo não possui o número de colunas necessário. '.implode(' | ',$row));
									break;
								}

								$uf = array();
			
								try
								{
									$key = self::validateValue(trim($row[0]), Parser::is_string());
									// Valida o tipo dos demais campos para garantir que é o arquivo desejado.
									self::validateValue(trim($row[1]), Parser::is_numeric());
									self::validateValue(trim($row[2]), Parser::is_numeric());

									$parsed++;
									if(!array_key_exists($key,$unique))
									{
										//$nbhd[UF_ID_UF] 		= self::validateValue('');
										$uf[UF_ABBREVIATION] 	= $key;

										if($db !== NULL)
										{
											UFBusiness::insert($uf);
											$lines++;
											$ufArray[] = $uf;
											unset($uf);
										}
									}
									// Adiciona a UF no array para garantir a unicidade
									$unique[$key] = $key;
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportAddress('Falha ao analisar o arquivo de UF: '. $e .' [Line]: '.implode(' | ',$row));
									if($db===NULL)
										return FALSE;
									else
									{
										$errors++;
										continue;
									}
								}
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($db !== NULL) Logger::loggerImportAddress('Importação de UF: '.$lines.' registros.');
			unset($unique);
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return FALSE;
	} 
	
	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela Address
	 * @param String $folder - path para o diretório onde estão os arquivos
	 * @param Array $fileNames - Array contendo o nome de todos os arquivos existentes em $folder
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param String $fileNameError - Armazena o nome do arquivo em caso de erro.
	 * @param Boolean $db - Flag que indica que os dados processados devem ou não ser persistidos
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseAddress($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$db=NULL)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('AddressBusiness');
			Zend_Loader::loadClass('AddressTypeBusiness');
			$addressType = NULL;
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($db != NULL) Logger::loggerImportAddress('Abrindo o arquivo '.$currentFile);

					// Carregado para utilizar as contantes
					Zend_Loader::loadClass(CLS_ADDRESS);
					if($handle !== FALSE)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$row = self::parseLine(fgets($handle), '@');
							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) < 11)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									Logger::loggerImportAddress('Falha ao ler o arquivo de Logradouro. O arquivo não possui o número de colunas necessário. '.implode(' | ',$row));
									break;
								}
								$address = array();

								try
								{
									$address[ADR_ID_ADDRESS] 			= self::validateValue(trim($row[0]), Parser::is_numeric());
									$address[ADR_ID_NEIGHBORHOOD] 		= self::validateValue(trim($row[3]), Parser::is_numeric());
									$address[ADR_ID_ADDRESS_TYPE] 		= self::validateValue(trim($row[8]), Parser::is_string());
									$address[ADR_ZIP_CODE] 				= self::validateValue(trim($row[7]), Parser::is_numeric());
									$address[ADR_ADDRESS] 				= self::validateValue(trim($row[5]), Parser::is_string());
									$address[ADR_ADDRESS_METAFONE]		= MetaPhoneClass::getMetaPhone($address[ADR_ADDRESS]);
									
									// Valida o tipo dos demais campos para garantir que é o arquivo desejado.
									self::validateValue(trim($row[1]), Parser::is_string());
									self::validateValue(trim($row[2]), Parser::is_numeric());
									self::validateValue(trim($row[4]), Parser::is_numeric(), TRUE);
									self::validateValue(trim($row[6]), Parser::is_string(), TRUE);
									// Usado na tabela de Address_type
									$type = self::validateValue(trim($row[8]), Parser::is_string());
									
									self::validateValue(trim($row[9]), Parser::is_string(), TRUE);
									self::validateValue(trim($row[10]), Parser::is_string(), TRUE);

									$parsed ++;
									if($db !== NULL)
									{
										$row = AddressBusiness::load($address[ADR_ID_ADDRESS]);
										if(count($row) == 0)
										{
											if(!$addressType[$type])
											{
												// Moonta array de dados a serem persistidos na tabela de Tipo_Logradouro
												$data[ADT_DESCRIPTION] = $type;
												$id = AddressTypeBusiness::insert($data);
												$addressType[$type] = $id;
											}
										
											$address[ADR_ID_ADDRESS_TYPE] = $addressType[$type];
											AddressBusiness::insert($address);
											$lines++;
											unset($type);
										}
									}
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportAddress('Falha ao analisar o arquivo de Logradouro: '. $e .' [Line]: '.implode(' | ',$row));
									if($db === NULL)
										return FALSE;
									else
									{
										$errors++;
										continue;
									}
								}
								
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($db !== NULL) Logger::loggerImportAddress('Importação de Logradouro: '.$lines.' registros.');
			unset($addressType);
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela AddressNickname
	 * @param String $folder - path para o diretório onde estão os arquivos
	 * @param Array $fileNames - Array contendo o nome de todos os arquivos existentes em $folder
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param String $fileNameError - Armazena o nome do arquivo em caso de erro.
	 * @param Boolean $db - Flag que indica que os dados processados devem ou não ser persistidos
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseAddressNickname($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$db=NULL)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('AddressNicknameBusiness');
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($db != NULL)Logger::loggerImportAddress('Abrindo o arquivo '.$currentFile);
					// Carregado para utilizar as contantes
					Zend_Loader::loadClass(CLS_ADDRESSNICKNAME);
					if($handle !== NULL)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$line = fgets($handle);
							$row = self::parseLine($line, '@');
							$addressArray = NULL;
							
							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) < 4)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									Logger::loggerImportAddress('Falha ao ler o arquivo de Logradouro_Apelido. O arquivo não possui o número de colunas necessário. '.implode(' | ',$row));
									break;
								}
								
								$address = array();
			
								try
								{
									$address[ADN_ID_ADDRESS] 		= self::validateValue(trim($row[0]), Parser::is_numeric());
									$address[ADN_ID_NICKNAME] 		= self::validateValue(trim($row[1]), Parser::is_numeric());
									$address[ADN_NICKNAME] 			= self::validateValue(trim($row[3]), Parser::is_string());
									$address[ADN_NICKNAME_METAFONE] = MetaPhoneClass::getMetaPhone($address[ADN_NICKNAME]);
									
									// Valida o tipo dos demais campos para garantir que é o arquivo desejado.
									self::validateValue(trim($row[2]), Parser::is_string());			
									
									$parsed++;
									if($db !== NULL)
									{
										AddressNicknameBusiness::insert($address);
										$lines++;
										unset($address);
									}
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportAddress('Falha ao analisar o arquivo de Logradouro_Apelido: '. $e .' [Line]: '.implode(' | ',$row));
									if($db===NULL)
										return FALSE;
									else
									{
										$errors++;
										continue;
									}
								}
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($db !== NULL) Logger::loggerImportAddress('Importação de Logradouro_Apelido: '.$lines.' registros.');
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return FALSE;
	}

	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela City
	 * @param String $folder - path para o diretório onde estão os arquivos
	 * @param Array $fileNames - Array contendo o nome de todos os arquivos existentes em $folder
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param String $fileNameError - Armazena o nome do arquivo em caso de erro.
	 * @param Boolean $db - Flag que indica que os dados processados devem ou não ser persistidos
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseCity($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$db=NULL)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('UFBusiness');
			Zend_Loader::loadClass('CityBusiness');
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			$ufMap = NULL;
			if($db !==NULL)
				$ufs = UFBusiness::loadAll();
			foreach($ufs as $uf)
			{
				$ufMap[$uf->{UF_ABBREVIATION}] = $uf->{UF_ID_UF};
			}
			unset($ufs);
			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($db != NULL)	Logger::loggerImportAddress('Abrindo o arquivo '.$currentFile);
					Zend_Loader::loadClass(CLS_CITY);
					if($handle !== NULL)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$line = fgets($handle);
							$row = self::parseLine($line, '@');

							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) < 8)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									Logger::loggerImportAddress('Falha ao ler o arquivo de Localidade. O arquivo não possui o número de colunas necessário. '.implode(' | ',$row));
									break;
								}
								
								$city = array();

								try
								{
									$city[CTY_ID_CITY] 			= self::validateValue(trim($row[0]), Parser::is_numeric());
									$city[CTY_ID_UF] 			= self::validateValue(trim($row[1]), Parser::is_string());
									$city[CTY_CITY] 			= self::validateValue(trim($row[2]), Parser::is_string());
									
									// Valida o tipo dos demais campos para garantir que é o arquivo desejado.
									self::validateValue(trim($row[3]), Parser::is_numeric(), TRUE);
									self::validateValue(trim($row[4]), Parser::is_numeric());
									self::validateValue(trim($row[5]), Parser::is_string());
									self::validateValue(trim($row[6]), Parser::is_numeric(), TRUE);
									self::validateValue(trim($row[7]), Parser::is_string(), TRUE);
									self::validateValue(trim($row[8]), Parser::is_numeric(), TRUE);
									
									$parsed++;
									if($db !== NULL)
									{
										if($ufMap !== NULL && count($ufMap) > 0)
										{
											$city[CTY_ID_UF] = $ufMap[ $city[CTY_ID_UF] ];
											CityBusiness::insert($city);
											$lines++;
											unset($city);
										}
									}
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportAddress('Falha ao analisar o arquivo de Localidade: '. $e .' [Line]: '.implode(' | ',$row));
									unset($city);
									if($db===NULL)
									{
										unset($ufMap);
										return FALSE;
									}	
									else
									{
										$errors++;
										continue;
									}
								}
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($db !== NULL) Logger::loggerImportAddress('Importação de Localidade: '.$lines.' registros.');
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return FALSE;
	} 

	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela Neighborhood
	 * @param String $folder - path para o diretório onde estão os arquivos
	 * @param Array $fileNames - Array contendo o nome de todos os arquivos existentes em $folder
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param String $fileNameError - Armazena o nome do arquivo em caso de erro.
	 * @param Boolean $db - Flag que indica que os dados processados devem ou não ser persistidos
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseNeighborhood($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$db=NULL)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('NeighborhoodBusiness');
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($db != NULL)Logger::loggerImportAddress('Abrindo o arquivo '.$currentFile);
					Zend_Loader::loadClass(CLS_NEIGHBORHOOD);
					if($handle !== NULL)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$line = fgets($handle);
							$row = self::parseLine($line, '@');

							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) < 5)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									Logger::loggerImportAddress('Falha ao ler o arquivo de Bairro. O arquivo não possui o número de colunas necessário. '.implode(' | ',$row));
									break;
								}
										
								$nbhd = array();
			
								try
								{
									$nbhd[NHD_ID_NEIGHBORHOOD] 	= self::validateValue(trim($row[0]), Parser::is_numeric());
									$nbhd[NHD_ID_CITY] 			= self::validateValue(trim($row[2]), Parser::is_numeric());
									$nbhd[NHD_NEIGHBORHOOD] 	= self::validateValue(trim($row[3]), Parser::is_string());
									
									// Valida o tipo dos demais campos para garantir que é o arquivo desejado.
									self::validateValue(trim($row[1]), Parser::is_string());
									self::validateValue(trim($row[4]), Parser::is_string(), TRUE);
									
									$parsed++;
									if($db !== NULL)
									{
										NeighborhoodBusiness::insert($nbhd);
										$lines++;
										unset($nbhd);
									}
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportAddress('Falha ao analisar o arquivo de Bairro: '. $e .' [Line]: '.implode(' | ',$row));
									if($db===NULL)
										return FALSE;
									else
									{
										$errors++;
										continue;
									}
								}
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($db !== NULL) Logger::loggerImportAddress('Importação de Bairro: '.$lines.' registros.');
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return NULL;
	} 




	/**
	 * Função de validação dos valores do arquivo.
	 * Utilizada com funções do tipo 'is_numeric()', 'is_int()'
	 */
	public static function validateValue($value, $funcName=NULL, $nullable=FALSE)
	{
		if($funcName != NULL && !empty($funcName))
		{
			Zend_Loader::loadClass('Utils');
			if($funcName($value) && !Utils::isEmpty($value))
			{
				return $value;
			}
			else
			{
				if($nullable)
				{
					return NULL;
				}
				throw new Exception('O valor '. $value . ' não é um valor válido para a função '.$funcName); 
			}
		}
		return NULL;
	}
	
//	/**
//	 * Faz a leitura do arquivo e retorna um array onde cada posição possui um array de valores
//	 */
//	public static function parseFile(&$handle, $numberOfLines=0, $splitToken=NULL)
//	{
//		$fileArray = NULL;
//		if($handle !== FALSE)
//		{
//			$i = 0;
//			while(!feof($handle) && $i <= $numberOfLines)
//			{
//				$row = fgets($handle);
//				
//				$fileArray[] = self::parseLine($row, $splitToken);
//				
//				if($numberOfLines != 0) $i++;
//				unset($row);
//			}
//		}
//		return $fileArray;
//	}

	public static function parseLine(&$row, $splitToken=NULL)
	{
		if($row !== NULL)
		{
			if($splitToken != NULL)
			{
				return split($splitToken, $row);
			}
			else
			{
				return $row;
			}
		}
		return NULL;
	}
	
	public static function dateFormat($date)
	{
		$year	= substr($date,-4);
		$month	= substr($date,2,-4);
		$day	= substr($date,0,-6);
		
		$dateFormat = $year.'-'.$month.'-'.$day;
		
		return $dateFormat;
	}
}
