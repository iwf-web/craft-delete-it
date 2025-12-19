<?php

namespace iwf\craftdeleteit\controllers;

use Craft;
use craft\web\Controller;
use craft\web\View;
use yii\web\Response;
use craft\commerce\elements\Product;

class DeleteItController extends Controller
{

    public function actionDelete(): ?Response
    {

        $countSections = 0;
        $sections = Craft::$app->getRequest()->getParam('sections');
        if (!empty($sections)) {
            foreach($sections as $sectionHandle) {
                $entryIds = \craft\elements\Entry::find()->section($sectionHandle)->ids();
                foreach($entryIds as $id) {
                    $countSections++;
                    Craft::$app->getElements()->deleteElementById($id,  hardDelete: true);
                }
            }
        }

        $countProductTypes = 0;
        $productTypes = Craft::$app->getRequest()->getParam('productTypes');
        if (!empty($productTypes)) {
            foreach($productTypes as $productTypeHandle) {
                $productIds = Product::find()->type($productTypeHandle)->limit(100)->ids();
                foreach($productIds as $id) {
                    $countProductTypes++;
                    Craft::$app->getElements()->deleteElementById($id,  hardDelete: true);
                }
            }
        }

        $response = "";
        if ($countSections > 0 && $countProductTypes > 0) {
            $response = $response . (Craft::t('delete-it', 'Deleted {count1} entries and {count2} products.', ['count1' => $countSections, 'count2' => $countProductTypes]));
        }
        else if ($countSections > 0) {
            $response = $response . (Craft::t('delete-it', 'Deleted {count} entries.', ['count' => $countSections]));
        }
        else if ($countProductTypes > 0) {
            $response = $response . (Craft::t('delete-it', 'Deleted {count} products.', ['count' => $countProductTypes]));
        }
        else {
            $response = (Craft::t('delete-it', 'No items were selected for deletion.'));
        }

        return $this->getSuccessResponse($response);

    }

    private function getSuccessResponse(string $message): ?Response
    {
        $this->setSuccessFlash($message);
        return $this->getResponse($message);
    }

    private function getResponse(string $message, bool $success = true): ?Response
    {
        $request = Craft::$app->getRequest();

        if (Craft::$app->getView()->templateMode == View::TEMPLATE_MODE_SITE || $request->getAcceptsJson()) {
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
