-- bu
INSERT INTO bu(libelle) VALUES ('OUTBOUND'), ('INBOUND'), ('HELP DESK'), ('BPO'), ('REDACTION'), ('DIGITAL'), ('DEV INFO'), ('ETUDES'), ('AUTRES');

--horaire_production
INSERT INTO horaire_production(libelle) VALUES ('Heures du pays de production'), ('Heures clients finaux');

-- type facturation
INSERT INTO type_facturation(libelle) VALUES ('Acte'), ('A déterminer'), ('A l''heure'), ('En régie forfaitaire');

-- langueTrt
INSERT INTO langue_trt(libelle) VALUES ('FR'), ('UK'), ('ES'), ('IT'), ('DE'), ('NL'), ('PT'), ('BR'), ('MA'), ('MG');

-- famille operation
INSERT INTO famille_operation(libelle) VALUES ('FRAIS DE MISE EN PLACE'), ('TELECOM');

-- operation
INSERT INTO operation(libelle, famille_operation_id) VALUES ('FRAIS DE MISE EN PLACE', 1), ('AUTRES BONUS', 1);

-- ########################### RESUME LEAD REFS ###########################################

-- origin_lead
INSERT INTO origin_lead(libelle) VALUE ('Email entrant'), ('Appel eserntrant'), ('Salon'), ('Prospection'), ('Apporteur d''affaires'),
('Contact client'), ('Campagne de Prospection'), ('Campagne de Prospection BIG BOSS'), ('Campagne de Prospection Mada'),
('Campagne de Prospection CCI Chine'), ('Salon'), ('BIG BOSS'), ('Formulaire en ligne'), ('Ancien client'), ('Diagnostique'),
('Base Salesforce'), ('Réseau');

-- potentiel_transformation
INSERT INTO potentiel_transformation(libelle) VALUES
('10% (Pas de devis envoyé)'), ('20% (Devis sans échanges)'),
('30% (Devis avec quelques échanges)'), ('40% (Bon feedback)'),
('50% (Shortlist à 2)'), ('70% (Négociation avancée)'),
('90% (Ok de principe)'), ('100% (BDC signé et validé)');

-- duree_trt
INSERT INTO duree_trt(libelle) VALUES ('Ponctuelle'), ('Récurrente');

-- ########################################################################################