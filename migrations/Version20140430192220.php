<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140430192220 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE BookFavorite (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_6EEAFE9CA76ED395 (user_id), INDEX IDX_6EEAFE9C16A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE BookFavorite ADD CONSTRAINT FK_6EEAFE9CA76ED395 FOREIGN KEY (user_id) REFERENCES User (id)");
        $this->addSql("ALTER TABLE BookFavorite ADD CONSTRAINT FK_6EEAFE9C16A2B381 FOREIGN KEY (book_id) REFERENCES Book (id)");
        $this->addSql("ALTER TABLE Book ADD favoritedCount INT NOT NULL");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE BookFavorite");
        $this->addSql("ALTER TABLE Book DROP favoritedCount");
    }
}
