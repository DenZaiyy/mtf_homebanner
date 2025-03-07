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

class AdminMtfHomeBannerController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;

        parent::__construct();

        $this->meta_title = $this->l('Home Banners Configuration');

        // Set the module parameter before calling parent constructor
        $this->module = Module::getInstanceByName('mtf_homebanner');

        if (!$this->module->active) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules'));
        }
    }

    public function initContent()
    {
        $this->content = $this->renderView();
        parent::initContent();
    }

    public function postProcess()
    {
        // Check if we're deleting an image
        if (Tools::isSubmit('action') && Tools::getValue('action') == 'deleteImage') {
            $this->processDeleteImage();
        }
        // Regular form submission processing
        elseif (Tools::isSubmit('submitMtf_HomeBannerModule')) {
            // Process form values
            $this->processConfigurationForm();

            // Redirect with success message
            $this->confirmations[] = $this->l('Settings updated successfully');
        }

        parent::postProcess();
    }

    public function renderView()
    {
        // Set form values
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this->module;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitMtf_HomeBannerModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminMtfHomeBanner', false);
        $helper->token = Tools::getAdminTokenLite('AdminMtfHomeBanner');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm()]);
    }

    /**
     * Process configuration form values
     */
    private function processConfigurationForm()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            if (
                $key != 'MTF_HOMEBANNER_IMAGE_1' &&
                $key != 'MTF_HOMEBANNER_IMAGE_2' &&
                $key != 'MTF_HOMEBANNER_IMAGE_3' &&
                $key != 'MTF_HOMEBANNER_IMAGE_4'
            ) {
                Configuration::updateValue($key, Tools::getValue($key));
            }
        }

        // Handle image uploads
        $this->handleImageUpload(1);
        $this->handleImageUpload(2);
        $this->handleImageUpload(3);
        $this->handleImageUpload(4);
    }

    /**
     * Handle banner image upload
     */
    private function handleImageUpload($num)
    {
        if (
            isset($_FILES['MTF_HOMEBANNER_IMAGE_' . $num]) &&
            !empty($_FILES['MTF_HOMEBANNER_IMAGE_' . $num]['name'])
        ) {

            // Create img directory if it doesn't exist
            $uploadDir = _PS_MODULE_DIR_ . $this->module->name . '/views/img/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Clean filename and ensure uniqueness
            $fileName = time() . '_' . Tools::strtolower(preg_replace('/[^A-Za-z0-9\-\.]/', '', $_FILES['MTF_HOMEBANNER_IMAGE_' . $num]['name']));
            $uploadFile = $uploadDir . $fileName;

            // Delete old image if exists
            $oldImage = Configuration::get('MTF_HOMEBANNER_IMAGE_' . $num);
            if ($oldImage && file_exists($uploadDir . $oldImage)) {
                unlink($uploadDir . $oldImage);
            }

            // Upload the new image
            if (move_uploaded_file($_FILES['MTF_HOMEBANNER_IMAGE_' . $num]['tmp_name'], $uploadFile)) {
                Configuration::updateValue('MTF_HOMEBANNER_IMAGE_' . $num, $fileName);
            }
        }
    }

    /**
     * Create the structure of your form
     */
    private function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('Home Banners Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    // Enable banners
                    [
                        'type' => 'switch',
                        'label' => $this->l('Enable module'),
                        'name' => 'MTF_HOMEBANNER_ENABLE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            ]
                        ],
                    ],

                    // Layout configuration
                    [
                        'type' => 'select',
                        'label' => $this->l('Display Layout'),
                        'name' => 'MTF_HOMEBANNER_DISPLAY',
                        'options' => [
                            'query' => [
                                ['id' => 'grid', 'name' => $this->l('Grid')],
                                ['id' => 'slider', 'name' => $this->l('Slider')],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Number of Columns'),
                        'name' => 'MTF_HOMEBANNER_DISPLAY_COLUMN',
                        'id' => 'mtf-column-select',
                        'options' => [
                            'query' => [
                                ['id' => '1', 'name' => $this->l('1 Column')],
                                ['id' => '2', 'name' => $this->l('2 Columns')],
                                ['id' => '3', 'name' => $this->l('3 Columns')],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],

                    // Banner 1
                    [
                        'type' => 'switch',
                        'label' => $this->l('Display Banner 1'),
                        'name' => 'MTF_HOMEBANNER_IMAGE_1_VISIBLE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            ]
                        ],
                    ],
                    [
                        'type' => 'file',
                        'label' => $this->l('Banner 1 Image'),
                        'name' => 'MTF_HOMEBANNER_IMAGE_1',
                        'display_image' => true,
                        'image' => $this->displayCurrentBannerImage(1),
                        'desc' => $this->l('Upload a banner image. If no image is selected, a default placeholder will be used.'),
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 1 Title'),
                        'name' => 'MTF_HOMEBANNER_TITLE_1',
                    ],
                    [
                        'type' => 'textarea',
                        'label' => $this->l('Banner 1 Caption'),
                        'name' => 'MTF_HOMEBANNER_CAPTION_1',
                        'autoload_rte' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 1 Link'),
                        'name' => 'MTF_HOMEBANNER_LINK_1',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 1 Link Title'),
                        'name' => 'MTF_HOMEBANNER_LINK_1_TITLE',
                    ],

                    // Banner 2
                    [
                        'type' => 'switch',
                        'label' => $this->l('Display Banner 2'),
                        'name' => 'MTF_HOMEBANNER_IMAGE_2_VISIBLE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            ]
                        ],
                    ],
                    [
                        'type' => 'file',
                        'label' => $this->l('Banner 2 Image'),
                        'name' => 'MTF_HOMEBANNER_IMAGE_2',
                        'display_image' => true,
                        'image' => $this->displayCurrentBannerImage(2),
                        'desc' => $this->l('Upload a banner image. If no image is selected, a default placeholder will be used.'),
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 2 Title'),
                        'name' => 'MTF_HOMEBANNER_TITLE_2',
                    ],
                    [
                        'type' => 'textarea',
                        'label' => $this->l('Banner 2 Caption'),
                        'name' => 'MTF_HOMEBANNER_CAPTION_2',
                        'autoload_rte' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 2 Link'),
                        'name' => 'MTF_HOMEBANNER_LINK_2',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 2 Link Title'),
                        'name' => 'MTF_HOMEBANNER_LINK_2_TITLE',
                    ],

                    // Banner 3
                    [
                        'type' => 'switch',
                        'label' => $this->l('Display Banner 3'),
                        'name' => 'MTF_HOMEBANNER_IMAGE_3_VISIBLE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            ]
                        ],
                    ],
                    [
                        'type' => 'file',
                        'label' => $this->l('Banner 3 Image'),
                        'name' => 'MTF_HOMEBANNER_IMAGE_3',
                        'display_image' => true,
                        'image' => $this->displayCurrentBannerImage(3),
                        'desc' => $this->l('Upload a banner image. If no image is selected, a default placeholder will be used.'),
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 3 Title'),
                        'name' => 'MTF_HOMEBANNER_TITLE_3',
                    ],
                    [
                        'type' => 'textarea',
                        'label' => $this->l('Banner 3 Caption'),
                        'name' => 'MTF_HOMEBANNER_CAPTION_3',
                        'autoload_rte' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 3 Link'),
                        'name' => 'MTF_HOMEBANNER_LINK_3',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 3 Link Title'),
                        'name' => 'MTF_HOMEBANNER_LINK_3_TITLE',
                    ],

                    // Banner 4
                    [
                        'type' => 'switch',
                        'label' => $this->l('Display Banner 4'),
                        'name' => 'MTF_HOMEBANNER_IMAGE_4_VISIBLE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            ]
                        ],
                    ],
                    [
                        'type' => 'file',
                        'label' => $this->l('Banner 4 Image'),
                        'name' => 'MTF_HOMEBANNER_IMAGE_4',
                        'display_image' => true,
                        'image' => $this->displayCurrentBannerImage(4),
                        'desc' => $this->l('Upload a banner image. If no image is selected, a default placeholder will be used.'),
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 4 Title'),
                        'name' => 'MTF_HOMEBANNER_TITLE_4',
                    ],
                    [
                        'type' => 'textarea',
                        'label' => $this->l('Banner 4 Caption'),
                        'name' => 'MTF_HOMEBANNER_CAPTION_4',
                        'autoload_rte' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 4 Link'),
                        'name' => 'MTF_HOMEBANNER_LINK_4',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner 4 Link Title'),
                        'name' => 'MTF_HOMEBANNER_LINK_4_TITLE',
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    /**
     * Display current banner image with delete button
     */
    private function displayCurrentBannerImage($num)
    {
        $imageName = Configuration::get('MTF_HOMEBANNER_IMAGE_' . $num);
        if ($imageName) {
            $imagePath = _PS_MODULE_DIR_ . $this->module->name . '/views/img/' . $imageName;
            if (file_exists($imagePath)) {
                // Use getBaseUrl instead of getMediaLink for better compatibility
                $shopUrl = Context::getContext()->shop->getBaseURL();
                $imageUrl = $shopUrl . 'modules/' . $this->module->name . '/views/img/' . $imageName;

                // Build the delete URL
                $deleteUrl = $this->context->link->getAdminLink('AdminMtfHomeBanner') . '&action=deleteImage&image_num=' . $num . '&token=' . Tools::getAdminTokenLite('AdminMtfHomeBanner');

                // Return image preview with delete button
                return '
                <div class="image-preview-container">
                    <img src="' . $imageUrl . '" class="img-thumbnail" style="max-width: 200px;">
                    <div class="delete-image-container" style="margin-top: 5px;">
                        <a href="' . $deleteUrl . '" class="btn btn-danger btn-sm" onclick="return confirm(\'' . $this->l('Are you sure you want to delete this image?') . '\');">
                            <i class="icon-trash"></i> ' . $this->l('Delete image') . '
                        </a>
                    </div>
                </div>';
            }
        }

        // Return placeholder message when no image is available
        return '<div class="alert alert-info">' . $this->l('No image selected. A default placeholder will be used.') . '</div>';
    }

    /**
     * Process image deletion
     */
    public function processDeleteImage()
    {
        $imageNum = (int)Tools::getValue('image_num');

        if ($imageNum >= 1 && $imageNum <= 4) {
            $imageName = Configuration::get('MTF_HOMEBANNER_IMAGE_' . $imageNum);

            if ($imageName) {
                // Delete the physical file
                $imagePath = _PS_MODULE_DIR_ . $this->module->name . '/views/img/' . $imageName;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                // Remove from configuration
                Configuration::updateValue('MTF_HOMEBANNER_IMAGE_' . $imageNum, null);

                $this->confirmations[] = $this->l('Image successfully deleted.');
            }
        }
    }

    /**
     * Set values for the inputs
     */
    private function getConfigFormValues()
    {
        return [
            'MTF_HOMEBANNER_ENABLE' => Configuration::get('MTF_HOMEBANNER_ENABLE'),
            'MTF_HOMEBANNER_DISPLAY' => Configuration::get('MTF_HOMEBANNER_DISPLAY'),
            'MTF_HOMEBANNER_DISPLAY_COLUMN' => Configuration::get('MTF_HOMEBANNER_DISPLAY_COLUMN'),

            'MTF_HOMEBANNER_IMAGE_1_VISIBLE' => Configuration::get('MTF_HOMEBANNER_IMAGE_1_VISIBLE'),
            'MTF_HOMEBANNER_TITLE_1' => Configuration::get('MTF_HOMEBANNER_TITLE_1'),
            'MTF_HOMEBANNER_CAPTION_1' => Configuration::get('MTF_HOMEBANNER_CAPTION_1'),
            'MTF_HOMEBANNER_LINK_1' => Configuration::get('MTF_HOMEBANNER_LINK_1'),
            'MTF_HOMEBANNER_LINK_1_TITLE' => Configuration::get('MTF_HOMEBANNER_LINK_1_TITLE'),

            'MTF_HOMEBANNER_IMAGE_2_VISIBLE' => Configuration::get('MTF_HOMEBANNER_IMAGE_2_VISIBLE'),
            'MTF_HOMEBANNER_TITLE_2' => Configuration::get('MTF_HOMEBANNER_TITLE_2'),
            'MTF_HOMEBANNER_CAPTION_2' => Configuration::get('MTF_HOMEBANNER_CAPTION_2'),
            'MTF_HOMEBANNER_LINK_2' => Configuration::get('MTF_HOMEBANNER_LINK_2'),
            'MTF_HOMEBANNER_LINK_2_TITLE' => Configuration::get('MTF_HOMEBANNER_LINK_2_TITLE'),


            'MTF_HOMEBANNER_IMAGE_3_VISIBLE' => Configuration::get('MTF_HOMEBANNER_IMAGE_3_VISIBLE'),
            'MTF_HOMEBANNER_TITLE_3' => Configuration::get('MTF_HOMEBANNER_TITLE_3'),
            'MTF_HOMEBANNER_CAPTION_3' => Configuration::get('MTF_HOMEBANNER_CAPTION_3'),
            'MTF_HOMEBANNER_LINK_3' => Configuration::get('MTF_HOMEBANNER_LINK_3'),
            'MTF_HOMEBANNER_LINK_3_TITLE' => Configuration::get('MTF_HOMEBANNER_LINK_3_TITLE'),


            'MTF_HOMEBANNER_IMAGE_4_VISIBLE' => Configuration::get('MTF_HOMEBANNER_IMAGE_4_VISIBLE'),
            'MTF_HOMEBANNER_TITLE_4' => Configuration::get('MTF_HOMEBANNER_TITLE_4'),
            'MTF_HOMEBANNER_CAPTION_4' => Configuration::get('MTF_HOMEBANNER_CAPTION_4'),
            'MTF_HOMEBANNER_LINK_4' => Configuration::get('MTF_HOMEBANNER_LINK_4'),
            'MTF_HOMEBANNER_LINK_4_TITLE' => Configuration::get('MTF_HOMEBANNER_LINK_4_TITLE'),
        ];
    }
}
