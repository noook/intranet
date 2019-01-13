<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190113191745 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE course_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE course (id INT NOT NULL, teacher_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_169E6FB941807E1D ON course (teacher_id)');
        $this->addSql('CREATE TABLE course_user (course_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(course_id, user_id))');
        $this->addSql('CREATE INDEX IDX_45310B4F591CC992 ON course_user (course_id)');
        $this->addSql('CREATE INDEX IDX_45310B4FA76ED395 ON course_user (user_id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course_user ADD CONSTRAINT FK_45310B4F591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course_user ADD CONSTRAINT FK_45310B4FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE course_user DROP CONSTRAINT FK_45310B4F591CC992');
        $this->addSql('DROP SEQUENCE course_id_seq CASCADE');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_user');
    }
}
