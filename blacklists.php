<?php

/**
*
* Класс для работы с черными списками
*
*/

class Blacklists extends CCS {
    /**
    * @var array $bl_formats Форматы черных списков
    */
    private $bl_formats = array(
            'json',
            'serialize'
		);
    /**
    * @var string $sql_bl Запрос
    */
    private $sql_bl = "select updated as lastseen, frequency, submited, unix_timestamp(updated) as lastseen_ts, spam_rate, votes_spam, votes_not_spam from bl_%ss where %s = %s and frequency >= %d;";
    /**
    * @var string $sql_bl_email Запрос
    */
    private $sql_bl_email = "select updated as lastseen, frequency, submited, unix_timestamp(updated) as lastseen_ts, spam_rate, votes_spam, votes_not_spam, emaild_id, in_list, frequency_last, email_exists, email_exists_check_datetime from bl_%ss where %s = %s;";
    /**
    * @var string $sql_bl_domain Запрос
    */
    private $sql_bl_domain = "select updated as lastseen, frequency, submited, unix_timestamp(updated) as lastseen_ts, in_list from bl_%ss where %s = %s and frequency >= %d;";
    /**
    * @var string $sql_bl_ip Запрос
    */
    private $sql_bl_ip = "
    select ip, ip.updated as lastseen, frequency, ip.asn_id, ip.submited, unix_timestamp(ip.updated) as lastseen_ts, spam_active_asn, hostname, network_dec, ip.network_id, ip.spam_rate, ip.votes_spam, ip.votes_not_spam, ip.in_list, ip.in_sfw, ip.frequency_last,ipn.in_sfw as in_sfw_network from bl_ips ip left join bl_ips_networks ipn on ip.network_id = ipn.network_id where ip = inet_aton(%s);
    ";
    /**
    * @var string $sql_bl_ip_like Запрос
    */
    private $sql_bl_ip_like = "select updated as lastseen, frequency, cast(submited as date) as submited, unix_timestamp(updated) as lastseen_ts, inet_ntoa(ip) as ip from bl_ips where inet_ntoa(ip) like '%s%%' and (frequency >= %d or spam_active_asn = 1);";
    /**
    * @var string $sql_bl_email_like Запрос
    */
    /**
    * @var string $sql_bl_ip Запрос
    */
    private $sql_bl_ip_v6 = "
	select inet6_2ntoa(ip6_left, ip6_right), ip.updated as lastseen, frequency, ip.submited, unix_timestamp(ip.updated) as lastseen_ts, ip.in_list, ip.in_sfw from bl_ips_v6 ip where inet6_2ntoa(ip6_left, ip6_right) = %s;
";
    private $sql_bl_email_like = "select updated as lastseen, frequency, cast(submited as date) as submited, email from bl_emails where email like '%%%s%%' and frequency >= %d limit %d;";
    /**
    * @var string $sql_bl_domain_like Запрос
    */
    private $sql_bl_domain_like = "select updated as lastseen, frequency, cast(submited as date) as submited, domain from bl_domains where domain like '%%%s%%' and frequency >= %d limit %d;";
    /**
    * @var string $sql_bl_asn_like Запрос
    */
    private $sql_bl_asn_like = "select updated as lastseen, cast(submited as date) as submited, org_name, asn_id from asn where org_name like '%%%s%%' limit %d;";

    /**
    * @var array $bl_response Массив с данными для выдачи на запрос по черным спискам
    */
    private $bl_response = null;

    /**
    * @var string $show_page Локальная страница
    */
    private $show_page = null;

    /**
    * @var array $bl_record_types Типы записей чёрных списков
    */
    private $bl_record_types = array ('ip' => true, 'email' => true, 'domain' => true);

    /**
    * @var string $serp_postfix Разделитель
    */
    private $serp_postfix = '|';

    /**
    * @var bool $direct_search Флаг поиска по базе через строку поиска
    */
    private $direct_search = false;

    /**
    * @var bool $skip_bl_rate_increment Флаг запрещающий инкрементацию счетчика запросов к базе
    */
    private $skip_bl_rate_increment = false;

    /**
    * @var string $format Флаг API запроса к черным спискам
    */
    private $format = null;

    /**
    * @var string $date_format Формат отображения даты на сайте
    */
    private $date_format = "M d, Y H:i:s";

    /**
    * @var bool $blackseo Режим проверки сайта на черное SEO.
    */
    private $blackseo = false;

	function __construct(){
		parent::__construct();
	}

    /**
      * Отображение страницы
      *
      * @param bool $ajax Используется ли ajax
      *
      * @return string
      */

	function show_page($ClassNotFound = false, $ajax = false) {

		$this->ccs_init();

        if(isset($_GET['action']) && $_GET['action']=='get-saod'){
            $this->get_spam_activity_on_date();
        }

        // Сохраняем отзыв

        if (count($_POST) && isset($_POST['review_feature']) && $_POST['review_feature'] == 1){

            // Временная защита от спама
            $banned_ips = array('185.19.20.246');

            if (in_array($_SERVER['REMOTE_ADDR'], $banned_ips))
                exit();

            $info = null; // Обработанный массив

            $info = $this->safe_vars($_POST);

            $letters = array('q','w','e','r','t','y','u','i','o','a','s','d','f','g','h','j','k','z','x','c','v','b');

            // Если неформат email введённый пользователем
            // или если значения review отличаются от 0 и 1
            // то не даём сохранять отзыв

            // Редактирование
            if (isset($_POST['edit_review']) && $_POST['edit_review'] == 1) {
              $edit_review_sql = sprintf("update bl_reviews 
                                          set vote = %d, review = %s
                                          where review_author_token = %s",
                                          preg_replace('/[^0-9]/i', '', $info['editspamreview']),
                                          $this->stringToDB($info['revtext']),
                                          $this->stringToDB(preg_replace('/[^a-z]/i', '', $info['edit_review_token'])));
              $this->db->run($edit_review_sql);
              apc_delete(str_replace(array('.','@'),'', $info['edit_record']));
              header('Location: '.$_SERVER['HTTP_REFERER'].'#reviewanchor');
              exit();

            }
            // Вставка нового
            else {

                if ($_POST['revyear'] != date('Y')){
                    header('Location: '.$_SERVER['HTTP_REFERER']);
                    exit();
                }

                if (!$this->valid_email($info['revemail']))
                    {
                        header('Location: '.$_SERVER['HTTP_REFERER']);
                        exit();
                    }

                // если запись к которой добавляется отзыв не email и не ip не даём сохранять отзыв
                if ($this->valid_email($info['review_record']) || preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/i',$info['review_record'])){
                    // Проверяем есть ли запись с таким IP|email и email оставляющего комментарий
                    $checkrecord = $this->db->select(sprintf("select email,record 
                                                              from bl_reviews
                                                              where email = %s
                                                              and record = %s",
                                                              $this->stringToDB($info['revemail']),
                                                              $this->stringToDB($info['review_record'])));
                    if ($checkrecord['email'] == $info['revemail'] && $checkrecord['record'] == $info['review_record']){
                        setcookie('noreview', 1, time() + 24*60*60, '/', $this->cookie_domain);
                        $wrongemailkey = rand(0,1000000);
                        apc_store('bn'.$wrongemailkey,$info['revname']);
                        apc_store('bt'.$wrongemailkey,$info['revtext']);
                        header('Location: '.$_SERVER['HTTP_REFERER']."?wrongemailkey=".$wrongemailkey."#noreviewanchor");
                        exit();
                    }

                    if (!$this->checks->antispam($info['revname'], $info['revemail'], $info['revtext'])) {
                        header('Location: '.$_SERVER['HTTP_REFERER']);
                        exit();
                    }

                    $review_sender_ip = $_SERVER['REMOTE_ADDR'];
                    $review_submitted = date('Y-m-d H:i:s');
                    $review_author_token = '';
                    for ($i=0;$i<10;$i++)
                        $review_author_token .= $letters[rand(0,count($letters)-1)];
                    $review_sql = sprintf("insert into bl_reviews 
                                           (bl_review_id, submitted, vote, nickname, email, review, 
                                           sender_ip, record, review_author_token)
                                           values (NULL, %s, %d, %s, %s, %s, %s, %s, %s)",
                                           $this->stringToDB($review_submitted),
                                           preg_replace('/[^0-9]/', '', $info['spamreview']),
                                           $this->stringToDB($info['revname']),
                                           $this->stringToDB($info['revemail']),
                                           $this->stringToDB($info['revtext']),
                                           $this->stringToDB($review_sender_ip),
                                           $this->stringToDB($info['review_record']),
                                           $this->stringToDB($review_author_token)
                                         );
                    $newreviewid = $this->db->run($review_sql);

                    setcookie('review', $review_author_token, time() + 24*60*60, '/', $this->cookie_domain);
                    apc_delete(str_replace(array('.','@'),'',$info['review_record']));
                    $revheaders  = 'MIME-Version: 1.0' . "\r\n";
                    $revheaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $revheaders .= 'From: Blacklists <noreply@cleantalk.org>' . "\r\n";
                    $revsubject = 'Отзыв в черных списках от '.$info['revemail'];
                    $revmessage = 'Привет<br><br>Мы получили новый отзыв от '.$info['revname'];
                    $revmessage.= ' ('.$info['revemail'].', '.$review_sender_ip.').';
                    $revmessage.= '<br><br>Текст отзыва<br><br>'.str_replace("\n",'<br>',$info['revtext']);
                    $revmessage.= '<br><br><a href="https://cleantalk.org/blacklists/'.$info['review_record'].'#anc'.$newreviewid.'">https://cleantalk.org/blacklists/'.$info['review_record'].'#anc'.$newreviewid.'</a>';
                    mail('welcome@cleantalk.org',$revsubject,$revmessage,$revheaders);
                    header('Location: '.$_SERVER['HTTP_REFERER'].'#anc'.$newreviewid);
                    exit();
                }
            }
        }

        $this->page_info['show_footer'] = true;
        $this->page_info['show_footer_lp'] = false;
        $this->page_info['show_html5'] = true;
        $this->page_info['new_design'] = true;
        $this->page_info['lang'] = $this->ct_lang;
        $this->page_info['show_benefits'] = false;
        $this->page_info['show_bl_stat'] = true;
        $this->page_info['show_bl_emails_pages'] = cfg::show_bl_emails_pages;
        $this->page_info['bread_crumb_name'] = $this->lang['l_reports_name'];
        $this->page_info['scripts_disable_general'] = true;

        $this->page_info['async_scripts'] = array(
            cfg::static_host_url . '/js/review.min.js?v=01122015'
        );

        if (isset($_GET['imgip'])) {
          $ip = strip_tags($_GET['imgip']);
          $image = imagecreatetruecolor(150, 40);
          $background_color = imagecolorallocate($image,255, 255, 255);
          imagefilledrectangle($image,0,0,200,50,$background_color);
          $text_color = imagecolorallocate($image, 0,0,0);
          imagestring($image,5,5,10,$ip,$text_color);
          header('Content-type: image/png');
          imagepng($image);
          imagedestroy($image);
          exit();
        }

        // Актвируем переключение страницы между языками
        $this->page_info['show_local_translate'] = true;
        $this->page_info['show_translate'] = false;
		$this->page_info['show_feedback'] = false;
		$this->page_info['show_ga'] = true;
		$this->page_info['index_bl_emails'] = cfg::index_bl_emails;
		$this->page_info['index_bl_domains'] = cfg::index_bl_domains;

        if (isset($this->lang['l_bl_head']))
            $this->page_info['head']['title'] = strip_tags($this->lang['l_bl_head']);

        $this->get_lang($this->ct_lang, 'FirstPage_main');

        switch ($this->link->id) {
            case '84':
                $this->show_spambots_check();
                $this->link->template = 'bootstrap/spambots-check.html';
                $this->page_info['scripts'] = array(
                    '/js/bootstrap.file-input.js',
                    '//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js',
                    '//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js',
                    '//cdn.datatables.net/plug-ins/1.10.16/sorting/ip-address.js',
                );
                break;
            default:
                $this->link->template = 'bootstrap/blacklists/index.html';
                $this->show_blacklists();
                break;
        }

        // Если запрос является AJAX, то выводим результат без основной "обертки" сайта
		$template = null;
		if ($this->ajax)
 			$template = $this->link->template;

        if ($this->link->id == 66 && $this->format !== false)
            $template = 'bl_packed.html';

        if (!$template)
            $template = 'includes/general.html';

        // Сохраняем информацию об источнике захода на сайт
        $this->set_lead_source(!isset($_COOKIE['lead_source']) ? 'blacklists' : null, null, true);

        $this->page_info['auth_user'] = $this->is_auth;

        $this->display($template);
	}

    /**
      * Функция массовой проверки данных в черных списках
      *
      * @return array
      */

    function show_spambots_check() {

        $this->page_info['register_url'] = '/register?utm_source=cleantalk.org&amp;utm_medium=spambots_check_purchase&amp;utm_campaign=bl_landing&amp;lead_source=spambots_check&product_name=database_api';

        $restricted = true;
        $auth_key = null;
        if ($this->user_id) {
            $row = $this->db->select(sprintf("select service_id, auth_key from services where user_id = %d and moderate_service = 1 and product_id = 2", $this->user_id));
            if ($row) {
                $restricted = false;
                $auth_key = $row['auth_key'];
            }
        }
        $this->page_info['restricted'] = $restricted;

        if (isset($this->lang['l_spambots_check']))
            $this->page_info['head']['title'] = strip_tags($this->lang['l_spambots_check_title']);

        $tools = new CleanTalkTools();
        $check_label = 'bl_bulk_check_';

        $info = $this->safe_vars($_POST);

        $records = null;
        $records_str = '';
        $records_hash = 0;
        if (isset($_FILES['list']['tmp_name']) && $_FILES['list']['tmp_name'] !== '') {
            $records_str = @file_get_contents($_FILES['list']['tmp_name']);
            if (!$records_str) {
                $records_str = '';
            }
        }

        if (isset($info['list_form']) && $info['list_form'] != '') {
            $records_str .= $info['list_form'];
        }

        if (isset($_GET['packet']) && preg_match("/^[a-f0-9]{1,32}$/", $_GET['packet'])) {
            $records_hash = $_GET['packet'];
        }

        if ($records_str != '') {
            $records_hash = dechex(crc32($records_str));
        }
        $check_label .= '_' . $records_hash;
        $records = $this->memcache->get($check_label);
        if(!$records){
            $input_data = $this->memcache->get($check_label.'_input');
            if(!empty($input_data)){
                $records_str = $input_data;
            }
        }
        if(!empty($records_str)){
            $this->memcache->set($check_label.'_input', $records_str, null, $this->options['mass_packet_search_store']);            
        }
//        $records = false;

        $new = false;
        if (!$records && $records_str != '') {
            // Ограничение на вызов метода раз в минуту
            if ($restricted && apc_exists('spam_check_limit_' . $_SERVER['REMOTE_ADDR'])) {
                $this->page_info['error_message'] = 'Please wait for 15 minute before send a new list or get a premium account to send lists without limits.';
                $this->page_info['packet_check_hint'] = sprintf($this->lang['l_packet_check_hint'],
                    number_format($this->options['max_packet_search_limit'], 0, '.', ' ')
                );
                $this->page_info['pay_for_full_access'] = sprintf($this->lang['l_pay_for_full_access'],
                    number_format($this->options['max_packet_search_limit'], 0, '.', ' ')
                );
                $this->page_info['pay_for_full_access_hint'] = sprintf($this->lang['l_pay_for_full_access_hint'],
                    number_format($this->options['max_packet_search_limit'], 0, '.', ' '),
                    cfg::spambots_check_free_records_limit
                );
                $this->get_mc_counters();
                if (isset($this->bl_counts['ips']) && isset($this->bl_counts['emails'])) {
                    $this->page_info['test_over_n'] = sprintf($this->lang['l_test_over_n'],
                        number_format($this->bl_counts['ips'] + $this->bl_counts['emails'], 0, '.', ' ')
                    );
                }
                $this->page_info['info']['list_form'] = $records_str;
                if(!empty($records_hash)){
                    $this->page_info['records_hash'] = $records_hash;
                }
                
                return;
            } else if ($restricted) {
                apc_store('spam_check_limit_' . $_SERVER['REMOTE_ADDR'], true, 15*60);
            }

            $separator = '__SEPARATOR__';
            $records_str = preg_replace("/[\ ,;\t\n]/", $separator, $records_str);
            foreach (explode($separator, $records_str) as $v) {
                // Хак для удаления перевода строки
                $v = preg_replace("/\s$/", "", $v);

                $v = str_replace("'", "", $v);
                $v = str_replace("\"", "", $v);

                if (!preg_match("/^[^\s]+$/", $v)) {
                    continue;
                }
                $records[] = $v;
            }
            $ips = null;
            $emails = null;
            $items = null;
            if ($records) {
                $i = 0;
                foreach ($records as $k => $record) {
                    if ($i >= $this->options['max_packet_search_limit']) {
                        unset($records[$k]);
                        continue;
                    }

                    $type = null;
                    $blacklisted = false;
                    if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $record)) {
                        $type = 'ip';
                    }
                    if (!$type && $this->valid_email($record)) {
                        $type = 'email';
                    }
                    if (!$type && preg_match("/^([^\s]+)\.([^\s]+)$/", $record)) {
//                        $type = 'domain';
                    }

                    if ($type) {
                        if ($restricted && $i >= cfg::spambots_check_free_records_limit) {
                            $records[$k] = array(
                                'record' => $record,
                                'blacklisted' => 0,
                                'human_status' => $this->user_id ? strtoupper($this->lang['l_renew_license']) : strtoupper($this->lang['l_get_full_access']),
                                'restricted' => true,
                                'type' => $type,
                                'frequency' => 'Unknown'
                            );
                        } else {
                            if (false) {
                                $record_label = 'bl_' . $type . '_f:' . $record;
                                $blacklisted = $this->memcache_db->get($record_label);
                                if ($blacklisted === false || (int) $blacklisted != 1) {
                                    $blacklisted = 0;
                                }
                            }

                            $blacklisted = (int) $blacklisted;

                            $records[$k] = array(
                                'record' => $record,
                                'blacklisted' => $blacklisted,
                                'human_status' => $blacklisted ? $this->lang['l_blacklisted'] : $this->lang['l_not_in_list'],
                                'css_class' => $blacklisted ? 'red_text' : 'green_text',
                                'restricted' => false,
                                'type' => $type
                            );
                            $items[] = $record;
                            switch($type) {
                                case 'ip':
                                    //$ips[] = ip2long($record);
                                    $ips[] = $record;
                                    break;
                                case 'email':
                                    $emails[] = $record;
                                    break;
                            }

                        }
                        $i++;
                    } else {
                        unset($records[$k]);
                    }
                }
                $new = true;
            }

