<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250105181426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added a partially unique index to basket table for user_id where deleted_at IS NULL';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE UNIQUE INDEX UNIQ_ACTIVE_BASKET_IDX
                    ON basket (user_id)
                    WHERE deleted_at IS NULL;'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_ACTIVE_BASKET_IDX;');
    }
}
