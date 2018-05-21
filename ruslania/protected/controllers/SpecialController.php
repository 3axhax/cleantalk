<?php

class SpecialController extends MyController
{
    public function actionList($mode)
    {
        $product = new Product();
        $groups = $product->GetProductsFor($mode);

        $titles = array('firms' => 'A_OFFERS_FRMS',
        'lib' => 'A_OFFERS_LIBS',
        'uni' => 'A_OFFERS_UNIVERCITY');

        $title = Yii::app()->ui->item('A_OFFERS').Yii::app()->ui->item($titles[$mode]);

        $this->breadcrumbs[] = $title;
        $this->render('list', array('groups' => $groups));
    }
}
