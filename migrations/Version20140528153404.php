<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140528153404 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE OrderDesign (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, design_id INT DEFAULT NULL, INDEX IDX_45D948B08D9F6D38 (order_id), INDEX IDX_45D948B0E41DC9B2 (design_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE OrderDesign ADD CONSTRAINT FK_45D948B08D9F6D38 FOREIGN KEY (order_id) REFERENCES `Order` (id)");
        $this->addSql("ALTER TABLE OrderDesign ADD CONSTRAINT FK_45D948B0E41DC9B2 FOREIGN KEY (design_id) REFERENCES Design (id)");
        $this->addSql("ALTER TABLE `Order` DROP FOREIGN KEY FK_34E8BC9CC2FA724C");
        $this->addSql("DROP INDEX IDX_34E8BC9CC2FA724C ON `Order`");
        $this->addSql("ALTER TABLE `Order` ADD createdAt DATETIME NOT NULL, ADD paidAt DATETIME DEFAULT NULL, ADD shippedAt DATETIME DEFAULT NULL, DROP designs_id, CHANGE confirmationCode confirmationCode VARCHAR(255) DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE OrderDesign");
        $this->addSql("ALTER TABLE `Order` ADD designs_id INT DEFAULT NULL, DROP createdAt, DROP paidAt, DROP shippedAt, CHANGE confirmationCode confirmationCode VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci");
        $this->addSql("ALTER TABLE `Order` ADD CONSTRAINT FK_34E8BC9CC2FA724C FOREIGN KEY (designs_id) REFERENCES Design (id)");
        $this->addSql("CREATE INDEX IDX_34E8BC9CC2FA724C ON `Order` (designs_id)");
    }
}