            $public_fields = array(
                'frequency' => 0,
                'country' => '-',
                'countryname' => '-'
            );
            /*$r_db = null;
            if ($ips) {
                $sql_ip = sprintf("select ip.ip,ip.frequency,ip.in_list,asn.asn_id,asn.org_name,asn.country,asn.spam_rate,ip.frequency from bl_ips ip left join asn on asn.asn_id = ip.asn_id where ip in (%s);",
                    implode(",", $ips)
                );
                $rows = $this->db->select($sql_ip, \true);
                foreach ($rows as $v) {
                    $r_db['ip'][long2ip($v['ip'])] = $v;
                }

            }
            if ($emails) {
                $sql_emails = '';
                foreach ($emails as $v) {
                    if ($sql_emails != '') {
                        $sql_emails .= ',';
                    }
                    $sql_emails .= $this->stringToDB($v);
                }
                $sql_email = sprintf("select e.email,e.in_list,ed.spam_rate,e.frequency from bl_emails e left join emaild ed on ed.emaild_id = e.emaild_id where email in (%s);",
                    $sql_emails
                );
                $rows = $this->db->select($sql_email, \true);
                foreach ($rows as $v) {
                    $r_db['email'][$v['email']] = $v;
                }

            }*/
            if ($items) {
                $response = array();
                try {
                    $parts = array_chunk($items,1000);
                    foreach ($parts as $part) {
                        $response = array_merge($response,$this->checks->spam_check($part, $auth_key));
                    }
					// $response = $this->checks->spam_check($items, $auth_key);

                } catch (Exception $e) {
                    $this->page_info['error_message'] = $e->getMessage();
                    return;
                }

                $ip_info = array();
                if(!empty($ips)){
                    try {
                        $parts = array_chunk($ips,1000);
                        foreach ($parts as $part) {
                            $ip_info = array_merge($ip_info,$this->checks->ip_info($part, $auth_key));
                        }
                        // $ip_info = $this->checks->ip_info($ips, $auth_key);
                    } catch (Exception $e) {
                        $this->page_info['error_message'] = $e->getMessage();
                    }
                }
                foreach ($records as $k => &$v) {
                    $v['updated'] = isset($response[$v['record']]) ? $response[$v['record']]['updated'] : false;
                    foreach ($public_fields as $k2 => $v2) {
                        $v[$k2] = $v['restricted'] ? '-' : $v2;
                    }
                    if (isset($ip_info[$v['record']])) {
                        $v['country'] = $ip_info[$v['record']]['code'];
                        $v['countryname'] = $ip_info[$v['record']]['name'];
                    }
                    $blacklisted = 0;
                    if (isset($response[$v['record']])) {
                        $blacklisted = $response[$v['record']]['appears'];
                    }
                    $v['frequency'] = isset($response[$v['record']]) ? $response[$v['record']]['frequency'] : false;
                    if ($v['restricted']) {
                        $v['css_class'] = 'grey_text';
                    } else {
                        $v['blacklisted'] = $blacklisted;
                        $v['css_class'] = $blacklisted ? 'red_text' : 'green_text';
                        $v['human_status'] = $blacklisted ? $this->lang['l_blacklisted'] : $this->lang['l_not_in_list'];
                        if(isset($response[$v['record']]['exists'])){
                            if($response[$v['record']]['exists']===1){
                                $v['exists'] = 'True';
                            }elseif($response[$v['record']]['exists']===0){
                                $v['exists'] = 'False';
                            }else{
                                $v['exists'] = '-';    
                            }
                        }else{
                            $v['exists'] = '-';
                        }
                    }
                    
                }
            }

            $log_file = "./" . cfg::logs_dir . "bl_packets.log";
            $to_file = sprintf("%s\t%s\t%s\n", date("Y-m-d H:i:s"), $this->remote_addr, $records_hash);
            $tools->write_to_log_file($log_file, $to_file);

            $this->post_log(sprintf("Пакетный поиск #%s, записей %d.", $records_hash, count($records)));
        }

        if ($records && count($records)) {
            $this->memcache->set($check_label, $records, null, ($this->user_id && !$restricted) ? $this->options['mass_packet_search_store'] : 10 );

            if ($new && $records_hash) {
                setcookie('records_hash', $records_hash, strtotime("+7 days"), '/', $this->cookie_domain);
                header("Location:?packet=" . $records_hash);
                exit;
            }

            //
            // Export data
            //
            if (isset($_GET['packet_format']) && preg_match("/^[^\s]{1,32}$/", $_GET['packet_format'])) {

                foreach ($records as $k => $v) {
                    unset($v['human_status']);
                    unset($v['css_class']);
                    $records[$k] = $v;
                }

                $output = null;
                $extension = null;
                switch ($_GET['packet_format']) {
                    case 'csv':
                        $output = $tools->array2csv($records);
                        break;
                    case 'json':
                        $output = json_encode($records);
                        break;
                }
                if ($output) {
                    $extension = strtolower($_GET['packet_format']);
                    $tools->download_send_headers("spambots-check-" . $records_hash . "." . $extension);
                    echo $output;
                    exit();
                }
            }
            
            if ($restricted && count($records) > cfg::spambots_check_free_records_limit){
                $check_records_count = cfg::spambots_check_free_records_limit;
            }else{
                $check_records_count = count($records);
            }
            $spam_records_count = 0;
            $blacklisted_records_count = 0;
            foreach ($records as $r) {
                if($r['blacklisted'])$blacklisted_records_count++;
                if($r['frequency'])$spam_records_count++;
            }
            $this->page_info['records'] = &$records;
            $this->page_info['records_count'] = count($records);
            $this->page_info['table_title'] = sprintf($this->lang['l_blacklist_check_title'], $check_records_count,$spam_records_count,$blacklisted_records_count);
            $this->page_info['records_hash'] = $records_hash;
            $this->page_info['head']['title'] = $this->lang['l_packet'] . ' #' . $records_hash . ' | ' . $this->page_info['head']['title'];

            if (!isset($info['list_form']) || $info['list_form'] == ''){
                $info['list_form'] = '';
                foreach ($records as $v) {
                    if ($info['list_form'] != '') {
                        $info['list_form'] .= ', ';
                    }
                    $info['list_form'] .= $v['record'];
                }
            }
        } else {
            $this->get_mc_counters();
            $this->page_info['bl_stat'] = $this->get_bl_stat();

            if ((!$info || count($info) == 0) && isset($_GET['record'])) {
                $info['list_form'] = $_GET['record'];
            }

        }

        $this->page_info['info'] = &$info;
        $this->page_info['packet_check_hint'] = sprintf($this->lang['l_packet_check_hint'],
            number_format($this->options['max_packet_search_limit'], 0, '.', ' ')
            );
        $this->page_info['pay_for_full_access'] = sprintf($this->lang['l_pay_for_full_access'],
            number_format($this->options['max_packet_search_limit'], 0, '.', ' ')
        );
        $this->page_info['pay_for_full_access_hint'] = sprintf($this->lang['l_pay_for_full_access_hint'],
            number_format($this->options['max_packet_search_limit'], 0, '.', ' '),
            cfg::spambots_check_free_records_limit
        );

        $this->get_mc_counters();
        if (isset($this->bl_counts['ips']) && isset($this->bl_counts['emails'])) {
            $this->page_info['test_over_n'] = sprintf($this->lang['l_test_over_n'],
                number_format($this->bl_counts['ips'] + $this->bl_counts['emails'], 0, '.', ' ')
            );
        }

        $this->page_info['banner_button_val'] = 'spambots_check_purchase';
        //var_dump($this->page_info); exit();

        return null;
    }


    /**
      * Функция работы с черными списками
      *
      * @return array
      */

    function show_blacklists() {

        $format = isset($_GET['format']) && in_array($_GET['format'], $this->bl_formats) ? $_GET['format'] : false;
        $this->format = &$format;

        /*
            Логика ограниченя частоты запросов к черным спискам
        */
        $requests = 0;
        $skip_rate = null;
        $bl_rate_fail = false;
        $max_bl_rate_fail = $this->options['max_bl_rate'];
        if ($format) {
            $max_bl_rate_fail = $this->options['max_bl_rate_api'];
        }

        $asn = null;
        $skip_rate_label = null;
        if ($this->remote_addr) {
            $skip_rate_label = 'skip_rate:' . $this->remote_addr;
            $skip_rate = apc_fetch($skip_rate_label);

        }

        if ($skip_rate !== 0 && $skip_rate !== 1 && $skip_rate_label) {
            $skip_rate = 0;
            $sql = sprintf("select asn_id, network_id, network_dec,spam_rate,in_sfw from bl_ips_networks where network = %d & mask limit 1;",
                ip2long($this->remote_addr)
            );
            $nets = $this->db->select($sql);
            if (isset($nets['asn_id'])) {
                $remote_addr_asn_id = $nets['asn_id'];
                $not_rated_as = explode(",", $this->options['not_rated_as']);
                foreach ($not_rated_as as $asn_id) {
                    if ($skip_rate)
                        continue;

                    if ($asn_id == $remote_addr_asn_id)
                        $skip_rate = 1;
                }
            }
            apc_store($skip_rate_label, $skip_rate, cfg::apc_cache_lifetime);
        }

        $bl_rate_prefix = 'bl_rate_' . $this->remote_addr;
        if ($skip_rate !== 1) {
            $bl_rate = $this->memcache->get($bl_rate_prefix);
            $bl_rate[] = time();
            foreach ($bl_rate as $v) {
                if (time() - $v < cfg::bl_rate_timeout)
                    $requests++;
            }

            if ($requests > $max_bl_rate_fail) {
                $bl_rate_fail = true;
                $logged_label = $bl_rate_prefix . '_logged';
                $logged = (int) $this->memcache->get($logged_label);
                if ($logged === 0 && cfg::debug) {
                    error_log(sprintf('Reached maximum queires rate to blacklists %s.', $this->remote_addr));
                    $this->memcache->set($logged_label, 1, null, cfg::bl_rate_timeout * 30);
                }
            }
        }

        if(isset($_GET['action']) && $_GET['action']=='get-api-response'){
            $this->get_api_response($bl_rate_fail);
        }elseif ($format)
            $this->show_bl_packed($format, $bl_rate_fail);
        else
            $this->show_bl($bl_rate_fail, $requests);

        //
        // Дабы не учитывать AJAX запросы к сервису
        //
        if (!$this->skip_bl_rate_increment && !$skip_rate) {
            $this->memcache->set($bl_rate_prefix, $bl_rate, null, cfg::bl_rate_timeout);
        }
        if(isset($_GET['action']) && $_GET['action']=='get-api-response'){
            exit;
        }
        return null;
    }

    /**
      * Функция вывода информации черных списков
      *
      * @return array
      */

    function show_bl($bl_rate_fail = false, $requests = 0){
        // Флаг регулирующий запись в журнал запросы к черным спискам
        $log_record = false;

        $this->page_info['show_description'] = true;
        $this->page_info['show_search'] = true;
        $this->page_info['hide_trial_notice'] = true;
        $this->page_info['show_tools_js'] = true;
        $this->page_info['show_mootools'] = false;
        $this->page_info['show_mash_check'] = true;
        $this->page_info['utm_label'] = '?utm_source=cleantalk.org&amp;utm_medium=signup_top_button&amp;utm_campaign=bl_landing';
        $this->page_info['show_domains_in_details'] = $this->options['show_domains_in_details'];
        $this->page_info['bottom_button_val'] = 'bottom_button';
        $this->page_info['banner_button_val'] = 'banner_button';

        $this->ajax_template = 'ajax/free_search.html';

        // Значение по-умолчанию для строки поиска по черным спискам
        $this->page_info['record'] = '';

        $this->page_info['bl_default'] = messages::bl_default;

        $this->blackseo = isset($_GET['blackseo']) ? true : false;

        if ($this->blackseo) {
            $this->page_info['bl_default'] = $this->lang['l_website_url'];
            $this->page_info['show_new_domains'] = true;
        }
   
        $this->get_apps(false);
        $this->get_api();

        $this->get_mc_counters();
        $this->page_info['bl_stat'] = $this->get_bl_stat(
            $this->blackseo
        );

        $record = null;
        $bl_search_label = 'bl_search';
        if (isset($_GET['record'])) {
            $record = htmlentities($_GET['record']);
            if(!strstr($record,'AS') && preg_match('/^[0-9]+$/i',$record,$recordmatches))
                $record = 'AS'.$record;
            $this->page_info['show_bl_stat'] = false;

        }

        if (isset($_COOKIE[$bl_search_label]) && $_COOKIE[$bl_search_label] == $record) {
            $this->direct_search = true;
        }

        // Удаляем из запроса параметры дабы работала логика определения записи
        $uri = $_SERVER['REQUEST_URI'];
        $uri = preg_replace("/(\?.+)$/", "", $uri);

        // Пример запроса: http://localhost:8888/blacklists/buscemi%40hotmail.com
        if ($record === null && preg_match("#^/blacklists/([^\s]+)$#", $uri, $matches)) {
            $record = urldecode($matches[1]);
            $log_record = false;
            $this->page_info['show_bl_stat'] = false;
        }

        //
        // Убираем из имени домена служебные символы
        // http://www.earcon.org/
        //
        if ($this->direct_search || $this->ajax) {
            $record = preg_replace("#^(https?)://([^/\r\n]+)(/[^\r\n]*)?#", "$2", $record);
        }

        //
        // Отчключил вывод тега в рамках эксперемента с индексацией сайта.
        // Шагимуратов. 17.11.2016.
        //
        if ($record && false) {
            $this->page_info['rel_canonical'] = 'https://cleantalk.org/blacklists/' . urlencode($record);
        }

        if ($record === null || $record == messages::bl_default || $record == '' || strlen($record) > 1024) {
            $this->get_bl_new_submited();
            $this->page_info['record_main_title'] = $this->lang['l_bl_head'];
            $this->page_info['show_top_go_main_link'] = false;
            $this->page_info['jsf_focus_on_field'] = 'record';
            $this->page_info['show_main_crumbs'] = true;

            if (!$this->is_auth) {
                $this->page_info['show_apps'] = 'record';
                $this->page_info['show_benefits'] = $record == null ? false : true;
                $this->get_lang($this->ct_lang, 'SimplePage');
                $this->page_info['show_bl_lp'] = true;
                $this->page_info['show_trailer'] = true;
            }

            $this->show_bl_stat();

            return false;
        }
     	
		// Дабы не задосить сервер ограничивиаем количество запросов с одного IP-адреса
        if ($bl_rate_fail === true) {
			$this->page_info['bl_message'] = 'Reached maximum queires rate to blacklists. Please wait a while or use Database API for unlimited checks, <br /><br />
				<a href="/register?product_name=database_api">Database API</a>';
            $this->page_info['record'] = &$record;
            $this->page_info['show_benefits'] = false;
            $this->page_info['show_description'] = false;
            $this->page_info['hide_bl_menu'] = true;
            $this->page_info['banner_button_val'] = 'banner_button_limit';
            header('HTTP/1.0 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: ' . cfg::bl_rate_timeout);
            $this->link->template = 'bootstrap/blacklists/view.html';

            return true;
        }

        if (cfg::bl_search_free !== true && $this->direct_search === true && $this->is_auth === false) {
            $this->page_info['need_auth'] = true;
            $this->page_info['bl_need_auth'] = sprintf($this->lang['l_bl_need_auth'], $_SERVER['REQUEST_URI']);
            $this->page_info['record'] = &$record;

            $this->page_info['show_apps_lp'] = false;
			$this->get_lang($this->ct_lang, 'Register');

            $this->get_tariffs();
            $this->page_info['trial_notice'] = sprintf($this->lang['l_trial_notice'], cfg::free_days);
            $this->page_info['submit_btn'] = sprintf($this->lang['l_create_account']);

            $this->page_info['engine'] = 'unknown';

            // Для продуктивного сервера делаем редирект на https, дабы работа авторизация с главной страницы сайта
            $this->page_info['http_prefix'] = 'http';
            if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] != 'localhost' && $_SERVER['SERVER_NAME'] != 'localhost,' )
                $this->page_info['http_prefix'] = 'https';

            return false;
        }

        $tools = new CleanTalkTools();
        $record = addslashes($record);
        $record = trim($record);
        $record = strip_tags($record);

        // Делаем выборку по отзывам
        $this->all_reviews = 0;
