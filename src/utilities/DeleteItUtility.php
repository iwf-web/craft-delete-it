<?php declare(strict_types=1);

/**
 * Craft Delete It
 *
 * @package   CraftDeleteIt
 * @author    IWF Web Solutions <web-solutions@iwf.ch>
 * @copyright Copyright (c) 2025-2025 IWF Web Solutions <web-solutions@iwf.ch>
 * @license   https://github.com/iwf-web/craft-delete-it/blob/main/LICENSE.txt MIT License
 * @link      https://github.com/iwf-web/craft-delete-it
 */

namespace iwf\craftdeleteit\utilities;

use craft\base\Utility;
use craft\commerce\Plugin;

class DeleteItUtility extends Utility
{
    /**
     * {@inheritdoc}
     */
    public static function displayName(): string
    {
        return \Craft::t('delete-it', 'Delete It!');
    }

    /**
     * {@inheritdoc}
     */
    public static function id(): string
    {
        return 'delete-it';
    }

    /**
     * {@inheritdoc}
     */
    public static function icon(): ?string
    {
        $iconPath = \Craft::getAlias('@iwf/craftdeleteit/icon-mask.svg');

        if (!\is_string($iconPath)) {
            return null;
        }

        return $iconPath;
    }

    /**
     * {@inheritdoc}
     */
    public static function contentHtml(): string
    {
        $sections = [];
        $productTypes = [];
        $sections = \Craft::$app->getEntries()->getAllSections();
        if (\Craft::$app->plugins->isPluginInstalled('commerce') === true) {
            $productTypes = Plugin::getInstance()->getProductTypes()->getAllProductTypes();
        }

        return \Craft::$app->getView()->renderTemplate('delete-it/_utilities/delete-it', [
            'sections' => $sections,
            'productTypes' => $productTypes,
        ]);
    }
}
