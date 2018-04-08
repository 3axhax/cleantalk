<?php
print_r('<<<<<<<<<<<   START_SCRIPT   >>>>>>>>>>'.PHP_EOL);

$db_config = array(
    'type' => 'mysql',
    'host' => 'localhost',
    'dbname' => 'cleantalk',
    'user' => 'root',
    'password' => '',
);

class Db
{
    private static $db;
    private static $db_config = array(
        'type' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'cleantalk',
        'user' => 'root',
        'password' => '',
    );
    public static function getConnection()
    {
        if (!isset(self::$db))
        {
            $params = self::$db_config;
            $dsn = "{$params['type']}:host={$params['host']};dbname={$params['dbname']}";
            self::$db = new PDO($dsn, $params['user'], $params['password']);
        }

        return self::$db;
    }
}

class Article
{
    static protected function tableName()
    {
        return 'articles';
    }

    static public function getArticleList()
    {
        $db = Db::getConnection();
        $articleList = array();
        $sql = $db->prepare('SELECT article_id, keywords FROM  '.self::tableName());
        $sql->execute();
        while($row = $sql->fetch()) {
            $articleList[$row['article_id']]['keywords'] = array();
            $keywordsList = explode(',', $row['keywords']);
            foreach ($keywordsList as $keyword)
            {
                $keyword = trim($keyword);
                $keyword = strtolower($keyword);
                if (!ctype_digit($keyword) && $keyword != '') $articleList[$row['article_id']]['keywords'][] = $keyword;
            }
        }
        return $articleList;
    }

    static public function convertArticleKeywords()
    {
        self::FromArticleToKeywords();
        self::FromKeywordsToArticle();
    }

    static private function FromArticleToKeywords()
    {
        $keywords = ArticlesKeywords::getKeywordList();
        $articleList = self::getArticleList();
        foreach ($articleList as $article)
        {
            foreach ($article['keywords'] as $keyword)
            {
                if (!in_array($keyword, $keywords) && $keyword != '')
                {

                    ArticlesKeywords::AddKeyword($keyword);
                    $keywords = ArticlesKeywords::getKeywordList();
                }
            }
        }
    }

    static private function FromKeywordsToArticle()
    {
        $keywords = ArticlesKeywords::getKeywordList();
        $articleList = self::getArticleList();
        $db = Db::getConnection();
        $sql = $db->prepare('UPDATE `'. self::tableName().'` SET `keywords` = :keywords WHERE `article_id` = :article_id');
        foreach ($articleList as $article_id => $article)
        {
            $setKeywords = '';
            $ch = false;
            foreach ($article['keywords'] as $keyword)
            {
                if (($id = array_search($keyword, $keywords)))
                {
                    $setKeywords .= ($setKeywords == '') ? $id :', '.$id;
                    $ch = true;
                }
                else $setKeywords .= ($setKeywords == '') ? $keyword :', '.$keyword;
            }
            if (($setKeywords != '') && $ch)
            {
                $sql->bindParam(':article_id', $article_id);
                $sql->bindParam(':keywords', $setKeywords);
                $sql->execute();
            }
        }
    }

    static public function getUniqueKeywords()
    {
        $articleList = self::getArticleList();
        $uniqueKeywordsList = array();
        foreach ($articleList as $keywords)
        {
            foreach ($keywords['keywords'] as $keyword)
            {
                if (!in_array($keyword, $uniqueKeywordsList)) $uniqueKeywordsList[]=$keyword;
            }
        }
        return $uniqueKeywordsList;
    }
}

class ArticlesKeywords
{
    static protected function tableName()
    {
        return 'articles_keywords';
    }

    static public function getKeywordList()
    {
        $db = Db::getConnection();
        $keywordList = array();
        $sql = $db->prepare('SELECT * FROM  '.self::tableName());
        $sql->execute();
        while($row = $sql->fetch()) {
            $keywordList[$row['keyword_id']] = $row['keyword'];
        }
        return $keywordList;
    }
    static public function AddKeyword($keyword)
    {
        $db = Db::getConnection();
        $dateTime = date('Y-m-d H:i:s');
        $sql = $db->prepare('INSERT INTO `'. self::tableName().'` (`keyword_id`, `keyword`, `created`) VALUES (NULL, :keyword, :created)');
        $sql->bindParam(':keyword', $keyword);
        $sql->bindParam(':created', $dateTime);
        $sql->execute();
    }
}
$db = Db::getConnection();
$string = file_get_contents('articles_for_help.sql');
$sql = $db->prepare($string);
$sql->execute();
Article::convertArticleKeywords();
echo '<<<<<<<<<<<   END_SCRIPT   >>>>>>>>>>';