/*
        if ($this->valid_email($record) || preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/i',$record)){
            if (isset($_COOKIE['noreview']) && $_COOKIE['noreview'] == 1) {
                $this->page_info['noreview'] = 1;
                setcookie('noreview', 1, time() - 24*60*60, '/', $this->cookie_domain);
            }
            $review_key = str_replace(array('.','@'),'',$record);
            $reviews = apc_fetch($review_key);
            if ($reviews === false) {
                $review_sql = sprintf("select bl_review_id as revid,submitted, vote, nickname, email,
                                       review, review_author_token
                                       from bl_reviews 
                                       where record = %s
                                       and approved = 1
                                       order by submitted desc;", $this->stringToDB($record));
                $reviews = $this->db->select($review_sql, true);

                foreach($reviews as $i => $onereview){
                    $reviews[$i]['submitted'] = date('F d, Y',strtotime($reviews[$i]['submitted']));
                    $reviews[$i]['gaimg'] = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $reviews[$i]['email'] ) ) ) . "?s=36";
                }
                apc_store($review_key, $reviews, cfg::apc_cache_lifetime);

            }
            if (isset($_COOKIE['review']))
                $this->page_info['review_token'] = preg_replace('/[^a-z]/i', '', $_COOKIE['review']);
            else
                $this->page_info['review_token'] = '';
            $this->page_info['newreview'] = true;
            $this->spam_reviews = 0;
            $this->all_reviews = count($reviews);
            foreach($reviews as $i => $review){
                $reviews[$i]['review'] = str_replace("\n", '<br>', $this->make_link($reviews[$i]['review']));
                if ($review['vote'] == 1)
                    $this->spam_reviews++;
            }
            foreach($reviews as $review){
                if ($this->page_info['review_token'] == $review['review_author_token']){
                    $this->page_info['newreview'] = false;
                    break;
                }
            }

            $this->page_info['reviews'] = $reviews;
            if (isset($_GET['wrongemailkey'])){
                $wrongemailkey = preg_replace('/[^0-9]/i', '', $_GET['wrongemailkey']);
                $this->page_info['revname'] = apc_fetch('bn'.$wrongemailkey);
                $this->page_info['revtext'] = apc_fetch('bt'.$wrongemailkey);
                apc_delete('bn'.$wrongemailkey);
                apc_delete('bt'.$wrongemailkey);
            }


        }
 */
        $this->page_info['modal'] = false;
        // To do not show the banner to Search engines.
        if (isset($_COOKIE['js_year']) && date("Y") == $_COOKIE['js_year']) {
            if (!$this->is_auth && !$this->app_mode) {

				$bl_views_count = 1;
				$bl_views_count_label = 'bl_views_count';
				if (isset($_COOKIE[$bl_views_count_label]) && preg_match("/^\d+$/",$_COOKIE[$bl_views_count_label])) {
					$bl_views_count = $_COOKIE[$bl_views_count_label];
					$bl_views_count++;
					if ($bl_views_count >= cfg::bl_views_show_banner) {
						$this->page_info['modal'] = true;
					}
				}
               	setcookie($bl_views_count_label, $bl_views_count, strtotime('+3 month'), '/');
            }
        }
        if (isset($_COOKIE['nobanner'])) {
            $this->page_info['modal'] = false;
        }

        $services_count = $this->get_services_count();
        $this->page_info['join_more_than'] = sprintf($this->lang['l_modal_1'],
                                             number_format($services_count, 0, '', ' '));

        if ($this->is_auth && !isset($_COOKIE['nobanner']))
            setcookie('nobanner', 1, time() + 7*24*60*60,'/');

        $this->show_page = $record;

        if (isset($_GET['action'])) {
            $record_delete_secret_key = md5(cfg::record_delete_secret_key + $this->remote_addr + $record);
            $record_type = isset($_GET['record_type']) && isset($this->bl_record_types[$_GET['record_type']]) ? $_GET['record_type'] : null;

            $bl_js_test = false;
            if (isset($_POST['bl_js_test'])) {
                if ($_POST['bl_js_test'] == $record_delete_secret_key) {
                    $bl_js_test = true;
                } else {
                    error_log(sprintf('Неизвестный ключ bl_js_test (%s), IP %s.', __FILE__, $this->remote_addr));
                    sleep(cfg::fail_timeout);
                }
            }

            switch($_GET['action']) {
                case 'delete':

                    $this->page_info['show_delete_question'] = true;
                    $this->page_info['record_delete_question'] = sprintf($this->lang['l_record_delete_question'], $record);
                    $this->page_info['record'] = &$record;
                    $this->page_info['hide_bl_menu'] = true;
                    $this->page_info['show_mootools'] = true;
                    $this->page_info['show_bl_stat'] = false;

                    $this->page_info['record_delete_secret_key'] = $record_delete_secret_key;
                    break;
                case 'deleted':
                    $r_delete = $this->db->select(sprintf("select record, action, delete_time from bl_record_remove where record = %s;",
                            $this->stringToDB($record)));

                    $this->page_info['show_deleted_info'] = true;
                    $this->page_info['record_delete_notice'] = sprintf($this->lang['l_record_delete_notice'], $record, $r_delete['delete_time']);
                    $this->page_info['show_bl_stat'] = false;

                    $this->get_bl_new_submited();
                    $this->get_bl_new_updated();

                    break;
                default:
                    break;
            }
            $this->page_info['show_description'] = false;
            $this->page_info['record'] = &$record;

            if ($bl_js_test && $record_type !== null) {

                $delete_record = true;
                // Запрещаем удалять из базы адреса спам активных автономных систем
                $spam_asn = false;
                if ($record_type === 'ip' && 0) {
                    $row_spam_asn = $this->db->select(sprintf("select spam_rate from bl_ips ip left join asn on asn.asn_id = ip.asn_id where ip = inet_aton(%s);", $this->stringToDB($record)));
                    if (isset($row_spam_asn['spam_rate']) && $row_spam_asn['spam_rate'] >= cfg::spam_asn_rate) {
                        $spam_asn = true;
                        $delete_record = false;
                    }
                }

                if ($this->bl_rate('bl_rate_remove', cfg::bl_rate_remove_timeout) >= cfg::max_bl_rate_remove) {
                    $delete_record = false;
                    $this->page_info['record_delete_notice'] = sprintf($this->lang['l_record_too_many']);
                }

                // Дабы не было запросов на удаление не существующих записей.
                if ($delete_record && $record_type == 'ip') {
                    $bl_fail_count = cfg::bl_fail_count;
                    $row = $this->db->select(sprintf($this->sql_bl_ip, $this->stringToDB($record), $bl_fail_count));
                    if (!isset($row['ip'])) {
                        $delete_record = false;
                    }
                }

		        if ($delete_record) {
                    $r_delete = $this->db->select(sprintf("select record, action, delete_time from bl_record_remove where record = %s and type = %s;",
                                $this->stringToDB($record), $this->stringToDB($_GET['record_type'])));

                    $delete_time = gmdate("Y-m-d H:i:s", time() + cfg::record_delete_timeout * 86400);
                    if ($r_delete === false) {
                        $sql = sprintf("insert into bl_record_remove (record, type, submited, sender_ip, action, delete_time) 
                            values (%s, %s, now(), %s, %d, %s);",
                            $this->stringToDB($record),
                            $this->stringToDB($record_type),
                            $this->stringToDB($this->remote_addr),
                            0,
                            $this->stringToDB($delete_time)
                        );
                    } else {
                        $sql = sprintf("update bl_record_remove set delete_time = %s, action = %d, sender_ip = %s where record = %s and type = %s;",
                                $this->stringToDB($delete_time),
                                0,
                                $this->stringToDB($this->remote_addr),
                                $this->stringToDB($record),
                                $this->stringToDB($record_type)
                        );
                    }

                    $this->db->run($sql);
                    $this->post_log(sprintf("Запись %s поставлена в очередь на удаление.", $record));
                    sleep(cfg::fail_timeout);

                    header("Location:/blacklists?record=" . $record . "&action=deleted" );
                    exit;
                } else {
                    $this->page_info['show_delete_question'] = false;
                    $this->page_info['show_deleted_info'] = true;
                    $this->page_info['hide_bl_menu'] = false;
                    $this->page_info['record_delete_notice'] = sprintf($this->lang['l_record_cant_delete'], $record);
                    sleep(cfg::fail_timeout);
                }
            }

            return null;
        }

        if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $this->show_page))
            $this->show_page = 'submited_by_date';

        // Не показываем статистику на странице с конкретным ip
        if (preg_match("/^\d+\.\d+\.\d+\.\d+$/", $this->show_page))
            $this->page_info['show_bl_stat'] = false;

        if (preg_match("/^as\d+$/i", $this->show_page)) {
            $asn_id_str = strtoupper($this->show_page);

            //
            // Запретил вывод информации о 47142 по письменному обращению на welcome@
            //
            if ($asn_id_str != 'AS47142') {
                $this->show_page = 'asn';
            }
        }

        // Показ сведений о домене

        if (preg_match("/^esr-[a-zA-Z0-9]+\.[a-z]+$/i",$this->show_page)){
            $domain_name = str_replace('esr-', '', $this->show_page);
            $this->page_info['domain_name'] = strtoupper($domain_name);
            $this->show_page = 'domain_info';
        }

        switch ($this->show_page) {
            case 'submited_today':
                $this->page_url = $this->page_url . '/' . $this->show_page;
                $this->link->template = 'bootstrap/blacklists/today.html';
                $this->get_submited_today();
                $record = '';
                $this->page_info['show_search'] = false;
                $this->page_info['show_bl_stat'] = false;
                break;
            case 'updated_today':
                $this->page_url = $this->page_url . '/' . $this->show_page;
                $this->link->template = 'bootstrap/blacklists/today.html';
                $this->get_updated_today();
                $record = '';
                $this->page_info['show_search'] = false;
                $this->page_info['show_bl_stat'] = false;
                break;
            case 'top20':
                $this->page_url = $this->page_url . '/' . $this->show_page;
                $this->link->template = 'bootstrap/blacklists/today.html';
                $this->get_most_active(20);
                $record = '';
                $this->page_info['show_search'] = false;
                $this->page_info['show_bl_stat'] = false;
                break;
            case 'archive':
                $this->link->template = 'bootstrap/blacklists/archive.html';
                $this->page_url = $this->page_url . '/' . $this->show_page;
                $this->show_archive();
                $record = '';
                $this->page_info['show_search'] = false;
                $this->page_info['show_bl_stat'] = false;
                break;
            case 'asn':
                $this->page_url = $this->page_url . '/' . $record;
                $this->link->template = 'bootstrap/blacklists/asn.html';
                $this->show_asn($record, $log_record);
                $this->page_info['show_asn'] = true;
                $this->page_info['show_bl_stat'] = false;
                $this->page_info['no_index_page'] = cfg::index_asn ? false : true;
                break;
            case 'submited_by_date':
                $this->page_url = $this->page_url . '/' . $record;
                $this->get_submited_by_date($record, $record);
                $record = '';
                $this->page_info['show_search'] = false;
				$this->page_info['show_bl_stat'] = false;
                $this->link->template = 'bootstrap/blacklists/today.html';
                break;
            case 'domain_info':
                $this->page_info['show_search'] = false;
                $this->page_info['show_description'] = false;
                $this->page_info['show_bl_stat'] = false;
                $this->page_info['show_domain_info'] = true;
                $this->page_info['no_index_page'] = true;
                $domain_sql = sprintf("select emaild_id, emails_count, bl_emails_count, spam_rate
                                       from emaild where domain = '%s';",$domain_name);
                $domain_info = $this->db->select($domain_sql,true);
                if ($domain_info){
                    $domain_info[0]['emails_count'] = number_format($domain_info[0]['emails_count'], 0, '.', ' ');
                    $domain_info[0]['bl_emails_count'] = number_format($domain_info[0]['bl_emails_count'], 0, '.', ' ');
                    $domain_info[0]['spam_rate'] = round($domain_info[0]['spam_rate']*100,2);
                    $this->page_info['domain_info'] = $domain_info[0];
                }
                else{
                    $domain_info[0]['emails_count'] = 0;
                    $domain_info[0]['bl_emails_count'] = 0;
                    $domain_info[0]['spam_rate'] = 0;
                    $this->page_info['domain_info'] = $domain_info[0];
                }
                $new_emails = apc_fetch('newemails'.$domain_name);
                if (!$new_emails && isset($domain_info[0]['emaild_id'])){
                    $new_emails_sql = sprintf("select email, submited, updated, frequency 
                                               from bl_emails 
                                               where emaild_id = %d 
                                               and in_list = 1
                                               limit 20;",
                        $domain_info[0]['emaild_id']);
                    $new_emails = $this->db->select($new_emails_sql,true);
                    apc_store('newemails'.$domain_name, $new_emails, 3600);
                }
                $this->page_info['new_emails'] = $new_emails;

                $active_emails = apc_fetch('activeemails'.$domain_name);
                if (!$active_emails && isset($domain_info[0]['emaild_id'])) {
                    $active_emails_sql = sprintf("select email, submited, updated, frequency
                                                  from bl_emails
                                                  where emaild_id = %d and updated between '%s' and %s
                                                  and in_list = 1
                                                  limit 20;",
                        $domain_info[0]['emaild_id'],
                        date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) - 7*86400),
                        'now()'
                    );
                    $active_emails = $this->db->select($active_emails_sql,true);
                    apc_store('activeemails'.$domain_name, $active_emails, 3600);
                }
                $this->page_info['active_emails'] = $active_emails;

                $chart = array();
                if ($domain_info && isset($domain_info[0]['emaild_id'])){
                    $chart = $this->db->select(sprintf("select substr(convert(date,CHAR),1,7) as
                                                        month,avg(emails_count) as ecount
                                                        from `emaild_history`
                                                        where emaild_id = %d
                                                        group by substr(convert(date,CHAR),1,7)",$domain_info[0]['emaild_id']),true);
                }

                $chartmonths = array();
                $chartvalues = array();

                for ($i=12;$i>=0;$i--){
                    $chartmonths[] = strftime("%b %Y", strtotime(date('Y-m-d')) - 30*86400*$i);
                    $chartvalues[] = 0;
                }

                if ($chart){
                    foreach($chartmonths as $i => $onechartmonth){
                        foreach($chart as $onechart){
                            if ($chartmonths[$i] == strftime("%b %Y", strtotime($onechart['month'])))
                                $chartvalues[$i] = round($onechart['ecount']);
                        }
                    }
                }

                $this->page_info['chartmonths'] = json_encode($chartmonths);
                $this->page_info['chartvalues'] = json_encode($chartvalues);
                $this->page_info['head']['title'] = sprintf("%s %s",
                    $domain_name,
                    $this->lang['l_esr_title']
                );
                break;
            default:
                $this->page_url = $this->page_url . '/' . $record;
                $this->get_bl_record_native($record, $log_record);
                $this->page_info['show_blacklist_api_link'] = false;
                $this->page_info['show_secount_auth_button'] = true;
                $this->page_info['blacklists_live_search'] = $this->options['blacklists_live_search'];

                if (!$this->is_auth) {
                    $this->page_info['show_bl_lp'] = true;
                }
                break;
        }

        $this->page_info['record'] = &$record;
        $this->page_info['submited'] = 1;

        $ctTools = new CleanTalkTools();
        $this->page_info['test_domain'] = $ctTools->get_domain($this->http_referer);
        // Дабы не проверять в черных списках собственный домен
        if (preg_match("/cleantalk\.(ru|org)$/", $this->page_info['test_domain']))
            $this->page_info['test_domain'] = null;

        # Тормозим 0.05 секунд дабы не задосить сервер
        usleep(cfg::fail_timeout_bl_ms);

        return true;
    }

    /**
      * Функция выводит статистику по базе данных
      *
      * @return array
      *
      */
    function show_bl_stat() {
        $mc_label = 'bl_stat_dates';
        $data = $this->memcache->get($mc_label);

        if (!isset($data['charts'])) {

            //
            // Cоздаем пустой график с интервалом года назад
            //
            $chart = null;
            $first_month = null;
            for ($i = $this->options['bl_stat_history_months']; $i >= 0; $i--) {
                $date = strftime("%b %Y", strtotime("-$i month -2 day"));
                $chart[$date] = null;

                if (!$first_month) {
                    $first_month = strftime("%Y-%m-01", strtotime("-$i month -2 day"));
                }
            }

            $sql = sprintf("
                select date, bl_ips, bl_emails, bl_domains from bl_stat where date >= %s order by date asc;
                ",
                $this->stringToDB($first_month)
                );
            $h = $this->db->select($sql, true);

            $data_labels = array(
                'bl_ips',
                'bl_emails'
            );
            foreach ($h as $v) {
                $date = strftime("%b %Y", strtotime($v['date']));
                foreach ($data_labels as $l) {
                    if (isset($chart[$date][$l])) {
                        $chart[$date][$l] = $chart[$date][$l] + $v[$l];
                    } else {
                        $chart[$date][$l] = $v[$l];
                    }
                }
            }

            $i = 0;
            foreach ($chart as $date => $v) {
                if (isset($v)) {
                    foreach ($v as $k2 => $v2) {
                        $charts[$k2][] = isset($v2) ? $v2 : 0;
                    }
                } else {
                    foreach ($data_labels as $l) {
                        $charts[$l][] = 0;
                    }
                }

                $chart_month[] = $date;
            }
            $data['charts'] = $charts;
            $data['chart_month'] = $chart_month;
            $this->memcache->set($mc_label, $data, null, cfg::bl_stat_records);
        }
        $this->page_info['charts'] = json_encode($data['charts']);
        $this->page_info['chart_month'] = json_encode($data['chart_month']);

        return null;
    }


    /**
      * Функция выводит спам активность с разбивкой по автономным системам
      *
      * @param int $asn Номер автономной системы
      *
      * @param bool $log_record Признак записи в лог
      *
      * @return array
      */

    function show_asn($asn = null, $log_record = false) {

        $this->page_info['show_description'] = false;
        $this->page_info['show_search'] = false;
        $this->page_info['show_networks'] = cfg::show_networks;

        $this->page_info['show_asn_social_buttons'] = false;

        $sql_asn = '';
        $asn_id = null;
        if ($asn !== null) {
            if (preg_match("/^as(\d+)$/i", $asn, $matches)) {
                $asn_id = $matches[1];
            }

            if (preg_match("/^\d+$/", $asn)) {
                $asn_id = $asn;
            }

            if ($asn_id) {
                $sql_asn = sprintf(' where asn_id = %d ', $asn_id);
            }
        }


        if ($asn_id) {
            $this->page_info['chart'] = true;
        }

        $rows = $this->db->select(sprintf("select asn_id, org_name, country, updated, bl_ips_count, ips_count, spam_rate from asn %s order by ips_count desc limit %d;", $sql_asn, cfg::asn_select_limit), true);
        if (count($rows) > 0) {
            $this->page_info['bl_page_title'] = $this->lang['l_asn_title'];
            if ($asn_id !== null) {
                $org_name = sprintf("AS%s %s", $asn_id, $rows[0]['org_name']);
				$ips = $this->db->select(sprintf("
 select * from (select inet_ntoa(ip) as ip, ip as ip_dec, frequency, submited, updated from bl_ips where frequency >= %d and asn_id = %d order by submited desc) as t1 limit %d;
",
					cfg::bl_fail_count, $asn_id, cfg::bl_select_limit), true);

                $ips_bl = null;
				$ips_low = null;
				foreach ($ips as $k => $v) {
                    if ($v['frequency'] >= cfg::bl_fail_count)
                        $ips_bl[] = $v;
                    else
                        $ips_low[] = $v;
                    $ips[$k]['submited'] = $this->date_to_gmt($ips[$k]['submited']);
                }
                if (count($ips_bl) > 0) {
                    $this->page_info['show_asn_ips'] = true;
                    $this->page_info['ips'] = $ips_bl;
                }
                if (count($ips_low) > 0) {
                    $this->page_info['show_asn_ips_low'] = true;
                    $this->page_info['ips_low'] = $ips_low;
                }
                $this->page_info['asn_ips_title'] = sprintf($this->lang['l_asn_ips_title'], $org_name);
                $this->page_info['asn_ips_title_low'] = $this->lang['l_asn_ips_title_low'];
                $this->page_info['bl_page_title'] = $org_name;
                $this->page_info['asn_id'] = $asn_id;
                if (!isset($this->page_info['head']['meta_description']))
                    $this->page_info['head']['meta_description'] = $this->page_info['asn_ips_title'];

                $this->page_info['head']['title'] = $org_name;

                $nets = $this->db->select(sprintf("select network_id, network_dec, length, bl_ips_count, ips_count, spam_rate, country from bl_ips_networks where asn_id = %d and ips_count > 0 order by mask desc, network;", $asn_id), true);
                foreach ($nets as $k => $v) {
                	if (isset($v['country'])) {
						if (class_exists('Locale')) {
							$v['country_fullname'] = Locale::getDisplayRegion('-' . $v['country']);
						}
                    	$v['country_ico'] = $v['country'];
                	} else {
                    	$v['country_ico'] = 'cleantalk';
                	}

                    $v['spam_rate'] = sprintf("%.2f%%", $v['spam_rate'] * 100);
                    $nets[$k] = $v;
				}
                $this->page_info['networks'] = $nets;
            } else {
                $this->page_info['head']['title'] = $this->lang['l_asn_title'];
            }

            foreach ($rows as $k => $v) {
                if (!isset($v['country']))
                    $v['country'] = '-';
                $v['bl_ips_count'] = number_format($v['bl_ips_count'], 0, '.', ' ');
                $v['ips_count'] = number_format($v['ips_count'], 0, '.', ' ');
                $v['spam_rate'] = sprintf("%.2f%%", $v['spam_rate'] * 100);

                if (isset($v['country'])) {
					if (class_exists('Locale')) {
						$v['country_fullname'] = Locale::getDisplayRegion('-' . $v['country']);
					}
                    $v['country_ico'] = $v['country'];
                } else {
                    $v['country_ico'] = 'cleantalk';
                }
                $rows[$k] = $v;
            }
            $this->page_info['asn'] = $rows;
            $this->page_info['show_asn'] = true;
            $this->page_info['show_search'] = false;

            $last_modified = 0;
            foreach ($rows as $asn) {
                $updated = strtotime($asn['updated']);

                if ($updated > $last_modified)
                    $last_modified = $updated;
            }

            if ($last_modified > 0)
                $this->set_last_modified($last_modified, $this->page_url);

        } else {
            if ($asn !== null) {
                $this->page_info['bl_message'] = sprintf($this->lang['l_bl_unknown'], $asn);
                if ($log_record === true)
                    $this->post_log($this->page_info['bl_message']);
            }
        }

        if (isset($this->page_info['chart'])) {
            //
            // Cоздаем пустой график с интервалом года назад
            //
            $chart = null;
            $first_month = null;

            // Сдвигаем дату на N дней для определения месяца
            $day_off = 3;
            // Если год високосный, то сдвигаем на на 1 день меньше
            if (date("L")) {
                $day_off = 2;
            }
            for ($i = $this->options['as_history_last_month']; $i >= 0; $i--) {
                $date = strftime("%b %Y", strtotime("-$i month -$day_off day"));
                $chart[$date] = null;

                if (!$first_month) {
                    $first_month = strftime("%Y-%m-01", strtotime("-$i month -$day_off day"));
                }
            }

            $sql = sprintf("
                select date, spam_active from asn_history_months where asn_id = %d and date >= %s order by date asc;
                ",
                $asn_id,
                $this->stringToDB($first_month)
                );
            $h = $this->db->select($sql, true);
            foreach ($h as $v) {
                $date = strftime("%b %Y", strtotime($v['date']));
                $chart[$date]['spam_active'] = $v['spam_active'];
            }

            foreach ($chart as $date => $v) {
                $chart_spam_active[] = isset($v['spam_active']) ? $v['spam_active'] : 0;
                $chart_month[] = $date;
            }

            $this->page_info['chart_spam_active'] = json_encode($chart_spam_active);
            $this->page_info['chart_month'] = json_encode($chart_month);
        }

        return true;
    }

    /**
      * Функция выводит ссылки на записи разбитые по дням за все время работы сервиса
      *
      * @return array
      */

    function show_archive() {

        $type = array('ips', 'emails', 'domains');
        $months = array(
                            'ru' => array(
                                 1 => 'Январь',
                                 2 => 'Февраль',
                                 3 => 'Март',
                                 4 => 'Апрель',
                                 5 => 'Май',
                                 6 => 'Июнь',
                                 7 => 'Июль',
                                 8 => 'Август',
                                 9 => 'Сентябрь',
                                 10 => 'Октябрь',
                                 11 => 'Ноябрь',
                                 12 => 'Декабрь',
                                ),
                            'en' => array(
                                 1 => 'January',
                                 2 => 'February',
                                 3 => 'March',
                                 4 => 'April',
                                 5 => 'May',
                                 6 => 'June',
                                 7 => 'July',
                                 8 => 'August',
                                 9 => 'September',
                                 10 => 'October',
                                 11 => 'November',
                                 12 => 'December',
                                ),
        );

        $dates_label = 'archive_dates';
        $dates = $this->memcache->get($dates_label);
        if ($dates === false && count($dates) < 3) {
            foreach ($type as $t) {
                $rows = $this->db->select(sprintf("select cast(submited as date) as date from bl_%s where frequency >= %d group by date;", $t, 3), true);
                foreach ($rows as $row) {
                    $parts = explode("-", $row['date']);
                    $month = $parts[1];
                    if (preg_match("/^0(\d)$/", $parts[1], $matches))
                        $month = $matches[1];

                    $day = $parts[2];
                    if (preg_match("/^0(\d)$/", $parts[2], $matches))
                        $day = $matches[1];

                    $dates[$parts[0]][$month][$day] = $row['date'];
                }
            }

            // Переворачиваем данные в массиве в порядке убывания, дабы наиболее "новые" записи индексировались в первую очередь
            $dates = array_reverse($dates, true);
            foreach ($dates as $k => $v) {
                $dates[$k] = array_reverse($v, true);
                foreach ($dates[$k] as $k2 => $v2) {
                    $dates[$k][$k2] = array_reverse($v2, true);
                }
            }

            $this->memcache->set($dates_label, $dates, null, 3600);
            $this->set_last_modified(time(), $this->page_url);
        }
        $this->page_info['dates_a'] = $dates;
        $this->page_info['months'] = $months[$this->ct_lang];
        $this->page_info['bl_page_title'] = $this->lang['l_bl_archive_title'];
        $this->page_info['show_description'] = false;
        $this->page_info['show_archive'] = true;

        if (isset($this->lang['l_bl_archive_title']))
            $this->page_info['head']['title'] = $this->lang['l_bl_archive_title'];

        return true;
    }

    /**
      * Функция вывода записей добавленных за последние 24 часа
      *
      * @param string $start_date Начальная дата
      *
      * @param string $end_date Конечная дата
      *
      * @return
      */

    public function get_submited_by_date($start_date = null, $end_date = null) {

        $ips = null;
        $emails = null;
        $domains = null;
        $mc_label = 'bl_date_stat:' . $start_date;
        if ($start_date !== null && $end_date !== null && $start_date === $end_date) {
//            $bl_date_stat = $this->memcache->get($mc_label);
            if (isset($bl_date_stat['ips']))
                $ips = $bl_date_stat['ips'];

            if (isset($bl_date_stat['emails']))
                $emails = $bl_date_stat['emails'];

            if (isset($bl_date_stat['domains']))
                $domains = $bl_date_stat['domains'];
        }
        if ($start_date === null)
            $start_date = date("Y-m-d 00:00:00");

        if ($end_date === null)
            $end_date = date("Y-m-d 23:59:59");

        if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $start_date))
            $start_date = $start_date . ' 00:00:00';

        if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $end_date))
            $end_date = $end_date . ' 23:59:59';

        if ($ips === null || $emails === null) {
            $sql = sprintf("select inet_ntoa(ip) as ip, frequency, submited, asn_id, updated from bl_ips where (frequency >= %d) and submited between %s and %s order by submited desc;", $this->options['bl_fail_count'], $this->stringToDB($start_date), $this->stringToDB($end_date));
            $ips = $this->db->select($sql, true);

            foreach ($ips as $k => $v) {
				$ips[$k]['submited'] = $this->date_to_gmt($ips[$k]['submited']);
				if (function_exists('geoip_country_code_by_name')) {
                	$ips[$k]['country'] = @geoip_country_code_by_name($ips[$k]['ip']);
				}
            }
            $emails = $this->db->select(sprintf("select email, frequency, submited, updated from bl_emails where frequency >= %d and submited between %s and %s order by submited desc;", $this->options['fail_count_private'], $this->stringToDB($start_date), $this->stringToDB($end_date)), true);

            if (cfg::show_bl_domains === true)
                $domains = $this->db->select(sprintf("select domain, frequency, submited, updated from bl_domains where frequency >= %d and submited between %s and %s order by submited desc;", $this->options['fail_count_private'], $this->stringToDB($start_date), $this->stringToDB($end_date)), true);

            $bl_date_stat['ips'] = $ips;
            $bl_date_stat['emails'] = $emails;
            $bl_date_stat['domains'] = $domains;

            // Записываем данные в кеш кроме случая когда выборка делается за сегодняшний день
            if ($end_date == date("Y-m-d 23:59:59")) {
                $last_modified = time();
            } else {
                // Время изменения страницы равно последней активности записей на странице
                $last_modified = 0;
                $updated_ts = 0;
                foreach ($ips as $v) {
                    $updated_ts = strtotime($v['updated']);
                    if ($updated_ts > $last_modified)
                        $last_modified = $updated_ts;
                }
                foreach ($emails as $v) {
                    $updated_ts = strtotime($v['updated']);
                    if ($updated_ts > $last_modified)
                        $last_modified = $updated_ts;
                }

                if ($domains !== null) {
                    foreach ($domains as $v) {
                        $updated_ts = strtotime($v['updated']);
                        if ($updated_ts > $last_modified)
                            $last_modified = $updated_ts;
                    }
                }

                $this->memcache->set($mc_label, $bl_date_stat, null, 86400);
            }

            $this->set_last_modified($last_modified, $this->page_url);
        }

        $this->page_info['ips'] = $ips;
        $this->page_info['emails'] = $emails;
        $this->page_info['domains'] = $domains;

        $this->page_info['show_description'] = false;
        $this->page_info['strict_select'] = true;

        $this->page_info['bl_page_title'] = sprintf($this->lang['l_bl_submited_today_title'], $start_date, $end_date);
        if (cfg::show_bl_domains) {
            $this->page_info['bl_message_date'] = sprintf($this->lang['l_bl_message_date'],
                count($ips),
                count($emails),
                count($domains)
            );
        } else {
            $this->page_info['bl_message_date'] = sprintf($this->lang['l_bl_message_date_wo_domains'],
                count($ips),
                count($emails)
            );
        }

        $this->page_info['head']['title'] = $this->page_info['bl_page_title'];

        $this->page_info['time_label'] = $this->lang['l_detected'];

        return true;
    }

    /**
      * Функция вывода записей добавленных за последние 24 часа
      *
      * @return array
      */

    public function get_submited_today() {

        // Увеличиваем порог вывода записей в списке Добавлены сегодня, дабы уменьшить количество записей и этим поднять скорость идексирования наиболее активных записей.
        $fail_count = $this->options['bl_fail_count'];

        $ips = $this->memcache->get('submited_today_ips');
        if ($ips === false) {
            $sql = sprintf("
            select inet_ntoa(ip.ip) as ip, frequency, ip.submited, ip.asn_id, ipn.network, ipn.mask, sec.frequency_all as sec_frequency from bl_ips ip left join bl_ips_networks ipn on ipn.network_id = ip.network_id left join bl_ips_security sec on sec.ip = ip.ip where (frequency >= %d) and ip.submited between now() - interval 24 hour and now() and frequency > 0 order by submited desc limit %d;
            ",
                $fail_count,
                cfg::submited_today_limit / 2
            );
            $ips = $this->db->select($sql, true);
            foreach ($ips as $k => $v) {
                if (function_exists('geoip_country_code_by_name')) {
                    $ips[$k]['country'] = @geoip_country_code_by_name($ips[$k]['ip']);
                } else {
                    $ips[$k]['country'] = 'UN';
                }
                $ips[$k]['submited'] = $this->date_to_gmt($ips[$k]['submited']);
                if (isset($v['network']) && $v['network'] && $v['mask'] && cfg::show_ip_rand) {
                    $net_range = pow(2,32) - $v['mask'];
                    //
                    // Устанавливаем начальное число в генераторе случайных чисел, дабы генератор возвращал одно и тоже значение из диапазона,
                    // это необходимо для того, чтобы ссылки на IP адреса и спам-активных сетей были постоянными.
                    srand(ip2long($v['ip']));

                    $ip_rand = rand($v['network'], $v['network'] + $net_range);

                    $ip_rand = long2ip($ip_rand);
                    $ips[$k]['ip_rand'] = $ip_rand;
                }
                if (isset($v['sec_frequency']) && $v['sec_frequency'] > 0) {
                    $ips[$k]['type'] = 'Spam&BruteForce';
                } else {
                    $ips[$k]['type'] = 'Spam';
                }
            }
//       var_dump($sql,$ips);
            $this->memcache->set('submited_today_ips', $ips, null, cfg::bl_stat_mc_timeout_medium);
            $this->set_last_modified(time(), $this->page_url);
        }
        $emails = null;
        $emails = $this->memcache->get('submited_today_emails');
        if ($emails === false) {
            $sql = sprintf("select email, frequency, e.submited from bl_emails e where frequency >= %d and e.submited between now() - interval 24 hour and now() order by submited desc limit %d;", $this->options['fail_count_private'], cfg::submited_today_limit_email);
            $emails = $this->db->select($sql, true);
            foreach ($emails as $k => $v) {
                $emails[$k]['submited'] = $this->date_to_gmt($emails[$k]['submited']);
            }
            $this->memcache->set('submited_today_emails', $emails, null, cfg::bl_stat_mc_timeout_medium);
            $this->set_last_modified(time(), $this->page_url);
        }
        $domains = $this->memcache->get('submited_today_domains');
        if (cfg::show_bl_domains === true && $domains === false) {
            $sql = sprintf("select domain, frequency, submited from bl_domains where frequency >= %d and submited between now() - interval 24 hour and now() order by submited desc limit %d;",
                $this->options['fail_count_private'],
                cfg::submited_today_limit / 20
            );

            $domains = $this->db->select($sql, true);
            $this->memcache->set('submited_today_domains', $domains, null, cfg::bl_stat_mc_timeout_medium);
            $this->set_last_modified(time(), $this->page_url);
        }
        $this->page_info['ips'] = $ips;
        $this->page_info['emails'] = $emails;
        $this->page_info['domains'] = $domains;

        $this->page_info['show_description'] = false;
        $this->page_info['strict_select'] = true;

        $this->page_info['bl_page_title'] = sprintf($this->lang['l_bl_submited_today_title'], date($this->date_format, time() - 86400), date($this->date_format, time()));
        if (cfg::show_bl_domains) {
            $this->page_info['bl_message_date'] = sprintf($this->lang['l_bl_message_date'],
                count($ips),
                count($emails),
                count($domains)
            );
        } else {
            $this->page_info['bl_message_date'] = sprintf($this->lang['l_bl_message_date_wo_domains'],
                count($ips),
                count($emails)
            );
        }


        $this->page_info['time_label'] = $this->lang['l_detected'];

        return true;
    }

    /**
      * Функция вывода записей добавленных за последние 24 часа
      *
      * @return array
      */

    public function get_updated_today() {

        // Увеличиваем порог вывода записей в списке Добавлены сегодня, дабы уменьшить количество записей и этим поднять скорость идексирования наиболее активных записей.
        $fail_count = $this->options['bl_fail_count'];
        $mc_label = 'updated_today';
        $ips = $this->memcache->get($mc_label . '_ips');
        if ($ips === false) {
            $ips = $this->db->select(sprintf(
                "select inet_ntoa(ip.ip) as ip, frequency, ip.updated, ip.asn_id, sec.frequency_all as sec_frequency from bl_ips ip left join bl_ips_security sec on sec.ip = ip.ip where frequency >= %d and ip.updated between now() - interval 3 hour and now() limit %d;",
                $fail_count,cfg::bl_updated_number
            ), true);

            foreach ($ips as $k => $v) {
                if (function_exists('geoip_country_code_by_name')) {
                    $ips[$k]['country'] = @geoip_country_code_by_name($ips[$k]['ip']);
                }
                $ips[$k]['submited'] = $v['updated'];
                $ips[$k]['submited'] = $this->date_to_gmt($ips[$k]['submited']);
                if (isset($v['sec_frequency']) && $v['sec_frequency'] > 0) {
                    $ips[$k]['type'] = 'Spam&BruteForce';
                } else {
                    $ips[$k]['type'] = 'Spam';
                }
                unset($ips[$k]['asn_id']);
            }

            $this->memcache->set($mc_label . '_ips', $ips, null, cfg::bl_stat_mc_timeout);
            $this->set_last_modified(time(), $this->page_url);
        }

        $emails = $this->memcache->get($mc_label . '_emails');
        if ($emails === false) {
            $emails = $this->db->select(sprintf("select email, frequency, updated from bl_emails where frequency >= %d and updated between now() - interval 3 hour and now() limit %d;", $this->options['fail_count_private'], cfg::bl_updated_number), true);
            foreach ($emails as $k => $v) {
                $emails[$k]['submited'] = $v['updated'];
            }

            $this->memcache->set($mc_label . '_emails', $emails, null, cfg::bl_stat_mc_timeout);
            $this->set_last_modified(time(), $this->page_url);
        }

        $this->page_info['ips'] = $ips;
        $this->page_info['emails'] = $emails;

        $this->page_info['show_description'] = false;
        $this->page_info['strict_select'] = true;

        $this->page_info['bl_page_title'] = sprintf($this->lang['l_bl_submited_today_title'], date($this->date_format, time() - 86400), date($this->date_format, time()));
        $this->page_info['bl_message_date'] = sprintf($this->lang['l_bl_message_date_wo_domains'],
            count($ips),
            count($emails)
        );


        $this->page_info['time_label'] = $this->lang['l_updated'];

        return true;
    }

    /**
    * Функция вывода наиболее активных записей
    */
    /**
      * Функция вывода наиболее активных записей
      *
      * @param int $rate Оценка
      *
      * @param bool $put_page_title Признак
      *
      * @return array
      */

    public function get_most_active($rate = 20, $put_page_title = true) {
        $m_label = 'most_' . $rate;
        $ips = $this->memcache->get($m_label . '_ips');
        if ($ips === false) {
            $ips = $this->db->select(sprintf(
                "select inet_ntoa(ip.ip) as ip, frequency, ip.submited, ip.asn_id, sec.frequency_all as sec_frequency from bl_ips ip left join bl_ips_security sec on sec.ip = ip.ip where datediff(now(), ip.updated) <= 7 order by frequency desc limit %d;",
                $rate
            ), true);

            foreach ($ips as $k => $v) {
                $ips[$k]['submited'] = $this->date_to_gmt($ips[$k]['submited']);
                if (function_exists('geoip_country_code_by_name')) {
                    $ips[$k]['country'] = @geoip_country_code_by_name($ips[$k]['ip']);
                }
                if (isset($v['sec_frequency']) && $v['sec_frequency'] > 0) {
                    $ips[$k]['type'] = 'Spam&BruteForce';
                } else {
                    $ips[$k]['type'] = 'Spam';
                }
            }
            $this->memcache->set($m_label . '_ips', $ips, null, cfg::bl_stat_mc_timeout_day);
            $this->set_last_modified(time(), $this->page_url);
        }

        $emails = $this->memcache->get($m_label . '_emails');
        if ($emails === false) {
            $emails = $this->db->select(sprintf("
            select email, frequency, submited from bl_emails where datediff(now(), updated) <= 7 order by frequency desc limit %d;
            ", $rate), true);
            $this->memcache->set($m_label . '_emails', $emails, null, cfg::bl_stat_mc_timeout_day);
            $this->set_last_modified(time(), $this->page_url);
        }

        $domains = $this->memcache->get($m_label . '_domains');
        if (cfg::show_bl_domains === true && $domains === false) {
            $domains = $this->db->select(sprintf("
            select domain, frequency, submited from bl_domains where datediff(now(), updated) <= 7 and (type is null or type = 'page') order by frequency desc limit %d;
            ", $rate), true);
            $this->memcache->set($m_label . '_domains', $domains, null, cfg::bl_stat_mc_timeout_day);
            $this->set_last_modified(time(), $this->page_url);
        }
        $this->page_info['ips'] = $ips;
        $this->page_info['emails'] = $emails;
        $this->page_info['domains'] = $domains;

        $this->page_info['show_description'] = false;
        $this->page_info['strict_select'] = true;

        $this->page_info['bl_page_title'] = sprintf($this->lang['l_bl_most_active_title'], $rate);

        if ($put_page_title)
            $this->page_info['head']['title'] = strip_tags($this->page_info['bl_page_title']);

        $this->page_info['time_label'] = $this->lang['l_detected'];

        return true;
    }

    /**
      * Функция вывода информации черных списков в упкаванном формате
      *
      * @param string $format Формат вывода
      *
      * @param bool $bl_rate_fail Признак
      *
      * @return array
      */

    function show_bl_packed($format = null, $bl_rate_fail = false){

        $this->bl_response['success'] = 1;

        // Дабы не задосить сервер ограничивиаем количество запросов с одного IP-адреса
        if ($bl_rate_fail === true) {
            $this->bl_response['success'] = 0;
            $this->bl_response['comment'] = 'Reached maximum queires rate to blacklists. Please wait a while.';

            $this->format_response($format);

            header('HTTP/1.0 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: ' . cfg::bl_rate_timeout);
            return true;
        }

        $email = null;
        if (isset($_GET['email']) && $this->valid_email($_GET['email']))
            $email = $_GET['email'];

        $ip = null;
        if (isset($_GET['ip']) && preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $_GET['ip']))
            $ip = $_GET['ip'];

        $domain = null;
        if (isset($_GET['domain']) && preg_match("/^[a-zа-я0-9_\.\-]+$/i", $_GET['domain']))
            $domain = $_GET['domain'];

        if ($email)
            $this->bl_response['email'] = $this->get_bl_record($email, 'email');

        if ($ip)
            $this->bl_response['ip'] = $this->get_bl_record($ip, 'ip');

        if ($domain)
            $this->bl_response['domain'] = $this->get_bl_record($domain, 'domain');

        $this->format_response($format);

       # Тормозим дабы не задосить сервер
        usleep(cfg::fail_timeout_bl_ms);

        return true;
    }

    /**
      * Функция формирования данных по записи в черных списках
      *
      * @param string $record Запись
      *
      * @param string $type Тип записи
      *
      * @return array
      */

    private function get_bl_record($record, $type){

        $record_sql = $this->stringToDB($record);
        if ($type == 'ip')
            $record_sql = 'inet_aton(' . $this->stringToDB($record) . ')';

        $row = $this->db->select(sprintf($this->sql_bl, $type, $type, $record_sql, 3));
        $row[$type] = $record;
        $row['appears'] = 0;
        if (isset($row['frequency']))
            $row['appears'] = 1;

        return $row;
    }

    /**
      * Функция формирования ответа по запросу к черным спискам
      *
      * @param string $format Формат ответа
      *
      * @return array
      */

    private function format_response($format){
        if ($format === 'json')
            $this->bl_response = json_encode($this->bl_response);

        if ($format === 'serialize')
            $this->bl_response = serialize($this->bl_response);

        $this->page_info['bl_response'] = $this->bl_response;

        return true;
    }


    /**
      * Функция выдачи информации о простой записи в черных списках - IP, email, domain
      *
      * @param string $record Запись
      *
      * @return bool $log_record Признак записи в лог
      */

    public function get_bl_record_native(&$record, $log_record = true) {
        $tools = new CleanTalkTools();

        $this->page_info['show_blacklists_social_buttons'] = true;

		$ip = null;
		$ip_v6 = null;
        $email = null;
        $domain = null;
        $show_history = false;
        if (!$this->ajax) {
            $record = urldecode($record);
            if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $record) && filter_var($record, FILTER_VALIDATE_IP)) {
                $ip = $record;
            } else if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\/history$/", $record) && filter_var(str_replace('/history', '', $record), FILTER_VALIDATE_IP)) {
                $record = str_replace('/history', '', $record);
                $ip = $record;
                $show_history = true;
            }
			if (preg_match('/^::ffff:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $record) // Адрес IPv4, отображённый на IPv6
				|| preg_match('/^[a-f0-9]{1,4}:[a-f0-9]{1,4}:[a-f0-9]{1,4}:[a-f0-9]{1,4}:[a-f0-9]{1,4}:[a-f0-9]{1,4}:[a-f0-9]{1,4}:[a-f0-9]{1,4}$/i', $record)) { // Адрес IPv6
                
               	$ip_v6 = $record; 
			}	
            if (!$ip && $this->valid_email($record)) {
                $email = $record;
            }

            if (!$email && !$ip && !$ip_v6 && preg_match("/^([^\s]+)\.([^\s]+)$/", $record)) {
                $domain = $tools->get_domain($record);
            }
        }

        $records_count = 0;
        $blacklisted = 0;
        $record_found = false;
        $has_spam_history = false;
		$network_info = null;
        if (($email || $ip || $domain || $ip_v6) && !$this->ajax) {
            $this->link->template = 'bootstrap/blacklists/view.html';
            $bl_fail_count = $this->options['fail_count_private'];
            $requests = array();
            $rows = null;
            $records = null;
            $ri_data = null;

            //
            // Инкрементируем счетчик запросов к черным спискам
            //
            if ($this->direct_search) {
                $tools->incr_mc_value('bl_search_count', 1, $this->memcache);
            }

            if ($this->is_auth === false)
                $this->get_apps(false);

            if ($email) {
                $row = $this->db->select(sprintf($this->sql_bl_email, 'email', 'email',  $this->stringToDB($email)));
                $type = 'email';
                if($row){
                    $email_parts = explode('@', $email);
                    $email_host = end($email_parts);
                    if($row['email_exists']=='NOT_EXISTS'){
                        $this->page_info['email_exists'] = $this->lang['l_email_not_exists'];
                        $this->page_info['email_exists_hint'] = sprintf($this->lang['l_email_not_exists_hint'],
                            $email,
                            $email_host,
                            $this->date_to_gmt($row['email_exists_check_datetime'],'M d, Y')
                        );
                    }
                    if($row['email_exists']=='EXISTS'){
                        $this->page_info['email_exists'] = $this->lang['l_email_exists'];
                        $this->page_info['email_exists_hint'] = sprintf($this->lang['l_email_exists_hint'],
                            $email,
                            $email_host,
                            $this->date_to_gmt($row['email_exists_check_datetime'],'M d, Y')
                        );
                    }
                }
            }

            $ip_brute = false;
            if ($ip) {
                $bl_fail_count = cfg::bl_fail_count;
                $sql = sprintf($this->sql_bl_ip,
                    $this->stringToDB($ip)
                );
                $row = $this->db->select($sql);
                $type = 'ip';

                // Проверим наличие brute force атак
                if ($row_brute = $this->db->select(sprintf("SELECT * FROM bl_ips_security WHERE ip = inet_aton(%s) AND in_list>0", $this->stringToDB($ip)))) {
                    $ip_brute = $row_brute;
					$ip_brute['log'] = array();
					$sql = sprintf("SELECT l.user_log, l.event, l.ip_country, l.submited, s.app_id, a.release_date, a.release_version FROM services_security_log l LEFT JOIN services_apps s ON s.service_id = l.service_id LEFT JOIN apps a ON a.app_id = s.app_id WHERE l.auth_ip = inet_aton(%s) AND l.event IN ('auth_failed', 'invalid_username', 'invalid_email') GROUP BY l.submited ORDER BY l.submited DESC LIMIT 50", $this->stringToDB($ip));
                    if ($row_brute_log = $this->db->select($sql, true)) {
                        foreach ($row_brute_log as $v) {
                            if ($v['user_log']) {
                                $sender_nickname_obfuscate = '';
                                foreach (explode(' ', $v['user_log']) as $word) {
                                    $sender_nickname_obfuscate = $sender_nickname_obfuscate . ' ' . $tools->obfuscate_string($word);
                                }
                                $v['user_log'] = $sender_nickname_obfuscate;
                            }
                            $ip_brute['log'][] = array(
                                'event' => $v['event'],
                                'country' => $v['ip_country'],
                                'date' => date('M d, Y H:i:s', strtotime($v['submited'])),
                                'login' => $v['user_log'],
                                'app' => ($v['release_version']) ? sprintf('v. %s <span class="text-muted">%s</span>', $v['release_version'], date('M d, Y', strtotime($v['release_date']))) : '-'
                            );
                        }
                    }
                }
            }
			
			if ($ip_v6) {
                $bl_fail_count = cfg::bl_fail_count;
                $sql = sprintf($this->sql_bl_ip_v6,
                    $this->stringToDB($ip_v6)
                );
				$row = $this->db->select($sql);
//				var_dump($sql, $row);
                $type = 'ip_v6';
			}

            if ($domain && !$ip) {
                $sql = sprintf($this->sql_bl_domain, 'domain', 'domain',  $this->stringToDB($domain), $bl_fail_count);
                $row = $this->db->select($sql);

                if (!isset($row['frequency'])) {
                    if (preg_match("/^www\.(.+)/", $domain, $matches)){
                        // $matches[1] - domain.name from www.domain.name
                        $domain = $matches[1];
                        $row = $this->db->select(sprintf($this->sql_bl_domain, 'domain', 'domain',  $this->stringToDB($domain), $bl_fail_count));
                    } else {
                        $domain = 'www.' . $domain;
                        $row = $this->db->select(sprintf($this->sql_bl_domain, 'domain', 'domain',  $this->stringToDB($domain), $bl_fail_count));
                    }
                }
                $type = 'domain';
            }

            // Если изменений для записи не было, то прерывем выполнение запроса,
            // дабы снизить нагрузку на сервер при индексировании сайта поисковыми системами
            if (isset($row['lastseen_ts']) && cfg::show_header_304) {
                $this->page_is_modified($row['lastseen_ts'], true);
                $this->last_modified = $row['lastseen_ts'];
            }

            $blacklisted = 0;
            $in_cache = false;
            $record_frequency = 1;
            $this->page_info['blacklisted'] = false;
            $country_fullname = '';
            if (isset($row['frequency']) && $row['frequency'] > 0) {
                $in_cache = $row['in_list'] ? true : false;

                // Дабы не выводить на странице 0 спам атак.
                $record_frequency = $row['frequency'] > 0 ? $row['frequency'] : 1;

                $this->page_info['bl_message'] = sprintf($this->lang['l_bl_found3'], $record);
                $this->page_info['bl_message_notice'] = sprintf($this->lang['l_bl_found_notice'],
                    $record_frequency,
                    $this->date_to_gmt($row['submited'],'M d, Y'),
                    $this->date_to_gmt($row['lastseen'])
                );
                $this->page_info['bl_message_notice_template'] = array(
                    $this->lang['l_bl_found_notice'],
                    $this->date_to_gmt($row['submited'],'M d, Y'),
                    $this->date_to_gmt($row['lastseen'])
                );
                if ($ip_brute) {
                    $this->page_info['bl_message'] = sprintf($this->lang['l_bl_found3b'], $record);
                    $this->page_info['bl_message_notice'] .= '<br>' . sprintf($this->lang['l_bl_found_notice_brute'], $ip_brute['frequency_all'], $this->date_to_gmt($ip_brute['updated']));
                    $this->page_info['bl_message_notice_template'] = array(
                        $this->lang['l_bl_found_notice'] . '<br>' . sprintf($this->lang['l_bl_found_notice_brute'], $ip_brute['frequency_all'], $this->date_to_gmt($ip_brute['updated'])),
                        $this->date_to_gmt($row['submited'],'M d, Y'),
                        $this->date_to_gmt($row['lastseen'])
                    );
                }
//                var_dump($this->date_to_gmt($row['lastseen']),$row['lastseen']);
                $this->page_info['datePublished'] = $this->date_to_gmt($row['submited'], DATE_ATOM);
                if ($type == 'ip' && false) {
                    $this->page_info['bl_message'] = sprintf($this->lang['l_bl_found2'],
                        $record,
                        $record_frequency
                    );
                }

                $this->page_info['review_record'] = $record;
                $this->page_info['rating_date'] = $this->date_to_gmt($row['submited']);
                $this->page_info['bread_crumb_name'] = $this->lang['l_reports_name_spam'];

                $this->page_info['record_frequency'] = sprintf($this->lang['l_record_frequency'], $row['frequency']);
                $link_tpl = '<a href="/blacklists/%s">%s</a>';
                $submited_part = sprintf($link_tpl,
                    $this->date_to_gmt($row['submited'], 'Y-m-d'),
                    $this->date_to_gmt($row['submited'], 'M d, Y')
                );
                $this->page_info['record_dates'] = sprintf($this->lang['l_record_dates'],
                    $submited_part
                );
                $record_found = true;

                if ($row['in_list'] == 2) {
                    $blacklisted = 1;
                    $this->page_info['blacklisted'] = true;
                }
                if ($row['frequency'] >= $this->options['bl_fail_count'] || ($ip && $row['spam_active_asn'] == 1)) {
                    $has_spam_history = true;
                }

                // Запрещаем индексировать документ, если по нему нет активности более N дней.
                if (time() - strtotime($row['lastseen']) > 86400 * cfg::no_index_days) {
//                    $this->page_info['no_index_page'] = $this->date_to_gmt($row['submited']);
                }
//                var_dump($row['lastseen']);
            } else if ($ip_brute) {
                $this->page_info['bl_message'] = sprintf($this->lang['l_bl_found3a'], $record);
                $this->page_info['bl_message_notice'] = sprintf($this->lang['l_bl_found_notice_brute'],
                    $ip_brute['frequency_all'],
                    $this->date_to_gmt($ip_brute['updated'])
                );
                $in_cache = true;
                $record_found = true;
            }
            $this->page_info['ip_brute'] = $ip_brute;

            /*
                Статус фильтрации
            */
            $this->page_info['record_status']['in_list'] = $in_cache;
            $this->page_info['record_status']['in_list_status'] = $this->lang['l_not_in_list'];
            $this->page_info['record_status']['in_list_updated'] = ($row['lastseen'] && $row['lastseen'] != '0000-00-00 00:00:00') ? date('M d, Y H:i:s', strtotime($row['lastseen'])) : '-';
            $this->page_info['record_status']['in_list_colour'] = '';
            if ($in_cache) {
                $this->page_info['record_status']['in_list_status'] = $blacklisted ? $this->lang['l_blacklisted'] : $this->lang['l_neutral'];
                $this->page_info['record_status']['in_list_colour'] = $blacklisted ? 'text-danger' : 'text-warning';
            }

            if (isset($row['spam_rate']) && $row['spam_rate'] > 0) {
                $spam_rate = $row['spam_rate'] * 100;
                $dec_points = 2;

                // Числа без дробной части показываем целыми
                if ((int) $spam_rate == $spam_rate) {
                    $dec_points = 0;
                }
                $spam_rate = number_format($spam_rate, $dec_points, '.', '');

                $ri_data['ratingCount'] = $row['votes_spam'] + $row['votes_not_spam'];
                $raing_count = $ri_data['ratingCount'];
                if ($this->all_reviews != 0) {
                    $review_count = round(($this->spam_reviews*5)/$this->all_reviews,2);
                    $final_rating = round((round($spam_rate/20,2) + $review_count)/2,2);
                }
                else
                    $final_rating = round($spam_rate/20,2);
                $ri_data['spam_rate_info'] = sprintf($this->lang['l_spam_rate_info'],
                    $final_rating,
                    $raing_count
                );

                $this->page_info['ratingValue'] = $spam_rate;
                $this->page_info['bestRating'] = 100;
                $this->page_info['ratingCount'] = $raing_count;

                $this->page_info['rating_all'] = $raing_count;
                $stars = $spam_rate/20;
                $lessstars = floor($stars);
                $morestars = ceil($stars);
                $offstars = 5 - $morestars;
                $floatstars = $stars - $lessstars;
                if ($floatstars>0.5)
                    $halfstars = 1;
                else
                    $halfstars = 0;
                $starsarr = array();
                for($i=1;$i<=$lessstars;$i++)
                    $starsarr[] = 'on';
                if ($halfstars==1)
                    $starsarr[] = 'half';
                if ($offstars>=1){
                  for($i=1;$i<=$offstars;$i++)
                    $starsarr[] = 'off';
                }
                $this->page_info['stars'] = $starsarr;

            }

            $this->page_info['record_info'] = $ri_data;

            if ($ip) {
                $this->page_info['api_example'] = 'ip';

                $this->page_info['record_status']['in_sfw_enable'] = true;
                $this->page_info['record_status']['in_sfw_status'] = $this->lang['l_not_in_list'];
                $this->page_info['record_status']['in_sfw_updated'] = ($row['lastseen'] && $row['lastseen'] != '0000-00-00 00:00:00') ? date('M d, Y H:i:s', strtotime($row['lastseen'])) : '-';
                if (isset($row['in_sfw'])) {
                    $ip_in_sfw = $row['in_sfw_network'] || $row['in_sfw'] ? true : false;
                    $this->page_info['record_status']['in_sfw'] = $ip_in_sfw;
                    $this->page_info['record_status']['in_sfw_status'] = $ip_in_sfw ? $this->lang['l_blacklisted'] : $this->lang['l_not_in_list'];
                }

                $this->page_info['record_status']['brute'] = $ip_brute ? date('M d, Y H:i:s', strtotime($ip_brute['updated'])) : false;

                $this->page_info['scripts'] = array(
                    'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.js'
                );
                $this->page_info['show_ip_history'] = $ip;
                $this->page_info['show_ip_history_title'] = sprintf('%s spam and brute force activity on date', $ip);

                /*$sql = sprintf("SELECT SUM(frequency) AS frequency, COUNT(*) AS items, DATE_FORMAT(date, '%%Y-%%m') AS d FROM bl_ips_history WHERE ip = inet_aton(%s) GROUP BY MONTH(`date`) ORDER BY `date`", $this->stringToDB($ip));
                $history_rows = $this->db->select($sql, true);
                if (count($history_rows)) {
                    $this->page_info['show_ip_history'] = $ip;
                    $this->page_info['scripts'] = array(
                        'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js',
                        'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.js'
                    );
                    $history = array();
                    $total = 0;
                    foreach ($history_rows as $val) {
                        $history[$val['d']] = $val['frequency'];
                        $total += $val['items'];
                    }
                    if ($total > 10) $this->page_info['ip_history_count'] = $total;
                    $this->page_info['ip_history_labels'] = array();
                    $this->page_info['ip_history_data'] = array();
                    for ($i = 11; $i >= 0; $i--) {
                        $date_start = strtotime(sprintf('-%d month', $i));
                        $this->page_info['ip_history_labels'][] = sprintf('%s %s', $this->lang['l_months'][date('m', $date_start) - 1], date('Y', $date_start));
                        if (isset($history[date('Y-m', $date_start)])) {
                            $this->page_info['ip_history_data'][] = $history[date('Y-m', $date_start)];
                        } else {
                            $this->page_info['ip_history_data'][] = 0;
                        }
                    }
                    $this->page_info['ip_history_labels'] = json_encode($this->page_info['ip_history_labels']);
                    $this->page_info['ip_history_data'] = json_encode($this->page_info['ip_history_data']);

                    $sql = sprintf("SELECT inet_ntoa(ip) AS ip_str, date, frequency FROM bl_ips_history WHERE ip = inet_aton(%s) ORDER BY date DESC", $this->stringToDB($ip));
                    if (!$show_history) $sql .= " LIMIT 10"; else $this->page_info['ip_history_count'] = null;
                    $history_rows = $this->db->select($sql, true);
                    foreach ($history_rows as $k => $v) {
                        $history_rows[$k]['date'] = date('M d Y', strtotime($v['date']));
                    }
                    $this->page_info['ip_history_list'] = $history_rows;
                }*/

                /*
                    Автономная система
                */
                $asn_id = null;
                $network_id = null;
                $nets = null;
                if (isset($row['asn_id'])){
                    $asn_id = $row['asn_id'];
                }
                $this->page_info['show_crumbs'] = true;

                if (isset($row['network_id'])){
                    $network_id = $row['network_id'];
                }
                if (!$asn_id || !$network_id) {
                    $sql = sprintf("select asn_id, network_id, network_dec,spam_rate,in_sfw from bl_ips_networks where network = %d & mask limit 1;",
                        ip2long($ip)
                    );
                    $nets = $this->db->select($sql);
    //                  var_dump($sql, $nets);
                    $asn_id = $nets['asn_id'];
                    $network_id = $nets['network_id'];
                    $row['asn_id'] = $nets['asn_id'];
                    $row['network_dec'] = $nets['network_dec'];
                }
                if ($asn_id) {
                    $apc_label = sprintf('asn_id:%d_%s', $asn_id, $this->ct_lang);
                    $asn_data = apc_fetch($apc_label);
                    if (!$asn_data || $asn_data['asn_id'] != $asn_id) {
                        $asn_data = $this->db->select(sprintf("select asn_id, org_name, country, address, phone, website, spam_rate from asn where asn_id = %d;", $asn_id));
                        apc_store($apc_label, $asn_data, cfg::apc_cache_lifetime);
                    }
                    $owner_info = null;
                    if ($asn_data) {
                        foreach ($asn_data as $k => $v) {
                            if ($k == 'country' && $v) {
                                $country_name = $v;
    //                                var_dump($this->ct_lang);
                                if (class_exists('Locale')) {
                                        $country_fullname = Locale::getDisplayRegion(
                                            '-' . $v,
                                            $this->ct_lang
                                        );
                                }
                                if ($country_fullname && $country_fullname != '') {
                                    $v = $country_fullname;
                                }
                                $v .= sprintf('<span class="flag %s"></span>',
                                    strtolower($country_name)
                                );
                            }
                            if ($k == 'asn_id') {
                                $v = sprintf('<a href="/blacklists/AS%d" class="grey_text">%d</a>',
                                    $v,
                                    $v
                                );
                            }
                            if ($k == 'spam_rate') {
                                $v = sprintf("%.2f%%", $v * 100);
                            }
                            $owner_info[] = array(
                                'title' => $this->lang['l_' . $k],
                                'data' => $v
                            );
                        }
                    }
                    $this->page_info['owner_info'] = $owner_info;
                }
                $this->page_info['asn_id'] = $asn_id;
                //
                // Addresses from same network.
                //
                if ($network_id && false) {
                    $apc_label = sprintf('network_id:%d_%s', $network_id, $this->ct_lang);
                    $data = apc_fetch($apc_label);
                    if (!$data) {
                        $sql = sprintf("
                        select t1.* from (select ip, submited, updated, frequency, hostname, in_sfw from bl_ips where network_id = %d and frequency >= %d) as t1 order by submited desc limit %d;
                        ",
                            $network_id,
                            $this->options['bl_fail_count'],
                            cfg::neighbours_ip_per_net
                        );
                        $data = $this->db->select($sql, true);
                        apc_store($apc_label, $data, cfg::apc_cache_lifetime_long);
                    }
                    foreach ($data as $k => $v) {
                        $v['submited'] = $this->date_to_gmt($v['submited']);
                        $v['updated'] = $this->date_to_gmt($v['updated']);
                        $v['ip'] = long2ip($v['ip']);
                        $data[$k] = $v;
                    }
                    $network_info['network_dec'] = $row['network_dec'];
                    $network_info['ips'] = $data;

                    if (count($data)) {
                        $this->page_info['network_info'] = $network_info;
                    }
                }
                //
                // Next N addresses in the network to do cross links.
                //
                if ($network_id && true) {
                    $apc_label = sprintf('ip_network:%u_%s', ip2long($ip), $this->ct_lang);
                    $data = apc_fetch($apc_label);
                    if (!$data) {
                        $sql = sprintf("
    select ip, submited, updated, frequency, hostname from bl_ips where frequency >= %d and network_id = %d and ip > inet_aton(%s) limit %d;",
                            $this->options['bl_fail_count'],
                            $network_id,
                            $this->stringToDB($ip),
                            cfg::neighbours_ip_per_net
                        );
                        $data = $this->db->select($sql, true);
                        if ($data) {
                            apc_store($apc_label, $data, cfg::apc_cache_lifetime_long);
                        } else {
                            $data = array();
                        }
                    }
                    foreach ($data as $k => $v) {
                        $v['submited'] = $this->date_to_gmt($v['submited']);
                        $v['updated'] = $this->date_to_gmt($v['updated']);
                        $v['ip'] = long2ip($v['ip']);
                        $data[$k] = $v;
                    }

                    $network_info['network_dec'] = $row['network_dec'];
                    $network_info['ips'] = $data;

                    if (count($data)) {
                        $this->page_info['network_info'] = $network_info;
                    }
                }
                $this->page_info['ip_info'] = $row;

                // Proxy info

                $sql = sprintf("SELECT http_server FROM bl_ips_proxies WHERE ip = inet_aton(%s)", $this->stringToDB($ip));
                if ($data = $this->db->select($sql)) {
                    $this->page_info['proxy_info'] = $data['http_server'];
                }
            }

            if ($email) {
                $this->page_info['api_example'] = 'email';

                $sql_get_emaild = "select emaild_id, submited, updated,emails_count,bl_emails_count,spam_rate from emaild where domain = %s;";
                $emaild_id = isset($row['emaild_id']) ? $row['emaild_id'] : null;
                $emaild = preg_replace("/^(.+\@)/", "", $email);
                $this->page_info['email_domain'] = $emaild;
                if (!$emaild_id) {
                    $sql = sprintf($sql_get_emaild,
                        $this->stringToDB(addslashes($emaild))
                    );
                    $row_e = $this->db->select($sql);
                    if (isset($row_e['emaild_id'])) {
                        $emaild_id = $row_e['emaild_id'];
                    }
                }
                /*
                    Спам активные адреса в этом же домене.
                */
                if ($emaild_id) {
                    $apc_label = sprintf('emaild_id_extra_emails:%d', $emaild_id);
					$data = apc_fetch($apc_label);
                    if (!$data && false) {
                        /*$sql = sprintf("select email, submited, updated, frequency from bl_emails where emaild_id = %d and frequency >= %d order by submited desc limit %d;",
                            $emaild_id,
                            $this->options['fail_count_private'],
                            cfg::neighbours_ip_per_net
                        );*/
                        // https://basecamp.com/2889811/projects/8701471/todos/273998377
                        $sql = sprintf("SELECT t.* FROM (SELECT email, submited, updated, frequency FROM bl_emails WHERE submited > now() - interval 1 day and emaild_id = %d AND frequency >= %d) AS t ORDER BY t.submited DESC LIMIT %d;",
                            $emaild_id,
                            $this->options['fail_count_private'],
                            cfg::neighbours_ip_per_net
                        );
                        $data = $this->db->select($sql, true);
                        apc_store($apc_label, $data, cfg::apc_cache_lifetime_long);
                    }
//					var_dump($data, $sql);
                    if ($data && count($data)) {
                        foreach ($data as $k => $v) {
                            if ($v['email'] == $email) {
                                unset($data[$k]);
                                continue;
                            }
                            $v['submited'] = $this->date_to_gmt($v['submited']);
                            $v['updated'] = $this->date_to_gmt($v['updated']);
                            $data[$k] = $v;
                        }
                        $this->page_info['extra_emails'] = $data;
                    }
                }
                /*
                    Информация о почтовом домене.
                */
                if ($emaild_id) {
                    if (!isset($row_e['emaild_id'])) {
                        $sql = sprintf($sql_get_emaild,
                            $this->stringToDB(addslashes($emaild))
                        );
                        $row_e = $this->db->select($sql);
                    }
                    if (isset($row_e['emaild_id'])) {
                        $emaild_info = $row_e;
                        $emaild_info['emails_count'] = number_format($emaild_info['emails_count'], 0, '.', ' ');
                        $emaild_info['bl_emails_count'] = number_format($emaild_info['bl_emails_count'], 0, '.', ' ');

                        //
                        // Спам рейтинг показываем только для доменов с большим количеством адресов
                        //
                        if ($row_e['emails_count'] > 100) {
                            $emaild_info['spam_rate_human'] = sprintf("%.2f%%", $emaild_info['spam_rate'] * 100);
                        }
                        $emaild_info['submited'] = $this->date_to_gmt($emaild_info['submited']);
                        $emaild_info['updated'] = $this->date_to_gmt($emaild_info['updated']);
                        $this->page_info['emaild_info'] = $emaild_info;
                    }
                }
            }

			$unknown_record = false;
//			var_dump($nets);exit;
            if (!$in_cache && isset($nets) && $nets && $type == 'ip') {
                $unknown_record = true;
                $as_link_tpl = ' (AS%d)';
                $as_link_part = '';
                if ($nets['asn_id']) {
                    $as_link_part = sprintf($as_link_tpl,
                        $nets['asn_id']
                    );
                }
                $frequency_total = isset($network_info['frequency_total']) ? $network_info['frequency_total'] : 1;
//                var_dump($this->page_info['bl_message'],$nets,$as_link_tpl, $frequency_total);
                $this->page_info['bl_message'] = sprintf($this->lang['l_bl_net'],
                    $record,
                    $nets['network_dec'],
                    $as_link_part,
                    number_format($frequency_total, 0, '.', ' ')
                );
                $this->page_info['unknown_record_net'] = $nets['network_dec'];
                $this->page_info['unknown_record_asn'] = $nets['asn_id'];
//                var_dump($this->page_info['bl_message'],$nets,$as_link_tpl, $frequency_total);
                if ($nets['in_sfw'] == 1 || $nets['spam_rate'] >= cfg::spam_network_rate) {
                    $has_spam_history = 1;
                    $record_frequency = $frequency_total;
                }
                $record_found = false;
            }
            $this->page_info['unknown_record'] = $unknown_record;

            if ($log_record === true)
                $this->post_log(strip_tags($this->page_info['bl_message']));

            $records_label = 'records:' . $type . ':' . $record;
            $record_data = false;

            //
            // Если запись не в кеше, то не показываем статистику спам атак.
            //
            $record_data = $this->memcache->get($records_label);
//            $record_data = false;
            $md_second_string = '';
            $min_ts = time();
            if ($record_data == false && $record_found) {
                $rows_sql = "select request_id, sender_email, inet_ntoa(sender_ip) as sender_ip, sender_nickname, datetime, agent, unix_timestamp(datetime) as datetime_ts from bl_history where sender_%s = %s limit %d;";
		        if ($email) {
                    $sql = sprintf($rows_sql, 'email', '\'' . $email . '\'', cfg::bl_select_limit);
                }
                if ($ip) {
                    $sql = sprintf($rows_sql, 'ip', 'inet_aton(\'' . $ip . '\')', cfg::bl_select_limit);
                    // IP, не находящиеся в ЧС также должны быть в истории
                    // https://basecamp.com/2889811/projects/8701471/todos/287788793
                    if ($unknown_record && isset($network_info['ips'])) {
                        $sql_ips = ip2long($ip);
                        foreach ($network_info['ips'] as $v) {
                            $sql_ips .= ',' . ip2long($v['ip']);
                        }
                        $sql = sprintf("select request_id, sender_email, inet_ntoa(sender_ip) as sender_ip, sender_nickname, datetime, agent, unix_timestamp(datetime) as datetime_ts from bl_history where sender_ip in (%s) limit %d;",
                            $sql_ips,
                            cfg::bl_select_limit
                        );
                    }
				}
				if ($ip_v6) {
					$sql = sprintf(" select request_id, sender_email, sender_nickname, datetime, agent, unix_timestamp(datetime) as datetime_ts,  inet6_2ntoa(sender_ip6_left, sender_ip6_right) as sender_ip from bl_history_v6 where inet6_2ntoa(sender_ip6_left, sender_ip6_right) = %s limit %d;",
						$this->stringToDB($ip_v6),
						cfg::bl_select_limit	
					);
					
				}
                //
                // Логика ограничений по количеству выборок из bl_history
                //
                $c_count = false;
                $apc_label = 'bl_history_connections';
                $allow_query = false;
                if (!$rows) {
                    $c_count = apc_fetch($apc_label);
                    $allow_query = true;
                }

                if ($c_count === false) {
                    $c_count = 0;
                } else {
                    if ($c_count >= cfg::max_bl_histroy_queires) {
                        $allow_query = false;
                        error_log(sprintf('Reached allowed limit %d/%d to query bl_history. Record %s.',
                            $c_count,
                            cfg::max_bl_histroy_queires,
                            $record
                        ));
                    }
                }

                if ($allow_query) {
                    $c_count++;
                    apc_store($apc_label, $c_count, cfg::bl_history_queries_limit_lifetime);

                    $rows = null;
                    if (cfg::bl_history_search_dbc) {
                        $rows = $this->db->select($sql, true);
                    }

                    if (cfg::bl_history_search_extra && !$rows) {
                        $this->init_dbh_extra();
                        if ($this->dbh_e && !$this->dbh_e->connect_errno) {
                            $rows = $this->db->select($sql, true, $this->dbh_e);
                        }
                    }

                    $c_count = apc_fetch($apc_label);
                    if ($c_count) {
                        $c_count--;
                    } else {
                        $c_count = 0;
                    }
                    // Счетчик сохраняем только для разрешенных запросов, дабы он успевал устаревать.
					apc_store($apc_label, $c_count, cfg::bl_history_queries_limit_lifetime);
					if (!$rows) {
						$rows = array();
					}
                	usort($rows, function($a, $b) {
                    	return $b['datetime_ts'] - $a['datetime_ts'];
                	});
				}
//var_dump($rows);
                if ($domain && cfg::show_bl_domains_details === true) {
                    $sql = sprintf("select rs.user_id, bl.request_id, sender_email, inet_ntoa(sender_ip) as sender_ip, sender_nickname, rs.datetime, sender_url, rs.collector_id, rs.allow, s.hostname, s.service_id from bl_logs bl left join requests rs on rs.request_id = bl.request_id left join services s on s.service_id = rs.service_id where type = 'domain' and record = %s and rs.allow = 0 and rs.request_type = 'comment' limit 30;",
                        $this->stringToDB($domain)
                        );
                    $rows = $this->db->select($sql, true);
                }

                /*
                    Делаем сортировку истории от новых записей к старым.
                */
                if (!$rows) {
                    $rows = array();
                } else if (isset($this->page_info['bl_message_notice_template']) && empty($this->page_info['bl_message_notice'])) {
                    $this->page_info['bl_message_notice'] = sprintf(
                        $this->page_info['bl_message_notice_template'][0],
                        count($rows),
                        $this->page_info['bl_message_notice_template'][1],
                        $this->page_info['bl_message_notice_template'][2]
                    );
                }
                foreach ($rows as $k => $v) {
                    if (isset($v['datetime']))
                        $v['datetime_ts'] = strtotime($v['datetime']);
                    $rows[$k] = $v;
                }
                usort($rows, function($a, $b) {
                    return $b['datetime_ts'] - $a['datetime_ts'];
                });

                $r = null;
                if (isset($rows) && $type != 'domain') {
                    foreach ($rows as $k => $v){
                        $domains = null;

                        //
                        // Вывод версии клиента
                        //
                        if (isset($v['agent'])) {
                            // Фиксим не стандартный агент для форумов phpBB3
                            if (preg_match("/^ct-phpbb-(\d+)$/", $v['agent'], $matches_engine)) {
                                $rows[$k]['agent'] = 'phpbb3-' . $matches_engine[1];
                                $v['agent'] = 'phpbb3-' . $matches_engine[1];
                            }

                            if (preg_match("/^(\w+)-(\d)(\d+)$/", $v['agent'], $matches)) {

                                // Фиксим названия агентов для коректного определения платформы
                                switch($matches[1]) {
                                    case 'joomla': $matches[1] = 'joomla15'; break;
                                }

                                if (isset($this->page_info['apps'][$matches[1]])) {
                                    $app_info = $this->page_info['apps'][$matches[1]];

                                    $v['agent'] = sprintf("v%d.%d %s",
                                                                    $matches[2],
                                                                    $matches[3],
                                                                    $app_info['info']
                                                                    );
                                    $v['engine'] =  $app_info['engine'];
                                }
                            }
                        }
                        if (!isset($v['agent'])) {
                            $v['agent'] = '-';
                        }

                        //
                        // Логика подгрузки продвигаемых доменов
                        //
                        $v['email_active'] = false;
                        if ($this->options['show_domains_in_details']) {
                            $d_rows = $this->db->select(sprintf("
                            select record as domain from bl_logs where type = 'domain' and request_id = %s limit 10;
                            ", $this->stringToDB($rows[$k]['request_id'])), true);
                            foreach ($d_rows as $d) {
                                if (isset($d['domain'])) {
                                    $d['active'] = false;
                                    if ($this->options['show_domains_pages'] && $this->get_cached_data('bl_domain_f:' . $d['domain']) !== false)
                                        $d['active'] = true;

                                    $domains[] = $d;
                                }
                            }
                            if (count($domains)) {
                                $v['domains'] = $domains;
                                $v['email_active'] = true;
                            }
                        }

                        if (isset($v['datetime'])) {
                            $v['datetime'] = $this->date_to_gmt($v['datetime']);
                        } else {
                            $v['datetime'] = '-';
                        }

                        $v['country_fullname'] = 'Unknown';
                        if (isset($v['sender_email'])) {
                            if (!$v['email_active'] && isset($v['sender_nickname']) && cfg::show_bl_emails_pages) {
                                $w_count = 0;
                                foreach (explode(" ", $v['sender_nickname']) as $v2) {
                                    if (strlen($v2) >= 2) {
                                        $w_count++;
                                    }
                                }
                                if ($w_count >= 3) {
                                    $v['email_active'] = true;
                                }
                            }
                        } else {
                            $v['sender_email'] = '-';
                            $v['email_active'] = false;
                        }
						if (isset($v['sender_ip']) && function_exists('geoip_country_code_by_name')) {
                            $v['country'] = @geoip_country_code_by_name($v['sender_ip']);
                        }

                        //
                        // Маскируем фразы *** дабы Гугл не ругался на возможный взлом страницы.
                        //
//                        if (isset($v['sender_nickname']) && preg_match("/\s/", $v['sender_nickname'])) {
                        if (isset($v['sender_nickname'])) {
                            $sender_nickname_obfuscate = '';
                            foreach (explode(' ', $v['sender_nickname']) as $word) {
                                $word = trim($word);
                                if (!empty($word)) $sender_nickname_obfuscate = $sender_nickname_obfuscate . ' ' . $tools->obfuscate_string($word);
                            }
                            $v['sender_nickname_obfuscate'] = $sender_nickname_obfuscate;
                        } else {
                            $v['sender_nickname_obfuscate'] = isset($v['sender_nickname ']) ? $v['sender_nickname'] : null;
                        }
                        $r[$k] = $v;
                    }
                }

                if (isset($rows) && $type == 'domain') {
                    $gr = array();
                    foreach ($rows as $k => $v){
                        if (isset($v['request_id']) && isset($v['collector_id'])) {
                            $gr[$v['collector_id']]['requests'][] = sprintf("%d:%s:%s",
                                $v['user_id'],
                                $v['request_id'],
                                $v['datetime']
                            );
                        }
                    }
                    foreach ($gr as $collector_id => $v) {
                        $result = $tools->get_remote_details(
                            str_replace('collector', 'collector' . $collector_id, $this->options['remote_details_url']),
                            implode(",", $v['requests']),
                            'message,sender'
                        );
                        $gr[$collector_id]['details'] = $result;
                    }
                    $result = null;
//                    var_dump($rows, $gr);

                    $min_ts = time();
                    foreach ($rows as $k => $v){

                        $message = null;
                        $data = null;
                        if (isset($v['request_id']) && isset($gr[$v['collector_id']]['details'][$v['request_id']]['message'])) {
                            $data = $gr[$v['collector_id']]['details'][$v['request_id']];

                            $message = strip_tags($data['message'], '<p>');
                            $message = preg_replace("/($domain)/", "<span style=\"color: red; font-weight: bold;\">$1</span>", $message);
                        } else {
                            continue;
                        }
                        $r[$k]['message'] = $message;

                        $r[$k]['username'] = '-';
                        if (isset($v['sender_nickname']))
                            $r[$k]['username'] = $v['sender_nickname'];

                        $r[$k]['email'] = '-';
                        if (isset($v['sender_email'])) {
                            $r[$k]['sender_email'] = $v['sender_email'];
                            $r[$k]['email_active'] = false;
                        }

                        $r[$k]['ip'] = '-';
                        $r[$k]['country'] = null;
						if (isset($v['sender_ip'])) {
                            $r[$k]['sender_ip'] = $v['sender_ip'];
                            $r[$k]['country'] = @geoip_country_code_by_name($r[$k]['ip']);
                            $r[$k]['ip_active'] = false;
                            $r[$k]['ip_active'] = true;
                        }

                        $r[$k]['sender_url'] = '-';
                        if (isset($v['sender_url']) && $v['sender_url'] != '') {
                            $r[$k]['sender_url'] = $v['sender_url'];
                            $r[$k]['sender_url'] = preg_replace("/($domain)/i", "<span style=\"color: red; font-weight: bold;\">$1</span>", $r[$k]['sender_url']);
                            if (strlen($r[$k]['sender_url']) > $this->options['sender_profile_url_size'])
                                $r[$k]['sender_url'] = substr($r[$k]['sender_url'], 0, $this->options['sender_profile_url_size']) . '...';
                        }
                        if (isset($v['datetime'])) {
                            $r[$k]['datetime'] = $this->date_to_gmt($v['datetime']);

                            $r_ts = strtotime($v['datetime']);
                            if ($r_ts > 0 && $r_ts < $min_ts) {
                                $min_ts = $r_ts;
                            }
                        }
//                        $v['hostname'] = 'www.mail.co.ru';
//                        $v['hostname'] = 'mail.co.ru';
                        if (isset($v['hostname'])) {
                            if (preg_match("/^(www\.)?([^\.]+)(.+)/", $v['hostname'], $matches)) {
                                $r[$k]['attacked_site'] = $matches[1] . $tools->obfuscate_string($matches[2]) . $matches[3];
//                                var_dump($matches);
                            } else {
                                $r[$k]['attacked_site'] = '-';
                            }
                        } else {
                            if (isset($v['service_id'])) {
                                $r[$k]['attacked_site'] = '#' . $v['service_id'];
                            } else {
                                $r[$k]['attacked_site'] = '-';
                            }
                        }
                    }
                }

                $records = $r;
                $record_data['records'] = $records;
                $this->memcache->set($records_label, $record_data, null, cfg::bl_stat_records);
            }
            if (!$records) {
                $records = $record_data['records'];
            }
//var_dump($records);
            //
            // Если запись не найдена в bl_* таблицах, но есть записи в bl_history. То информацию о записи формируем из истории.
            //
            if ($records && $record_found === false && $type != 'domains' && is_array($records)) {
                $discovered = time();
                $last_update = 1;
                foreach ((array)$records as $r) {
                    if (!isset($r['datetime'])) {
                        continue;
                    }
                    $record_ts = strtotime($r['datetime']);
                    if ($record_ts < $discovered) {
                        $discovered = $record_ts;
                    }
                    if ($last_update > $discovered) {
                        $last_update = $record_ts;
                    }
                }
                $record_frequency = count($records);
                $this->page_info['bl_message'] = sprintf($this->lang['l_bl_found3'],
                    $record,
                    $record_frequency,
                    date('M d Y, H:s:i', $discovered),
                    date('M d Y, H:s:i', $last_update)
                );
                $record_found = true;
                $blacklisted = 1;
                if ($record_frequency >= $this->options['bl_fail_count']) {
                    $has_spam_history = true;
                }
             }

            if ($type == 'domain') {
                $days = round((time() - $min_ts) / 86400);
                if (!$days) {
                    $days = 1;
                }
                $this->page_info['title_comment'] = sprintf($this->lang['l_found_domain_records'],
                    count($records),
                    $days
                );
                $this->page_info['records_d'] = &$records;
            } else {
                $this->page_info['records'] = &$records;
            }

            $this->page_info['records_count'] = count($records);
            $this->page_info['record_type'] = $type;

            $records_count = 0;
            if (is_array($records)) {
                $records_count = count($records);
            }
            if (!$records_count && $domain) {
                $record_found = false;
            }

            //
            // Убираем точки из @gmail.com адресов
            //
            $title_record = $record;
            if ($email && preg_match("/^(.+)\@gmail.com$/", $record, $matches)) {
                $title_record = preg_replace("/\./", "", $matches[1]) . '@gmail.com';
            }

            if ($domain) {
                $this->page_info['head']['title'] = sprintf($this->lang['l_blackseo_title'],
                    $title_record
                );
            } else {
                $country_part = '';
//              var_dump($country_fullname);
                if ($country_fullname !== '' && false) {
                    $country_part = sprintf(' - %s %s',
                        $this->lang['l_country'],
                        $country_fullname
                    );
                }
                $constant_part = '';
                if ($type == 'ip') {
                    $constant_part = '. ' . $this->lang['l_abuse_db'];
                }
                $this->page_info['head']['title'] = ($type=='ip') ? strtoupper($type) : ucfirst($type);
                $this->page_info['head']['title'] .= ' ' . sprintf($this->lang['l_spam_bot'],
                    $title_record,
                    $country_part,
                    $constant_part
                );
            }

		    if ($has_spam_history) {
//                $this->page_info['head']['meta_description'] = strip_tags($this->page_info['bl_message']);
                $meta_description = ($type=='ip') ? strtoupper($type) : ucfirst($type);
                $meta_description .= ' ' . sprintf($this->page_info['l_bl_found_meta_history'],
                    $record,
                    $record_frequency
                );

               if ($country_fullname !== '') {
                    $md_second_string = sprintf($this->lang['l_bl_found_meta_origin2'],
                        $country_fullname
                    );
                    $meta_description .= ' ' . $md_second_string;
                }
                if ($domain) {
                    $meta_description = sprintf($this->page_info['l_bl_found_meta_domain'],
                        $record
                    );
                }
                $this->page_info['head']['meta_description'] = $meta_description . ' ' .$this->lang['l_bl_found_meta_more'];

                if (isset($this->lang['l_blacklists_keywords'])) {
                    $this->page_info['head']['keywords'] = sprintf($this->lang['l_blacklists_keywords'], $record);
                }
            }
            $this->page_info['record_main_title'] = sprintf($this->lang['l_record_main_title'], $record, $this->lang['l_bl_serp_title']);
            $this->page_info['show_top_go_main_link'] = false;
            $this->page_info['show_google_microdata'] = true;

            $r_delete = $this->db->select(sprintf("select record, action, delete_time, unix_timestamp(delete_time) as delete_time_ts from bl_record_remove where record = %s;",
            $this->stringToDB($record)));

            if (isset($r_delete['delete_time']) && $r_delete['delete_time_ts'] > time()) {
                $this->page_info['show_deleted_info'] = true;
                $this->page_info['record_delete_notice'] = sprintf($this->lang['l_record_delete_notice'], $record, $r_delete['delete_time']);
            }

            if ($r_delete === false || ($r_delete['delete_time_ts'] < time())) {
                if ($record_found) {
                    $this->page_info['show_delete_record'] = true;
                }
            }

            // Не даем удалять из черных списков адреса спам активных AS
            if ($ip && isset($ri_data['spam_rate']) && $ri_data['spam_rate'] >= cfg::spam_asn_rate) {
//                $this->page_info['show_delete_record'] = false;
            }
        }
        if ($record_found) {
            //
            // Инкрементируем счетчик запросов к черным спискам
            //
            if ($this->direct_search) {
                $tools->incr_mc_value('bl_search_count_found', 1, $this->memcache);
            }
        } else {
            $this->page_info['bl_message'] = sprintf($this->lang['l_bl_not_found'], $record);
            //
            // Запрещаем индексацию документов без истории в БД для улучшения поисковой выдачи.
            //
            if (!$ip) {
				$this->page_info['no_index_page'] = true;
            }
            if ($domain) {
                $this->page_info['show_new_domains'] = true;
                $this->get_bl_new_submited();
            }
#            header('HTTP/1.0 404 Not Found');
#            header('Status: 404 Not Found');
        }


        if ($domain) {
            $this->page_info['no_index_page'] = cfg::show_bl_domains ? false : true;
        }
        $this->page_info['record_found'] = $record_found;
        if (!$record_found && !($ip || $email) && cfg::free_search) {
            $records = null;

            $bl_prefix = "bl_free_search:" . $record;
            $records = $this->memcache->get($bl_prefix);

            if (!$records) {
                $free_search_limit = $this->options['bl_free_search_limit'];
                if (preg_match("/^\d{1,3}\.\d{1,3}[\.0-9]+$/", $record)) {
                    $sql = sprintf($this->sql_bl_ip_like, $record, cfg::bl_fail_count, $free_search_limit);
                    $rows = $this->db->select($sql, true);
                    $records = $this->get_free_records($rows, 'ip', $records, $record);
                    $free_search_limit = $free_search_limit - count($records);
                    if ($free_search_limit < 0) {
                        $free_search_limit = 0;
                    }
                }

                $ip_search = false;
                if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}.\d{1,3}$/", $record) || ($records && count($records))) {
                    $ip_search = true;
                }

                //
                // Поиск по email, domain, as делаем только в случаи отсутствия записей по адресам, т.к.
                // взаимоисключающие запросы
                //
                if (preg_match("/^[^\s]+$/", $record) && !$ip_search) {
                    // Emails
                    if ($free_search_limit) {
                        $sql = sprintf($this->sql_bl_email_like, $record, $this->options['fail_count_private'], $free_search_limit);
                        $rows = $this->db->select($sql, true);
                        $records = $this->get_free_records($rows, 'email', $records, $record);
                        $free_search_limit = $free_search_limit - count($records);
                        if ($free_search_limit < 0) {
                            $free_search_limit = 0;
                        }
                    }

                    // Domains
                    if ($free_search_limit) {
                        $sql = sprintf($this->sql_bl_domain_like, $record, $this->options['fail_count_private'], $free_search_limit);
                        $rows = $this->db->select($sql, true);
                        $records = $this->get_free_records($rows, 'domain', $records, $record);
                        $free_search_limit = $free_search_limit - count($records);
                        if ($free_search_limit < 0) {
                            $free_search_limit = 0;
                        }
                    }
                    // ASN
                    if ($free_search_limit) {
                        $sql = sprintf($this->sql_bl_asn_like, $record, $free_search_limit);
                        $rows = $this->db->select($sql, true);
                        $records = $this->get_free_records($rows, 'org_name', $records, $record);
                    }

                }
                if ($records && count($records)) {
                    // Сортируем по времени обновления записи
                    usort($records, function($a, $b) {
                        return $b['lastseen_ts'] - $a['lastseen_ts'];
                    });

                    $this->memcache->set($bl_prefix, $records, null, cfg::bl_stat_mc_timeout);
                }
            }

            $records_count = 0;
            if ($records) {
                $records_count = count($records);
            }

            //
            // Сохраняем в журнал информацию о неизвестном запросе
            //
            if ($records_count == 0 && ($this->direct_search || $this->ajax)) {
                $log_file = "./" . cfg::logs_dir . "bl_requests.log";
                $to_file = sprintf("%s\t%s\t%s\n", date("Y-m-d H:i:s"), $this->remote_addr, $record);
                $tools->write_to_log_file($log_file, $to_file);
            }

            if ($records_count != 0) {
                $this->page_info['records'] = $records;
                $this->page_info['show_delete_record'] = false;
            }
            $this->page_info['records_count'] = $records_count;
            $this->page_info['free_search'] = true;
            $this->page_info['free_search_result'] = sprintf($this->lang['l_free_search_result'],
                number_format($records_count, 0, '.', ' ')
            );

            // Для AJAX запросов пропускаем инкрементрацию счетчика, т.к. иначе не работает форма поиска
            if ($this->ajax) {
                $this->skip_bl_rate_increment = true;
            }
        }

        $this->page_info['show_apps'] = true;
        if ($this->is_auth)
            $this->page_info['show_apps'] = false;

        $this->page_info['show_description'] = false;
        $this->page_info['blacklisted'] = $blacklisted;
        $this->page_info['has_spam_history'] = $has_spam_history;
        $this->page_info['show_part_history'] = false;

        if (!$this->is_auth){
            $this->page_info['show_benefits'] = true;
            $this->page_info['hide_bl_menu'] = true;
            $this->page_info['enable_tweets'] = true;
            $this->get_lang($this->ct_lang, 'SimplePage');
        }
        return true;

    }

    /**
      * Функция подсчета количества обращений на удаление из черных списков
      *
      * @param string $bl_rate_prefix Префикс
      *
      * @param int $bl_rate_timeout Тайм-аут
      *
      * @return int
      */
    function bl_rate($bl_rate_prefix = 'bl_rate', $bl_rate_timeout){
        $bl_rate_prefix = $bl_rate_prefix . ":" . $this->remote_addr;
        $bl_rate = $this->memcache->get($bl_rate_prefix);
        $bl_rate[] = time();
        $rate = 0;
        foreach ($bl_rate as $v) {
            if (time() - $v < $bl_rate_timeout)
                $rate++;
        }
        $this->memcache->set($bl_rate_prefix, $bl_rate, null, $bl_rate_timeout);

        return $rate;
    }

    /**
      * Функция возвращает массив данных для вывода на странице свободного поиска
      *
      * @param array $rows Массив записей
      *
      * @param string $type Тип
      *
      * @param string $records Записи
      *
      * @param string $record Запись
      *
      * @return array
      */

    function get_free_records($rows, $type, $records, $record) {

        if (!is_array($rows)) {
            return $records;
        }

        foreach ($rows as $v) {
            $v['record'] = $v[$type];
            $v['record_display'] = preg_replace("#($record)#i", "<span class=\"red_text bold_text\">$1</span>", $v['record']);

            if (isset($v['asn_id'])) {
                $v['record'] = 'AS' . $v['asn_id'];
                $v['record_display'] .= sprintf(" (%s)", $v['record']);
            }
            if (!isset($v['frequency'])) {
                $v['frequency'] = '-';
            }

            unset($v[$type]);

            $v['lastseen_ts'] = strtotime($v['lastseen']);

            $records[] = $v;
        }

        return $records;
    }

    /**
      * Функция частично скрывает email адрес
      *
      * @param string $email Email
      *
      * @return string
      */

    public function obfuscate_email($email) {
        $em   = explode("@", $email);
        $name = implode(array_slice($em, 0, count($em)-1), '@');
        $len  = floor(strlen($name)/2);

        return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
    }

    /**
      * Возвращает статистику по черным спискам
      *
      * @return string
      */

    function get_bl_stat($blackseo = false) {
        $result = '';
        if ($blackseo) {
            $result = sprintf($this->lang['l_bl_message_blackseo'],
                $this->page_info['bl_domains_count']
            );
        } else {
			$result = sprintf($this->lang['l_bl_message'],
				isset($this->page_info['bl_ips_count']) ? $this->page_info['bl_ips_count'] : 0,
				isset($this->page_info['bl_emails_count']) ? $this->page_info['bl_emails_count'] : 0,
				isset($this->page_info['bl_domains_count']) ? $this->page_info['bl_domains_count'] : 0
			);
        }

        return $result;
    }

    /**
      * Возвращает дату относительно GMT
      *
      * @param string $date Дата
      *
      * @param string $date_format Формат даты
      *
      * @return string
      */

    public function date_to_gmt($date, $date_format = null) {
        if ($date_format == null) {
            $date_format = $this->date_format;
        }

        $date = strtotime($date);

//        return date($date_format, $date + (3600 * cfg::billing_timezone));
        return gmdate($date_format, $date);
    }

    /**
      * Функция подсвечивает ссылки в тексте
      *
      * @param string $message Текст
      *
      * @return string
      */

    function make_link($message){
        // Ищем email и заменяем на временные метки
        preg_match_all('/[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9]+([\.a-zA-Z0-9]*)/i',$message,$emailmatches);
        foreach($emailmatches[0] as $i=>$oneematches)
            $message = str_replace($oneematches,'**'.$i,$message);

        preg_match_all('/([http:\/\/|https:\/\/]*)([www\.]*)([a-zA-Z0-9_-]+)(\.[a-z]+)([^\s>]*)/i', $message, $matches);

        foreach($matches[0] as $i=>$onematch)
            $message = str_replace($onematch, '**link'.$i, $message);

        foreach($matches[0] as $i=>$onematch){
            if (stristr($onematch,'http')||strstr($onematch,'https'))
                $message = str_replace('**link'.$i,'<a rel="nofollow" href="'.$onematch.'" target="_blank">'.$onematch.'</a>',$message);
            else
                $message = str_replace('**link'.$i,'<a rel="nofollow" href="http://'.$onematch.'" target="_blank">http://'.$onematch.'</a>',$message);
        }

        // Возвращаем email обратно
        foreach($emailmatches[0] as $i=>$oneematches)
            $message = str_replace('**'.$i,$oneematches,$message);

        return $message;
    }

    function get_api_response($bl_rate_fail = false){
        $api_key = 'u6era2ame9ev';
        $result = array();
        if ($bl_rate_fail === true) {
            $result['error'] = 'Reached maximum queires rate to blacklists. Please wait a while or use Database API for unlimited checks.';
        }elseif (isset($_GET['record'])){
            $api_result = false;
            if($ip = filter_var($_GET['record'], FILTER_VALIDATE_IP)){
                $api_result = @file_get_contents('https://api.cleantalk.org/?method_name=spam_check&auth_key='.$api_key.'&ip='.$ip);
            }elseif($email = filter_var($_GET['record'], FILTER_VALIDATE_EMAIL)){
                $api_result = @file_get_contents('https://api.cleantalk.org/?method_name=spam_check&auth_key='.$api_key.'&email='.$email);
            }else{
                $result['error'] = 'Record wrong format';
            }
            if($api_result){
                $result = json_decode($api_result);
            }else{
                $result['error'] = 'Api error';
            }
        }else{
            $result['error'] = 'Empty request';
        }
        echo json_encode($result);
    }
    function get_spam_activity_on_date(){
        $result = array();
        if (isset($_GET['record']) && $ip = filter_var($_GET['record'], FILTER_VALIDATE_IP)) {
            $label = 'spam_activity_on_date_'.$ip;
            $result = apc_fetch($label);
            if(!$result){
                // История спам/брутфорс-атак
                $sql = sprintf("SELECT SUM(frequency) AS frequency, COUNT(*) AS items, DATE_FORMAT(date, '%%Y-%%m') AS d FROM bl_ips_history WHERE ip = inet_aton(%s) GROUP BY MONTH(`date`) ORDER BY `date`", $this->stringToDB($ip));
                $history_rows_spam = $this->db->select($sql, true);
                $sql = sprintf("SELECT SUM(attacks) AS frequency, COUNT(*) AS items, DATE_FORMAT(datetime, '%%Y-%%m') AS d FROM services_security_stat WHERE auth_ip = inet_aton(%s) GROUP BY MONTH(`datetime`) ORDER BY `datetime`", $this->stringToDB($ip));
                $history_rows_brute = $this->db->select($sql, true);
                if (!empty($history_rows_spam) || !empty($history_rows_brute)) {
                    $result['chart_data'] = array(
                        'show_legend' => false,
                        'history' => array(),
                        'labels' => array(),
                        'datasets' => array()
                    );
                    $history = array();
                    $total = 0;
                    if (!empty($history_rows_spam) && !empty($history_rows_brute)) {
                        $result['chart_data']['show_legend'] = json_encode(array(
                            'display' => true,
                            'position' => 'top',
                            'labels' => array(
                                'usePointStyle' => true
                            )
                        ));
                        $result['chart_data']['datasets'] = array('spam' => array(), 'brute' => array());
                        $history = array('spam' => array(), 'brute' => array());
                        $total = array('spam' => 0, 'brute' => 0);
                        foreach ($history_rows_spam as $val) {
                            $history['spam'][$val['d']] = $val['frequency'];
                            $total['spam'] += $val['items'];
                        }
                        foreach ($history_rows_brute as $val) {
                            $history['brute'][$val['d']] = $val['frequency'];
                            $total['brute'] += $val['items'];
                        }
                    } else if (!empty($history_rows_spam)) {
                        $result['show_ip_history_title'] = sprintf('%s spam activity on date', $ip);
                        foreach ($history_rows_spam as $val) {
                            $history[$val['d']] = $val['frequency'];
                            $total += $val['items'];
                        }
                    } else if (!empty($history_rows_brute)) {
                        $result['show_ip_history_title'] = sprintf('%s brute force activity on date', $ip);
                        foreach ($history_rows_brute as $val) {
                            $history[$val['d']] = $val['frequency'];
                            $total += $val['items'];
                        }
                    }

                    for ($i = 11; $i >= 0; $i--) {
                        $date_start = strtotime(sprintf('-%d month', $i));
                        $result['chart_data']['labels'][] = sprintf('%s %s', $this->lang['l_months'][date('m', $date_start) - 1], date('Y', $date_start));
                        if (isset($history['spam']) && isset($history['brute'])) {
                            if (isset($history['spam'][date('Y-m', $date_start)])) {
                                $result['chart_data']['datasets']['spam'][] = $history['spam'][date('Y-m', $date_start)];
                            } else {
                                $result['chart_data']['datasets']['spam'][] = 0;
                            }
                            if (isset($history['brute'][date('Y-m', $date_start)])) {
                                $result['chart_data']['datasets']['brute'][] = $history['brute'][date('Y-m', $date_start)];
                            } else {
                                $result['chart_data']['datasets']['brute'][] = 0;
                            }
                        } else {
                            if (isset($history[date('Y-m', $date_start)])) {
                                $result['chart_data']['datasets'][] = $history[date('Y-m', $date_start)];
                            } else {
                                $result['chart_data']['datasets'][] = 0;
                            }
                        }
                    }

                    if (isset($result['chart_data']['datasets']['spam'])) {
                        $result['chart_data']['datasets'] = array(
                            array(
                                'data' => $result['chart_data']['datasets']['spam'],
                                'borderColor' => '#c03427',
                                'borderWidth' => 2,
                                'label' => 'Spam'
                            ),
                            array(
                                'data' => $result['chart_data']['datasets']['brute'],
                                'borderColor' => '#3427c0',
                                'borderWidth' => 2,
                                'label' => 'Brute Force'
                            )
                        );
                    } else {
                        $result['chart_data']['datasets'] = array(
                            array(
                                'data' => $result['chart_data']['datasets'],
                                'borderColor' => '#c03427',
                                'borderWidth' => 2
                            )
                        );
                    }

                    // table
                    $sql = sprintf("SELECT inet_ntoa(ip) AS ip_str, date, frequency FROM bl_ips_history WHERE ip = inet_aton(%s) ORDER BY date DESC LIMIT 10", $this->stringToDB($ip));
                    $history_rows = $this->db->select($sql, true);
                    foreach ($history_rows as $k => $v) {
                        $history_rows[$k]['date'] = date('M d Y', strtotime($v['date']));
                    }
                    $result['ip_history_list'] = $history_rows;
                }
                apc_store($label, $result, cfg::apc_cache_lifetime);
            }
        }else{
            $result['error'] = "Wrong IP address";
        }
        
        echo json_encode($result);
        exit();
    }
}
