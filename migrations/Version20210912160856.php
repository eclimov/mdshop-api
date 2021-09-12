<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210912160856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoices (id INT AUTO_INCREMENT NOT NULL, carrier_id INT DEFAULT NULL, seller_id INT DEFAULT NULL, buyer_id INT DEFAULT NULL, loading_point_id INT DEFAULT NULL, unloading_point_id INT DEFAULT NULL, approved_by_employee_id INT DEFAULT NULL, processed_by_employee_id INT DEFAULT NULL, order_date DATETIME NOT NULL, delivery_date DATETIME NOT NULL, attached_document VARCHAR(255) NOT NULL, recipient_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6A2F2F9521DFC797 (carrier_id), INDEX IDX_6A2F2F958DE820D9 (seller_id), INDEX IDX_6A2F2F956C755722 (buyer_id), INDEX IDX_6A2F2F9537752631 (loading_point_id), INDEX IDX_6A2F2F95A386F81E (unloading_point_id), INDEX IDX_6A2F2F958E8FB63B (approved_by_employee_id), INDEX IDX_6A2F2F95DC7C19EE (processed_by_employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F9521DFC797 FOREIGN KEY (carrier_id) REFERENCES companies (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F958DE820D9 FOREIGN KEY (seller_id) REFERENCES companies (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F956C755722 FOREIGN KEY (buyer_id) REFERENCES companies (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F9537752631 FOREIGN KEY (loading_point_id) REFERENCES company_addresses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F95A386F81E FOREIGN KEY (unloading_point_id) REFERENCES company_addresses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F958E8FB63B FOREIGN KEY (approved_by_employee_id) REFERENCES company_employees (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F95DC7C19EE FOREIGN KEY (processed_by_employee_id) REFERENCES company_employees (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE invoices');
    }
}
