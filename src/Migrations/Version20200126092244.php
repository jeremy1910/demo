<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200126092244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, role_name VARCHAR(255) NOT NULL, libele VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, created_user_id INT DEFAULT NULL, modified_user_id INT DEFAULT NULL, username VARCHAR(254) NOT NULL, password VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, adresse_mail VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, modified_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649E104C1D3 (created_user_id), INDEX IDX_8D93D649BAA24139 (modified_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_roles (user_id INT NOT NULL, roles_id INT NOT NULL, INDEX IDX_54FCD59FA76ED395 (user_id), INDEX IDX_54FCD59F38C751C4 (roles_id), PRIMARY KEY(user_id, roles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5F37A13BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, num_category_id INT DEFAULT NULL, image_id INT NOT NULL, user_id INT DEFAULT NULL, last_edit_user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, last_edit DATETIME DEFAULT NULL, body LONGTEXT NOT NULL, description TINYTEXT NOT NULL, nb_view INT DEFAULT NULL, INDEX IDX_23A0E6668A3D00A (num_category_id), UNIQUE INDEX UNIQ_23A0E663DA5256D (image_id), INDEX IDX_23A0E66A76ED395 (user_id), INDEX IDX_23A0E661466127B (last_edit_user_id), FULLTEXT INDEX IDX_23A0E662B36786B (title), FULLTEXT INDEX IDX_23A0E666DE44026 (description), FULLTEXT INDEX IDX_23A0E66DBA80BB2 (body), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_tag (article_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_919694F97294869C (article_id), INDEX IDX_919694F9BAD26311 (tag_id), PRIMARY KEY(article_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, created_user_id INT DEFAULT NULL, modified_user_id INT DEFAULT NULL, libele VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, modified_at DATETIME DEFAULT NULL, image_path VARCHAR(255) DEFAULT NULL, INDEX IDX_64C19C1E104C1D3 (created_user_id), INDEX IDX_64C19C1BAA24139 (modified_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, created_user_id INT DEFAULT NULL, modified_user_id INT DEFAULT NULL, tag_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, modified_at DATETIME DEFAULT NULL, INDEX IDX_389B783E104C1D3 (created_user_id), INDEX IDX_389B783BAA24139 (modified_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_article (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history_search_article (id INT AUTO_INCREMENT NOT NULL, by_user_id INT DEFAULT NULL, content TINYTEXT DEFAULT NULL, author TINYTEXT DEFAULT NULL, category TINYTEXT DEFAULT NULL, created_after DATETIME DEFAULT NULL, created_before DATETIME DEFAULT NULL, tag TINYTEXT DEFAULT NULL, search_date DATETIME DEFAULT NULL, INDEX IDX_5C174EDCDC9C2434 (by_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forgotten_password (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, hash LONGTEXT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_2EDC8D24A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E104C1D3 FOREIGN KEY (created_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BAA24139 FOREIGN KEY (modified_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59F38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6668A3D00A FOREIGN KEY (num_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E663DA5256D FOREIGN KEY (image_id) REFERENCES image_article (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E661466127B FOREIGN KEY (last_edit_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F97294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F9BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1E104C1D3 FOREIGN KEY (created_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1BAA24139 FOREIGN KEY (modified_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783E104C1D3 FOREIGN KEY (created_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783BAA24139 FOREIGN KEY (modified_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history_search_article ADD CONSTRAINT FK_5C174EDCDC9C2434 FOREIGN KEY (by_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forgotten_password ADD CONSTRAINT FK_2EDC8D24A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59F38C751C4');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E104C1D3');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BAA24139');
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59FA76ED395');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13BA76ED395');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E661466127B');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1E104C1D3');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1BAA24139');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783E104C1D3');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783BAA24139');
        $this->addSql('ALTER TABLE history_search_article DROP FOREIGN KEY FK_5C174EDCDC9C2434');
        $this->addSql('ALTER TABLE forgotten_password DROP FOREIGN KEY FK_2EDC8D24A76ED395');
        $this->addSql('ALTER TABLE article_tag DROP FOREIGN KEY FK_919694F97294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6668A3D00A');
        $this->addSql('ALTER TABLE article_tag DROP FOREIGN KEY FK_919694F9BAD26311');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E663DA5256D');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_roles');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_tag');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE image_article');
        $this->addSql('DROP TABLE history_search_article');
        $this->addSql('DROP TABLE forgotten_password');
    }
}
