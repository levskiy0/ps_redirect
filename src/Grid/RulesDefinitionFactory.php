<?php

namespace PrestaShop\Module\Ps_Redirect\Grid;

use PrestaShop\Module\Ps_Redirect\Column\UrlColumn;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ToggleColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractFilterableGridDefinitionFactory;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use PrestaShopBundle\Form\Admin\Type\YesAndNoChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Created by 40x.Pro@gmail.com
 * Date: 13.09.2023
 */
final class RulesDefinitionFactory extends AbstractFilterableGridDefinitionFactory
{

    const GRID_ID = 'ps_redirect-rules';

    protected function getId()
    {
        return self::GRID_ID;
    }

    protected function getName()
    {
        return $this->trans('Rules', [], 'Modules.Ps_Redirect');
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new BulkActionColumn('bulk'))
                ->setOptions([
                    'bulk_field' => 'rule_id',
                ])
            )
            ->add((new DataColumn('rule_id'))
                ->setName($this->trans('ID', [], 'Modules.Ps_Redirect'))
                ->setOptions([
                    'field' => 'rule_id',
                ])
            )
            ->add((new UrlColumn('url_from'))
                ->setName($this->trans('URL From', [], 'Modules.Ps_Redirect'))
                ->setOptions([
                    'field' => 'url_from',
                ])
            )
            ->add((new UrlColumn('url_to'))
                ->setName($this->trans('URL To', [], 'Modules.Ps_Redirect'))
                ->setOptions([
                    'field' => 'url_to',
                ])
            )
            ->add((new DataColumn('redirect'))
                ->setName($this->trans('Type', [], 'Modules.Ps_Redirect'))
                ->setOptions([
                    'field' => 'strategy',
                ])
            )
            ->add((new ToggleColumn('active'))
                ->setName($this->trans('Is Active', [], 'Admin.Global'))
                ->setOptions([
                    'field' => 'active',
                    'primary_field' => 'rule_id',
                    'route' => 'admin_ps_redirect_toggle_status',
                    'route_param_name' => 'ruleId',
                ])
            )
            ->add(
                (new ActionColumn('actions'))
                    ->setOptions([
                        'actions' => (new RowActionCollection())
                            ->add(
                                (new LinkRowAction('edit'))
                                    ->setIcon('edit')
                                    ->setOptions([
                                        'route' => 'admin_ps_redirect_edit',
                                        'route_param_name' => 'ruleId',
                                        'route_param_field' => 'rule_id',
                                    ])
                            )
                            ->add(
                                (new SubmitRowAction('delete'))
                                    ->setName($this->trans('Delete', [], 'Admin.Actions'))
                                    ->setIcon('delete')
                                    ->setOptions([
                                        'method' => 'POST',
                                        'route' => 'admin_ps_redirect_delete',
                                        'route_param_name' => 'ruleId',
                                        'route_param_field' => 'rule_id',
                                        'confirm_message' => $this->trans(
                                            'Delete selected item?',
                                            [],
                                            'Admin.Notifications.Warning'
                                        ),
                                    ])
                            ),
                    ])
            );
    }

    protected function getFilters()
    {
        $filters = (new FilterCollection())
            ->add(
                (new Filter('url_from', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Search URL', [], 'Admin.Ps_Redirect'),
                        ],
                    ])
                    ->setAssociatedColumn('url_from')
            )
            ->add(
                (new Filter('url_to', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Search URL', [], 'Admin.Ps_Redirect'),
                        ],
                    ])
                    ->setAssociatedColumn('url_to')
            )
            ->add(
                (new Filter('active', YesAndNoChoiceType::class))
                    ->setAssociatedColumn('active')
            )
            ->add(
                (new Filter('actions', SearchAndResetType::class))
                    ->setAssociatedColumn('actions')
                    ->setTypeOptions([
                        'reset_route' => 'admin_common_reset_search_by_filter_id',
                        'reset_route_params' => [
                            'filterId' => self::GRID_ID,
                        ],
                        'redirect_route' => 'admin_ps_redirect_list',
                    ])
            );

        return $filters;
    }

    protected function getBulkActions()
    {
        return (new BulkActionCollection())
            ->add(
                (new SubmitBulkAction('enable_selection'))
                    ->setName($this->trans('Enable selection', [], 'Admin.Actions'))
                    ->setOptions([
                        'submit_route' => 'admin_ps_redirect_bulk_enable_status',
                    ])
            )
            ->add(
                (new SubmitBulkAction('disable_selection'))
                    ->setName($this->trans('Disable selection', [], 'Admin.Actions'))
                    ->setOptions([
                        'submit_route' => 'admin_ps_redirect_bulk_disable_status',
                    ])
            )
            ->add(
                (new SubmitBulkAction('delete_selection'))
                    ->setName($this->trans('Delete selection', [], 'Admin.Actions'))
                    ->setOptions([
                        'submit_route' => 'admin_ps_redirect_bulk_delete',
                    ])
            );
    }

}
