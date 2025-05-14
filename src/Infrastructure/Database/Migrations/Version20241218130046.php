<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241218130046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added the basket table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE basket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE basket (
                                id INT NOT NULL,
                                user_id INT DEFAULT NULL,
                                shop_num INT DEFAULT NULL,
                                order_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                                total_bonus INT DEFAULT 0 NOT NULL,
                                region INT NOT NULL,
                                is_delivery BOOLEAN DEFAULT false NOT NULL,
                                is_express BOOLEAN DEFAULT true NOT NULL,
                                has_alcohol BOOLEAN DEFAULT false NOT NULL,
                                slicing_cost NUMERIC(10, 2) NOT NULL,
                                total_cost NUMERIC(10, 2) NOT NULL,
                                total_discount_cost NUMERIC(10, 2) NOT NULL,
                                weight NUMERIC(10, 3) NOT NULL, 
                                PRIMARY KEY(id));',
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE basket_id_seq CASCADE');
        $this->addSql('DROP TABLE basket');
    }
}
