<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Repository;

use MakinaCorpus\Preferences\PreferencesRepository;
use MakinaCorpus\Preferences\ValueType;
use MakinaCorpus\Preferences\Value\ValueValidator;

/**
 * SQL based implementation
 */
class ArrayPreferencesRepository implements PreferencesRepository
{
    private mixed $initializer = null;
    /** @var mixed[] */
    private ?array $data = null;

    public function __construct(null|array|callable $data = null)
    {
        if (\is_callable($data)) {
            $this->initializer = $data;
        } else {
            $this->data = $data ?? [];
        }
    }

    private function initialize()
    {
        if (null === $this->data && $this->initializer) {
            $data = ($this->initializer)();
            $this->initializer = null;

            if (empty($data)) {
                $this->data = [];
            } else if (\is_array($data)) {
                $this->data = $data;
            } else if (\is_iterable($data)) {
                $this->data = \iterator_to_array($data);
            } else {
                throw new \InvalidArgumentException("Initializer did not return an iterable or an array.");
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function list(): iterable
    {
        $this->initialize();

        return \array_keys($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function all(): iterable
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name): bool
    {
        $this->initialize();

        return isset($this->data[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name)
    {
        $this->initialize();

        return $this->data[$name] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiple(array $names): array
    {
        $this->initialize();

        return \array_intersect_key($this->data, \array_flip($names));
    }

    /**
     * {@inheritdoc}
     */
    public function getType(string $name): ValueType
    {
        $this->initialize();

        return ValueValidator::getTypeOf($this->get($name));
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $name, $value, ?ValueType $type = null): void
    {
        $this->initialize();

        $this->data[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $name): void
    {
        $this->initialize();

        unset($this->data[$name]);
    }
}
