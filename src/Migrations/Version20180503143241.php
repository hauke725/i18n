<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180503143241 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE translation_occurrence ADD token_action_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE translation_occurrence ADD CONSTRAINT FK_C4753033E9F77D72 FOREIGN KEY (token_action_id) REFERENCES action (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C4753033E9F77D72 ON translation_occurrence (token_action_id)');
        $this->addSql('ALTER TABLE translation_key ADD deleted BOOLEAN DEFAULT \'false\' NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE translation_key DROP deleted');
        $this->addSql('ALTER TABLE translation_occurrence DROP CONSTRAINT FK_C4753033E9F77D72');
        $this->addSql('DROP INDEX IDX_C4753033E9F77D72');
        $this->addSql('ALTER TABLE translation_occurrence DROP token_action_id');
    }
}
