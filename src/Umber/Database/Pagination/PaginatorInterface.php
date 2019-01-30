<?php

declare(strict_types=1);

namespace Umber\Database\Pagination;

use Countable;
use IteratorAggregate;
use Traversable;

interface PaginatorInterface extends Traversable, Countable, IteratorAggregate
{
    public function getResultPerPageCount(): int;

    public function getResultSetCount(): int;

    public function getResultTotalCount(): int;

    public function getPageTotalCount(): int;

    public function getCurrentPageNumber(): int;

    /**
     * @return mixed[]
     */
    public function asArray(): array;
}
