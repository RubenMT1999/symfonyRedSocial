<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230125184823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_9474526C4B89032C ON comments');
        $this->addSql('ALTER TABLE comments ADD id_post_id INT DEFAULT NULL, ADD id_user_id INT DEFAULT NULL, DROP post_id');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A9514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A9514AA5C ON comments (id_post_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A79F37AE5 ON comments (id_user_id)');
        $this->addSql('ALTER TABLE user_profile DROP user_id');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405BF396750 FOREIGN KEY (id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405BF396750');
        $this->addSql('ALTER TABLE user_profile ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A9514AA5C');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A79F37AE5');
        $this->addSql('DROP INDEX IDX_5F9E962A9514AA5C ON comments');
        $this->addSql('DROP INDEX IDX_5F9E962A79F37AE5 ON comments');
        $this->addSql('ALTER TABLE comments ADD post_id INT NOT NULL, DROP id_post_id, DROP id_user_id');
        $this->addSql('CREATE INDEX IDX_9474526C4B89032C ON comments (post_id)');
    }
}
