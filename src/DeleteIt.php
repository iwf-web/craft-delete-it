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

namespace iwf\craftdeleteit;

use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Utilities;
use iwf\craftdeleteit\utilities\DeleteItUtility;
use yii\base\Event;

/**
 * Delete it plugin.
 *
 * @method static DeleteIt getInstance()
 */
class DeleteIt extends Plugin
{
    public string $schemaVersion = '1.0.0';

    public function init(): void
    {
        parent::init();

        if (\Craft::$app->getRequest()->getIsCpRequest()) {
            $this->registerUtilities();
        }
    }

    /**
     * Registers utilities.
     */
    private function registerUtilities(): void
    {
        Event::on(Utilities::class, Utilities::EVENT_REGISTER_UTILITIES,
            static function (RegisterComponentTypesEvent $event): void {
                $event->types[] = DeleteItUtility::class;
            },
        );
    }
}
