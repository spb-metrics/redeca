/*   INSERT   TABELA   auth_role    */
INSERT INTO auth_role (id_role, role) VALUES (1, 'Administrador da Rede');
INSERT INTO auth_role (id_role, role) VALUES (2, 'Gerente da Rede');
INSERT INTO auth_role (id_role, role) VALUES (3, 'Coordenador da Entidade');
INSERT INTO auth_role (id_role, role) VALUES (4, 'Técnico');
INSERT INTO auth_role (id_role, role) VALUES (5, 'Operador');

/*   INSERT   TABELA   RES_SUPPLY_TYPE    */
INSERT INTO res_supply_type (id_supply, supply) VALUES (1, 'Rede Pública');
INSERT INTO res_supply_type (id_supply, supply) VALUES (2, 'Poço/Nascente');
INSERT INTO res_supply_type (id_supply, supply) VALUES (3, 'Carro pipa');
INSERT INTO res_supply_type (id_supply, supply) VALUES (4, 'Outro');

/*  INSERT   TABELA   RES_WATER_TYPE   */
INSERT INTO res_water_type (id_water, water) VALUES (1, 'Filtração');
INSERT INTO res_water_type (id_water, water) VALUES (2, 'Fervura');
INSERT INTO res_water_type (id_water, water) VALUES (3, 'Cloração');
INSERT INTO res_water_type (id_water, water) VALUES (4, 'Sem tratamento');
INSERT INTO res_water_type (id_water, water) VALUES (5, 'Outro');

/*  INSERT   TABELA   COV_COVERAGE_HEALTH_TYPE   */
INSERT INTO cov_coverage_health_type (id_coverage_health, coverage_health) VALUES (1, 'PACS');
INSERT INTO cov_coverage_health_type (id_coverage_health, coverage_health) VALUES (2, 'PSF');
INSERT INTO cov_coverage_health_type (id_coverage_health, coverage_health) VALUES (3, 'Similares a PSF');
INSERT INTO cov_coverage_health_type (id_coverage_health, coverage_health) VALUES (4, 'Outro');

/*  INSERT   TABELA   RES_BUILDING_TYPE   */
INSERT INTO res_building_type (id_building, building) VALUES (1, 'Tijolo/Alvenaria');
INSERT INTO res_building_type (id_building, building) VALUES (2, 'Adobe');
INSERT INTO res_building_type (id_building, building) VALUES (3, 'Taipa revestida');
INSERT INTO res_building_type (id_building, building) VALUES (4, 'Taipa nÃ£o revestida');
INSERT INTO res_building_type (id_building, building) VALUES (5, 'Madeira');
INSERT INTO res_building_type (id_building, building) VALUES (6, 'Material aproveitado');
INSERT INTO res_building_type (id_building, building) VALUES (7, 'Outro');

/*  INSERT   TABELA   RES_ILLUMINATION_TYPE   */
INSERT INTO res_illumination_type (id_illumination, illumination) VALUES (1, 'Relógio próprio');
INSERT INTO res_illumination_type (id_illumination, illumination) VALUES (2, 'Sem relógio');
INSERT INTO res_illumination_type (id_illumination, illumination) VALUES (3, 'Relógio comunitário');
INSERT INTO res_illumination_type (id_illumination, illumination) VALUES (4, 'Lampião');
INSERT INTO res_illumination_type (id_illumination, illumination) VALUES (5, 'Vela');
INSERT INTO res_illumination_type (id_illumination, illumination) VALUES (6, 'Outro');

/*  INSERT   TABELA   RES_TRASH_TYPE   */
INSERT INTO res_trash_type (id_trash, trash) VALUES (1, 'Coletado');
INSERT INTO res_trash_type (id_trash, trash) VALUES (2, 'Queimado');
INSERT INTO res_trash_type (id_trash, trash) VALUES (3, 'Enterrado');
INSERT INTO res_trash_type (id_trash, trash) VALUES (4, 'Céu aberto');
INSERT INTO res_trash_type (id_trash, trash) VALUES (5, 'Outro');

/*  INSERT   TABELA   RES_SANITARY_TYPE   */
INSERT INTO res_sanitary_type (id_sanitary, sanitary) VALUES (1, 'Rede Pública');
INSERT INTO res_sanitary_type (id_sanitary, sanitary) VALUES (2, 'Fossa rudimentar');
INSERT INTO res_sanitary_type (id_sanitary, sanitary) VALUES (3, 'Fossa séptica');
INSERT INTO res_sanitary_type (id_sanitary, sanitary) VALUES (4, 'Vala');
INSERT INTO res_sanitary_type (id_sanitary, sanitary) VALUES (5, 'Céu aberto');
INSERT INTO res_sanitary_type (id_sanitary, sanitary) VALUES (6, 'Outro');

