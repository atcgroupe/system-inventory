<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128110924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contract_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE device_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE device_info_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE model_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_info_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, name VARCHAR(50) NOT NULL, is_provider BOOLEAN NOT NULL, is_manufacturer BOOLEAN NOT NULL, technical_department_phone VARCHAR(12) DEFAULT NULL, technical_department_procedure TEXT DEFAULT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE contact (id INT NOT NULL, company_id INT NOT NULL, gender SMALLINT NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, email VARCHAR(100) DEFAULT NULL, phone VARCHAR(12) DEFAULT NULL, mobile VARCHAR(12) DEFAULT NULL, role SMALLINT NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C62E638979B1AD6 ON contact (company_id)');
        $this->addSql('CREATE TABLE contract (id INT NOT NULL, provider_id INT NOT NULL, reference VARCHAR(100) NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, type SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E98F2859A53A8AA ON contract (provider_id)');
        $this->addSql('CREATE TABLE device (id INT NOT NULL, model_id INT NOT NULL, provider_id INT NOT NULL, serial VARCHAR(50) NOT NULL, manufacture_year VARCHAR(4) DEFAULT NULL, purchase_date DATE DEFAULT NULL, manufacturer_warranty_end_date DATE DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_92FB68E7975B7E7 ON device (model_id)');
        $this->addSql('CREATE INDEX IDX_92FB68EA53A8AA ON device (provider_id)');
        $this->addSql('CREATE TABLE device_tag (device_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(device_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_E9776D1A94A4C7D4 ON device_tag (device_id)');
        $this->addSql('CREATE INDEX IDX_E9776D1ABAD26311 ON device_tag (tag_id)');
        $this->addSql('CREATE TABLE device_info (id INT NOT NULL, tag_id INT NOT NULL, device_id INT NOT NULL, value VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42B0E46DBAD26311 ON device_info (tag_id)');
        $this->addSql('CREATE INDEX IDX_42B0E46D94A4C7D4 ON device_info (device_id)');
        $this->addSql('CREATE TABLE model (id INT NOT NULL, manufacturer_id INT NOT NULL, name VARCHAR(50) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D79572D9A23B42D ON model (manufacturer_id)');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, name VARCHAR(20) NOT NULL, color VARCHAR(10) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tag_info (id INT NOT NULL, tag_id INT NOT NULL, name VARCHAR(50) NOT NULL, label VARCHAR(50) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_25868EE1BAD26311 ON tag_info (tag_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859A53A8AA FOREIGN KEY (provider_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68EA53A8AA FOREIGN KEY (provider_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE device_tag ADD CONSTRAINT FK_E9776D1A94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE device_tag ADD CONSTRAINT FK_E9776D1ABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE device_info ADD CONSTRAINT FK_42B0E46DBAD26311 FOREIGN KEY (tag_id) REFERENCES tag_info (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE device_info ADD CONSTRAINT FK_42B0E46D94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9A23B42D FOREIGN KEY (manufacturer_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_info ADD CONSTRAINT FK_25868EE1BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE contact DROP CONSTRAINT FK_4C62E638979B1AD6');
        $this->addSql('ALTER TABLE contract DROP CONSTRAINT FK_E98F2859A53A8AA');
        $this->addSql('ALTER TABLE device DROP CONSTRAINT FK_92FB68EA53A8AA');
        $this->addSql('ALTER TABLE model DROP CONSTRAINT FK_D79572D9A23B42D');
        $this->addSql('ALTER TABLE device_tag DROP CONSTRAINT FK_E9776D1A94A4C7D4');
        $this->addSql('ALTER TABLE device_info DROP CONSTRAINT FK_42B0E46D94A4C7D4');
        $this->addSql('ALTER TABLE device DROP CONSTRAINT FK_92FB68E7975B7E7');
        $this->addSql('ALTER TABLE device_tag DROP CONSTRAINT FK_E9776D1ABAD26311');
        $this->addSql('ALTER TABLE tag_info DROP CONSTRAINT FK_25868EE1BAD26311');
        $this->addSql('ALTER TABLE device_info DROP CONSTRAINT FK_42B0E46DBAD26311');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contact_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contract_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE device_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE device_info_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE model_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_info_id_seq CASCADE');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE device');
        $this->addSql('DROP TABLE device_tag');
        $this->addSql('DROP TABLE device_info');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_info');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
