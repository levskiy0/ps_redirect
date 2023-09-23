<?php
/**
 * Created by 40x.Pro@gmail.com
 * Date: 13.09.2023
 */

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class Ps_Redirect extends Module
{

    static $rules = null;

    public $secureKey;

    public $dbPrefix;

    public function __construct()
    {
        $this->name = 'ps_redirect';
        $this->author = 'github.com/owles';
        $this->version = '1.0.0';
        $this->need_instance = 0;
        $this->tab = 'seo';
        $this->bootstrap = true;
        $this->displayName = $this->trans('SEO Redirect', []);
        $this->description = $this->trans('PrestaShop SEO (301, 302, 303) redirect module.', []);
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];
        $this->secureKey = Tools::encrypt($this->name);
        $this->dbPrefix = _DB_PREFIX_;

        $tabNames = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tabNames[$lang['locale']] = $this->trans('SEO Redirect', [], 'Modules.Ps_Redirect', $lang['locale']);
        }

        $this->tabs = [
            [
                'route_name' => 'admin_ps_redirect_list',
                'class_name' => 'AdminRedirectWidget',
                'visible' => true,
                'name' => $tabNames,
                'parent_class_name' => 'ShopParameters',
                'wording' => 'SEO Redirect',
                'wording_domain' => 'Modules.Ps_Redirect',
            ],
        ];

        parent::__construct();
    }

    // ToDo - Add tab

    public function getContent()
    {
        $sfContainer = SymfonyContainer::getInstance();
        $router = $sfContainer->get('router');

        Tools::redirectAdmin(
            $router->generate('admin_ps_redirect_list')
        );
    }

    public function install()
    {
        $engine = _MYSQL_ENGINE_;

        $queries = [
            "CREATE TABLE IF NOT EXISTS `{$this->dbPrefix}redirect_rules`(
    			`rule_id` int(10) unsigned NOT NULL auto_increment,
    			`id_shop` int(10) unsigned NOT NULL,
    			`url_from` varchar(255) default '',
    			`url_to` varchar(255) default '',
                `strategy` int(10) unsigned NOT NULL default '0',
                `active` BOOL default true,
    			PRIMARY KEY (`rule_id`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8",
        ];

        foreach ($queries as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return parent::install();
    }

    public function uninstall()
    {
        $tableNames = [
            'redirect_rules',
        ];

        foreach ($tableNames as $tableName) {
            $query = 'DROP TABLE IF EXISTS ' . $this->dbPrefix . $tableName;
            Db::getInstance()->execute($query);
        }

        return parent::uninstall();
    }
}
