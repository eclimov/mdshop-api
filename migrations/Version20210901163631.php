<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210901163631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE companies (id INT AUTO_INCREMENT NOT NULL, bank_affiliate_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) NOT NULL, iban VARCHAR(255) NOT NULL, fiscal_code VARCHAR(255) NOT NULL, vat VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8244AA3A5E237E06 (name), UNIQUE INDEX UNIQ_8244AA3A3EE4B093 (short_name), UNIQUE INDEX UNIQ_8244AA3AFAD56E62 (iban), UNIQUE INDEX UNIQ_8244AA3AD7BBA58B (fiscal_code), UNIQUE INDEX UNIQ_8244AA3A84B32233 (vat), INDEX IDX_8244AA3A5F8A2381 (bank_affiliate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_8244AA3A5F8A2381 FOREIGN KEY (bank_affiliate_id) REFERENCES bank_affiliates (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE companies');
    }
}
