<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216183950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE megusta DROP FOREIGN KEY FK_AC6340B379F37AE5');
        $this->addSql('ALTER TABLE megusta DROP FOREIGN KEY FK_AC6340B39514AA5C');
        $this->addSql('DROP INDEX idx_ac6340b39514aa5c ON megusta');
        $this->addSql('CREATE INDEX IDX_7F96AC749514AA5C ON megusta (id_post_id)');
        $this->addSql('DROP INDEX idx_ac6340b379f37ae5 ON megusta');
        $this->addSql('CREATE INDEX IDX_7F96AC7479F37AE5 ON megusta (id_user_id)');
        $this->addSql('ALTER TABLE megusta ADD CONSTRAINT FK_AC6340B379F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE megusta ADD CONSTRAINT FK_AC6340B39514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE megusta DROP FOREIGN KEY FK_7F96AC749514AA5C');
        $this->addSql('ALTER TABLE megusta DROP FOREIGN KEY FK_7F96AC7479F37AE5');
        $this->addSql('DROP INDEX idx_7f96ac749514aa5c ON megusta');
        $this->addSql('CREATE INDEX IDX_AC6340B39514AA5C ON megusta (id_post_id)');
        $this->addSql('DROP INDEX idx_7f96ac7479f37ae5 ON megusta');
        $this->addSql('CREATE INDEX IDX_AC6340B379F37AE5 ON megusta (id_user_id)');
        $this->addSql('ALTER TABLE megusta ADD CONSTRAINT FK_7F96AC749514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE megusta ADD CONSTRAINT FK_7F96AC7479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
    }
}
