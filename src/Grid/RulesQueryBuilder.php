<?php
/**
 * Created by 40x.Pro@gmail.com
 * Date: 13.09.2023
 */

namespace PrestaShop\Module\Ps_Redirect\Grid;

use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use Doctrine\DBAL\Connection;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

class RulesQueryBuilder extends AbstractDoctrineQueryBuilder
{

    public function __construct(Connection $connection, $dbPrefix)
    {
        parent::__construct($connection, $dbPrefix);
    }

    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getBaseQuery();
        $qb->select('b.rule_id, b.url_from, b.url_to, b.strategy, b.active')
            ->orderBy(
                $searchCriteria->getOrderBy(),
                $searchCriteria->getOrderWay()
            )
            ->setFirstResult($searchCriteria->getOffset())
            ->setMaxResults($searchCriteria->getLimit());

        $this->applyFilters($qb, $searchCriteria);

        return $qb;
    }

    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getBaseQuery();
        $qb->select('COUNT(b.rule_id)');

        $this->applyFilters($qb, $searchCriteria);

        return $qb;
    }

    private function applyFilters(QueryBuilder $qb, SearchCriteriaInterface $searchCriteria) {
        $filters = [
            'rule_id',
            'active',
            'url_from',
            'url_to'
        ];

        foreach ($searchCriteria->getFilters() as $filterName => $filterValue) {
            if (!in_array($filterName, $filters)) {
                continue;
            }

            if ('rule_id' === $filterName) {
                $qb->andWhere("b.rule_id = :$filterName");
                $qb->setParameter($filterName, $filterValue);

                continue;
            }

            $qb->andWhere("$filterName LIKE :$filterName");
            $qb->setParameter($filterName, '%'.$filterValue.'%');
        }
    }

    private function getBaseQuery(): QueryBuilder
    {
        return $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix.'redirect_rules', 'b');
    }
}
