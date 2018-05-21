<h1>Подписка в подарок</h1>

<hr>
<?php $eUrl = Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey($entity))); ?>
<div class="news_box nb<?= $entity ?>">
    <div class="container">
        <div class="title">
            <?= $ui->item("A_NEW_NEW_CATEGORY"); ?> <a href="<?= $eUrl; ?>" id="enity<?= $entity ?>"><b><?= Entity::GetTitle($entity); ?></b></a>
            <div class="pult">
                <a href="javascript:;" onclick="$('.nb<?= $entity ?> .btn_left.slick-arrow').click()" class="btn_left"><img src="/new_img/btn_left_news.png" alt=""/></a>
                <a href="javascript:;" onclick="$('.nb<?= $entity ?> .btn_right.slick-arrow').click()" class="btn_right"><img src="/new_img/btn_right_news.png" alt=""/></a>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.cnt<?= $entity ?> ul').slick({
                lazyLoad: 'ondemand',
                slidesToShow: 6,
                slidesToScroll: 6
            });
        });
    </script>

    <div class="container cnt<?= $entity ?>">
        <ul class="books">
            <?php
            foreach ($group as $item) :
                ?>
                <li>
                    <?php $item['status'] = Product::GetStatusProduct($entity, $item['id']);?>
                    <?php $this->renderPartial('/entity/_render_index_item', array('item' => $item)); ?>
                </li>

            <?php endforeach; ?>
        </ul>
    </div>
</div>
<hr>
<div class="span10">
    <h2 class="cattitle"><?=$ui->item('RUSLANIA_RECOMMENDS'); ?>:</h2>

    <ul class="left_list entity text recomends">
            <li class="iconentity-<?=$item['icon_entity']; ?>">

                <?
                $o = new Offer;
                $offer = $o->GetItems(67); ?>

                <?
                if (count($offer[Entity::GetTitle($entity)]['items'])) {
                    echo '<div class="items_goods_recomends">';
                    echo '<div class="slider_recomend custom-slider">';
                    foreach ($offer[Entity::GetTitle($entity)]['items'] as $of) {

                        if (true) {
                            echo '<div class="item slider_recomend__item">';
                            echo '<a href="' . ProductHelper::createUrl($of) . '" class="slider__img-block">
										<div class="img slider__img" style="background: url(\'' . Picture::Get($of, Picture::SMALL) . '\') center center no-repeat; background-size: 100%; position: relative">';
                            $this->renderStatusLables(Product::GetStatusProduct($of['entity'], $of['id']), '', true);
                            echo '</div></a>';
                            echo '</div>';
                        }
                    }
                    echo '</div><div class="clearfix"></div></div>';
                }
                ?>
            </li>


    </ul>
</div>