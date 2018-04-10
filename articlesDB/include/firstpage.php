<?php

/**
* Класс для работы со страницами
*
*/

class FirstPage extends CCS {

    public function __construct() {
        parent::__construct();
    }

    /**
      * Показ страницы
      *
      * @return string
      */

	function show_page(){
		$this->ccs_init();
		$http_host = 'cleantalk.ru';
		if ($this->ct_lang == 'en')
			$http_host = 'cleantalk.org';

        // По-умолчанию отключаем показ ссылок в подвале
        $this->page_info['show_registration'] = false;
        $this->page_info['show_translate'] = false;
        $this->page_info['show_footer'] = false;
        $this->page_info['show_footer_lp'] = true;
        $this->page_info['show_header_lp'] = false;
        $this->page_info['hide_lp_form_links'] = true;
        $this->page_info['hide_trial_notice'] = true;
        $this->page_info['show_feedback'] = false;
        $this->page_info['show_slimbox'] = true;
        $this->page_info['show_tools_js'] = true;
        $this->page_info['show_mootools_more'] = false;
        $this->page_info['hide_top_menu'] = false;
        $this->page_info['enable_self_passwords'] = $this->options['enable_self_passwords'];
		$this->page_info['show_twitter_meta'] = false;

        $this->get_lang($this->ct_lang, 'Register');
        $this->get_lang($this->ct_lang, 'SimplePage_about');
        $this->get_lang($this->ct_lang, 'Blacklists');

        $this->get_apps();
        $this->get_api();

        if (isset($_GET['new_site']))
			setcookie('new_site', 1, null, '/');

        $this->page_info['lang'] = $this->ct_lang;

        // Обработка страниц

        if ($this->link->template == 'article.html') {
            $this->get_apps();

            if (!strstr($_SERVER['REQUEST_URI'], 'help')) {
                header('Location: /help/' . $this->link->seo_url, true, 301);
                exit();
            }

            $this->set_lead_source(null, true, true);

            if ($this->ct_lang == 'ru')
                $article = $this->db->select(sprintf("select a.article_id, a.article_title, a.article_content,
                                              a.keywords, a.description
                                              from articles a join links b
                                              on a.article_linkid = b.id
                                              where b.seo_url = %s AND a.article_status = '%s'",
                                              $this->stringToDB($this->link->seo_url.'-ru'), 'PUBLIC'));
            else
                $article = $this->db->select(sprintf("select article_id, article_title, article_content,
                                                      keywords, description
                                                      from articles
                                                      where article_linkid = %d AND `article_status` = '%s'",$this->link->id, 'PUBLIC'));
            $this->page_info['new_design'] = true;
            $this->page_info['show_footer'] = true;
            $this->page_info['static_menu'] = true;
            $this->page_info['article_title'] = $article['article_title'];
            $article['article_content'] = str_replace(array('<p>beginphp</p>', '<p>endphp</p>'), array('beginphp', 'endphp'), $article['article_content']);
            //$article['article_content'] = preg_replace('/<p>beginphp<\/p>([\n\r]*)<p>(.+)<\/p>([\n\r]*)<p>endphp<\/p>/isU','<pre class="brush: php; toolbar: false;">$2</pre>',$article['article_content']);
            $article['article_content'] = preg_replace('#(beginphp)(.+)(endphp)#siU','<pre class="brush: php; toolbar: false;">$2</pre>', $article['article_content']);
            $article['article_content'] = preg_replace('/<p>beginjs<\/p>([\n\r]*)<p>(.+)<\/p>([\n\r]*)<p>endjs<\/p>/i','<pre class="brush: jscript; toolbar: false;">$2</pre>',$article['article_content']);
            $article['article_content'] = preg_replace('/<p>beginplain<\/p>([\n\r]*)<p>(.+)<\/p>([\n\r]*)<p>endplain<\/p>/i','<pre class="brush: plain; toolbar: false;">$2</pre>',$article['article_content']);
            $article['article_content'] = str_replace('<br />', "\n", $article['article_content']);

            // Боковое меню

            $sidemenu = apc_fetch('sidemenu_'.$this->ct_lang);

            if (!$sidemenu)
                $sidemenu = $this->get_sidemenu();

            $this->page_info['cloud_data'] = $this->getWeightKeywords();

            $this->page_info['article_content'] = $article['article_content'];
            $this->page_info['head']['keywords'] = isset($article['keywords']) ? $this->getKeywords($article['keywords']) : '';
            $this->page_info['head']['meta_description'] = isset($article['description']) ? $article['description'] : '';
            $this->page_info['seo_url'] = $_SERVER['REQUEST_URI'];
            $this->page_info['head']['title'] = isset($article['article_title']) ? $article['article_title'] : '';
            $this->page_info['show_search'] = true;
            $this->page_info['sidemenu'] = &$sidemenu;
            if (strstr($this->link->seo_url, 'install')){
                $engi = str_replace('install-', '', $this->link->seo_url);
                $this->page_info['app_file'] = isset($this->apps[$engi]['app_file']) ? $this->apps[$engi]['app_file'] : false;
                $this->page_info['link_name'] = isset($this->apps[$engi]['link_name']) ? $this->apps[$engi]['link_name'] : false;
            }
            if (isset($article['article_title']) && $article['article_title'] == 'Presskit')
                $this->page_info['show_search'] = false;

            $this->page_info['ct_lang'] = $this->ct_lang;
            if ($this->set_sidemenu($sidemenu)) {
                $page_template = 'bootstrap/article.html';
                $template = 'includes/general.html';
            } else {
                $page_template = 'bootstrap/article.html';
                $template = 'includes/general.html';
            }

            // find articles with same keywords

            if (isset($article['keywords']))
            {
                $article_status = 'PUBLIC';
                $keywords = explode(', ', $article['keywords']);
                $condition = '';
                foreach ($keywords as $keyword)
                {
                    $condition .= (($condition != '') ? " OR " : "" )."`keywords` REGEXP '(^|, )".$keyword."($|,)'";
                }
                $interesting_articles = ($this->db->select(sprintf(
                    "SELECT a.article_title, a.updated, a.keywords, a.article_content,
                    b.seo_url 
                    FROM articles a join links b on a.article_linkid = b.id 
                    WHERE article_id != %d AND a.article_status = '%s' AND (%s) ORDER BY a.updated DESC LIMIT 0,3",
                    $article['article_id'], $article_status, $condition),
                    true));
                for ($i = 0; $i< count($interesting_articles); $i++) {
                    $interesting_articles[$i]['keywords'] = $this->getKeywords($interesting_articles[$i]['keywords']);
                    $content = $interesting_articles[$i]['article_content'];
                    $content = strip_tags($content);
                    $content = substr($content, 0, strpos($content, ' ',  100)).' ...';
                    //$content = substr($content, 0, 100).' ...';
                    $interesting_articles[$i]['article_content'] = $content;
                }
                $this->page_info['interesting_articles'] = $interesting_articles;
            }

            //Add block with New Articles in main help page

            if (($this->link->id == 108) || ($this->link->id == 133)) {
                $article_status = 'PUBLIC';
                $last_articles = ($this->db->select(sprintf(
                    "SELECT a.article_title, a.updated,
                    b.seo_url 
                    FROM articles a join links b on a.article_linkid = b.id 
                    WHERE a.article_status = '%s' ORDER BY a.updated DESC LIMIT 0,5",
                    $article_status),
                    true));
                $this->page_info['last_articles'] = $last_articles;
            }

            $this->page_info['deferred_css'] = array(
                '/highlight/styles/shCore.css?v=25122015',
                '/highlight/styles/shThemeDefault.css?v=25122015',
                '/css/font-awesome.min.css?v=12052016',
                '/css/sidemenu.min.css?v=26042017',
                '/css/jqcloud.css'
            );

            $this->page_info['scripts'] = array(
                '/highlight/scripts/shCore.js',
                '/highlight/scripts/shAutoloader.js',
                '/highlight/scripts/shBrushPhp.js',
                '/highlight/scripts/shBrushPlain.js',
                '/highlight/scripts/shBrushJScript.js',
                '/js/jqcloud.js'
            );

            $this->display($template, $page_template);
            exit();
        }


        $page_template = null;
        $template = null;
        $lead_source = null;
        $this->page_info['fb_seo_url'] = $this->link->seo_url;
        $this->page_info['fb_lang'] = $this->ct_lang;

        $social = array(
            'enabled' => false,
            'linkedin' => array('api_key' => '81er18byz9ye13')
        );
        switch ($this->ct_lang) {
            case 'ru':
                $this->page_info['lang_code'] = 'ru_RU';
                break;
            default:
                $this->page_info['lang_code'] = 'en_US';
                break;
        }

        switch($this->link->id) {
            case 1:
                $this->get_lang($this->ct_lang,'main');
                $this->get_lang($this->ct_lang,'FirstPage_main');
                $this->get_lang($this->ct_lang, 'SimplePage');
                $this->get_lang($this->ct_lang, 'Blacklists');
                $this->get_services_count();

                $this->get_mobile_apps();
                $this->get_bl_top20_spam_brute();
                $this->get_bl_new_submited();
                $this->get_bl_top20_2days();
                $this->get_bl_reviews_last();

                $this->page_info['show_registration'] = true;
                $this->page_info['show_auth'] = true;
                $this->page_info['show_footer'] = true;
                $this->page_info['show_footer_lp'] = false;
                $this->page_info['hide_top_menu'] = false;
                $this->page_info['enable_tweets'] = true;
                $this->page_info['marked_top_row'] = true;
                $this->page_info['show_slimbox'] = false;
                $this->page_info['show_tools_js'] = true;
                $this->page_info['new_design'] = true;
                $this->page_info['main_page'] = true;
                $this->page_info['engine'] = 'unknown';
                $this->page_info['lang'] = $this->ct_lang;

                $template = 'includes/general.html';
                $page_template = 'bootstrap/index.html';

                $this->set_last_modified();
                $this->last_modified = $this->page_is_modified(null,true);

                break;
			case 93:
                $page_template = 'main.html';
                $friend_invite_key = null;
                if (preg_match("#^/friends/([a-z]+)#i", $_SERVER['REQUEST_URI'], $matches)) {
                    $friend_invite_key = $matches[1];
                }

                if ($friend_invite_key) {
                    $row = $this->db->select(sprintf("select user_id from users where friend_invite_key = %s;", $this->stringToDB($friend_invite_key)));
                    if (isset($row['user_id'])) {
                        $this->check_referral($row['user_id']);
                    }
                }
			case 13:
				$this->page_info['engine_common_name'] = 'форум';
				$this->page_info['cpe_ip'] = 'МОД';
				$this->page_info['cms'] = 'phpBB3';
				$this->page_info['engine'] = 'phpbb3';
				$this->page_info['show_sms'] = false;
                $this->page_info['show_slimbox'] = false;
				$this->get_lang($this->ct_lang, 'FAQ');
                $social['enabled'] = true;
                // Статистика сервиса CleanTalk
#                $this->get_ct_stat();
                $this->page_info['scripts'] = array(
                    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.1.1/ekko-lightbox.min.js'
                );
                $template = 'bootstrap/landing/phpbb.html';
			break;
			case 15: // Joomla
                 $reldata = $this->get_app_info(null,'joomla15',$this->ct_lang,null);
                 $reldata['release_date'] = date('M d, Y', strtotime($reldata['release_date']));
                 $this->page_info['reldata'] = $reldata;
                 $this->page_info['lang'] = $this->ct_lang;
                 $this->page_info['engine'] = 'joomla15';
                 $this->get_lang($this->ct_lang, 'FirstPage_joomla15');
                 $social['enabled'] = true;
                 $this->defineSocial($social);
                 //$this->display('newjoomla.html');
                 $this->display('bootstrap/landing/default.html');
                 exit();
			case 25: // WordPress
                 $this->page_info['lang'] = $this->ct_lang;
                 $this->page_info['engine'] = 'wordpress';
                 $this->get_lang($this->ct_lang, 'FirstPage_wordpress');
                 $social['enabled'] = true;
                $template = 'bootstrap/landing/default.html';
                $lead_source = 'landing_page';
				break;
			case 27: // DLE
                $this->page_info['show_slimbox'] = false;
				break;
			case 49: // IP.Board
				$this->page_info['engine_common_name'] = 'форум';
				$this->page_info['cpe_ip'] = 'Хук';
				$this->page_info['engine'] = 'ipboard';
				$this->get_lang($this->ct_lang, 'FAQ');
                $this->page_info['show_slimbox'] = false;
                $social['enabled'] = true;
                // Статистика сервиса CleanTalk
#                $this->get_ct_stat();
                $this->page_info['scripts'] = array(
                    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.1.1/ekko-lightbox.min.js'
                );
                $template = 'bootstrap/landing/ipb.html';
 				break;
			case 50: // vBulletin
				$this->page_info['engine_common_name'] = 'форум';
				$this->page_info['cpe_ip'] = 'МОД';
				$this->page_info['engine'] = 'vbulletin';
				$this->get_lang($this->ct_lang, 'FAQ');
                $this->page_info['show_slimbox'] = false;
                $social['enabled'] = true;
                $this->page_info['scripts'] = array(
                    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.1.1/ekko-lightbox.min.js'
                );
                $template = 'bootstrap/landing/vbulletin.html';
				break;
			case 75: // Drupal
                 /*$this->page_info['show_slimbox'] = false;
                 $this->page_info['lang'] = $this->ct_lang;
                 $this->page_info['engine'] = '';
                 $this->page_info['fblink'] = 'drupal-anti-spam-module-no-captcha';
                 $this->get_lang($this->ct_lang, 'FirstPage_drupal');
                $social['enabled'] = true;
                $this->defineSocial($social);
                 //$this->display('newdesign.html');
                $this->display('bootstrap/landing/2x2.html');*/
                header("Location: http://get.cleantalk.org/drupal-8-anti-spam-module-no-captcha");
                exit();
				break;
            case 80: // SMF
                 $reldata = $this->get_app_info(null,'smf',$this->ct_lang,null);
                 $this->page_info['reldata'] = $reldata;
                 $this->page_info['lang'] = $this->ct_lang;
                 $this->page_info['engine'] = 'smf';
                 $this->page_info['fblink'] = 'smf-anti-spam-mod';
                 $this->get_lang($this->ct_lang, 'FirstPage_smf');
                //$template = 'newdesign.html';
                 $template = 'bootstrap/landing/2x2.html';
                $lead_source = 'landing_page';
                 $social['enabled'] = true;
                break;
            case 87 : // Xenforo
                $this->page_info['show_slimbox'] = false;
                $social['enabled'] = true;
                $this->page_info['scripts'] = array(
                    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.1.1/ekko-lightbox.min.js'
                );
                $template = 'bootstrap/landing/xenforo.html';
                break;
            case 90: //phpbb31
                $this->page_info['lang'] = $this->ct_lang;
                $this->page_info['engine'] = 'phpbb31';
                $this->get_lang($this->ct_lang, 'FirstPage_phpbb31');
                $social['enabled'] = true;
                $this->defineSocial($social);
                //$this->display('phpbb31.html');
                $this->display('bootstrap/landing/default.html');
                exit();
            case 99: // IPS CS 4
                $this->get_lang($this->ct_lang, 'FirstPage_ipboard');
                $reldata = $this->get_app_info(null,'ipscs4',$this->ct_lang,null);
                $this->page_info['reldata'] = $reldata;
                $this->page_info['engine'] = 'ipboard4';
                $this->page_info['fblink'] = 'ips-cs-4-anti-spam-plugin';
                $this->page_info['lang'] = $this->ct_lang;
                $social['enabled'] = true;
                $this->defineSocial($social);
                $this->display('bootstrap/landing/2x2.html');
                exit();
            case 100: // Website protection without captcha
                $this->get_lang($this->ct_lang, 'FirstPage_wpwocaptcha');
                $this->page_info['lang'] = $this->ct_lang;
                $this->display('newdesign.html');
                exit();
            case 465: // WooCommerce
                $this->get_lang($this->ct_lang, 'FirstPage_woocommerce');
                $this->page_info['lang'] = $this->ct_lang;
                $social['enabled'] = true;
                $this->defineSocial($social);
                $this->display('bootstrap/landing/2x2.html');
                exit();
                break;
            // Drupal 8
            case 125:
                 /*$this->page_info['lang'] = $this->ct_lang;
                 $this->page_info['engine'] = '';
                 $this->page_info['fblink'] = 'drupal-8-anti-spam-module-wo-captcha';
                 $this->get_lang($this->ct_lang, 'FirstPage_drupal8');
                 //$this->display('newdesign.html');
                 $this->display('bootstrap/landing/2x2.html');*/
                 header("Location: http://get.cleantalk.org/drupal-8-anti-spam-module-no-captcha");
                 exit();
                break;
            // Bitrix
            case 71:
                $social['enabled'] = true;
                $this->get_lang($this->ct_lang, 'FirstPage_bitrix');
                $this->page_info['styles'] = array('https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.1.1/ekko-lightbox.min.css');
                $this->page_info['scripts'] = array(
                    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.1.1/ekko-lightbox.min.js'
                );
                $template = 'bootstrap/landing/bitrix.html';
                break;
			default:
		}

        $this->page_info['is_auth'] = $this->is_auth;

        $this->set_lead_source($lead_source, true, true);

       // Для продуктивного сервера делаем редирект на https, дабы работа авторизация с главной страницы сайта
        $this->page_info['http_prefix'] = 'http';
        if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] != 'localhost' && $_SERVER['SERVER_NAME'] != 'localhost,' )
            $this->page_info['http_prefix'] = 'https';

        if (!isset($this->page_info['engine'])) {
            $engine = $this->db->select(sprintf("select engine, lang from platforms where link_id = %d;", $this->link->id));
            if (isset($engine['engine']))
                $this->page_info['engine'] = $engine['engine'];

            if (isset($engine['lang']) && !preg_match("/en/", $engine['lang'])) {
                $this->page_info['show_local_translate'] = false;
                $this->page_info['show_translate'] = false;
            }
        }

        $this->page_info['trial_notice'] = sprintf($this->lang['l_trial_notice'], $this->options['free_days']);
        $this->page_info['submit_btn'] = sprintf(strtoupper($this->lang['l_create_account']));

        $this->save_engine($this->page_info['engine']);
	    $this->get_lang($this->ct_lang, 'FirstPage_' . $this->page_info['engine']);

        if ($this->link->id != 1) {
            $this->page_info['app_info'] = $this->get_app_info(null, $this->page_info['engine'], $this->ct_lang);
            $this->page_info['sliders'] = $this->get_sliders($this->page_info['engine'], $this->ct_lang);

            $benefits_file = 'benefits/lp_' . $this->page_info['engine'] . '.html';
            if (file_exists('./templates/' . $benefits_file)) {
                $this->page_info['benefits_file'] = $benefits_file;
            }
        }

		if (isset($this->lang['l_page_title']) && $this->ct_lang != 'ru') {
			$this->page_info['head']['title'] = $this->lang['l_page_title'];
        }

		if (isset($this->lang['l_meta_description']) && $this->lang['l_meta_description'] != '') {
			$this->page_info['head']['meta_description'] = sprintf($this->lang['l_meta_description']);
        }

        if (isset($_GET['t_code']) && preg_match("/^\w{1,12}$/", $_GET['t_code']) && isset($this->lang['l_share_t_code_' . $_GET['t_code']])) {
            $this->page_info['head']['title'] = $this->lang['l_share_t_code_' . $_GET['t_code']];
        }

        $this->page_info['show_social'] = false;
        $this->page_info['show_facebook'] = false;

        if ($social['enabled']) $this->defineSocial($social);

		$this->display($template, $page_template);
	}

