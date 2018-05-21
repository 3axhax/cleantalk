<?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>

<div class="container">

            <?php $this->renderPartial('/site/login_form', array('model' => new User,
                                                                 'uiKey' => 'A_LEFT_PERSONAL_LOGIN',
                                                                 'class' => '',
                                                                 'refresh' => true)); ?>

            <div class="center" style="margin-top: 20px;">
                        <?= Yii::app()->ui->item('A_LEFT_PERSONAL_REGISTRATION'); ?>
                   </div>
            <?php $this->renderPartial('/site/register_form2', array('model' => new User, 'refresh' => true, 'h1'=>true)); ?>
        
		</div>

