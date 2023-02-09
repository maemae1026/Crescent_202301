<?php

declare(strict_types=1);
require_once dirname(__FILE__) . '/News.php';

// 全件の取得
echo '<pre>' . print_r((new News())->all()) . '</pre>';

(new News())->test();