    /**
     * Подключает социальные кнопки на странице.
     *
     * Структура параметров:
     * {
     *   og: {
     *     title: "Заголовок страницы|l_page_title",
     *     description: "Описание страницы|l_meta_description,
     *     url: "URL страницы|https://cleantalk/$_SERVER['REQUEST_URI']"
     *   },
     *   linkedin: {
     *     api_key: API_KEY
     *   }
     * }
     *
     * @param $params array Параметры социальных кнопок
     */
	public function defineSocial($params) {
	    if (!isset($this->page_info['l_meta_description'])) {
            if (isset($this->lang['l_meta_description']) && $this->lang['l_meta_description'] != '') {
                $this->page_info['head']['meta_description'] = sprintf($this->lang['l_meta_description']);
            }
        }
	    $defaultOG = array(
	        'title' => $this->page_info['l_page_title'],
            'description' => isset($this->page_info['l_meta_description']) ? $this->page_info['l_meta_description'] : '',
            'url' => 'https://cleantalk.org' . $_SERVER['REQUEST_URI']
        );
	    if (!isset($params['og'])) $params['og'] = $defaultOG;
        if (!isset($params['og']['title'])) $params['og']['title'] = $defaultOG['title'];
        if (!isset($params['og']['description'])) $params['og']['description'] = $defaultOG['description'];
        if (!isset($params['og']['url'])) $params['og']['url'] = $defaultOG['url'];

        $this->page_info['social'] = $params;
    }

