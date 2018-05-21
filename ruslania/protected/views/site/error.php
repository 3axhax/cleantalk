
            <?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>
<div class="container cabinet">
            <?php if($code == 404) : ?>

            <div class="text information info-box">
                <h3><?=$ui->item('MSG_NO_ITEMS'); ?></h3>
                <p><?=$ui->item('MSG_CATALOG_NOTHING_WAS_FOUND1'); ?>
                    <br/>
                    <?=sprintf($ui->item('MSG_CATALOG_NOTHING_WAS_FOUND2'), '/'); ?>
                </p>
            </div>

            <?php elseif($code == 403) : ?>

                <div class="text information info-box">
                    <h3><?=$code; ?></h3>
                    <p><?=$message; ?></p>
                </div>

            <?php else : ?>

                <div class="text information info-box">
                    <h3><?=$code; ?></h3>
                </div>

            <?php endif; ?>


         </div>   