<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use CapCollectif\IdToUuid\IdToUuidMigration;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205155203 extends IdToUuidMigration
{
    public function postUp(Schema $schema): void
    {
        $this->migrate('books');
    }
}