<?php
/**
 * Created by 40x.Pro@gmail.com
 * Date: 14.09.2023
 */

namespace PrestaShop\Module\Ps_Redirect\Form;


use Meta;
use Module;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RuleFormType extends TranslatorAwareType
{

    private function getChoices(array $array): array
    {
        $choices = [];
        foreach ($array as $k => $v) {
            if ($v === 'ps_redirect') {
                continue;
            }
            $choices[$v] = $v;
        }
        return $choices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $domain = \Context::getContext()->link->getBaseLink();

        $builder
            ->add('rule_id', HiddenType::class)
            ->add('active', SwitchType::class, [
                'label' => $this->trans('Is active', 'Admin.Global'),
            ])
            ->add('strategy', ChoiceType::class, [
                'choices' => [
                    '301 Moved Permanently' => 301,
                    '302 Moved Temporarily' => 302,
                    '303 See Other' => 303,
                ],
                'attr' => [
                    'data-toggle' => 'select2',
                    'data-minimumResultsForSearch' => '7',
                ],
                'label' => $this->trans('Redirect type', 'Admin.Global'),
            ])
            ->add('url_from', TextType::class, [
                'label' => $this->trans('URL From - ' . $domain, 'Admin.Global'),
            ])
            ->add('url_to', TextType::class, [
                'label' => $this->trans('URL To', 'Admin.Global'),
            ]);
    }

    public function getBlockPrefix()
    {
        return 'rule_block';
    }

}
