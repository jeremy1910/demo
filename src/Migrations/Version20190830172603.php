<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190830172603 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE history_search_article ADD by_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE history_search_article ADD CONSTRAINT FK_5C174EDCDC9C2434 FOREIGN KEY (by_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5C174EDCDC9C2434 ON history_search_article (by_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE history_search_article DROP FOREIGN KEY FK_5C174EDCDC9C2434');
        $this->addSql('DROP INDEX IDX_5C174EDCDC9C2434 ON history_search_article');
        $this->addSql('ALTER TABLE history_search_article DROP by_user_id');
    }
}
