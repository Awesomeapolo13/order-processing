<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241222164556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added basket_item table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE basket_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE basket_item (
                                id INT NOT NULL, 
                                basket_id INT NOT NULL, 
                                sup_code VARCHAR(255) NOT NULL, 
                                is_slicing BOOLEAN DEFAULT false NOT NULL,
                                quantity INT DEFAULT NULL,
                                added_bonus INT DEFAULT 0 NOT NULL,
                                is_available_for_order BOOLEAN DEFAULT true NOT NULL,
                                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                                per_item_price NUMERIC(10, 2) NOT NULL,
                                discount_price NUMERIC(10, 2) NOT NULL,
                                slicing_cost NUMERIC(10, 2) NOT NULL,
                                total_cost NUMERIC(10, 2) NOT NULL,
                                total_discount_cost NUMERIC(10, 2) NOT NULL,
                                weight NUMERIC(10, 3) NOT NULL,
                                PRIMARY KEY(id)
                         )'
        );
        $this->addSql('CREATE INDEX IDX_D4943C2B1BE1FB52 ON basket_item (basket_id)');
        $this->addSql('ALTER TABLE basket_item ADD CONSTRAINT FK_D4943C2B1BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE basket_item_id_seq CASCADE');
        $this->addSql('ALTER TABLE basket_item DROP CONSTRAINT FK_D4943C2B1BE1FB52');
        $this->addSql('DROP TABLE basket_item');
    }
}
