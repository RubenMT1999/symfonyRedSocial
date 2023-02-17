<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215190202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dislike (id INT AUTO_INCREMENT NOT NULL, id_post_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, INDEX IDX_FE3BECAA9514AA5C (id_post_id), INDEX IDX_FE3BECAA79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, id_post_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, INDEX IDX_AC6340B39514AA5C (id_post_id), INDEX IDX_AC6340B379F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relio (id INT AUTO_INCREMENT NOT NULL, id_post_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, INDEX IDX_6137DDE09514AA5C (id_post_id), INDEX IDX_6137DDE079F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dislike ADD CONSTRAINT FK_FE3BECAA9514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE dislike ADD CONSTRAINT FK_FE3BECAA79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B39514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B379F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relio ADD CONSTRAINT FK_6137DDE09514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE relio ADD CONSTRAINT FK_6137DDE079F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post DROP relio');
        $this->addSql('ALTER TABLE user_profile ADD user_id INT NOT NULL, ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB405E69385EB ON user_profile (twitter_username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dislike DROP FOREIGN KEY FK_FE3BECAA9514AA5C');
        $this->addSql('ALTER TABLE dislike DROP FOREIGN KEY FK_FE3BECAA79F37AE5');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B39514AA5C');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B379F37AE5');
        $this->addSql('ALTER TABLE relio DROP FOREIGN KEY FK_6137DDE09514AA5C');
        $this->addSql('ALTER TABLE relio DROP FOREIGN KEY FK_6137DDE079F37AE5');
        $this->addSql('DROP TABLE dislike');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE relio');
        $this->addSql('DROP INDEX UNIQ_D95AB405E69385EB ON user_profile');
        $this->addSql('ALTER TABLE user_profile DROP user_id, DROP image');
        $this->addSql('ALTER TABLE post ADD relio INT DEFAULT NULL');
    }
}
