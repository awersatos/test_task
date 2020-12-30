<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201230061909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE author ALTER COLUMN id SET DEFAULT nextval(\'author_id_seq\');');
        $handle = fopen("data/authors_uniq.txt", "r+");

        for ($j = 0; $j < 100; $j++) {
            $sql = "INSERT INTO author(name) VALUES ";
            for ($i = 0; $i < 100; $i++) {
                $authorName = trim(fgets($handle));
                $sql .= (($i === 0) ? '' : ',') . "('" . $authorName . "')";
            }
            $this->addSql($sql);
        }
        fclose($handle);

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM author');
    }
}
