<?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>


            <?php $this->renderPartial('login_form', array('model' => new User, 'refresh' => false, 'class' => '')); ?>
       
