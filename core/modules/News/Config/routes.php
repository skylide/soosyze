<?php

use Soosyze\Components\Router\Route as R;

R::useNamespace('SoosyzeCore\News\Controller');

R::get('news.index', 'news', 'News@index');
R::get('news.page', 'news/page/:id', 'News@page', [ ':id' => '\\d*' ]);
R::get('news.years', 'news/:year:id', 'News@viewYears', [ ':year' => '\\d{4}', ':id' => '(/page/\d+)?' ]);
R::get('news.month', 'news/:year/:month:id', 'News@viewMonth', [ ':year' => '\\d{4}', ':month' => '0[1-9]|1[0-2]', ':id'  => '(/page/\d+)?' ]);
R::get('news.day', 'news/:year/:month/:day:id', 'News@viewDay', [ ':year' => '\\d{4}', ':month' => '0[1-9]|1[0-2]', ':day' => '[0-2][1-9]|3[0-1]', ':id' => '(/page/\d+)?' ]);
R::get('news.rss', 'news/rss.xml', 'News@viewRss');
