<?php

class Banner
{
    const TYPE_LINK = 1;
    const TYPE_PRODUCT = 2;

    public function GetAllBanners()
    {
        $retArray = Yii::app()->dbCache->get('Banners');
        if ($retArray === false)
        {
            $sql = 'SELECT * '
                . 'FROM banners AS b '
                . 'JOIN banner_list AS bl ON b.banner_id=bl.id '
                . 'ORDER BY place_id, language, sort_order';

            $rows = Yii::app()->db->createCommand($sql)->queryAll();
            $items = array();

            $retArray = array();
            foreach ($rows as $row)
            {
                $plc = $row['place_id'];
                $lang = strtoupper($row['language']);
                if (!empty($row['entity_id']) && !empty($row['item_id']))
                {
                    $items[$row['entity_id']][] = $row['item_id'];
                    $row['key'] = $row['entity_id'] . '_' . $row['item_id'];
                }
                $retArray[$plc][$lang][] = $row;
            }

            // Получить из БД все инфу по товарам в БД
            $p = new Product;
            $dbItems = array();
            foreach ($items as $entity => $ids)
            {
                $tmp = $p->GetProducts($entity, $ids);
                foreach ($tmp as $item)
                {
                    $key = $entity . '_' . $item['id'];
                    $dbItems[$key] = $item;
                }
            }

            foreach ($retArray as $place => $data)
            {
                foreach ($data as $lang => $items)
                {
                    foreach ($items as $idx => $item)
                    {
                        if (isset($item['key']) && !empty($item['key']) && isset($dbItems[$item['key']]))
                        {
                            $retArray[$place][$lang][$idx] = array_merge($retArray[$place][$lang][$idx], $dbItems[$item['key']]);
                            $image = Yii::app()->params['PicDomain'] . '/pictures/small/' . $dbItems[$item['key']]['image'];
                            $retArray[$place][$lang][$idx]['image'] = $image;
                        }
                        else
                        {
                            $retArray[$place][$lang][$idx]['image'] =
                                Yii::app()->params['PicDomain'] . '/pictures/banners-right/' .
                                $item['banner_id'] . '_' . $retArray[$place][$lang][$idx]['image'];
                        }
                    }
                }
            }

            Yii::app()->dbCache->set('Banners', $retArray, Yii::app()->params['DbCacheTime']);
        }

        return $retArray;
    }
}