    /**
      * Подсчет статистики сервиса CleanTalk
      *
      * @return array
      */

    public function get_ct_stat() {
        $stat = $this->memcache->get('main_stat');
        if ($stat === false) {
            $row = $this->db->select("
            select sum(allow) as allow, sum(count) as count from requests_stat_services where datediff(now(), date) <= 1;
            ");
            $stat['spam'] = number_format($row['count'] - $row['allow'], 0, ',', ' ');
            $row = $this->db->select(sprintf("select count(*) as count from requests where datetime between now() - interval 24 hour and now() and allow = 1 and method_name = 'check_message';"));
            $stat['messages'] = number_format($row['count'], 0, ',', ' ');
            $row = $this->db->select(sprintf("select count(*) as count from requests where datetime between now() - interval 24 hour and now() and allow = 1 and method_name = 'check_newuser';"));
            $stat['regs'] = number_format($row['count'], 0, ',', ' ');
            $row = $this->db->select(sprintf("select count(distinct service_id) as count from requests where datetime between now() - interval 24 hour and now();"));
            $stat['customers_online'] = number_format($row['count'], 0, ',', ' ');

            $stat['updated'] = time();
            $this->memcache->set('main_stat', $stat, null, $this->options['stat_mc_timeout']);

            $this->set_last_modified();
        } else {
            $stat['updated'] = time();
        }
        $this->page_info['stat'] = &$stat;
        $this->page_info['stat_updated'] = sprintf($this->lang['l_stat_updated'], date(DATE_W3C, $stat['updated']));

        return true;
    }

    /**
      * Функция возращает информацию об антиспам приложении
      *
      * @param string $release_version Версия релиза
      *
      * @param string $engine Платформа
      *
      * @param string $lang Язык
      *
      * @param bool $return_formated Признак
      *
      * @return array
      */

    function get_app_info($release_version = null, $engine = null, $lang = 'en', $return_formated = true) {
        $info = null;

        $rv_sql = ' order by release_date desc limit 1';
        if ($release_version)
            $rv_sql = ' and release_version = ' . $this->stringToDB($release_version);

        if (!$engine)
            return $info;

        $label = 'app_info_' . $engine . '_' . $lang;
        if ($release_version)
            $label .= '_' . $release_version;
        if ($return_formated)
            $label .= '_formated';

        $info = $this->memcache->get($label);

        if ($info)
            return $info;

        $sql = sprintf("select options, options_add, components, changelog, engine, templates, platform_versions, app_id, release_date, release_version, mobile_apps from apps where engine = '%s'%s;", $engine, $rv_sql);
        $info = $this->db->select($sql);

        if (!$info)
            return $info;

        //
        // Статус 2 - не учитывать язык метки.
        //
        $lists = array(
            'options' => 1,
            'options_add' => 1,
            'components' => 2,
            'templates' => 1
        );

        $data = null;
        foreach ($info as $k => $l) {
            $type = 'string';
            if (isset($lists[$k])) {
                $sql_lang = sprintf(' and lang = %s', $this->stringToDB($lang));
                if ($lists[$k] == 2)
                    $sql_lang = '';

                $items = $this->list_to_items($info[$k]);
                if (!$items) {
                    continue;
                }
                $sql = sprintf("select value from site_labels where label_id in (%s)%s;",
                    $items,
                    $sql_lang
                );

                $rows = $this->db->select($sql, true);
                if ($rows) {
                    $l = null;
                    foreach ($rows as $v) {
                        $l[] = $v['value'];
                    }

                    if (count($l) == 1) {
                        $l = $l[0];
                    } else {
                        $type = 'list';

                    }
                }

            }
            if ($k == 'mobile_apps' && $l !== null) {
                $type = 'list_image';
            }

            $app_info_label = 'l_app_info_' . $k;
            if (isset($this->lang[$app_info_label]) && $l) {
                $data[] = array(
                    'name' => $this->lang[$app_info_label],
                    'value' => $l,
                    'type' => $type,
                    );
            }
        }

        if ($return_formated) {
            $info = $data;
        }

        $this->memcache->set($label, $info, null, $this->options['app_info_cache_timeout']);

        return $info;
    }

    /**
      * Функция возращает информацию о скриншотах для приложения
      *
      * @param string $engine Платформа
      *
      * @param string $lang Язык
      *
      * @return array
      */

    function get_sliders($engine = null, $lang = 'en') {
        $info = null;
        if (!$engine)
            return $info;

        $label = 'sliders_info_' . $engine . '_' . $lang;

        $info = $this->memcache->get($label);
        $info = false;
        if ($info)
            return $info;

        $sliders_dir = "." . $this->options['sliders_base_dir'] . $this->page_info['engine'];
        if (!file_exists($sliders_dir))
            return $info;

        $sliders_count = 0;
        foreach (scandir($sliders_dir) as $v) {
            if (preg_match("/slide\d.png$/", $v))
                $sliders_count++;
        }
        $this->page_info['sliders_count'] = $sliders_count;
        $sliders = null;
        for ($i = 1; $i <= $sliders_count; $i++) {
            $slider_template = '%s%s/slide%d%s.png';
            $slider_path = sprintf($slider_template, $this->options['sliders_base_dir'], $this->page_info['engine'], $i, '');
            $slider_preview_path = sprintf($slider_template, $this->options['sliders_base_dir'], $this->page_info['engine'], $i, '_s');

            $slider_title = null;
            if (isset($this->lang['l_slider_title_' . $i]))
                $slider_title = $this->lang['l_slider_title_' . $i];

            $sliders[] = array(
                'file' => $slider_path,
                'file_preview' => $slider_preview_path,
                'title' => $slider_title,
            );
        }
        $info = $sliders;

        $this->memcache->set($label, $info, null, $this->options['app_info_cache_timeout']);

        return $info;
    }

    //string of keywords in article

    private function getKeywords($idKeywords)
    {
        $idKeywords = explode(', ', $idKeywords);
        $keywords = array();
        $condition = '';
        foreach ($idKeywords as $idKeyword)
        {
            $condition .= (($condition != '') ? " OR " : "" )."`keyword_id`=".$idKeyword;
        }
        $keywords = ($this->db->select(sprintf(
            "SELECT keyword 
                    FROM articles_keywords 
                    WHERE %s ORDER BY created DESC",
                    $condition),
            true));
        for($i = 0; $i<count($keywords); $i++)
        {
            $keywords[$i] = $keywords[$i]['keyword'];
        }
        return implode(', ', $keywords);
    }

    private function getWeightKeywords()
    {
        $keywordsLimit = 20;
        $keywords = array();
        $articleList = ($this->db->select(sprintf(
            "SELECT article_id, keywords
                    FROM articles"
                    ),true));
        foreach ($articleList as $article)
        {
            if (isset($article['keywords']) && $article['keywords'] != ''){
                $keywordsList = explode(', ', $article['keywords']);
                foreach ($keywordsList as $keyword)
                {
                    $keywords[] = $keyword;
                }
            }
        }
        $keywords = array_count_values($keywords);
        arsort($keywords);
        $keywords = array_slice($keywords, 0, $keywordsLimit, true);
        $keyString = array();
        foreach ($keywords as $keyword => $weight)
        {
            $keywordRow = ($this->db->select(sprintf(
                "SELECT keyword
                    FROM articles_keywords
                        WHERE keyword_id = %d", $keyword
            )));
            $keyString[$keywordRow['keyword']] = $weight;
        }
        //print_r($keyString);
        return $keyString;
    }

}
?>