/*  INSERT   TABELA   RES_STATUS_TYPE   */
INSERT INTO res_status_type (id_status, status_type) VALUES (1, 'Próprio');
INSERT INTO res_status_type (id_status, status_type) VALUES (2, 'Alugado');
INSERT INTO res_status_type (id_status, status_type) VALUES (3, 'Arrendado');
INSERT INTO res_status_type (id_status, status_type) VALUES (4, 'Cedido');
INSERT INTO res_status_type (id_status, status_type) VALUES (5, 'Invasão');
INSERT INTO res_status_type (id_status, status_type) VALUES (6, 'Financiado');
INSERT INTO res_status_type (id_status, status_type) VALUES (7, 'Outra');

/*  INSERT   TABELA   RES_MORADA_TYPE   */
INSERT INTO res_morada_type (id_morada, morada) VALUES (1, 'Casa');
INSERT INTO res_morada_type (id_morada, morada) VALUES (2, 'Apartamento');
INSERT INTO res_morada_type (id_morada, morada) VALUES (3, 'Cômodos');
INSERT INTO res_morada_type (id_morada, morada) VALUES (4, 'Outro');

/*  INSERT   TABELA   RES_LOCALITY_TYPE   */
INSERT INTO res_locality_type(id_locality, place) VALUES (1, 'Urbana');
INSERT INTO res_locality_type(id_locality, place) VALUES (2, 'Rural');

/* INSERT TABELA emp_income_type */
INSERT INTO emp_income_type (id_income, income) VALUES (1, 'emprego');
INSERT INTO emp_income_type (id_income, income) VALUES (2, 'aposentadoria');
INSERT INTO emp_income_type (id_income, income) VALUES (3, 'seguro-desemprego');
INSERT INTO emp_income_type (id_income, income) VALUES (4, 'pensao');
INSERT INTO emp_income_type (id_income, income) VALUES (5, 'outras rendas');

/* emp_employment_status_type */ 
INSERT INTO emp_employment_status_type (id_employment_status, employment_status) VALUES (1, 'Empregador');
INSERT INTO emp_employment_status_type (id_employment_status, employment_status) VALUES (2, 'Assalariado com carteira de trabalho');
INSERT INTO emp_employment_status_type (id_employment_status, employment_status) VALUES (3, 'Assalariado sem carteira de trabalho');
INSERT INTO emp_employment_status_type (id_employment_status, employment_status) VALUES (4, 'Autônomo com previdência social');
INSERT INTO emp_employment_status_type (id_employment_status, employment_status) VALUES (5, 'Autônomo sem previdência social');
INSERT INTO emp_employment_status_type (id_employment_status, employment_status) VALUES (6, 'Aposentado/Pensionista ');
INSERT INTO emp_employment_status_type (id_employment_status, employment_status) VALUES (7, 'Trabalhador rural');
INSERT INTO emp_employment_status_type (id_employment_status, employment_status) VALUES (8, 'Empregador rural');
INSERT INTO emp_employment_status_type (id_employment_status, employment_status) VALUES (9, 'Não trabalha');
INSERT INTO emp_employment_status_type (id_employment_status, employment_status) VALUES (10, 'Outra');

/* exp_expense_type */
INSERT INTO exp_expense_type (id_expense, expense) VALUES (1, 'HABITAÇÂO');
INSERT INTO exp_expense_type (id_expense, expense) VALUES (2, 'ALIMENTAÇÃO');
INSERT INTO exp_expense_type (id_expense, expense) VALUES (3, 'ÁGUA');
INSERT INTO exp_expense_type (id_expense, expense) VALUES (4, 'LUZ');
INSERT INTO exp_expense_type (id_expense, expense) VALUES (5, 'TRANSPORTE');
INSERT INTO exp_expense_type (id_expense, expense) VALUES (6, 'MEDICAMENTOS');
INSERT INTO exp_expense_type (id_expense, expense) VALUES (7, 'GÁS');
INSERT INTO exp_expense_type (id_expense, expense) VALUES (8, 'OUTRAS');

