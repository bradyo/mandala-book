<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140310231901 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("UPDATE Design SET status = :new_status WHERE status = :old_status", array(
            'new_status' => 'public',
            'old_status' => 'saved'
        ));
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("UPDATE Design SET status = :new_status WHERE status = :old_status", array(
            'new_status' => 'saved',
            'old_status' => 'public'
        ));
    }
}
