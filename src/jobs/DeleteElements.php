<?php

/**
 * Craft Delete It
 *
 * @package   CraftDeleteIt
 * @author    IWF Web Solutions <web-solutions@iwf.ch>
 * @copyright Copyright (c) 2025-2025 IWF Web Solutions <web-solutions@iwf.ch>
 * @license   https://github.com/iwf-web/craft-delete-it/blob/main/LICENSE.txt MIT License
 * @link      https://github.com/iwf-web/craft-delete-it
 */

namespace iwf\craftdeleteit\jobs;

use craft\queue\BaseJob;
use yii\queue\Queue;

/**
 * Hard-deletes a batch of elements by ID.
 *
 * @author IWF Web Solutions <web-solutions@iwf.ch>
 * @since  1.1.0
 */
class DeleteElements extends BaseJob
{
    // =========================================================================
    // Public Properties
    // =========================================================================

    /**
     * @var int[] Element IDs to hard-delete.
     */
    public array $ids = [];

    // =========================================================================
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     *
     * @author IWF Web Solutions <web-solutions@iwf.ch>
     * @since  1.1.0
     */
    public function execute($queue): void
    {
        $total = count($this->ids);

        foreach ($this->ids as $i => $id) {
            $this->setProgress($queue, $i / $total);
            \Craft::$app->getElements()->deleteElementById($id, hardDelete: true);
        }
    }

    // =========================================================================
    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     *
     * @author IWF Web Solutions <web-solutions@iwf.ch>
     * @since  1.1.0
     */
    protected function defaultDescription(): ?string
    {
        return \Craft::t('delete-it', 'Deleting {count} elements', ['count' => count($this->ids)]);
    }
}
