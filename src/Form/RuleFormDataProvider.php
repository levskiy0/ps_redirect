<?php
/**
 * Created by 40x.Pro@gmail.com
 * Date: 14.09.2023
 */

namespace PrestaShop\Module\Ps_Redirect\Form;

use Db;
use PrestaShop\Module\Ps_Redirect\Model\Rule;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

class RuleFormDataProvider implements FormDataProviderInterface
{

    /**
     * @var int|null
     */
    private $idRule;

    public function getData()
    {
        $rule = new Rule($this->idRule);

        return ['rule_form' => [
            'id' => $rule->id,
            'rule_id' => $rule->rule_id,
            'active' => $rule->id ? (bool) $rule->active : true,
            'url_from' => $rule->url_from,
            'url_to' => $rule->url_to,
            'strategy' => $rule->strategy,
        ]];
    }

    public function setData(array $data)
    {
        $rule = $data['rule_form'];

        if ($rule['rule_id'] === null) {
            Db::getInstance()->insert('redirect_rules', [
                'url_from' => $rule['url_from'],
                'url_to' => $rule['url_to'],
                'active' => (bool) $rule['active'],
                'strategy' => $rule['strategy'],
            ]);
        } else {
            Db::getInstance()->update('redirect_rules', [
                'url_from' => $rule['url_from'],
                'url_to' => $rule['url_to'],
                'active' => (bool) $rule['active'],
                'strategy' => $rule['strategy'],
            ], "rule_id = {$rule['rule_id']}");
        }

        return [];
    }

    public function setIdRule($idRule)
    {
        $this->idRule = $idRule;

        return $this;
    }
}
