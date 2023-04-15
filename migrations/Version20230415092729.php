<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230415092729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nutrition (id INT AUTO_INCREMENT NOT NULL, fruit_id_id INT NOT NULL, carbohydrates DOUBLE PRECISION NOT NULL, protein DOUBLE PRECISION NOT NULL, fat DOUBLE PRECISION NOT NULL, calories DOUBLE PRECISION NOT NULL, sugar DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_B7C360F1853A268 (fruit_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nutrition ADD CONSTRAINT FK_B7C360F1853A268 FOREIGN KEY (fruit_id_id) REFERENCES fruit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nutrition DROP FOREIGN KEY FK_B7C360F1853A268');
        $this->addSql('DROP TABLE nutrition');
    }
}
