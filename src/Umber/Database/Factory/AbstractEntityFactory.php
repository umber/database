<?php

declare(strict_types=1);

namespace Umber\Database\Factory;

use Umber\Database\EntityFactoryInterface;
use Umber\Database\EntityInterface;
use Umber\Date\Factory\DateTimeFactoryInterface;
use Umber\Prototype\Column\Date\CreatedAtAwareInterface;
use Umber\Prototype\Column\Date\DeletedAtAwareInterface;
use Umber\Prototype\Column\Date\UpdatedAtAwareInterface;
use Umber\Prototype\Hint\DateAwareHintInterface;

/**
 * An abstraction for the entity factory interface.
 *
 * @see EntityFactoryInterface
 */
abstract class AbstractEntityFactory implements EntityFactoryInterface
{
    private $class;
    private $dateTimeFactory;

    public function __construct(string $class, DateTimeFactoryInterface $dateTimeFactory)
    {
        $this->class = $class;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * Return the entity class.
     */
    final public function getEntityClass(): string
    {
        return $this->class;
    }

    /**
     * Construct (and optionally prepare) an instance of the class.
     */
    final protected function construct(bool $prepare): EntityInterface
    {
        $class = $this->getEntityClass();
        $entity = $this->constructEntityClass($class);

        if ($prepare) {
            $this->prepare($entity);
        }

        return $entity;
    }

    /**
     * Return the date time factory.
     */
    final protected function getDateTimeFactory(): DateTimeFactoryInterface
    {
        return $this->dateTimeFactory;
    }

    /**
     * Prepare a newly constructed entity.
     *
     * This method should be used to set defaults within an entity.
     */
    protected function prepare(EntityInterface $entity): void
    {
        $this->prepareDateAwareHint($entity);
    }

    /**
     * Construct the entity class provided.
     *
     * This method should be overridden should the constructor to the entity contain
     * required parameters. By default this code will assume no parameters.
     */
    protected function constructEntityClass(string $class): EntityInterface
    {
        /** @var EntityInterface $entity */
        $entity = new $class();

        return $entity;
    }

    /**
     * Prepare a newly constructed entity that has a date aware hinting.
     */
    protected function prepareDateAwareHint(EntityInterface $entity): void
    {
        if (!($entity instanceof DateAwareHintInterface)) {
            return;
        }

        // The current date, provided by factory for mocking.
        $now = $this->dateTimeFactory->create();

        if ($entity instanceof CreatedAtAwareInterface) {
            $entity->setCreatedAt($now);
        }

        if ($entity instanceof UpdatedAtAwareInterface) {
            $entity->setUpdatedAt($now);
        }

        if (!($entity instanceof DeletedAtAwareInterface)) {
            return;
        }

        $entity->setNotDeleted();
    }
}
