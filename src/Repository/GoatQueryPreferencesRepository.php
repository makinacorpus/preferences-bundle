<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Repository;

use Goat\Query\QueryBuilder;
use Goat\Runner\Runner;
use MakinaCorpus\Preferences\PreferencesRepository;
use MakinaCorpus\Preferences\ValueType;
use MakinaCorpus\Preferences\Value\ValueValidator;

/**
 * SQL based implementation
 */
class GoatQueryPreferencesRepository implements PreferencesRepository
{
    const TABLE_NAME_DEFAULT = 'preferences';

    private Runner $runner;
    private string $tableName = self::TABLE_NAME_DEFAULT;

    /**
     * Default constructor
     *
     * @codeCoverageIgnore
     *   Because it is called within a data provider.
     */
    public function __construct(Runner $runner, ?string $tableName = null)
    {
        $this->runner = $runner;
        $this->tableName = $tableName ?? self::TABLE_NAME_DEFAULT;
    }

    /**
     * {@inheritdoc}
     */
    public function list(): iterable
    {
        return $this
            ->runner
            ->getQueryBuilder()
            ->select($this->tableName)
            ->columns(['name'])
            ->execute()
            ->setHydrator(fn (array $row) => $row['name'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): iterable
    {
        return \iterator_to_array(
            $this
                ->runner
                ->getQueryBuilder()
                ->select($this->tableName)
                ->columns(['value', 'is_serialized', 'name'])
                ->execute()
                ->setKeyColumn('name')
                ->setHydrator(static function (array $row) {
                    if ($row['is_serialized']) {
                        return \unserialize($row['value']);
                    }
                    return $row['value'];
                })
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name): bool
    {
        return (bool)$this
            ->runner
            ->getQueryBuilder()
            ->select($this->tableName)
            ->columnExpression('true')
            ->where('name', $name)
            ->execute()
            ->fetchField()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name)
    {
        return $this
            ->runner
            ->getQueryBuilder()
            ->select($this->tableName)
            ->columns(['value', 'is_serialized'])
            ->where('name', $name)
            ->execute()
            ->setHydrator(static function (array $row) {
                if ($row['is_serialized']) {
                    return \unserialize($row['value']);
                }
                return $row['value'];
            })
            ->fetch()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiple(array $names): array
    {
        return \iterator_to_array($this
            ->runner
            ->getQueryBuilder()
            ->select($this->tableName)
            ->columns(['name', 'value', 'is_serialized'])
            ->where('name', $names)
            ->execute()
            ->setKeyColumn('name')
            ->setHydrator(static function (array $row) {
                if ($row['is_serialized']) {
                    return \unserialize($row['value']);
                }
                return $row['value'];
            })
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getType(string $name): ValueType
    {
        return ($this
            ->runner
            ->getQueryBuilder()
            ->select($this->tableName)
            ->columns(['is_collection', 'is_hashmap', 'type'])
            ->where('name', $name)
            ->execute()
            ->setHydrator(function (array $row) {
                return new ValueType($row['type'], $row['is_collection'], null, $row['is_hashmap']);
            })
            ->fetch()
        ) ?? new ValueType('string');
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $name, $value, ?ValueType $type = null): void
    {
        $serialized = false;
        $type = $type ?? ValueValidator::getTypeOf($value);

        if (!\is_string($value)) {
            $value = \serialize($value);
            $serialized = true;
        }

        $this->runner->runTransaction(function (
            QueryBuilder $builder
        ) use (
            $serialized, $name, $value, $type
        ) {
            $exists = $builder
                ->select($this->tableName)
                ->columnExpression('true')
                ->where('name', $name)
                ->forUpdate()
                ->execute()
                ->fetchField()
            ;

            $now = new \DateTimeImmutable();
            if ($exists) {
                $builder
                    ->update($this->tableName)
                    ->sets([
                        'is_collection' => $type->collection,
                        'is_hashmap' => $type->hashMap,
                        'is_serialized' => $serialized,
                        'type' => $type->nativeType,
                        'updated_at' => $now,
                        'value' => $value,
                    ])
                    ->where('name', $name)
                    ->execute()
                ;
            } else {
                $builder
                    ->insert($this->tableName)
                    ->values([
                        'created_at' => $now,
                        'is_collection' => $type->collection,
                        'is_hashmap' => $type->hashMap,
                        'is_serialized' => $serialized,
                        'name' => $name,
                        'type' => $type->nativeType,
                        'updated_at' => $now,
                        'value' => $value,
                    ])
                    ->execute()
                ;
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $name): void
    {
        $this
            ->runner
            ->getQueryBuilder()
            ->delete($this->tableName)
            ->where('name', $name)
            ->execute()
        ;
    }
}
