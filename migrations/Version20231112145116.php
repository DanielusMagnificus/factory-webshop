<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112145116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), INDEX IDX_64C19C1796A8F92 (parent_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract_list (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, sku VARCHAR(255) NOT NULL, INDEX IDX_2CBDB670A76ED395 (user_id), INDEX IDX_2CBDB6704584665A (product_id), UNIQUE INDEX contract_list_unique (product_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, tax_percentage SMALLINT NOT NULL, code VARCHAR(2) NOT NULL, UNIQUE INDEX UNIQ_5373C9665E237E06 (name), UNIQUE INDEX UNIQ_5373C96677153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_address (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, user_id INT NOT NULL, address VARCHAR(255) NOT NULL, apartment_number INT NOT NULL, INDEX IDX_750D05FF92F3E70 (country_id), INDEX IDX_750D05FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, value SMALLINT NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, UNIQUE INDEX UNIQ_E1E0B40E77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount_order (discount_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_DE034B0F4C7C611F (discount_id), INDEX IDX_DE034B0F8D9F6D38 (order_id), PRIMARY KEY(discount_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `orders` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, order_date_time DATETIME NOT NULL, INDEX IDX_E52FFDEEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_list (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, store_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, sku VARCHAR(255) NOT NULL, INDEX IDX_399A0AA24584665A (product_id), INDEX IDX_399A0AA2B092A811 (store_id), UNIQUE INDEX product_store_unique (product_id, store_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, sku VARCHAR(255) NOT NULL, published DATETIME NOT NULL, UNIQUE INDEX UNIQ_D34A04ADF9038C4 (sku), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CDFC73564584665A (product_id), INDEX IDX_CDFC735612469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchased_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, order__id INT NOT NULL, base_price DOUBLE PRECISION NOT NULL, price_after_tax DOUBLE PRECISION NOT NULL, INDEX IDX_22A4A88D4584665A (product_id), INDEX IDX_22A4A88D251A8A50 (order__id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FF5758775E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D6496B01BC5B (phone_number), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1796A8F92 FOREIGN KEY (parent_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE contract_list ADD CONSTRAINT FK_2CBDB670A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE contract_list ADD CONSTRAINT FK_2CBDB6704584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE delivery_address ADD CONSTRAINT FK_750D05FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE delivery_address ADD CONSTRAINT FK_750D05FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE discount_order ADD CONSTRAINT FK_DE034B0F4C7C611F FOREIGN KEY (discount_id) REFERENCES discount (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE discount_order ADD CONSTRAINT FK_DE034B0F8D9F6D38 FOREIGN KEY (order_id) REFERENCES `orders` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `orders` ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE price_list ADD CONSTRAINT FK_399A0AA24584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE price_list ADD CONSTRAINT FK_399A0AA2B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purchased_product ADD CONSTRAINT FK_22A4A88D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchased_product ADD CONSTRAINT FK_22A4A88D251A8A50 FOREIGN KEY (order__id) REFERENCES `orders` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1796A8F92');
        $this->addSql('ALTER TABLE contract_list DROP FOREIGN KEY FK_2CBDB670A76ED395');
        $this->addSql('ALTER TABLE contract_list DROP FOREIGN KEY FK_2CBDB6704584665A');
        $this->addSql('ALTER TABLE delivery_address DROP FOREIGN KEY FK_750D05FF92F3E70');
        $this->addSql('ALTER TABLE delivery_address DROP FOREIGN KEY FK_750D05FA76ED395');
        $this->addSql('ALTER TABLE discount_order DROP FOREIGN KEY FK_DE034B0F4C7C611F');
        $this->addSql('ALTER TABLE discount_order DROP FOREIGN KEY FK_DE034B0F8D9F6D38');
        $this->addSql('ALTER TABLE `orders` DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE price_list DROP FOREIGN KEY FK_399A0AA24584665A');
        $this->addSql('ALTER TABLE price_list DROP FOREIGN KEY FK_399A0AA2B092A811');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE purchased_product DROP FOREIGN KEY FK_22A4A88D4584665A');
        $this->addSql('ALTER TABLE purchased_product DROP FOREIGN KEY FK_22A4A88D251A8A50');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE contract_list');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE delivery_address');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE discount_order');
        $this->addSql('DROP TABLE `orders`');
        $this->addSql('DROP TABLE price_list');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE purchased_product');
        $this->addSql('DROP TABLE store');
        $this->addSql('DROP TABLE `user`');
    }
}
