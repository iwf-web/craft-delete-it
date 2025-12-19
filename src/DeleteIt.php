<?php

namespace iwf\craftdeleteit;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Utilities;
use iwf\craftdeleteit\utilities\DeleteItUtility;
use yii\base\Event;

/**
 * Delete it plugin
 *
 * @method static DeleteIt getInstance()
 * @author iwf <s.friedrich@iwf.ch>
 * @copyright iwf
 * @license MIT
 */
class DeleteIt extends Plugin
{
    public string $schemaVersion = '1.0.0';

    public function init(): void
    {
        parent::init();

        if (Craft::$app->getRequest()->getIsCpRequest()) {
            $this->registerUtilities();
        }
    }

    /**
     * Registers utilities
     */
    private function registerUtilities(): void
    {
        Event::on(Utilities::class, Utilities::EVENT_REGISTER_UTILITIES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = DeleteItUtility::class;
            }
        );
    }
}
