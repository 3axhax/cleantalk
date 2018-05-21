<?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>

<div class="container cabinet">

<div class="row">
        <div class="span10">

            <ul class="items">
                <li>
                    <?php $this->renderPartial('/client/_one_request', array('request' => $request)); ?>
                </li>
            </ul>


            <!-- /content -->
        </div>
    <div class="span2">

                <?php $this->renderPartial('/site/_me_left'); ?>

            </div>
        </div>
        </div>
