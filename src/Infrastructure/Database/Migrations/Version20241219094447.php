<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241219094447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added basket delivery table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE basket_delivery_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE basket_delivery (
                                id INT NOT NULL,
                                is_from_user_shop BOOLEAN DEFAULT false NOT NULL,
                                slot_number INT NOT NULL,
                                distance NUMERIC(10, 3) NOT NULL,
                                long_distance BOOLEAN NOT NULL,
                                long_duration BOOLEAN NOT NULL,
                                delivery_cost NUMERIC(10, 2) NOT NULL,
                                delivery_discount_cost NUMERIC(10, 2) NOT NULL,
                                PRIMARY KEY(id));',
        );
        $this->addSql('ALTER TABLE basket ADD delivery_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507B12136921 FOREIGN KEY (delivery_id) REFERENCES basket_delivery (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2246507B12136921 ON basket (delivery_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE basket DROP CONSTRAINT FK_2246507B12136921');
        $this->addSql('DROP SEQUENCE basket_delivery_id_seq CASCADE');
        $this->addSql('DROP TABLE basket_delivery');
        $this->addSql('DROP INDEX UNIQ_2246507B12136921');
        $this->addSql('ALTER TABLE basket DROP delivery_id');
    }
}
