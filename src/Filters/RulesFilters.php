<?php
/**
 * Created by 40x.Pro@gmail.com | github.com/owles
 * Date: 23.09.2023
 */

namespace PrestaShop\Module\Ps_Redirect\Filters;

use PrestaShop\Module\Ps_Redirect\Grid\RulesDefinitionFactory;
use PrestaShop\PrestaShop\Core\Search\Filters;

final class RulesFilters extends Filters
{
    /** @var string */
    protected $filterId = RulesDefinitionFactory::GRID_ID;

    /**
     * {@inheritdoc}
     */
    public static function getDefaults()
    {
        return [
            'limit' => 50,
            'offset' => 0,
            'orderBy' => 'rule_id',
            'sortOrder' => 'DESC',
            'filters' => [],
        ];
    }
}
