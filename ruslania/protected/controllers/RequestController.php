<?php

class RequestController extends MyController
{
    public function actionView($rid)
    {
        $r = new Request();
        $request = $r->GetRequest($rid);
        if(!empty($request) && $request['uid'] != $this->uid) throw new CException('NotYourRequest'.$rid.'_'.$this->uid);
        if(empty($request)) throw new CHttpException(404);

        $this->breadcrumbs[Yii::app()->ui->item("A_LEFT_PERSONAL_NOTAVAIBLE_ORDERS")] = Yii::app()->createUrl('my/requests');
        $this->breadcrumbs[] = sprintf(Yii::app()->ui->item('REQUEST_MSG_NUMBER'), $request['id']);
        $this->render('view', array('request' => $request));
    }
}