/* edu_school_type */
INSERT INTO edu_school_type (id_school_type, school_type) VALUES (1, 'Pública municipal');
INSERT INTO edu_school_type (id_school_type, school_type) VALUES (2, 'Pública estadual');
INSERT INTO edu_school_type (id_school_type, school_type) VALUES (3, 'Pública federal');
INSERT INTO edu_school_type (id_school_type, school_type) VALUES (4, 'Particular');
INSERT INTO edu_school_type (id_school_type, school_type) VALUES (5, 'Outra');

/* edu_school_year_type */
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (1, 'Maternal I');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (2, 'Maternal II  ');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (3, 'Maternal III');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (4, 'Jardim I');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (5, 'Jardim II');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (6, 'Jardim III');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (7, 'CA (alfabetização)');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (8, '1a série do ensino fundamental');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (9, '2a série do ensino fundamental');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (10, '3a série do ensino fundamental');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (11, '4a série do ensino fundamental');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (12, '5a série do ensino fundamental');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (13, '6a série do ensino fundamental');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (14, '7a série do ensino fundamental');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (15, '8a série do ensino fundamental');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (16, '1a série do ensino médio');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (17, '2a série do ensino médio');
INSERT INTO edu_school_year_type (id_school_year, school_year) VALUES (18, '3a série do ensino médio');

/* edu_period_type */
INSERT INTO edu_period_type (id_period, period) VALUES (1, 'Manhã');
INSERT INTO edu_period_type (id_period, period) VALUES (2, 'Tarde');
INSERT INTO edu_period_type (id_period, period) VALUES (3, 'Noite');
INSERT INTO edu_period_type (id_period, period) VALUES (4, 'Integral');

/* edu_degree_type */
INSERT INTO edu_degree_type (id_degree, degree) VALUES (1, 'Analfabeto');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (2, 'Até 4a série incompleta do ensino fundamental');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (3, 'Com 4a série completa do ensino fundamental');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (4, 'De 5a a 8a série incompleta do ensino fundamental');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (5, 'Ensino fundamental completo');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (6, 'Ensino médio incompleto');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (7, 'Ensino médio completo');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (8, 'Superior incompleto');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (9, 'Superior completo');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (10, 'Especialização');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (11, 'Mestrado');
INSERT INTO edu_degree_type (id_degree, degree) VALUES (12, 'Doutorado');

/* sop_social_program_origin_type */
INSERT INTO sop_social_program_origin_type (id_origin, origin) VALUES (1, 'Federal');
INSERT INTO sop_social_program_origin_type (id_origin, origin) VALUES (2, 'Estadual');
INSERT INTO sop_social_program_origin_type (id_origin, origin) VALUES (3, 'Municipal');
INSERT INTO sop_social_program_origin_type (id_origin, origin) VALUES (4, 'ONG');
INSERT INTO sop_social_program_origin_type (id_origin, origin) VALUES (5, 'Outros');

/* sop_social_program_type */
INSERT INTO sop_social_program_type (id_social_program_type, benefit, id_origin) VALUES (1, 'PETI', 1);
INSERT INTO sop_social_program_type (id_social_program_type, benefit, id_origin) VALUES (2, 'LOAS', 1);
INSERT INTO sop_social_program_type (id_social_program_type, benefit, id_origin) VALUES (3, 'Agente Jovem', 1);
INSERT INTO sop_social_program_type (id_social_program_type, benefit, id_origin) VALUES (4, 'Previdência Rural', 1);
INSERT INTO sop_social_program_type (id_social_program_type, benefit, id_origin) VALUES (5, 'PRONAF', 1);
INSERT INTO sop_social_program_type (id_social_program_type, benefit, id_origin) VALUES (6, 'PROGER', 1);
INSERT INTO sop_social_program_type (id_social_program_type, benefit, id_origin) VALUES (7, 'Bolsa Família', 1);
INSERT INTO sop_social_program_type (id_social_program_type, benefit, id_origin) VALUES (8, 'Outros', 5);

/* csg_consanguine_type */
INSERT INTO csg_consanguine_type (id_consanguine_type, description) VALUES (1, 'Pai/Mãe');
INSERT INTO csg_consanguine_type (id_consanguine_type, description) VALUES (2, 'Avô/Avó');
INSERT INTO csg_consanguine_type (id_consanguine_type, description) VALUES (3, 'Filho/Filha');

/* per_deficiency_type */
INSERT INTO per_deficiency_type (id_deficiency, name) VALUES (1, 'Cegueira');
INSERT INTO per_deficiency_type (id_deficiency, name) VALUES (2, 'Mudez');
INSERT INTO per_deficiency_type (id_deficiency, name) VALUES (3, 'Surdez');
INSERT INTO per_deficiency_type (id_deficiency, name) VALUES (4, 'Mental');
INSERT INTO per_deficiency_type (id_deficiency, name) VALUES (5, 'Fisica');
INSERT INTO per_deficiency_type (id_deficiency, name) VALUES (6, 'Outra');

