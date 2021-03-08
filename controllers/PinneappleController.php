<?php


namespace app\controllers;


use Yii;
use yii\web\Controller;
use yii\web\Response;

class PinneappleController extends Controller
{
    public function actionFeedback()
    {
        // ajax validation
        if (Yii::$app->request->isAjax) {

            $data = json_decode($_POST['feedback']);

            $htmlMail = '<h3>User Information </h3> Browser Version: ' . $data->browser->appVersion
                . '<p>User Agent: ' . $data->browser->userAgent . '</p>'
                . '<p>Platform: ' . $data->browser->platform . '</p><hr>'
                . '<p>URL: ' . $data->url . '</p>'
                . (Yii::$app->user->isGuest ? '' : '<p>User: ' . Yii::$app->user->identity->username . '</p>')
                . '<p>Note: ' . $data->note . '</p>';

            // Send email with image attached as HTML file
            Yii::$app->mailer->compose()
                ->setFrom('svobodova@bertik.net')
                ->setTo('ullriher@fel.cvut.cz')
                ->setSubject('Feedback Fcheck')
                ->setHtmlBody($htmlMail)
                ->attachContent('<!DOCTYPE html><html><body><img src="' . $data->img . '" /></body></html>', ['fileName' => 'screengrab.html', 'contentType' => 'text/html'])
                ->send();
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $data;
        }
        return false;
    }
}