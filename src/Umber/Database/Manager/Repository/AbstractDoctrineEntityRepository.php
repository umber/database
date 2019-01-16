<?php

declare(strict_types=1);

namespace Umber\Database\Manager\Repository;

use Umber\Database\Manager\DoctrineDatabaseManager;
use Umber\Database\Pagination\PaginatorFactoryInterface;

use Doctrine\ORM\QueryBuilder;

/**
 * A custom entity repository that is stripped back.
 */
abstract class AbstractDoctrineEntityRepository
{
    protected const SORT_ASC = 'ASC';
    protected const SORT_DESC = 'DESC';

    private $database;
    private $paginatorFactory;
    private $entity;

    public function __construct(
        DoctrineDatabaseManager $database,
        PaginatorFactoryInterface $paginatorFactory,
        string $entity
    ) {
        $this->database = $database;
        $this->paginatorFactory = $paginatorFactory;
        $this->entity = $entity;
    }

    /**
     * Return the paginator factory helper.
     */
    final protected function getPaginatorFactory(): PaginatorFactoryInterface
    {
        return $this->paginatorFactory;
    }

    /**
     * Create a query builder.
     */
    final protected function createQueryBuilder(string $alias, ?string $indexBy = null): QueryBuilder
    {
        return $this->database->createQueryBuilder()
            ->select($alias)
            ->from($this->entity, $alias, $indexBy);
    }
}
