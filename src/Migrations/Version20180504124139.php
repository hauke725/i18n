<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180504124139 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE api_one_time_access_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE api_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE api_one_time_access (id INT NOT NULL, api_user_id INT DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C3351E674A50A7F2 ON api_one_time_access (api_user_id)');
        $this->addSql('CREATE INDEX IDX_C3351E67B23DB7B8 ON api_one_time_access (created)');
        $this->addSql('COMMENT ON COLUMN api_one_time_access.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE api_user (id INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, username VARCHAR(255) NOT NULL, api_key VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC64A0BAB23DB7B8 ON api_user (created)');
        $this->addSql('COMMENT ON COLUMN api_user.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE api_one_time_access ADD CONSTRAINT FK_C3351E674A50A7F2 FOREIGN KEY (api_user_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api_one_time_access DROP CONSTRAINT FK_C3351E674A50A7F2');
        $this->addSql('DROP SEQUENCE api_one_time_access_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE api_user_id_seq CASCADE');
        $this->addSql('DROP TABLE api_one_time_access');
        $this->addSql('DROP TABLE api_user');
    }
}
