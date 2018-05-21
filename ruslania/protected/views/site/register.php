<?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>
 
 <div class="container">
 
 <?php echo $ui->item('REG_INFO_MESSAGE'); ?>
<h1 class="h1_reg">Регистрация</h1>

            
           
            <?php $this->renderPartial('register_form', array('model' => $model)); ?>
        



</div>