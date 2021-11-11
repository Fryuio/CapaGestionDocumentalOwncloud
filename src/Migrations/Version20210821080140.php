<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210821080140 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE available_rights (id INT AUTO_INCREMENT NOT NULL, `right` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clients_rights ADD available_rights_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clients_rights ADD CONSTRAINT FK_4B705E4DAE63569 FOREIGN KEY (available_rights_id) REFERENCES available_rights (id)');
        $this->addSql('CREATE INDEX IDX_4B705E4DAE63569 ON clients_rights (available_rights_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients_rights DROP FOREIGN KEY FK_4B705E4DAE63569');
        $this->addSql('DROP TABLE available_rights');
        $this->addSql('DROP INDEX IDX_4B705E4DAE63569 ON clients_rights');
        $this->addSql('ALTER TABLE clients_rights DROP available_rights_id');
    }
}
