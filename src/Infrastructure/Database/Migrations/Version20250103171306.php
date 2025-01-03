<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250103171306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fixed created_at and updated_at fields from basket and basket_item tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE basket ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE basket ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN basket.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN basket.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE basket_item ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE basket_item ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN basket_item.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN basket_item.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE basket_item ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE basket_item ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN basket_item.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN basket_item.updated_at IS NULL');
        $this->addSql('ALTER TABLE basket ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE basket ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN basket.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN basket.updated_at IS NULL');
    }
}
