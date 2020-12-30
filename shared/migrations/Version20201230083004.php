<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201230083004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE book ALTER COLUMN id SET DEFAULT nextval(\'book_id_seq\');');
        $handle = fopen("data/books_uniq.txt", "r+");

        for ($j = 0; $j < 100; $j++) {
            $sql = "INSERT INTO book (name, author_id) VALUES ";
            for ($i = 0; $i < 100; $i++) {
                $book = trim(fgets($handle));

                if (preg_match("/^[а-яА-Яa-zA-Z]{3,}/u", $book, $matches)) {
                    $authorName = $matches[0];
                    $bookName = trim(str_replace($authorName, '', $book), " ,.-\t\r\n\'\"") . ' ISDN' . random_int(500, 50000);
                    $bookName = str_replace('\'', '', $bookName);
                    if(strlen($bookName) < 3 || strlen($authorName) < 3) {
                        continue;
                    }
                    $sql .= (($i === 0) ? '' : ',') . "('" . $bookName . "', (SELECT COALESCE((SELECT id  FROM author WHERE name LIKE '%" . $authorName . "%' LIMIT 1), 1051)))";

                }
            }
            $this->addSql($sql);
        }
        fclose($handle);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM book');

    }
}
