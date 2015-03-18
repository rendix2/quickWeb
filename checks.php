<?php

$_GET['akce'] = isset($_GET['akce']) ? $_GET['akce'] : '';
$_GET['article_id'] = isset($_GET['article_id']) ? $_GET['article_id'] : '';
$_GET['cat_id'] = isset($_GET['cat_id']) ? $_GET['cat_id'] : '';
$_GET['set_active'] = isset($_GET['set_active']) ? $_GET['set_active'] : '';
$_GET['delete_article'] = isset($_GET['delete_article']) ? $_GET['delete_article'] : '';
$_GET['delete_comment'] = isset($_GET['delete_comment']) ? $_GET['delete_comment']: '';
$_GET['version_id'] = isset($_GET['version_id']) ? $_GET['version_id'] : '';
$_GET['version'] = isset($_GET['version']) ? $_GET['version'] : '';
$_GET['page'] = isset($_GET['page']) ? $_GET['page'] : 0;
$_GET['page_id'] = isset($_GET['page_id']) ? $_GET['page_id'] : '';

$_POST['search_word'] = isset($_POST['search_word']) ? $_POST['search_word'] : '';
$_POST['type'] = isset($_POST['type']) ? $_POST['type'] : '';
$_POST['article_title'] = isset($_POST['article_title']) ? $_POST['article_title'] : '';
$_POST['article_text'] = isset($_POST['article_text']) ? $_POST['article_text'] : '';
$_POST['cat_name'] = isset($_POST['cat_name']) ? $_POST['cat_name'] : '';
$_POST['article_comments_enable'] = isset($_POST['article_comments_enable']) ? $_POST['article_comments_enable'] : '';
$_POST['page_name'] = isset($_POST['page_name']) ? $_POST['page_name'] : '';
$_POST['page_filename'] = isset($_POST['page_filename']) ? $_POST['page_filename'] : '';
$_POST['user_name'] = isset($_POST['user_name']) ? $_POST['user_name'] : '';
$_POST['user_mail'] = isset($_POST['user_mail']) ? $_POST['user_mail'] : '';
$_POST['comment_text'] = isset($_POST['comment_text']) ? $_POST['comment_text'] : '';
$_POST['cat'] = isset($_POST['cat']) ? $_POST['cat'] : array();
$_POST['page'] = isset($_POST['page']) ? $_POST['page'] : array();
$_POST['pages'] = isset($_POST['pages']) ? $_POST['pages'] : array();

$_SESSION['admin'] = isset($_SESSION['admin']) ? $_SESSION['admin'] : array();
$_SESSION['user'] = isset($_SESSION['user']) ? $_SESSION['user'] : array();
$_SESSION['admin']['user_logged'] = isset($_SESSION['admin']['user_logged']) ? $_SESSION['admin']['user_logged'] : 0;
$_SESSION['user']['user_author'] = isset($_SESSION['user']['user_author']) ? $_SESSION['user']['user_author'] : '';
$_SESSION['user']['user_name'] = isset($_SESSION['user']['user_name']) ? $_SESSION['user']['user_name'] : '';
$_SESSION['user']['user_id'] = isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : -1;
$_SESSION['user']['user_logged'] = isset($_SESSION['user']['user_logged']) ? $_SESSION['user']['user_logged'] : 0;
$_SESSION['user']['user_show_article_readings'] = isset($_SESSION['user']['user_show_article_readings']) ? $_SESSION['user']['user_show_article_readings'] : '';
$_SESSION['user']['user_show_comments_count'] = isset($_SESSION['user']['user_show_comments_count']) ? $_SESSION['user']['user_show_comments_count'] : '';
$_SESSION['user']['user_show_article_author'] = isset($_SESSION['user']['user_show_article_author']) ? $_SESSION['user']['user_show_article_author'] : '';
$_SESSION['user']['user_show_article_time'] = isset($_SESSION['user']['user_show_article_time']) ? $_SESSION['user']['user_show_article_time'] : '';
$_SESSION['user']['user_show_article_edited'] = isset($_SESSION['user']['user_show_article_edited']) ? $_SESSION['user']['user_show_article_edited'] : '';
$_SESSION['user']['template_name'] = isset($_SESSION['user']['template_name']) ? $_SESSION['user']['template_name'] : '';

$_SESSION['buff_mess'] = isset($_SESSION['buff_mess']) ? $_SESSION['buff_mess'] : array();
$_SESSION['buff_mess']['good'] = isset($_SESSION['buff_mess']['good']) ? $_SESSION['buff_mess']['good'] : array();
$_SESSION['buff_mess']['bad'] = isset($_SESSION['buff_mess']['bad']) ? $_SESSION['buff_mess']['bad'] : array();

?>
