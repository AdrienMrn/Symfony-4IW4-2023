<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231123073330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Mise à jour de ma DB V1';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE proof_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(event_id, user_id))');
        $this->addSql('CREATE INDEX IDX_92589AE271F7E88B ON event_user (event_id)');
        $this->addSql('CREATE INDEX IDX_92589AE2A76ED395 ON event_user (user_id)');
        $this->addSql('CREATE TABLE organisation_user (organisation_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(organisation_id, user_id))');
        $this->addSql('CREATE INDEX IDX_CFD7D6519E6B1585 ON organisation_user (organisation_id)');
        $this->addSql('CREATE INDEX IDX_CFD7D651A76ED395 ON organisation_user (user_id)');
        $this->addSql('CREATE TABLE proof (id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FBF940DD7E3C61F9 ON proof (owner_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organisation_user ADD CONSTRAINT FK_CFD7D6519E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organisation_user ADD CONSTRAINT FK_CFD7D651A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE proof ADD CONSTRAINT FK_FBF940DD7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organisation ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE organisation ADD CONSTRAINT FK_E6E132B412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E6E132B412469DE2 ON organisation (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE proof_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE event_user DROP CONSTRAINT FK_92589AE271F7E88B');
        $this->addSql('ALTER TABLE event_user DROP CONSTRAINT FK_92589AE2A76ED395');
        $this->addSql('ALTER TABLE organisation_user DROP CONSTRAINT FK_CFD7D6519E6B1585');
        $this->addSql('ALTER TABLE organisation_user DROP CONSTRAINT FK_CFD7D651A76ED395');
        $this->addSql('ALTER TABLE proof DROP CONSTRAINT FK_FBF940DD7E3C61F9');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('DROP TABLE organisation_user');
        $this->addSql('DROP TABLE proof');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('ALTER TABLE organisation DROP CONSTRAINT FK_E6E132B412469DE2');
        $this->addSql('DROP INDEX IDX_E6E132B412469DE2');
        $this->addSql('ALTER TABLE organisation DROP category_id');
    }
}
