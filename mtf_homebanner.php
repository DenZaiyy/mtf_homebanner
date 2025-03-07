<?php

/**
 * 2023-2024 MTFibertech
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @author    MTFibertech <contact@mtfibertech.com>
 * @copyright 2023-2024 MTFibertech
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Mtf_HomeBanner extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'mtf_homebanner';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'MTFibertech';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '8.0.0',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('MTF - HomeBanner');
        $this->description = $this->l('Display multiple images on home section');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    /**
     * Install the module and register hooks
     *
     * @return bool
     */
    public function install()
    {
        Configuration::updateValue('MTF_HOMEBANNER_ENABLE', 0);
        Configuration::updateValue('MTF_HOMEBANNER_IMAGE_1', null);
        Configuration::updateValue('MTF_HOMEBANNER_TITLE_1', '');
        Configuration::updateValue('MTF_HOMEBANNER_CAPTION_1', '');
        Configuration::updateValue('MTF_HOMEBANNER_LINK_1', '');
        Configuration::updateValue('MTF_HOMEBANNER_LINK_1_TITLE', '');
        Configuration::updateValue('MTF_HOMEBANNER_IMAGE_1_VISIBLE', 0);

        Configuration::updateValue('MTF_HOMEBANNER_IMAGE_2', null);
        Configuration::updateValue('MTF_HOMEBANNER_TITLE_2', '');
        Configuration::updateValue('MTF_HOMEBANNER_CAPTION_2', '');
        Configuration::updateValue('MTF_HOMEBANNER_LINK_2', '');
        Configuration::updateValue('MTF_HOMEBANNER_LINK_2_TITLE', '');
        Configuration::updateValue('MTF_HOMEBANNER_IMAGE_2_VISIBLE', 0);

        Configuration::updateValue('MTF_HOMEBANNER_IMAGE_3', null);
        Configuration::updateValue('MTF_HOMEBANNER_TITLE_3', '');
        Configuration::updateValue('MTF_HOMEBANNER_CAPTION_3', '');
        Configuration::updateValue('MTF_HOMEBANNER_LINK_3', '');
        Configuration::updateValue('MTF_HOMEBANNER_LINK_3_TITLE', '');
        Configuration::updateValue('MTF_HOMEBANNER_IMAGE_3_VISIBLE', 0);

        Configuration::updateValue('MTF_HOMEBANNER_IMAGE_4', null);
        Configuration::updateValue('MTF_HOMEBANNER_TITLE_4', '');
        Configuration::updateValue('MTF_HOMEBANNER_CAPTION_4', '');
        Configuration::updateValue('MTF_HOMEBANNER_LINK_4', '');
        Configuration::updateValue('MTF_HOMEBANNER_LINK_4_TITLE', '');
        Configuration::updateValue('MTF_HOMEBANNER_IMAGE_4_VISIBLE', 0);

        Configuration::updateValue('MTF_HOMEBANNER_DISPLAY', 'grid');
        Configuration::updateValue('MTF_HOMEBANNER_DISPLAY_COLUMN', 3);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayHome') &&
            $this->installTab();
    }

    /**
     * Uninstall the module and remove configuration
     *
     * @return bool
     */
    public function uninstall()
    {
        Configuration::deleteByName('MTF_HOMEBANNER_ENABLE');
        Configuration::deleteByName('MTF_HOMEBANNER_IMAGE_1');
        Configuration::deleteByName('MTF_HOMEBANNER_TITLE_1');
        Configuration::deleteByName('MTF_HOMEBANNER_CAPTION_1');
        Configuration::deleteByName('MTF_HOMEBANNER_LINK_1');
        Configuration::deleteByName('MTF_HOMEBANNER_LINK_1_TITLE');
        Configuration::deleteByName('MTF_HOMEBANNER_IMAGE_1_VISIBLE');

        Configuration::deleteByName('MTF_HOMEBANNER_IMAGE_2');
        Configuration::deleteByName('MTF_HOMEBANNER_TITLE_2');
        Configuration::deleteByName('MTF_HOMEBANNER_CAPTION_2');
        Configuration::deleteByName('MTF_HOMEBANNER_LINK_2');
        Configuration::deleteByName('MTF_HOMEBANNER_LINK_2_TITLE');
        Configuration::deleteByName('MTF_HOMEBANNER_IMAGE_2_VISIBLE');

        Configuration::deleteByName('MTF_HOMEBANNER_IMAGE_3');
        Configuration::deleteByName('MTF_HOMEBANNER_TITLE_3');
        Configuration::deleteByName('MTF_HOMEBANNER_CAPTION_3');
        Configuration::deleteByName('MTF_HOMEBANNER_LINK_3');
        Configuration::deleteByName('MTF_HOMEBANNER_LINK_3_TITLE');
        Configuration::deleteByName('MTF_HOMEBANNER_IMAGE_3_VISIBLE');

        Configuration::deleteByName('MTF_HOMEBANNER_IMAGE_4');
        Configuration::deleteByName('MTF_HOMEBANNER_TITLE_4');
        Configuration::deleteByName('MTF_HOMEBANNER_CAPTION_4');
        Configuration::deleteByName('MTF_HOMEBANNER_LINK_4');
        Configuration::deleteByName('MTF_HOMEBANNER_LINK_4_TITLE');
        Configuration::deleteByName('MTF_HOMEBANNER_IMAGE_4_VISIBLE');

        Configuration::deleteByName('MTF_HOMEBANNER_DISPLAY');
        Configuration::deleteByName('MTF_HOMEBANNER_DISPLAY_COLUMN');

        return parent::uninstall() && $this->uninstallTab();
    }

    /**
     * Install Tab in PrestaShop admin
     */
    public function installTab()
    {
        // First check if mtf_tabs module exists and is installed
        if (!Module::isInstalled('mtf_tabs')) {
            // Fallback to IMPROVE tab
            $tabRepository = $this->get('prestashop.core.admin.tab.repository');
            $improveTab = $tabRepository->findOneByClassName('IMPROVE');
            $parentId = $improveTab ? $improveTab->getId() : 0;
        } else {
            // Instancier le module mtf_tabs pour accéder à ses méthodes
            $mtfTabsModule = Module::getInstanceByName('mtf_tabs');

            // Vérifier si la classe est disponible avant d'essayer d'utiliser ses méthodes
            if (method_exists('Mtf_Tabs', 'getConfigureTabId')) {
                $configureId = Mtf_Tabs::getConfigureTabId();

                if (!$configureId && method_exists('Mtf_Tabs', 'getParentTabId')) {
                    $configureId = Mtf_Tabs::getParentTabId();
                }

                $parentId = $configureId ?: 0;
            } else {
                // Fallback: récupérer directement l'ID depuis la base de données
                $sql = 'SELECT id_tab FROM ' . _DB_PREFIX_ . 'tab WHERE class_name = "AdminMTFConfigure"';
                $configureId = Db::getInstance()->getValue($sql);

                if (!$configureId) {
                    $sql = 'SELECT id_tab FROM ' . _DB_PREFIX_ . 'tab WHERE class_name = "AdminMTFModules"';
                    $configureId = Db::getInstance()->getValue($sql);
                }

                $parentId = $configureId ?: 0;
            }
        }

        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminMtfHomeBanner';
        $tab->name = [];

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Home Banners';
        }

        $tab->id_parent = $parentId;
        $tab->module = $this->name;

        return $tab->add();
    }

    /**
     * Uninstall Tab
     */
    public function uninstallTab()
    {
        $tabRepository = $this->get('prestashop.core.admin.tab.repository');

        try {
            $tab = $tabRepository->findOneByClassName('AdminMtfHomeBanner');
            if ($tab) {
                $tabPS = new Tab($tab->getId());
                return $tabPS->delete();
            }
        } catch (Exception $e) {
            // Tab not found, nothing to delete
        }

        return true;
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminMtfHomeBanner'));
    }

    /**
     * Add the CSS & JavaScript files
     */
    public function hookHeader()
    {
        if (!$this->active) {
            return;
        }

        $this->context->controller->registerStylesheet(
            'mtf-homebanner-style',
            'modules/' . $this->name . '/assets/css/mtf_homebanner.css',
            ['media' => 'all', 'priority' => 150]
        );

        if (Configuration::get('MTF_HOMEBANNER_DISPLAY') == 'slider') {
            $this->context->controller->registerStylesheet(
                'mtf-homebanner-slick-style',
                'modules/' . $this->name . '/assets/css/slick.css',
                ['media' => 'all', 'priority' => 150]
            );

            $this->context->controller->registerJavascript(
                'mtf-homebanner-slick-js',
                'modules/' . $this->name . '/assets/js/slick.min.js',
                ['position' => 'bottom', 'priority' => 150]
            );
        }

        $this->context->controller->registerJavascript(
            'mtf-homebanner-js',
            'modules/' . $this->name . '/assets/js/mtf_homebanner.js',
            ['position' => 'bottom', 'priority' => 151]
        );
    }

    /**
     * Display content on homepage
     */
    public function hookDisplayHome()
    {
        $banners = [];
        $activeBanners = 0;

        $placeholderImage = "default-placeholder.png";

        // Get banners data
        for ($i = 1; $i <= 4; $i++) {
            if (Configuration::get('MTF_HOMEBANNER_IMAGE_' . $i . '_VISIBLE')) {
                $bannerImage = Configuration::get('MTF_HOMEBANNER_IMAGE_' . $i);

                // Check if image exists, use placeholder if not
                $imagePath = _PS_MODULE_DIR_ . $this->name . '/views/img/' . $bannerImage;
                if (!$bannerImage || !file_exists($imagePath)) {
                    $bannerImage = $placeholderImage;
                }

                $banners[] = [
                    'id' => $i,
                    'image' => $bannerImage,
                    'title' => Configuration::get('MTF_HOMEBANNER_TITLE_' . $i),
                    'caption' => Configuration::get('MTF_HOMEBANNER_CAPTION_' . $i),
                    'link' => Configuration::get('MTF_HOMEBANNER_LINK_' . $i),
                    'linkTitle' => Configuration::get('MTF_HOMEBANNER_LINK_' . $i . '_TITLE'),
                ];
                $activeBanners++;
            }
        }

        // If no banners are active, don't display anything
        if (empty($banners)) {
            return '';
        }

        $this->context->smarty->assign([
            'banners' => $banners,
            'banner_path' => _MODULE_DIR_ . $this->name . '/views/img/',
            'display_type' => Configuration::get('MTF_HOMEBANNER_DISPLAY'),
            'display_columns' => Configuration::get('MTF_HOMEBANNER_DISPLAY_COLUMN'),
            'active_banners' => $activeBanners,
            'enable' => Configuration::get('MTF_HOMEBANNER_ENABLE')
        ]);

        return $this->display(__FILE__, 'views/templates/hook/mtf_homebanner.tpl');
    }
}
