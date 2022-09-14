<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220914061600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE greeting_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE customer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE detail_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ingredient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pizza_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE customer (id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE detail (id INT NOT NULL, order_delivery_id INT DEFAULT NULL, pizza_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, size VARCHAR(2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2E067F939A116A7 ON detail (order_delivery_id)');
        $this->addSql('CREATE INDEX IDX_2E067F93D41D1D42 ON detail (pizza_id)');
        $this->addSql('CREATE TABLE ingredient (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, customer_id INT DEFAULT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F52993989395C3F3 ON "order" (customer_id)');
        $this->addSql('CREATE TABLE pizza (id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pizza_ingredient (pizza_id INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(pizza_id, ingredient_id))');
        $this->addSql('CREATE INDEX IDX_6FF6C03FD41D1D42 ON pizza_ingredient (pizza_id)');
        $this->addSql('CREATE INDEX IDX_6FF6C03F933FE08C ON pizza_ingredient (ingredient_id)');
        $this->addSql('ALTER TABLE detail ADD CONSTRAINT FK_2E067F939A116A7 FOREIGN KEY (order_delivery_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail ADD CONSTRAINT FK_2E067F93D41D1D42 FOREIGN KEY (pizza_id) REFERENCES pizza (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pizza_ingredient ADD CONSTRAINT FK_6FF6C03FD41D1D42 FOREIGN KEY (pizza_id) REFERENCES pizza (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pizza_ingredient ADD CONSTRAINT FK_6FF6C03F933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE greeting');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993989395C3F3');
        $this->addSql('ALTER TABLE pizza_ingredient DROP CONSTRAINT FK_6FF6C03F933FE08C');
        $this->addSql('ALTER TABLE detail DROP CONSTRAINT FK_2E067F939A116A7');
        $this->addSql('ALTER TABLE detail DROP CONSTRAINT FK_2E067F93D41D1D42');
        $this->addSql('ALTER TABLE pizza_ingredient DROP CONSTRAINT FK_6FF6C03FD41D1D42');
        $this->addSql('DROP SEQUENCE customer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE detail_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ingredient_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE pizza_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE greeting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE greeting (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE detail');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE pizza');
        $this->addSql('DROP TABLE pizza_ingredient');
    }
}
