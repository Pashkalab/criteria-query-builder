<?php

declare(strict_types=1);

namespace Paymaxi\Component\Query\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Paymaxi\Component\Query\Validator\Adapter\ArrayAdapter;
use Paymaxi\Component\Query\Validator\ValidatorInterface;

/**
 * Class EnumerationFilter
 *
 * @package Paymaxi\Component\Query\Filter
 */
final class EnumerationFilter extends AbstractFilter
{
    /** @var string */
    private $delimiter;

    /**
     * EnumerationFilter constructor.
     *
     * @param string $queryField
     * @param string $fieldName
     * @param string $delimiter
     */
    public function __construct(string $queryField, string $fieldName = null, string $delimiter = ',')
    {
        parent::__construct($queryField, $fieldName);
        $this->delimiter = $delimiter;
    }

    /**
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        parent::setValidator(new ArrayAdapter($validator));
    }


    /**
     * @param QueryBuilder $queryBuilder
     * @param Criteria $criteria
     * @param $value
     */
    public function apply(QueryBuilder $queryBuilder, Criteria $criteria, $value)
    {
        $values = explode($this->delimiter, $value);

        if (!$this->validate($values)) {
            $this->thrower->invalidValueForKey($this->getQueryField());
        }

        $criteria->andWhere(Criteria::expr()->in($this->fieldName, $values));
    }
}