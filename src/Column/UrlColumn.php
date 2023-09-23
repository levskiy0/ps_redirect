<?php
/**
 * Created by 40x.Pro@gmail.com | github.com/owles
 * Date: 23.09.2023
 */

namespace PrestaShop\Module\Ps_Redirect\Column;

use PrestaShop\PrestaShop\Core\Grid\Column\AbstractColumn;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UrlColumn extends AbstractColumn
{

    public function getType()
    {
        return 'url';
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired([
                'field',
            ])
            ->setDefaults([
                'clickable' => true,
            ])
            ->setAllowedTypes('field', 'string');
    }


}
