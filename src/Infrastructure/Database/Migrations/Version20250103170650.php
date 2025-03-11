<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250103170650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added deleted_at and version fields.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE basket ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE basket ADD version INT DEFAULT 1 NOT NULL');
        $this->addSql('COMMENT ON COLUMN basket.deleted_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE basket DROP deleted_at');
        $this->addSql('ALTER TABLE basket DROP version');
    }
}
