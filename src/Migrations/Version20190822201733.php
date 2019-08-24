<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190822201733 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_23A0E662B36786B6DE44026DBA80BB2 ON article');
        $this->addSql('CREATE FULLTEXT INDEX IDX_23A0E662B36786B ON article (title)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_23A0E666DE44026 ON article (description)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_23A0E66DBA80BB2 ON article (body)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_23A0E662B36786B ON article');
        $this->addSql('DROP INDEX IDX_23A0E666DE44026 ON article');
        $this->addSql('DROP INDEX IDX_23A0E66DBA80BB2 ON article');
        $this->addSql('CREATE FULLTEXT INDEX IDX_23A0E662B36786B6DE44026DBA80BB2 ON article (title, description, body)');
    }
}
