<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215185324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dislike ADD id_post_id INT DEFAULT NULL, ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dislike ADD CONSTRAINT FK_FE3BECAA9514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE dislike ADD CONSTRAINT FK_FE3BECAA79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FE3BECAA9514AA5C ON dislike (id_post_id)');
        $this->addSql('CREATE INDEX IDX_FE3BECAA79F37AE5 ON dislike (id_user_id)');
        $this->addSql('ALTER TABLE `like` ADD id_post_id INT DEFAULT NULL, ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B39514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B379F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B39514AA5C ON `like` (id_post_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B379F37AE5 ON `like` (id_user_id)');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D5B065342');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D1353E739');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D54E4C86B');
        $this->addSql('DROP INDEX IDX_5A8A6C8D54E4C86B ON post');
        $this->addSql('DROP INDEX IDX_5A8A6C8D1353E739 ON post');
        $this->addSql('DROP INDEX IDX_5A8A6C8D5B065342 ON post');
        $this->addSql('ALTER TABLE post DROP id_like_id, DROP id_dislike_id, DROP id_relio_id');
        $this->addSql('ALTER TABLE relio ADD id_post_id INT DEFAULT NULL, ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE relio ADD CONSTRAINT FK_6137DDE09514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE relio ADD CONSTRAINT FK_6137DDE079F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6137DDE09514AA5C ON relio (id_post_id)');
        $this->addSql('CREATE INDEX IDX_6137DDE079F37AE5 ON relio (id_user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495B065342');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F16E48BC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491353E739');
        $this->addSql('DROP INDEX IDX_8D93D6491353E739 ON user');
        $this->addSql('DROP INDEX IDX_8D93D6495B065342 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649F16E48BC ON user');
        $this->addSql('ALTER TABLE user DROP id_like_id, DROP dislike_id, DROP id_relio_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B39514AA5C');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B379F37AE5');
        $this->addSql('DROP INDEX IDX_AC6340B39514AA5C ON `like`');
        $this->addSql('DROP INDEX IDX_AC6340B379F37AE5 ON `like`');
        $this->addSql('ALTER TABLE `like` DROP id_post_id, DROP id_user_id');
        $this->addSql('ALTER TABLE dislike DROP FOREIGN KEY FK_FE3BECAA9514AA5C');
        $this->addSql('ALTER TABLE dislike DROP FOREIGN KEY FK_FE3BECAA79F37AE5');
        $this->addSql('DROP INDEX IDX_FE3BECAA9514AA5C ON dislike');
        $this->addSql('DROP INDEX IDX_FE3BECAA79F37AE5 ON dislike');
        $this->addSql('ALTER TABLE dislike DROP id_post_id, DROP id_user_id');
        $this->addSql('ALTER TABLE relio DROP FOREIGN KEY FK_6137DDE09514AA5C');
        $this->addSql('ALTER TABLE relio DROP FOREIGN KEY FK_6137DDE079F37AE5');
        $this->addSql('DROP INDEX IDX_6137DDE09514AA5C ON relio');
        $this->addSql('DROP INDEX IDX_6137DDE079F37AE5 ON relio');
        $this->addSql('ALTER TABLE relio DROP id_post_id, DROP id_user_id');
        $this->addSql('ALTER TABLE post ADD id_like_id INT DEFAULT NULL, ADD id_dislike_id INT DEFAULT NULL, ADD id_relio_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D5B065342 FOREIGN KEY (id_like_id) REFERENCES `like` (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D1353E739 FOREIGN KEY (id_relio_id) REFERENCES relio (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D54E4C86B FOREIGN KEY (id_dislike_id) REFERENCES dislike (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D54E4C86B ON post (id_dislike_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D1353E739 ON post (id_relio_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D5B065342 ON post (id_like_id)');
        $this->addSql('ALTER TABLE user ADD id_like_id INT DEFAULT NULL, ADD dislike_id INT DEFAULT NULL, ADD id_relio_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495B065342 FOREIGN KEY (id_like_id) REFERENCES `like` (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F16E48BC FOREIGN KEY (dislike_id) REFERENCES dislike (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491353E739 FOREIGN KEY (id_relio_id) REFERENCES relio (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491353E739 ON user (id_relio_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6495B065342 ON user (id_like_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F16E48BC ON user (dislike_id)');
    }
}
