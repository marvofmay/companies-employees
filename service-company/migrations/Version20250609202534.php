<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250609202534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE access (uuid CHAR(36) NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, module_uuid CHAR(36) NOT NULL, UNIQUE INDEX UNIQ_6692B545E237E06 (name), INDEX IDX_6692B546B4315AA (module_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE address (uuid CHAR(36) NOT NULL, street VARCHAR(250) NOT NULL, postcode VARCHAR(10) NOT NULL, city VARCHAR(50) NOT NULL, country VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, company_uuid CHAR(36) DEFAULT NULL, employee_uuid CHAR(36) DEFAULT NULL, UNIQUE INDEX UNIQ_D4E6F8192124A48 (company_uuid), UNIQUE INDEX UNIQ_D4E6F8164A61AE1 (employee_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE company (uuid CHAR(36) NOT NULL, name VARCHAR(1000) NOT NULL, nip VARCHAR(20) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX idx_nip (nip), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE contact (uuid CHAR(36) NOT NULL, type VARCHAR(50) NOT NULL, data VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, company_uuid CHAR(36) DEFAULT NULL, employee_uuid CHAR(36) DEFAULT NULL, INDEX IDX_4C62E63892124A48 (company_uuid), INDEX IDX_4C62E63864A61AE1 (employee_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE employee (uuid CHAR(36) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, pesel VARCHAR(11) NOT NULL, employment_from DATE NOT NULL, employment_to DATE DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, company_uuid CHAR(36) NOT NULL, role_uuid CHAR(36) NOT NULL, INDEX IDX_5D9F75A192124A48 (company_uuid), INDEX IDX_5D9F75A16FC02232 (role_uuid), INDEX index_first_name (first_name), INDEX index_last_name (last_name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE module (uuid CHAR(36) NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_C2426285E237E06 (name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE permission (uuid CHAR(36) NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_E04992AA5E237E06 (name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role (uuid CHAR(36) NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_57698A6A5E237E06 (name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role_access (created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, role_uuid CHAR(36) NOT NULL, access_uuid CHAR(36) NOT NULL, INDEX IDX_AD4FCAE56FC02232 (role_uuid), INDEX IDX_AD4FCAE527A636A0 (access_uuid), PRIMARY KEY(role_uuid, access_uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role_access_permission (created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, role_uuid CHAR(36) NOT NULL, access_uuid CHAR(36) NOT NULL, permission_uuid CHAR(36) NOT NULL, INDEX IDX_8A9ECDD46FC02232 (role_uuid), INDEX IDX_8A9ECDD427A636A0 (access_uuid), INDEX IDX_8A9ECDD480B1CB06 (permission_uuid), PRIMARY KEY(role_uuid, access_uuid, permission_uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (uuid CHAR(36) NOT NULL, employee_uuid CHAR(36) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64964A61AE1 (employee_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE access ADD CONSTRAINT FK_6692B546B4315AA FOREIGN KEY (module_uuid) REFERENCES module (uuid)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F8192124A48 FOREIGN KEY (company_uuid) REFERENCES company (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F8164A61AE1 FOREIGN KEY (employee_uuid) REFERENCES employee (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact ADD CONSTRAINT FK_4C62E63892124A48 FOREIGN KEY (company_uuid) REFERENCES company (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact ADD CONSTRAINT FK_4C62E63864A61AE1 FOREIGN KEY (employee_uuid) REFERENCES employee (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A192124A48 FOREIGN KEY (company_uuid) REFERENCES company (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A16FC02232 FOREIGN KEY (role_uuid) REFERENCES role (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_access ADD CONSTRAINT FK_AD4FCAE56FC02232 FOREIGN KEY (role_uuid) REFERENCES role (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_access ADD CONSTRAINT FK_AD4FCAE527A636A0 FOREIGN KEY (access_uuid) REFERENCES access (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_access_permission ADD CONSTRAINT FK_8A9ECDD46FC02232 FOREIGN KEY (role_uuid) REFERENCES role (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_access_permission ADD CONSTRAINT FK_8A9ECDD427A636A0 FOREIGN KEY (access_uuid) REFERENCES access (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_access_permission ADD CONSTRAINT FK_8A9ECDD480B1CB06 FOREIGN KEY (permission_uuid) REFERENCES permission (uuid) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D64964A61AE1 FOREIGN KEY (employee_uuid) REFERENCES employee (uuid)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE access DROP FOREIGN KEY FK_6692B546B4315AA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8192124A48
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8164A61AE1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact DROP FOREIGN KEY FK_4C62E63892124A48
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact DROP FOREIGN KEY FK_4C62E63864A61AE1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A192124A48
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A16FC02232
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_access DROP FOREIGN KEY FK_AD4FCAE56FC02232
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_access DROP FOREIGN KEY FK_AD4FCAE527A636A0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_access_permission DROP FOREIGN KEY FK_8A9ECDD46FC02232
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_access_permission DROP FOREIGN KEY FK_8A9ECDD427A636A0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_access_permission DROP FOREIGN KEY FK_8A9ECDD480B1CB06
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D64964A61AE1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE access
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE address
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE company
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contact
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE employee
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE module
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE permission
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role_access
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role_access_permission
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