/* per_race */
INSERT INTO per_race VALUES (1, 'Branca');
INSERT INTO per_race VALUES (2, 'Negra');
INSERT INTO per_race VALUES (3, 'Parda');
INSERT INTO per_race VALUES (4, 'Amarela');
INSERT INTO per_race VALUES (5, 'Indígena');

/* per_marital_status */
INSERT INTO per_marital_status VALUES (1, 'Solteiro(a)');
INSERT INTO per_marital_status VALUES (2, 'Casado(a)');
INSERT INTO per_marital_status VALUES (3, 'Divorciado(a)');
INSERT INTO per_marital_status VALUES (4, 'Separado(a)');
INSERT INTO per_marital_status VALUES (5, 'Viúvo(a)');

/* per_nationality */
INSERT INTO per_nationality VALUES (1, 'Brasileiro(a)');
INSERT INTO per_nationality VALUES (2, 'Estrangeiro(a)');

/* eas_official_letter_origin */
INSERT INTO eas_official_letter_origin VALUES (1, 'Prefeitura Municipal');
INSERT INTO eas_official_letter_origin VALUES (2, 'Judiciário');

/* eas_lawsuit_origin */
INSERT INTO eas_lawsuit_origin VALUES (1, 'Prefeitura Municipal');
INSERT INTO eas_lawsuit_origin VALUES (2, 'Judiciário');

/* gas_assistance_benefit_type */
INSERT INTO gas_assistance_benefit_type (id_assistance_benefit_type, description) VALUES (1, 'Cesta básica');
INSERT INTO gas_assistance_benefit_type (id_assistance_benefit_type, description) VALUES (2, 'Remédios');
INSERT INTO gas_assistance_benefit_type (id_assistance_benefit_type, description) VALUES (3, 'Roupas');
INSERT INTO gas_assistance_benefit_type (id_assistance_benefit_type, description) VALUES (4, 'Outros');

/* con_telephone_type */
INSERT INTO con_telephone_type (id_telephone, telephone) VALUES (1, 'Fixo');
INSERT INTO con_telephone_type (id_telephone, telephone) VALUES (2, 'Celular');
INSERT INTO con_telephone_type (id_telephone, telephone) VALUES (3, 'Fax');

/* ast_target_market */
INSERT INTO ast_target_market (id_target_market, target_market) VALUES (1, '0 - 7 anos');
INSERT INTO ast_target_market (id_target_market, target_market) VALUES (2, '8 - 13 anos');
INSERT INTO ast_target_market (id_target_market, target_market) VALUES (3, '14 - 18 anos');

/* ast_program_type */
INSERT INTO ast_program_type (id_program_type, program_type, id_target_market) VALUES (1, 'Profissionalizante', 3);
INSERT INTO ast_program_type (id_program_type, program_type, id_target_market) VALUES (2, 'Complementação Escolar', 3);
INSERT INTO ast_program_type (id_program_type, program_type, id_target_market) VALUES (3, 'Prestação de Serviço à Comunidade (PSC)', 3);
INSERT INTO ast_program_type (id_program_type, program_type, id_target_market) VALUES (4, 'Liberdade Assistida', 3);
INSERT INTO ast_program_type (id_program_type, program_type, id_target_market) VALUES (5, 'Semi-liberdade', 3);
INSERT INTO ast_program_type (id_program_type, program_type, id_target_market) VALUES (6, 'Internação', 3);
INSERT INTO ast_program_type (id_program_type, program_type, id_target_market) VALUES (7, 'Abrigamento', 3);
INSERT INTO ast_program_type (id_program_type, program_type, id_target_market) VALUES (8, 'Programas específicos', 2);
INSERT INTO ast_program_type (id_program_type, program_type, id_target_market) VALUES (9, 'Creches e Escolas (Escolar)', 1);

/* ent_entity_area_type */
INSERT INTO ent_entity_area_type (id_entity_area, entity_area) VALUES (1, 'Vida e Saúde ');
INSERT INTO ent_entity_area_type (id_entity_area, entity_area) VALUES (2, 'Educação, Cultura, Esporte e Lazer');
INSERT INTO ent_entity_area_type (id_entity_area, entity_area) VALUES (3, 'Convivência familiar e comunitária');
INSERT INTO ent_entity_area_type (id_entity_area, entity_area) VALUES (4, 'Liberdade, respeito e Dignidade');
INSERT INTO ent_entity_area_type (id_entity_area, entity_area) VALUES (5, 'Profissionalização e Proteção no Trabalho. ');

