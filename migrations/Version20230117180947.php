<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117180947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE followers_user DROP FOREIGN KEY FK_37412759A76ED395');
        $this->addSql('ALTER TABLE followers_user DROP FOREIGN KEY FK_3741275915BF9993');
        $this->addSql('DROP TABLE followers_user');
        $this->addSql('ALTER TABLE followers ADD id_emisor_id INT DEFAULT NULL, ADD id_receptor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA7EBEA3BF8 FOREIGN KEY (id_emisor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA7207F40F6 FOREIGN KEY (id_receptor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8408FDA7EBEA3BF8 ON followers (id_emisor_id)');
        $this->addSql('CREATE INDEX IDX_8408FDA7207F40F6 ON followers (id_receptor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE followers_user (followers_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_37412759A76ED395 (user_id), INDEX IDX_3741275915BF9993 (followers_id), PRIMARY KEY(followers_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE followers_user ADD CONSTRAINT FK_37412759A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE followers_user ADD CONSTRAINT FK_3741275915BF9993 FOREIGN KEY (followers_id) REFERENCES followers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA7EBEA3BF8');
        $this->addSql('ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA7207F40F6');
        $this->addSql('DROP INDEX IDX_8408FDA7EBEA3BF8 ON followers');
        $this->addSql('DROP INDEX IDX_8408FDA7207F40F6 ON followers');
        $this->addSql('ALTER TABLE followers DROP id_emisor_id, DROP id_receptor_id');
    }
}
