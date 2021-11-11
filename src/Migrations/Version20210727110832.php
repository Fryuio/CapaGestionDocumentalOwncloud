<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210727110832 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library_node ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE library_node ADD CONSTRAINT FK_23B02228727ACA70 FOREIGN KEY (parent_id) REFERENCES library_node (id)');
        $this->addSql('CREATE INDEX IDX_23B02228727ACA70 ON library_node (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library_node DROP FOREIGN KEY FK_23B02228727ACA70');
        $this->addSql('DROP INDEX IDX_23B02228727ACA70 ON library_node');
        $this->addSql('ALTER TABLE library_node DROP parent_id');
    }
}
