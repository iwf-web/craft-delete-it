<?php declare(strict_types=1);

/**
 * Craft Delete It
 *
 * @package   CraftDeleteIt
 * @author    IWF Web Solutions <web-solutions@iwf.ch>
 * @copyright Copyright (c) 2025-2026 IWF Web Solutions <web-solutions@iwf.ch>
 * @license   https://github.com/iwf-web/craft-delete-it/blob/main/LICENSE.txt MIT License
 * @link      https://github.com/iwf-web/craft-delete-it
 */

namespace iwf\craftdeleteit\controllers;

use craft\commerce\elements\Product;
use craft\elements\Entry;
use craft\elements\User;
use craft\web\Controller;
use craft\web\View;
use yii\db\Query;
use yii\web\Response;

class DeleteItController extends Controller
{
    public function actionDelete(): ?Response
    {
        $countSections = 0;
        $sections = \Craft::$app->getRequest()->getParam('sections');
        if (!empty($sections)) {
            foreach ($sections as $sectionHandle) {
                $entryIds = Entry::find()->section($sectionHandle)->ids();
                foreach ($entryIds as $id) {
                    ++$countSections;
                    \Craft::$app->getElements()->deleteElementById($id, hardDelete: true);
                }
            }
        }

        $countProductTypes = 0;
        $productTypes = \Craft::$app->getRequest()->getParam('productTypes');
        if (!empty($productTypes)) {
            foreach ($productTypes as $productTypeHandle) {
                $productIds = Product::find()->type($productTypeHandle)->limit(100)->ids();
                foreach ($productIds as $id) {
                    ++$countProductTypes;
                    \Craft::$app->getElements()->deleteElementById($id, hardDelete: true);
                }
            }
        }

        $countUsers = 0;
        $currentUserId = \Craft::$app->getUser()->getId();

        $userGroups = \Craft::$app->getRequest()->getParam('userGroups');
        if (!empty($userGroups)) {
            foreach ($userGroups as $groupHandle) {
                $query = User::find()->group($groupHandle)->admin(false);
                if ($currentUserId !== null) {
                    $query->andWhere(['!=', 'elements.id', $currentUserId]);
                }
                foreach ($query->ids() as $id) {
                    ++$countUsers;
                    \Craft::$app->getElements()->deleteElementById($id, hardDelete: true);
                }
            }
        }

        if (!empty(\Craft::$app->getRequest()->getParam('ungroupedUsers'))) {
            $query = User::find()
                ->admin(false)
                ->andWhere(['not exists',
                    (new Query())
                        ->from('{{%usergroups_users}} ugu')
                        ->where('ugu.userId = users.id'),
                ]);
            if ($currentUserId !== null) {
                $query->andWhere(['!=', 'elements.id', $currentUserId]);
            }
            foreach ($query->ids() as $id) {
                ++$countUsers;
                \Craft::$app->getElements()->deleteElementById($id, hardDelete: true);
            }
        }

        $parts = [];
        if ($countSections > 0) {
            $parts[] = \Craft::t('delete-it', '{count} entries', ['count' => $countSections]);
        }
        if ($countProductTypes > 0) {
            $parts[] = \Craft::t('delete-it', '{count} products', ['count' => $countProductTypes]);
        }
        if ($countUsers > 0) {
            $parts[] = \Craft::t('delete-it', '{count} users', ['count' => $countUsers]);
        }

        $response = empty($parts)
            ? \Craft::t('delete-it', 'No items were selected for deletion.')
            : \Craft::t('delete-it', 'Deleted {items}.', ['items' => implode(', ', $parts)]);

        return $this->getSuccessResponse($response);
    }

    private function getSuccessResponse(string $message): ?Response
    {
        $this->setSuccessFlash($message);

        return $this->getResponse($message);
    }

    private function getResponse(string $message, bool $success = true): ?Response
    {
        $request = \Craft::$app->getRequest();

        if (\Craft::$app->getView()->templateMode === View::TEMPLATE_MODE_SITE || $request->getAcceptsJson()) {
            return $this->asJson([
                'success' => $success,
                'message' => $message,
            ]);
        }

        if (!$success) {
            return null;
        }

        return $this->redirectToPostedUrl();
    }
}
