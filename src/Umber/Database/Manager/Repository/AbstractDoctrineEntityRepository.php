<?php

declare(strict_types=1);

namespace Umber\Database\Manager\Repository;

use Umber\Database\EntityRepositoryInterface;
use Umber\Database\Pagination\PaginatorFactoryInterface;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * A custom entity repository.
 */
abstract class AbstractDoctrineEntityRepository implements EntityRepositoryInterface, ObjectRepository
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

    /**
     * {@inheritdoc}
     *
     * @internal These methods are implemented for the interface, do not consume them.
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return $this->entityManager->find($this->entity, $id);
    }

    /**
     * {@inheritdoc}
     *
     * @internal These methods are implemented for the interface, do not consume them.
     */
    public function findAll()
    {
        return $this->findBy([]);
    }

    /**
     * {@inheritdoc}
     *
     * @internal These methods are implemented for the interface, do not consume them.
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        $persister = $this->entityManager->getUnitOfWork()->getEntityPersister($this->entity);

        return $persister->loadAll($criteria, $orderBy, $limit, $offset);
    }

    /**
     * {@inheritdoc}
     *
     * @internal These methods are implemented for the interface, do not consume them.
     */
    public function findOneBy(array $criteria, ?array $orderBy = null)
    {
        $persister = $this->entityManager->getUnitOfWork()->getEntityPersister($this->entity);

        return $persister->load($criteria, null, null, [], null, 1, $orderBy);
    }

    /**
     * {@inheritdoc}
     *
     * @internal These methods are implemented for the interface, do not consume them.
     */
    public function getClassName()
    {
        return $this->entity;
    }
}
