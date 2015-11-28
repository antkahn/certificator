<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * create table quiz and link table between quiz and answers
 */
class Version20151129000331 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_answers (quiz_id INT NOT NULL, answer_id INT NOT NULL, INDEX IDX_428A6BA6853CD175 (quiz_id), INDEX IDX_428A6BA6AA334807 (answer_id), PRIMARY KEY(quiz_id, answer_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz_answers ADD CONSTRAINT FK_428A6BA6853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz_answers ADD CONSTRAINT FK_428A6BA6AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quiz_answers DROP FOREIGN KEY FK_428A6BA6853CD175');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_answers');
    }
}
