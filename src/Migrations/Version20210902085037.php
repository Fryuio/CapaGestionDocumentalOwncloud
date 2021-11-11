<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902085037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE storages ADD document_library_id INT DEFAULT NULL, ADD storage_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE storages ADD CONSTRAINT FK_3AEE41A55FE6EA81 FOREIGN KEY (document_library_id) REFERENCES document_library (id)');
        $this->addSql('CREATE INDEX IDX_3AEE41A55FE6EA81 ON storages (document_library_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE storages DROP FOREIGN KEY FK_3AEE41A55FE6EA81');
        $this->addSql('DROP INDEX IDX_3AEE41A55FE6EA81 ON storages');
        $this->addSql('ALTER TABLE storages DROP document_library_id, DROP storage_path');
    }
}
