<?php

declare(strict_types=1);

namespace Umber\Database\Manager\Repository;

use Umber\Database\EntityRepositoryInterface;
use Umber\Database\Pagination\PaginatorFactoryInterface;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * A custom entity repository that is stripped back.
 */
abstract class AbstractDoctrineEntityRepository implements
    EntityRepositoryInterface
{
    protected const SORT_ASC = 'ASC';
    protected const SORT_DESC = 'DESC';

    private $entity;
    private $entityManager;
    private $paginatorFactory;

    public function __construct(
        string $entity,
        EntityManagerInterface $entityManager,
        PaginatorFactoryInterface $paginatorFactory
    ) {
        $this->entity = $entity;
        $this->entityManager = $entityManager;
        $this->paginatorFactory = $paginatorFactory;
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
        return $this->entityManager->createQueryBuilder()
            ->select($alias)
            ->from($this->entity, $alias, $indexBy);
    }
}
