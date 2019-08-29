<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827113118 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD created_user_id INT DEFAULT NULL, ADD modified_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E104C1D3 FOREIGN KEY (created_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BAA24139 FOREIGN KEY (modified_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E104C1D3 ON user (created_user_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649BAA24139 ON user (modified_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E104C1D3');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BAA24139');
        $this->addSql('DROP INDEX IDX_8D93D649E104C1D3 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649BAA24139 ON user');
        $this->addSql('ALTER TABLE user DROP created_user_id, DROP modified_user_id');
    }
}
