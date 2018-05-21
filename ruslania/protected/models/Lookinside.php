<?php

class Lookinside extends CMyActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'all_lookinside';
    }
}


/*
DELETE FROM all_lookinside;
INSERT INTO `all_lookinside` (entity, item_id, resource_filename)
SELECT 10 AS entity, item_id, resource_filename FROM `books_lookinside`
UNION
SELECT 20 AS entity, item_id, resource_filename FROM audio_lookinside
UNION
SELECT 30 AS entity, item_id, resource_filename FROM pereodics_lookinside
UNION
SELECT 15 AS entity, item_id, resource_filename FROM musicsheets_lookinside
UNION
SELECT 22 AS entity, item_id, resource_filename FROM music_lookinside
UNION
SELECT 40 AS entity, item_id, resource_filename FROM video_lookinside
UNION
SELECT 24 AS entity, item_id, resource_filename FROM soft_lookinside
UNION
SELECT 60 AS entity, item_id, resource_filename FROM maps_lookinside
UNION
SELECT 50 AS entity, item_id, resource_filename FROM printed_lookinside
*/