<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180430065134 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER SEQUENCE translation_key_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE translation_occurrence_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE translation_file_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE translation_value_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE action_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE language_id_seq INCREMENT BY 1');
        $this->addSql('ALTER TABLE translation_value ADD translation_key_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE translation_value ADD language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE translation_value ADD CONSTRAINT FK_7A87305BD07ED992 FOREIGN KEY (translation_key_id) REFERENCES translation_key (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE translation_value ADD CONSTRAINT FK_7A87305B82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7A87305BD07ED992 ON translation_value (translation_key_id)');
        $this->addSql('CREATE INDEX IDX_7A87305B82F1BAF4 ON translation_value (language_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER SEQUENCE translation_key_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE translation_occurrence_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE translation_file_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE translation_value_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE action_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE language_id_seq INCREMENT BY 1');
        $this->addSql('ALTER TABLE translation_value DROP CONSTRAINT FK_7A87305BD07ED992');
        $this->addSql('ALTER TABLE translation_value DROP CONSTRAINT FK_7A87305B82F1BAF4');
        $this->addSql('DROP INDEX IDX_7A87305BD07ED992');
        $this->addSql('DROP INDEX IDX_7A87305B82F1BAF4');
        $this->addSql('ALTER TABLE translation_value DROP translation_key_id');
        $this->addSql('ALTER TABLE translation_value DROP language_id');
    }
}
