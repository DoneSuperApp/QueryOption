<?php

namespace DoneSuperApp\QueryOption;

use ArrayAccess;
use Countable;

class QueryFilterCollection implements ArrayAccess, Countable, \Iterator, Arrayable
{
    /** @var QueryFilter[]  */
    private array $filters = [];

    private int $iteratorCursor = 0;

    public function deleteByName(string $name): self
    {
        foreach ($this->filters as $key => $filter) {
            if ($filter->getField() === $name) {
                unset($this->filters[$key]);
            }
        }

        return $this;
    }

    /**
     * @param string $field
     * @param string|mixed|null $operator
     * @param mixed|null $value
     *
     * @return $this
     * @throws Exceptions\InvalidFilterOperatorException
     */
    public function addFilterParams(string $field, $operator, $value = null): self
    {
        $this->addFilter(new QueryFilter($field, $operator, $value));

        return $this;
    }

    /**
     * @param QueryFilter $queryFilter
     *
     * @return $this
     */
    public function addFilter(QueryFilter $queryFilter): self
    {
        if ($this->hasFilter($queryFilter->getField())) {
            $this->deleteByName($queryFilter->getField());
        }

        $this->filters[] = $queryFilter;

        return $this;
    }

    public function hasFilter(string $name): bool
    {
        return $this->findByName($name) instanceof QueryFilter;
    }

    public function findByName(string $name): ?QueryFilter
    {
        foreach ($this->filters as $filter) {
            if ($filter->getField() === $name) {
                return $filter;
            }
        }

        return null;
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function isNotEmpty(): bool
    {
        return $this->isEmpty() === false;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->filters[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->filters[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        throw new \Exception('use addFilter to add new filters');
    }

    public function offsetUnset($offset): void
    {
        $this->deleteByName($offset);
    }

    public function count(): int
    {
        return count($this->filters);
    }

    public function current(): mixed
    {
        return $this->filters[$this->iteratorCursor];
    }

    public function next(): void
    {
        $this->iteratorCursor++;
    }

    public function key(): mixed
    {
        return $this->iteratorCursor;
    }

    public function valid(): bool
    {
        return $this->iteratorCursor >= 0 && $this->iteratorCursor < $this->count();
    }

    public function rewind(): void
    {
        $this->iteratorCursor = 0;
    }

    public function toArray(): array
    {
        $items = [];
        foreach ($this->filters as $filter) {
            $items[] = $filter->toArray();
        }

        return $items;
    }
}
