<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200218095717 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, country_id INT NOT NULL, city VARCHAR(255) NOT NULL, town VARCHAR(255) NOT NULL, district VARCHAR(255) NOT NULL, addition LONGTEXT DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, eligible TINYINT(1) NOT NULL, INDEX IDX_5E9E89CB9395C3F3 (customer_id), INDEX IDX_5E9E89CBF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE justification (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, identity_id INT DEFAULT NULL, authority_id INT DEFAULT NULL, delivery_country_id INT NOT NULL, identifier VARCHAR(255) NOT NULL, emission DATE NOT NULL, expiration DATE NOT NULL, INDEX IDX_263F55A19395C3F3 (customer_id), INDEX IDX_263F55A1FF3ED4A8 (identity_id), INDEX IDX_263F55A181EC865B (authority_id), INDEX IDX_263F55A1E76AA954 (delivery_country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE identity (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_check (id INT AUTO_INCREMENT NOT NULL, payment_id INT DEFAULT NULL, bank_id INT DEFAULT NULL, numero VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, beneficiary VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_362462664C3A3BB (payment_id), INDEX IDX_3624626611C8FB41 (bank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_choice (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, civility_id INT DEFAULT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, birth DATE NOT NULL, birth_place VARCHAR(255) NOT NULL, nationality VARCHAR(255) NOT NULL, INDEX IDX_81398E09A76ED395 (user_id), INDEX IDX_81398E0923D6A298 (civility_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oorder (id INT AUTO_INCREMENT NOT NULL, offer_id INT DEFAULT NULL, customer_id INT NOT NULL, reference VARCHAR(255) NOT NULL, amount DOUBLE PRECISION DEFAULT NULL, idparty VARCHAR(255) DEFAULT NULL, order_idpcu VARCHAR(255) DEFAULT NULL, customer_idgaia VARCHAR(255) DEFAULT NULL, order_idgaia VARCHAR(255) DEFAULT NULL, customer_idbscs VARCHAR(255) DEFAULT NULL, order_idbscs VARCHAR(255) DEFAULT NULL, step INT DEFAULT NULL, date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7B6B78A953C674EE (offer_id), INDEX IDX_7B6B78A99395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oorder_option (oorder_id INT NOT NULL, option_id INT NOT NULL, INDEX IDX_C42B9FE38D6EE88A (oorder_id), INDEX IDX_C42B9FE3A7C41D6F (option_id), PRIMARY KEY(oorder_id, option_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_offer (option_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_103D3A56A7C41D6F (option_id), INDEX IDX_103D3A5653C674EE (offer_id), PRIMARY KEY(option_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interest (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, INDEX IDX_6C3E1A679395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_features (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_features_offer (offer_features_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_4568533F4FEA2C0B (offer_features_id), INDEX IDX_4568533F53C674EE (offer_id), PRIMARY KEY(offer_features_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, payment_choice_id INT NOT NULL, order_id_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_6D28840DDAAF49F9 (payment_choice_id), INDEX IDX_6D28840DFCDAEAAA (order_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE civility (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, civility_id INT DEFAULT NULL, login VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_8D93D64923D6A298 (civility_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user_role (user_id INT NOT NULL, user_role_id INT NOT NULL, INDEX IDX_2D084B47A76ED395 (user_id), INDEX IDX_2D084B478E0E3CA6 (user_role_id), PRIMARY KEY(user_id, user_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE authority (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, justification_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_C53D045FCF51766D (justification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mobile_money (id INT AUTO_INCREMENT NOT NULL, payment_id INT NOT NULL, numero VARCHAR(255) NOT NULL, amount VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F939A2A84C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE justification ADD CONSTRAINT FK_263F55A19395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE justification ADD CONSTRAINT FK_263F55A1FF3ED4A8 FOREIGN KEY (identity_id) REFERENCES identity (id)');
        $this->addSql('ALTER TABLE justification ADD CONSTRAINT FK_263F55A181EC865B FOREIGN KEY (authority_id) REFERENCES authority (id)');
        $this->addSql('ALTER TABLE justification ADD CONSTRAINT FK_263F55A1E76AA954 FOREIGN KEY (delivery_country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE bank_check ADD CONSTRAINT FK_362462664C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE bank_check ADD CONSTRAINT FK_3624626611C8FB41 FOREIGN KEY (bank_id) REFERENCES bank (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E0923D6A298 FOREIGN KEY (civility_id) REFERENCES civility (id)');
        $this->addSql('ALTER TABLE oorder ADD CONSTRAINT FK_7B6B78A953C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE oorder ADD CONSTRAINT FK_7B6B78A99395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE oorder_option ADD CONSTRAINT FK_C42B9FE38D6EE88A FOREIGN KEY (oorder_id) REFERENCES oorder (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oorder_option ADD CONSTRAINT FK_C42B9FE3A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_offer ADD CONSTRAINT FK_103D3A56A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_offer ADD CONSTRAINT FK_103D3A5653C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE interest ADD CONSTRAINT FK_6C3E1A679395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE offer_features_offer ADD CONSTRAINT FK_4568533F4FEA2C0B FOREIGN KEY (offer_features_id) REFERENCES offer_features (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_features_offer ADD CONSTRAINT FK_4568533F53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DDAAF49F9 FOREIGN KEY (payment_choice_id) REFERENCES payment_choice (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES oorder (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64923D6A298 FOREIGN KEY (civility_id) REFERENCES civility (id)');
        $this->addSql('ALTER TABLE user_user_role ADD CONSTRAINT FK_2D084B47A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user_role ADD CONSTRAINT FK_2D084B478E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FCF51766D FOREIGN KEY (justification_id) REFERENCES justification (id)');
        $this->addSql('ALTER TABLE mobile_money ADD CONSTRAINT FK_F939A2A84C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBF92F3E70');
        $this->addSql('ALTER TABLE justification DROP FOREIGN KEY FK_263F55A1E76AA954');
        $this->addSql('ALTER TABLE user_user_role DROP FOREIGN KEY FK_2D084B478E0E3CA6');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FCF51766D');
        $this->addSql('ALTER TABLE justification DROP FOREIGN KEY FK_263F55A1FF3ED4A8');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DDAAF49F9');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB9395C3F3');
        $this->addSql('ALTER TABLE justification DROP FOREIGN KEY FK_263F55A19395C3F3');
        $this->addSql('ALTER TABLE oorder DROP FOREIGN KEY FK_7B6B78A99395C3F3');
        $this->addSql('ALTER TABLE interest DROP FOREIGN KEY FK_6C3E1A679395C3F3');
        $this->addSql('ALTER TABLE oorder_option DROP FOREIGN KEY FK_C42B9FE38D6EE88A');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DFCDAEAAA');
        $this->addSql('ALTER TABLE oorder_option DROP FOREIGN KEY FK_C42B9FE3A7C41D6F');
        $this->addSql('ALTER TABLE option_offer DROP FOREIGN KEY FK_103D3A56A7C41D6F');
        $this->addSql('ALTER TABLE offer_features_offer DROP FOREIGN KEY FK_4568533F4FEA2C0B');
        $this->addSql('ALTER TABLE bank_check DROP FOREIGN KEY FK_362462664C3A3BB');
        $this->addSql('ALTER TABLE mobile_money DROP FOREIGN KEY FK_F939A2A84C3A3BB');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E0923D6A298');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64923D6A298');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09A76ED395');
        $this->addSql('ALTER TABLE user_user_role DROP FOREIGN KEY FK_2D084B47A76ED395');
        $this->addSql('ALTER TABLE justification DROP FOREIGN KEY FK_263F55A181EC865B');
        $this->addSql('ALTER TABLE bank_check DROP FOREIGN KEY FK_3624626611C8FB41');
        $this->addSql('ALTER TABLE oorder DROP FOREIGN KEY FK_7B6B78A953C674EE');
        $this->addSql('ALTER TABLE option_offer DROP FOREIGN KEY FK_103D3A5653C674EE');
        $this->addSql('ALTER TABLE offer_features_offer DROP FOREIGN KEY FK_4568533F53C674EE');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE justification');
        $this->addSql('DROP TABLE identity');
        $this->addSql('DROP TABLE bank_check');
        $this->addSql('DROP TABLE payment_choice');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE oorder');
        $this->addSql('DROP TABLE oorder_option');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE option_offer');
        $this->addSql('DROP TABLE interest');
        $this->addSql('DROP TABLE offer_features');
        $this->addSql('DROP TABLE offer_features_offer');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE civility');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user_role');
        $this->addSql('DROP TABLE authority');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE bank');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE mobile_money');
    }
}
