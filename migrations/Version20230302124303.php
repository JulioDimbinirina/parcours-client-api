<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302124303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE suite_process (id INT AUTO_INCREMENT NOT NULL, bdc_id INT NOT NULL, is_customer_will_send_bdc SMALLINT NOT NULL, is_seizure_contract SMALLINT NOT NULL, is_devis_pass_to_prod_after_sign SMALLINT NOT NULL, UNIQUE INDEX UNIQ_E11BCF1A28DF9AB0 (bdc_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE suite_process ADD CONSTRAINT FK_E11BCF1A28DF9AB0 FOREIGN KEY (bdc_id) REFERENCES bdc (id)');
        $this->addSql('ALTER TABLE bdc_operation ADD CONSTRAINT FK_342EE1DF28DF9AB0 FOREIGN KEY (bdc_id) REFERENCES bdc (id)');
        $this->addSql('ALTER TABLE bdc_operation ADD CONSTRAINT FK_342EE1DF44AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id)');
        $this->addSql('ALTER TABLE bdc_operation ADD CONSTRAINT FK_342EE1DF800DC1FD FOREIGN KEY (langue_trt_id) REFERENCES langue_trt (id)');
        $this->addSql('ALTER TABLE bdc_operation ADD CONSTRAINT FK_342EE1DF8D06DB10 FOREIGN KEY (type_facturation_id) REFERENCES type_facturation (id)');
        $this->addSql('ALTER TABLE bdc_operation ADD CONSTRAINT FK_342EE1DF9EFA7AED FOREIGN KEY (famille_operation_id) REFERENCES famille_operation (id)');
        $this->addSql('ALTER TABLE bdc_operation ADD CONSTRAINT FK_342EE1DFE0319FBC FOREIGN KEY (bu_id) REFERENCES bu (id)');
        $this->addSql('ALTER TABLE bdc_operation ADD CONSTRAINT FK_342EE1DF8E47663F FOREIGN KEY (cout_horaire_id) REFERENCES cout_horaire (id)');
        $this->addSql('ALTER TABLE bdc_operation ADD CONSTRAINT FK_342EE1DF357C0A59 FOREIGN KEY (tarif_id) REFERENCES tarif (id)');
        $this->addSql('ALTER TABLE bdc_operation ADD CONSTRAINT FK_342EE1DFCB40E30D FOREIGN KEY (designation_acte_id) REFERENCES operation (id)');
        $this->addSql('ALTER TABLE bdc_operation_objectif_qualitatif ADD CONSTRAINT FK_9115D9877B27786B FOREIGN KEY (bdc_operation_id) REFERENCES bdc_operation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bdc_operation_objectif_qualitatif ADD CONSTRAINT FK_9115D98784C558C9 FOREIGN KEY (objectif_qualitatif_id) REFERENCES objectif_qualitatif (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bdc_operation_objectif_quantitatif ADD CONSTRAINT FK_A25B503F7B27786B FOREIGN KEY (bdc_operation_id) REFERENCES bdc_operation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bdc_operation_objectif_quantitatif ADD CONSTRAINT FK_A25B503F899CB9C9 FOREIGN KEY (objectif_quantitatif_id) REFERENCES objectif_quantitatif (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_document ADD CONSTRAINT FK_F68FBAB39395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE client_document ADD CONSTRAINT FK_F68FBAB38826AFA6 FOREIGN KEY (type_document_id) REFERENCES type_document (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6389395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE contact_has_profil_contact ADD CONSTRAINT FK_A248224CE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_has_profil_contact ADD CONSTRAINT FK_A248224CDC677EB4 FOREIGN KEY (profil_contact_id) REFERENCES profil_contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coordone_bancaire ADD CONSTRAINT FK_F8A9344AE7D306A2 FOREIGN KEY (societe_facturation_id) REFERENCES societe_facturation (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09B4B46626 FOREIGN KEY (categorie_client_id) REFERENCES categorie_client (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E0955B93C0F FOREIGN KEY (mapping_client_id) REFERENCES mapping_client (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E095BBD1224 FOREIGN KEY (adresse_facturation_id) REFERENCES adresse_facturation (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE devise ADD CONSTRAINT FK_43EDA4DF899CF741 FOREIGN KEY (pays_facturation_id) REFERENCES pays_facturation (id)');
        $this->addSql('ALTER TABLE fiche_client ADD CONSTRAINT FK_7158A9829395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE fiche_client ADD CONSTRAINT FK_7158A9821E4CCA8D FOREIGN KEY (nature_prestation_id) REFERENCES nature_prestation (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE indicator_qualitatif ADD CONSTRAINT FK_DB5AD6F584C558C9 FOREIGN KEY (objectif_qualitatif_id) REFERENCES objectif_qualitatif (id)');
        $this->addSql('ALTER TABLE indicator_qualitatif ADD CONSTRAINT FK_DB5AD6F57B27786B FOREIGN KEY (bdc_operation_id) REFERENCES bdc_operation (id)');
        $this->addSql('ALTER TABLE indicator_qualitatif ADD CONSTRAINT FK_DB5AD6F553EF6646 FOREIGN KEY (lead_detail_operation_id) REFERENCES lead_detail_operation (id)');
        $this->addSql('ALTER TABLE indicator_quantitatif ADD CONSTRAINT FK_1C1A0F20899CB9C9 FOREIGN KEY (objectif_quantitatif_id) REFERENCES objectif_quantitatif (id)');
        $this->addSql('ALTER TABLE indicator_quantitatif ADD CONSTRAINT FK_1C1A0F2053EF6646 FOREIGN KEY (lead_detail_operation_id) REFERENCES lead_detail_operation (id)');
        $this->addSql('ALTER TABLE indicator_quantitatif ADD CONSTRAINT FK_1C1A0F207B27786B FOREIGN KEY (bdc_operation_id) REFERENCES bdc_operation (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728E8D06DB10 FOREIGN KEY (type_facturation_id) REFERENCES type_facturation (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728E800DC1FD FOREIGN KEY (langue_trt_id) REFERENCES langue_trt (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728EE0319FBC FOREIGN KEY (bu_id) REFERENCES bu (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728E44AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728E9EFA7AED FOREIGN KEY (famille_operation_id) REFERENCES famille_operation (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728EF9A66EC9 FOREIGN KEY (horaire_production_id) REFERENCES horaire_production (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728E3615FA65 FOREIGN KEY (resume_lead_id) REFERENCES resume_lead (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728E899CF741 FOREIGN KEY (pays_facturation_id) REFERENCES pays_facturation (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728EDD21E7CC FOREIGN KEY (pays_production_id) REFERENCES pays_production (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728E8E47663F FOREIGN KEY (cout_horaire_id) REFERENCES cout_horaire (id)');
        $this->addSql('ALTER TABLE lead_detail_operation ADD CONSTRAINT FK_29FD728ECB40E30D FOREIGN KEY (designation_acte_id) REFERENCES operation (id)');
        $this->addSql('ALTER TABLE lead_detail_operation_objectif_qualitatif ADD CONSTRAINT FK_DE65A1F553EF6646 FOREIGN KEY (lead_detail_operation_id) REFERENCES lead_detail_operation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lead_detail_operation_objectif_qualitatif ADD CONSTRAINT FK_DE65A1F584C558C9 FOREIGN KEY (objectif_qualitatif_id) REFERENCES objectif_qualitatif (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lead_detail_operation_objectif_quantitatif ADD CONSTRAINT FK_1C1F305753EF6646 FOREIGN KEY (lead_detail_operation_id) REFERENCES lead_detail_operation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lead_detail_operation_objectif_quantitatif ADD CONSTRAINT FK_1C1F3057899CB9C9 FOREIGN KEY (objectif_quantitatif_id) REFERENCES objectif_quantitatif (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D9EFA7AED FOREIGN KEY (famille_operation_id) REFERENCES famille_operation (id)');
        $this->addSql('ALTER TABLE reject_bdc ADD CONSTRAINT FK_1042830928DF9AB0 FOREIGN KEY (bdc_id) REFERENCES bdc (id)');
        $this->addSql('ALTER TABLE resume_lead ADD CONSTRAINT FK_D657A00FB52250E0 FOREIGN KEY (origin_lead_id) REFERENCES origin_lead (id)');
        $this->addSql('ALTER TABLE resume_lead ADD CONSTRAINT FK_D657A00F18D61EF7 FOREIGN KEY (duree_trt_id) REFERENCES duree_trt (id)');
        $this->addSql('ALTER TABLE resume_lead ADD CONSTRAINT FK_D657A00FB0738B00 FOREIGN KEY (potentiel_transformation_id) REFERENCES potentiel_transformation (id)');
        $this->addSql('ALTER TABLE resume_lead ADD CONSTRAINT FK_D657A00F9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE societe_facturation ADD CONSTRAINT FK_774486CB899CF741 FOREIGN KEY (pays_facturation_id) REFERENCES pays_facturation (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649727ACA70 FOREIGN KEY (parent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DD21E7CC FOREIGN KEY (pays_production_id) REFERENCES pays_production (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649727ACA70 ON user (parent_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649DD21E7CC ON user (pays_production_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE suite_process');
        $this->addSql('ALTER TABLE bdc_operation DROP FOREIGN KEY FK_342EE1DF28DF9AB0');
        $this->addSql('ALTER TABLE bdc_operation DROP FOREIGN KEY FK_342EE1DF44AC3583');
        $this->addSql('ALTER TABLE bdc_operation DROP FOREIGN KEY FK_342EE1DF800DC1FD');
        $this->addSql('ALTER TABLE bdc_operation DROP FOREIGN KEY FK_342EE1DF8D06DB10');
        $this->addSql('ALTER TABLE bdc_operation DROP FOREIGN KEY FK_342EE1DF9EFA7AED');
        $this->addSql('ALTER TABLE bdc_operation DROP FOREIGN KEY FK_342EE1DFE0319FBC');
        $this->addSql('ALTER TABLE bdc_operation DROP FOREIGN KEY FK_342EE1DF8E47663F');
        $this->addSql('ALTER TABLE bdc_operation DROP FOREIGN KEY FK_342EE1DF357C0A59');
        $this->addSql('ALTER TABLE bdc_operation DROP FOREIGN KEY FK_342EE1DFCB40E30D');
        $this->addSql('ALTER TABLE bdc_operation_objectif_qualitatif DROP FOREIGN KEY FK_9115D9877B27786B');
        $this->addSql('ALTER TABLE bdc_operation_objectif_qualitatif DROP FOREIGN KEY FK_9115D98784C558C9');
        $this->addSql('ALTER TABLE bdc_operation_objectif_quantitatif DROP FOREIGN KEY FK_A25B503F7B27786B');
        $this->addSql('ALTER TABLE bdc_operation_objectif_quantitatif DROP FOREIGN KEY FK_A25B503F899CB9C9');
        $this->addSql('ALTER TABLE client_document DROP FOREIGN KEY FK_F68FBAB39395C3F3');
        $this->addSql('ALTER TABLE client_document DROP FOREIGN KEY FK_F68FBAB38826AFA6');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6389395C3F3');
        $this->addSql('ALTER TABLE contact_has_profil_contact DROP FOREIGN KEY FK_A248224CE7A1254A');
        $this->addSql('ALTER TABLE contact_has_profil_contact DROP FOREIGN KEY FK_A248224CDC677EB4');
        $this->addSql('ALTER TABLE coordone_bancaire DROP FOREIGN KEY FK_F8A9344AE7D306A2');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09B4B46626');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E0955B93C0F');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E095BBD1224');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09A76ED395');
        $this->addSql('ALTER TABLE devise DROP FOREIGN KEY FK_43EDA4DF899CF741');
        $this->addSql('ALTER TABLE fiche_client DROP FOREIGN KEY FK_7158A9829395C3F3');
        $this->addSql('ALTER TABLE fiche_client DROP FOREIGN KEY FK_7158A9821E4CCA8D');
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5ECE7A1254A');
        $this->addSql('ALTER TABLE indicator_qualitatif DROP FOREIGN KEY FK_DB5AD6F584C558C9');
        $this->addSql('ALTER TABLE indicator_qualitatif DROP FOREIGN KEY FK_DB5AD6F57B27786B');
        $this->addSql('ALTER TABLE indicator_qualitatif DROP FOREIGN KEY FK_DB5AD6F553EF6646');
        $this->addSql('ALTER TABLE indicator_quantitatif DROP FOREIGN KEY FK_1C1A0F20899CB9C9');
        $this->addSql('ALTER TABLE indicator_quantitatif DROP FOREIGN KEY FK_1C1A0F2053EF6646');
        $this->addSql('ALTER TABLE indicator_quantitatif DROP FOREIGN KEY FK_1C1A0F207B27786B');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728E8D06DB10');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728E800DC1FD');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728EE0319FBC');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728E44AC3583');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728E9EFA7AED');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728EF9A66EC9');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728E3615FA65');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728E899CF741');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728EDD21E7CC');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728E8E47663F');
        $this->addSql('ALTER TABLE lead_detail_operation DROP FOREIGN KEY FK_29FD728ECB40E30D');
        $this->addSql('ALTER TABLE lead_detail_operation_objectif_qualitatif DROP FOREIGN KEY FK_DE65A1F553EF6646');
        $this->addSql('ALTER TABLE lead_detail_operation_objectif_qualitatif DROP FOREIGN KEY FK_DE65A1F584C558C9');
        $this->addSql('ALTER TABLE lead_detail_operation_objectif_quantitatif DROP FOREIGN KEY FK_1C1F305753EF6646');
        $this->addSql('ALTER TABLE lead_detail_operation_objectif_quantitatif DROP FOREIGN KEY FK_1C1F3057899CB9C9');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D9EFA7AED');
        $this->addSql('ALTER TABLE reject_bdc DROP FOREIGN KEY FK_1042830928DF9AB0');
        $this->addSql('ALTER TABLE resume_lead DROP FOREIGN KEY FK_D657A00FB52250E0');
        $this->addSql('ALTER TABLE resume_lead DROP FOREIGN KEY FK_D657A00F18D61EF7');
        $this->addSql('ALTER TABLE resume_lead DROP FOREIGN KEY FK_D657A00FB0738B00');
        $this->addSql('ALTER TABLE resume_lead DROP FOREIGN KEY FK_D657A00F9395C3F3');
        $this->addSql('ALTER TABLE societe_facturation DROP FOREIGN KEY FK_774486CB899CF741');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649727ACA70');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DD21E7CC');
        $this->addSql('DROP INDEX IDX_8D93D649727ACA70 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649DD21E7CC ON user');
    }
}
