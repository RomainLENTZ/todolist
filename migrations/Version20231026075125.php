<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231026075125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_category (task_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(task_id, category_id), CONSTRAINT FK_468CF38D8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_468CF38D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_468CF38D8DB60186 ON task_category (task_id)');
        $this->addSql('CREATE INDEX IDX_468CF38D12469DE2 ON task_category (category_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, name, color FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, CONSTRAINT FK_64C19C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO category (id, name, color) SELECT id, name, color FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE INDEX IDX_64C19C1A76ED395 ON category (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, title, description, created_at, expiration_date, closed FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, to_do_list_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(999) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expiration_date DATETIME DEFAULT NULL, closed BOOLEAN NOT NULL, CONSTRAINT FK_527EDB25B3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO task (id, title, description, created_at, expiration_date, closed) SELECT id, title, description, created_at, expiration_date, closed FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
        $this->addSql('CREATE INDEX IDX_527EDB25B3AB48EB ON task (to_do_list_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__to_do_list AS SELECT id, title FROM to_do_list');
        $this->addSql('DROP TABLE to_do_list');
        $this->addSql('CREATE TABLE to_do_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, CONSTRAINT FK_4A6048ECA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO to_do_list (id, title) SELECT id, title FROM __temp__to_do_list');
        $this->addSql('DROP TABLE __temp__to_do_list');
        $this->addSql('CREATE INDEX IDX_4A6048ECA76ED395 ON to_do_list (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE task_category');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, name, color FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO category (id, name, color) SELECT id, name, color FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, title, description, created_at, expiration_date, closed FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(999) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expiration_date DATETIME DEFAULT NULL, closed BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO task (id, title, description, created_at, expiration_date, closed) SELECT id, title, description, created_at, expiration_date, closed FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
        $this->addSql('CREATE TEMPORARY TABLE __temp__to_do_list AS SELECT id, title FROM to_do_list');
        $this->addSql('DROP TABLE to_do_list');
        $this->addSql('CREATE TABLE to_do_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO to_do_list (id, title) SELECT id, title FROM __temp__to_do_list');
        $this->addSql('DROP TABLE __temp__to_do_list');
    }
}
