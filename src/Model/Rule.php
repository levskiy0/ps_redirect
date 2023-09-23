<?php
/**
 * Created by 40x.Pro@gmail.com
 * Date: 14.09.2023
 */

namespace PrestaShop\Module\Ps_Redirect\Model;

class Rule extends \ObjectModel
{
    /**
     * @var int
     */
    public $rule_id;

    /**
     * @var string
     */
    public $url_from;

    /**
     * @var string
     */
    public $url_to;

    /**
     * @var int
     */
    public $strategy;

    /**
     * @var bool
     */
    public $active;

    public static $definition = [
        'table' => 'redirect_rules',
        'primary' => 'rule_id',
        'multilang' => false,
        'fields' => [
            'url_from' => ['type' => self::TYPE_STRING, 'required' => true],
            'url_to' => ['type' => self::TYPE_STRING, 'required' => true],
            'strategy' => ['type' => self::TYPE_INT, 'required' => true],
            'active' => ['type' => self::TYPE_BOOL, 'required' => true],
        ],
    ];

    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'rule_id' => $this->id,
            'url_from' => $this->url_from,
            'url_to' => $this->url_to,
            'strategy' => $this->strategy,
            'active' => $this->active,
        ];
    }
}
