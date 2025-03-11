<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250103171659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fixed basket_item foreign key';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE basket_item DROP CONSTRAINT FK_D4943C2B1BE1FB52');
        $this->addSql('ALTER TABLE basket_item ADD CONSTRAINT FK_D4943C2B1BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE basket_item DROP CONSTRAINT fk_d4943c2b1be1fb52');
        $this->addSql('ALTER TABLE basket_item ADD CONSTRAINT fk_d4943c2b1be1fb52 FOREIGN KEY (basket_id) REFERENCES basket (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
