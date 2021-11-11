<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210907074803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document ADD document_id INT DEFAULT NULL, ADD last_version TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('CREATE INDEX IDX_D8698A76C33F7837 ON document (document_id)');
        $this->addSql('ALTER TABLE storage_item ADD document_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE storage_item ADD CONSTRAINT FK_1636EE0BC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('CREATE INDEX IDX_1636EE0BC33F7837 ON storage_item (document_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76C33F7837');
        $this->addSql('DROP INDEX IDX_D8698A76C33F7837 ON document');
        $this->addSql('ALTER TABLE document DROP document_id, DROP last_version');
        $this->addSql('ALTER TABLE storage_item DROP FOREIGN KEY FK_1636EE0BC33F7837');
        $this->addSql('DROP INDEX IDX_1636EE0BC33F7837 ON storage_item');
        $this->addSql('ALTER TABLE storage_item DROP document_id');
        $this->addSql('ALTER TABLE storages CHANGE metadata metadata LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
    }
}
