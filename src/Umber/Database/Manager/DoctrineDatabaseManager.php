<?php

declare(strict_types=1);

namespace Umber\Database\Manager;

use Doctrine\ORM\QueryBuilder;
use Umber\Database\DatabaseManagerInterface;
use Umber\Database\EntityRepositoryFactoryInterface;

use Symfony\Bridge\Doctrine\RegistryInterface;

use Doctrine\ORM\EntityManagerInterface;

/**
 * A doctrine database manager.
 */
final class DoctrineDatabaseManager
{
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Return the doctrine entity manager.
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->registry->getEntityManager();
    }

    /**
     * Create a doctrine query builder.
     */
    public function createQueryBuilder(): QueryBuilder
    {
        return $this->getEntityManager()->createQueryBuilder();
    }
}
