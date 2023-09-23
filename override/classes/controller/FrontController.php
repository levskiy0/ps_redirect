<?php

/**
 * Created by 40x.Pro@gmail.com | github.com/owles
 * Date: 23.09.2023
 */
class FrontController extends FrontControllerCore
{

    public function init()
    {
        // ToDo - Move to the module + add cache
        if (Module::isEnabled('ps_redirect')) {
            $currentUrl = $_SERVER['REQUEST_URI'];
            if ($redirect = Db::getInstance()->getRow(
                'SELECT url_from, url_to, active, strategy FROM '._DB_PREFIX_.'redirect_rules WHERE active = 1 AND url_from = "'.pSQL($currentUrl).'"'
            )) {
                switch ($redirect['strategy']) {
                    case 301: Tools::redirect($redirect['url_to'], __PS_BASE_URI__, null, 'HTTP/1.1 301 Moved Permanently'); die();
                    case 302: Tools::redirect($redirect['url_to'], __PS_BASE_URI__, null, 'HTTP/1.1 302 Moved Temporarily'); die();
                    case 303: Tools::redirect($redirect['url_to'], __PS_BASE_URI__, null, 'HTTP/1.1 303 See Other'); die();
                }
            }
        }

        return parent::init();
    }

}
