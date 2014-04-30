<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140430193510 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE `Order` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, designs_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, confirmationCode VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, deliveryMethod VARCHAR(255) NOT NULL, recipientEmail VARCHAR(255) DEFAULT NULL, shippingName VARCHAR(255) DEFAULT NULL, shippingStreet VARCHAR(255) DEFAULT NULL, shippingCity VARCHAR(255) DEFAULT NULL, shippingState VARCHAR(255) DEFAULT NULL, shippingZip VARCHAR(255) DEFAULT NULL, goodsCost NUMERIC(6, 2) NOT NULL, taxCost NUMERIC(6, 2) NOT NULL, shippingCost NUMERIC(6, 2) NOT NULL, totalCost NUMERIC(6, 2) NOT NULL, INDEX IDX_34E8BC9CA76ED395 (user_id), INDEX IDX_34E8BC9CC2FA724C (designs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE `Order` ADD CONSTRAINT FK_34E8BC9CA76ED395 FOREIGN KEY (user_id) REFERENCES User (id)");
        $this->addSql("ALTER TABLE `Order` ADD CONSTRAINT FK_34E8BC9CC2FA724C FOREIGN KEY (designs_id) REFERENCES Design (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE `Order`");
    }
}
