<?php

class BookshelfController extends MyController
{
    public function actionList()
    {
        $this->breadcrumbs[] = Yii::app()->ui->item('BOOKSHELF_LIST');

        $dp = new CActiveDataProvider('Bookshelf', array(
            'criteria' => array(
                'condition' => 'is_visible=1',
                'order' => 'date_of DESC'
            ),
            'pagination'=>array('pageSize'=> 20),
        ));

        $this->render('list', array('dp' => $dp));
    }

    public function actionView($id)
    {
        $model = Bookshelf::model()->findByPk($id);

        if(empty($model))
            throw new HttpException(404);

        $o = new Offer;
        $groups = $o->GetItems($model['offer_id']);

        $this->breadcrumbs[Yii::app()->ui->item('BOOKSHELF_LIST')] = Yii::app()->createUrl('bookshelf/list');
        $this->breadcrumbs[] = $model['title'];

        $this->render('view', array('model' => $model, 'groups' => $groups));;
    }

}
