<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210816085439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE node_metadata ADD clave VARCHAR(45) DEFAULT \'NULL\', ADD valor VARCHAR(45) DEFAULT \'NULL\', DROP `key`, DROP value');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE node_metadata ADD `key` VARCHAR(45) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, ADD value VARCHAR(45) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, DROP clave, DROP valor');
    }
}