/* ent_entity_group_type */
INSERT INTO ent_entity_group_type (id_entity_group, entity_group) VALUES (17, 'Creches');

/* act_status_class */
INSERT INTO act_status_class VALUES (1, 'Em atendimento');
INSERT INTO act_status_class VALUES (2, 'Em espera');
INSERT INTO act_status_class VALUES (3, 'Encerrado');

/* fam_kinship_type */
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (01, 'Mãe');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (02, 'Esposo(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (03, 'Companheiro(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (04, 'Filho(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (05, 'Pai');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (06, 'Avô/Avó');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (07, 'Irmão/Irmã');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (08, 'Cunhado(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (09, 'Genro/Nora');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (10, 'Sobrinho(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (11, 'Primo(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (12, 'Sogro(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (13, 'Neto(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (14, 'Tio(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (15, 'Adotivo(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (16, 'Padrasto/Madrasta');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (17, 'Enteado(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (18, 'Bisneto(a)');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (19, 'Sem parentesco');
INSERT INTO fam_kinship_type (id_kinship, kinship) VALUES (20, 'Outro');

/*	INSERT TABELA auth_resource		*/
INSERT INTO `auth_resource` (`id_resource`, `controller_name`, `resource_type`) VALUES (1, '/user', 'A'),
(2, '/activity', 'A'),
(3, '/additional-information', 'A'),
(4, '/area', 'A'),
(5, '/associate-entity', 'A'),
(6, '/attendance', 'E'),
(7, '/auth', 'A'),
(8, '/benefit', 'E'),
(9, '/biological-relationship', 'E'),
(10, '/class', 'E'),
(11, '/classification', 'A'),
(12, '/education', 'E'),
(13, '/entity', 'A'),
(14, '/export', 'A'),
(15, '/family-expense', 'E'),
(16, '/family-relationship', 'E'),
(17, '/group', 'A'),
(18, '/health', 'E'),
(19, '/history', 'E'),
(20, '/import', 'A'),
(21, '/income', 'E'),
(22, '/index', 'A'),
(23, '/network', 'A'),
(24, '/person', 'E'),
(25, '/profile', 'A'),
(26, '/program', 'A'),
(27, '/region', 'A'),
(28, '/report', 'A'),
(29, '/residence', 'E'),
(30, '/search-address', 'E'),
(31, '/search', 'E'),
(32, '/entity-initial', 'A'),
(33, '/access-denied', 'A'),
(34, '/activity-detail', 'A'),
(35, '/person-log', 'A');

-- permissões do tipo Administrator
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('1', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('2', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('3', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('4', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('5', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('6', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('7', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('8', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('9', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('10', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('11', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('12', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('13', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('14', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('15', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('16', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('17', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('18', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('19', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('20', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('21', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('22', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('23', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('24', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('25', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('26', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('27', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('28', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('29', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('30', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('31', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('32', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('33', '1');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('35', '1');

-- permissões do tipo Gerente da Rede
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('1', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('6', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('7', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('8', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('9', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('10', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('12', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('13', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('14', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('15', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('16', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('18', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('19', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('21', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('22', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('23', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('24', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('28', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('29', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('30', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('31', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('33', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('34', '2');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('35', '2');

-- permissões do tipo Coordenador da Entidade
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('1', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('6', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('7', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('8', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('9', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('10', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('12', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('13', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('14', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('15', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('16', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('18', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('19', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('21', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('22', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('23', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('24', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('28', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('29', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('30', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('31', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('33', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('34', '3');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('35', '3');

-- permissões do tipo Técnico
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('6', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('7', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('8', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('9', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('10', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('12', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('15', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('16', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('18', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('19', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('21', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('22', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('23', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('24', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('29', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('30', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('31', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('33', '4');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('35', '4');

-- permissões do tipo Operador
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('7', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('8', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('9', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('12', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('15', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('16', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('18', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('21', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('22', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('23', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('24', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('29', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('30', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('31', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('33', '5');
INSERT INTO auth_role_resource (id_resource, id_role) VALUES ('35', '5');

/*	INSERT TABELA auth_user	*/
INSERT INTO auth_user VALUES ('1', null, null, '1', 'root', 'root', md5('root'), 'admin@admin.com.br', '29836240802', '1', curdate(), '1');

