<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210823100942 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE storage_item ADD document_library_id INT DEFAULT NULL, ADD library_node_id INT DEFAULT NULL, ADD path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE storage_item ADD CONSTRAINT FK_1636EE0B5FE6EA81 FOREIGN KEY (document_library_id) REFERENCES document_library (id)');
        $this->addSql('ALTER TABLE storage_item ADD CONSTRAINT FK_1636EE0B3CCC27E7 FOREIGN KEY (library_node_id) REFERENCES library_node (id)');
        $this->addSql('CREATE INDEX IDX_1636EE0B5FE6EA81 ON storage_item (document_library_id)');
        $this->addSql('CREATE INDEX IDX_1636EE0B3CCC27E7 ON storage_item (library_node_id)');
        $this->addSql('ALTER TABLE storages ADD storage_path VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE storage_item DROP FOREIGN KEY FK_1636EE0B5FE6EA81');
        $this->addSql('ALTER TABLE storage_item DROP FOREIGN KEY FK_1636EE0B3CCC27E7');
        $this->addSql('DROP INDEX IDX_1636EE0B5FE6EA81 ON storage_item');
        $this->addSql('DROP INDEX IDX_1636EE0B3CCC27E7 ON storage_item');
        $this->addSql('ALTER TABLE storage_item DROP document_library_id, DROP library_node_id, DROP path');
        $this->addSql('ALTER TABLE storages DROP storage_path');
    }
}
