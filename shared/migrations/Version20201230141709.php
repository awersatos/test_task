<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201230141709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $transliterator = \Transliterator::create('Any-Latin');
        $transliteratorToASCII = \Transliterator::create('Latin-ASCII');
        $this->addSql('ALTER TABLE ext_translations ALTER COLUMN id SET DEFAULT nextval(\'ext_translations_id_seq\');');
        $conn = $this->connection;
        $stmt = $conn->prepare("SELECT * FROM book LIMIT 100 OFFSET :offset");

        for ($i = 0; $i < 100; $i++) {
            $stmt->bindValue("offset", $i * 100);
            $stmt->execute();
            $books = $stmt->fetchAllAssociative();

            if(count($books) === 0) {
                break;
            }

            $sql = "INSERT INTO ext_translations (locale, object_class, field, foreign_key, content) VALUES ";

            foreach ($books as $key => $book) {
                $transliterateName = $transliteratorToASCII->transliterate($transliterator->transliterate($book['name']));
                $sql .= (($key === 0) ? '' : ',')
                    . "('en', 'App\Entity\Book', 'name', "
                    . $book['id']
                    . ","
                    . $conn->quote($transliterateName)
                    . ")";
            }

            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM ext_translations');

    }
}
