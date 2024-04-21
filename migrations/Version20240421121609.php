<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421121609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748A6B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748A6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE categorie CHANGE titre titre VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE inscritption DROP FOREIGN KEY inscritption_ibfk_1');
        $this->addSql('ALTER TABLE inscritption CHANGE formation_id formation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inscritption ADD CONSTRAINT FK_C61262A25200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY fk_projet_user');
        $this->addSql('DROP INDEX fk_projet_user ON projet');
        $this->addSql('ALTER TABLE projet CHANGE id_user id_user2 INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9FBDD95DF FOREIGN KEY (id_user2) REFERENCES user (id_user)');
        $this->addSql('CREATE INDEX fk_projet_user ON projet (id_user2)');
        $this->addSql('DROP INDEX email ON user');
        $this->addSql('DROP INDEX username ON user');
        $this->addSql('ALTER TABLE user CHANGE ImgPath ImgPath VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY fk_projet');
        $this->addSql('ALTER TABLE jaime DROP date');
        $this->addSql('DROP INDEX fk_projet ON jaime');
        $this->addSql('CREATE INDEX IDX_3CB7780576222944 ON jaime (id_projet)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT fk_projet FOREIGN KEY (id_projet) REFERENCES projet (id_projet)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748A6B3CA4B');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE categorie CHANGE titre titre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE description description VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE inscritption DROP FOREIGN KEY FK_C61262A25200282E');
        $this->addSql('ALTER TABLE inscritption CHANGE formation_id formation_id INT NOT NULL');
        $this->addSql('ALTER TABLE inscritption ADD CONSTRAINT inscritption_ibfk_1 FOREIGN KEY (formation_id) REFERENCES formation (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB7780576222944');
        $this->addSql('ALTER TABLE jaime ADD date DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('DROP INDEX idx_3cb7780576222944 ON jaime');
        $this->addSql('CREATE INDEX fk_projet ON jaime (id_projet)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB7780576222944 FOREIGN KEY (id_projet) REFERENCES projet (id_projet)');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9FBDD95DF');
        $this->addSql('DROP INDEX fk_projet_user ON projet');
        $this->addSql('ALTER TABLE projet CHANGE id_user2 id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT fk_projet_user FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('CREATE INDEX fk_projet_user ON projet (id_user)');
        $this->addSql('ALTER TABLE user CHANGE ImgPath ImgPath VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX email ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX username ON user (username)');
    }
}
