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

use craft\ecs\SetList;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__.'/src',
        __FILE__,
    ]);

    $ecsConfig->sets([
        SetList::CRAFT_CMS_4,
    ]);
};
