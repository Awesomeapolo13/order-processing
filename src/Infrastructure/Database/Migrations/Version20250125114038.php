<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250125114038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add product quantity parameters';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE basket_item ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE basket_item ADD is_pack BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE basket_item ADD pack_weight NUMERIC(10, 3) DEFAULT NULL');
        $this->addSql('ALTER TABLE basket_item ALTER weight DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE basket_item DROP type');
        $this->addSql('ALTER TABLE basket_item DROP is_pack');
        $this->addSql('ALTER TABLE basket_item DROP pack_weight');
        $this->addSql('ALTER TABLE basket_item ALTER weight SET NOT NULL');
    }
}
