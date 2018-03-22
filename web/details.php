<?php
include "basic.php";

$c_id = isset($_GET['c_id']) ? $_GET['c_id'] : 0;
if (!$c_id) {
    exit('缺少参数c_id');
}

$sql = "SELECT * FROM currencys WHERE c_index=$c_id";
$data = $db->getRow($sql);

// TODO
$market_sql = "SELECT * FROM markets WHERE c_index=$c_id AND TO_DAYS(NOW())-TO_DAYS(create_at) <= 1";
$market_list = $db->getRows($market_sql);

$count = $db->getRow("SELECT COUNT(*) FROM markets WHERE c_index=$c_id AND TO_DAYS(NOW())-TO_DAYS(create_at)<=1");

$historical_sql = "SELECT * FROM historical_data WHERE c_index=$c_id AND TO_DAYS(NOW())-TO_DAYS(create_at)<=2";
$historical_list = $db->getRows($historical_sql);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>数字货币列表</title>
    <meta name="description" content="数字货币列表。">

    <link rel="canonical" href="http://docs.ghostchina.com/zh/installation/">
    <link rel="alternate" hreflang="en" href="http://docs.ghostchina.com/installation/">
    <link rel="alternate" hreflang="zh" href="http://docs.ghostchina.com/zh/installation/">
    <link rel="alternate" hreflang="ko" href="http://docs.ghostchina.com/ko/installation/">
    <link rel="alternate" hreflang="de" href="http://docs.ghostchina.com/de/installation/">
    <link rel="alternate" hreflang="fr" href="http://docs.ghostchina.com/fr/installation/">
    <link rel="alternate" hreflang="pl" href="http://docs.ghostchina.com/pl/installation/">
    <link rel="alternate" hreflang="ja" href="http://docs.ghostchina.com/ja/installation/">
    <link rel="alternate" hreflang="it" href="http://docs.ghostchina.com/it/installation/">
    <link rel="alternate" hreflang="zh_TW" href="http://docs.ghostchina.com/zh_TW/installation/">

    <link rel="stylesheet" href="assets/bootstrap.css">
    <link rel="stylesheet" href="assets/main.css">

    <!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="http://docs.ghostchina.com/assets/img/favicon.ico">
</head>
<body>

<header class="navbar navbar-fixed-top bs-docs-nav">
    <nav class="container">
        <a href="http://docs.ghostchina.com/zh/" class="navbar-brand">
            数字货币列表
        </a>
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="nav-collapse collapse bs-navbar-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="http://docs.ghostchina.com/zh/installation/">
                        Title
                    </a>
                </li>
                <li>
                    <a href="http://docs.ghostchina.com/zh/usage/">
                        Title
                    </a>
                </li>
                <li>
                    <a href="http://docs.ghostchina.com/zh/mail/">
                        Title
                    </a>
                </li>
                <li>
                    <a href="http://docs.ghostchina.com/zh/themes/">
                        Title
                    </a>
                </li>
                <li>
                    <a href="http://ghost.org/forum/">
                        Title
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown"
                       href="http://docs.ghostchina.com/zh/installation/#">中文 <b class="caret"></b></a>

                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">

                        <li><a href="http://docs.ghostchina.com/installation/">English</a></li>
                        <li></li>
                        <li><a href="http://docs.ghostchina.com/ko/installation/">한국의</a></li>
                        <li><a href="http://docs.ghostchina.com/de/installation/">Deutsch</a></li>
                        <li><a href="http://docs.ghostchina.com/fr/installation/">Français</a></li>
                        <li><a href="http://docs.ghostchina.com/pl/installation/">Polski</a></li>
                        <li><a href="http://docs.ghostchina.com/ja/installation/">日本語</a></li>
                        <li><a href="http://docs.ghostchina.com/it/installation/">Italiano</a></li>
                        <li><a href="http://docs.ghostchina.com/zh_TW/installation/">繁體中文</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<main id="main" role="main">

    <div class="container">
        <div class="row">
            <section id="content" class="col-lg-12">
                <h2>详情 <a id="overview"></a></h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Market Cap</th>
                        <th>Volume (24h)</th>
                        <th>Circulating Supply</th>
                        <th>Max Supply</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?= "$" . number_format($data['market_cap']); ?></td>
                        <td><?= "$" . $data['volume_24h'] ?></td>
                        <td><?= $data['circulating_supply'] ?></td>
                        <td><?= $data['max_supply'] ?></td>
                    </tr>
                    </tbody>
                </table>

                <ul id="myTab" class="nav nav-tabs">
                    <li class="active"><a href="#home" data-toggle="tab">Markets</a></li>
                    <li><a href="#ios" data-toggle="tab">Historical Data</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade in active" id="home">
                        <h2>Markets</h2>
                        <table class="table">
                            <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>Source</th>
                                <th>Pair</th>
                                <th>Volume (24h)</th>
                                <th>Price</th>
                                <th>Volume (%)</th>
                                <th>Updated</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($market_list as $item) { ?>
                                <tr>
                                    <td>1</td>
                                    <td>Date</td>
                                    <td><?= $item['source'] ?></td>
                                    <td><?= $item['pair'] ?></td>
                                    <td><?= "$" . $item['volume'] ?></td>
                                    <td><?= "$" . $item['price'] ?></td>
                                    <td><?= round($item['volumes'], 2) . '%' ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="ios">
                        <h2>Historical Data</h2>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Open</th>
                                <th>High</th>
                                <th>Low</th>
                                <th>Close</th>
                                <th>Volume</th>
                                <th>Market Cap</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($historical_list as $item) { ?>
                                <tr>
                                    <td><?= $item['old_date'] ?></td>
                                    <td><?= $item['open'] ?></td>
                                    <td><?= $item['hight'] ?></td>
                                    <td><?= $item['low'] ?></td>
                                    <td><?= $item['close'] ?></td>
                                    <td><?= $item['volume'] ?></td>
                                    <td><?= number_format($item['market_cap']) ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

</main>

<footer id="global-footer">
    <p>XXXXXXXXXXXXXXXXXXX<a href="http://john.onolan.org/"> XXXXX </a> + <a
                href="http://erisds.co.uk/">XXX XXX</a> + XXXXXXXXXXXXXXXXXXXXXXXXXXXXX <a
                href="https://github.com/TryGhost/Ghost/contributors">XXXXXXXXXXXXXXXXX</a>.</p>
    <hr>
    <p>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX<a
                href="http://tryghost.org/about/license.html">MIT</a> License.<br>
        XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX <a href="http://creativecommons.org/licenses/by/3.0/">XXXXXXXXXXXXXXXX</a>
        AAAAAAA.</p>
</footer>

<script src="assets/jquery.min.js"></script>
<script src="assets/bootstrap.min.js"></script>
<script src="assets/main.js"></script>
<script type="text/javascript">
    var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
    document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Faab3afe3c1d7901ed91ebed930658acb' type='text/javascript'%3E%3C/script%3E"));
</script>
<script src="assets/h.js" type="text/javascript"></script>
</body>
</html>