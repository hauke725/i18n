<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180429070503 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE translation_key_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE translation_occurrence_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE translation_file_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE translation_value_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE action_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE language_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE translation_key (id INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AADCBD56B23DB7B8 ON translation_key (created)');
        $this->addSql('COMMENT ON COLUMN translation_key.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE translation_occurrence (id INT NOT NULL, action_id INT DEFAULT NULL, translation_key_id INT DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C47530339D32F035 ON translation_occurrence (action_id)');
        $this->addSql('CREATE INDEX IDX_C4753033D07ED992 ON translation_occurrence (translation_key_id)');
        $this->addSql('CREATE INDEX IDX_C4753033B23DB7B8 ON translation_occurrence (created)');
        $this->addSql('COMMENT ON COLUMN translation_occurrence.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE translation_file (id INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A1BD958BB23DB7B8 ON translation_file (created)');
        $this->addSql('COMMENT ON COLUMN translation_file.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE translation_value (id INT NOT NULL, file_id INT DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, value TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7A87305B93CB796C ON translation_value (file_id)');
        $this->addSql('CREATE INDEX IDX_7A87305BB23DB7B8 ON translation_value (created)');
        $this->addSql('COMMENT ON COLUMN translation_value.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE action (id INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_47CC8C92B23DB7B8 ON action (created)');
        $this->addSql('COMMENT ON COLUMN action.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE language (id INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D4DB71B5B23DB7B8 ON language (created)');
        $this->addSql('COMMENT ON COLUMN language.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE translation_occurrence ADD CONSTRAINT FK_C47530339D32F035 FOREIGN KEY (action_id) REFERENCES action (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE translation_occurrence ADD CONSTRAINT FK_C4753033D07ED992 FOREIGN KEY (translation_key_id) REFERENCES translation_key (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE translation_value ADD CONSTRAINT FK_7A87305B93CB796C FOREIGN KEY (file_id) REFERENCES translation_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE translation_occurrence DROP CONSTRAINT FK_C4753033D07ED992');
        $this->addSql('ALTER TABLE translation_value DROP CONSTRAINT FK_7A87305B93CB796C');
        $this->addSql('ALTER TABLE translation_occurrence DROP CONSTRAINT FK_C47530339D32F035');
        $this->addSql('DROP SEQUENCE translation_key_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE translation_occurrence_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE translation_file_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE translation_value_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE action_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE language_id_seq CASCADE');
        $this->addSql('DROP TABLE translation_key');
        $this->addSql('DROP TABLE translation_occurrence');
        $this->addSql('DROP TABLE translation_file');
        $this->addSql('DROP TABLE translation_value');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE language');
    }
}
