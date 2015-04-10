<?php

define('DEBUG', 0);
define('USING_NAME_SPACEC', 0);
define('USING_TRIGGERS', 0);

define('WEB_ADRESS', 'http://new.vsichni-chytry.com');
define('WEB_MAIL', 'rendix2@seznam.cz');

// tables
define('ARTICLES_TABLE', 'articles');
define('ARTICLES_DELETE_TABLE', 'article_deleted');
define('ARTICLES_CATS_TABLE', 'articles_cats');
define('ARTICLES_COMMENTS_TABLE', 'articles_comments');
define('ARTICLES_EDIT_TABLE', 'articles_edit');
define('ARTICLES_PAGES_TABLE', 'articles_pages');
define('ARTICLES_READINGS_TABLE', 'articles_readings');
define('ARTICLES_SETTINGS_TABLE', 'articles_settings');
define('ARTICLES_VERSIONS_TABLE', 'articles_versions');
define('LANGS_TABLE', 'langs');
define('PAGES_TABLE', 'pages');
define('PAGES_CATS_TABLE', 'pages_cats');
define('POOLS_TABLE', 'pools');
define('POOLS_ANSWERS_TABLE', 'pools_answers');
define('POOLS_ANSWERS_VOTE_TABLE', 'pools_answers_vote');
define('CATS_TABLE', 'cats');
define('USERS_TABLE', 'users');
define('TEMPLATES_TABLE', 'templates');
define('UPLOAD_FILES_TABLE', 'files');

// pass salt
define('PASSWORD_SALT', 'Gaulenty');

// forms max lengths
define('ARTICLES_TITLE_MIN_LENGTH', 5);
define('ARTICLES_TITLE_MAX_LENGTH', 250);
define('ARTICLES_TEXT_MIN_LENGTH', 5);
define('ARTICLES_TEXT_MAX_LENGTH', 65535);
define('COMMENT_TEXT_MIN_LENGTH', 3);
define('USER_NAME_MAX_LENGTH', 50);
define('USER_NAME_MIN_LENGTH', 3);
define('USER_PASSWORD_MAX_LENGTH', 50);
define('USER_PASSWORD_MIN_LENGTH', 8);
define('CAT_NAME_MIN_LENGTH', 3);
define('CAT_NAME_MAX_LENGTH', 50);
define('SEARCH_WORD_MIN_LENGTH', 3);
define('SEARCH_WORD_MAX_LENGTH', 40);

// Users class
define('MAX_LOGIN_TRIES', 5);
define('MAX_LOGIN_TIME', 1800);

// articles
define('ADMIN_USERS_PAGE_LIMIT', '5');
define('ARTICLES_PAGE_LIMIT', '5');
define('ARTICLES_ADMIN_HOME_PAGE_LIMIT', 5);
define('PAGES_PAGE_LIMIT', 15);
define('PAGES_ADMIN_HOME_PAGE_LIMIT', 5);

// user article show settings
define('NO_SHOW', 0);
define('SHOW_MAIN_PAGE', 1);
define('SHOW_ARTICLE_PAGE', 2);
define('SHOW_ALL_PAGE', 3);

?>
