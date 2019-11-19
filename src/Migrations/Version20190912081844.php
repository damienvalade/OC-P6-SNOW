<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190912081844 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commentaries (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, tricks_id INT NOT NULL, commentarie VARCHAR(255) NOT NULL, is_valide TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, INDEX IDX_4ED55CCB67B3B43D (users_id), INDEX IDX_4ED55CCB3B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, tricks_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_16DB4F893B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tricks (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, type_tricks_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, main_picture VARCHAR(255) NOT NULL, INDEX IDX_E1D902C167B3B43D (users_id), INDEX IDX_E1D902C18FD37044 (type_tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_tricks (id INT AUTO_INCREMENT NOT NULL, name_tricks VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, level_administration LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', reset_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, tricks_id INT NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_7CC7DA2C3B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaries ADD CONSTRAINT FK_4ED55CCB67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE commentaries ADD CONSTRAINT FK_4ED55CCB3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F893B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C167B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C18FD37044 FOREIGN KEY (type_tricks_id) REFERENCES type_tricks (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentaries DROP FOREIGN KEY FK_4ED55CCB3B153154');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F893B153154');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C3B153154');
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C18FD37044');
        $this->addSql('ALTER TABLE commentaries DROP FOREIGN KEY FK_4ED55CCB67B3B43D');
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C167B3B43D');
        $this->addSql('DROP TABLE commentaries');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE tricks');
        $this->addSql('DROP TABLE type_tricks');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE video');
    }
}
