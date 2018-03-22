<?php
include "basic.php";

$c_id = isset($_GET['c_id']) ? $_GET['c_id'] : 0;
if (!$c_id) {
    exit('缺少参数c_id');
}

$sql = "SELECT * FROM currencys WHERE c_index=$c_id";
$data = $db->getRow($sql);

// TODO 应取当日的数据
$market_sql = "SELECT * FROM markets WHERE c_index=$c_id AND TO_DAYS(NOW())-TO_DAYS(create_at) <= 1";
$market_list = $db->getRows($market_sql);

$count = $db->getRow("SELECT COUNT(*) FROM markets WHERE c_index=$c_id AND TO_DAYS(NOW())-TO_DAYS(create_at)<=1");

$historical_sql = "SELECT * FROM historical_data WHERE c_index=$c_id AND TO_DAYS(NOW())-TO_DAYS(create_at)<=2";
$historical_list = $db->getRows($historical_sql);
$title = "虚拟货币详情"
?>

<?php include 'head.php'; ?>

<header class="navbar navbar-fixed-top bs-docs-nav">
    <nav class="container">
        <a href="/" class="navbar-brand">数字货币列表</a>
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="nav-collapse collapse bs-navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="/">Title</a></li>
                <li><a href="/">Title</a></li>
                <li><a href="/">Title</a></li>
                <li><a href="/">Title</a></li>
                <li><a href="/">Title</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="/">中文 <b class="caret"></b></a>

                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">

                        <li><a href="/">English</a></li>
                        <li></li>
                        <li><a href="/">한국의</a></li>
                        <li><a href="/">Deutsch</a></li>
                        <li><a href="/">Français</a></li>
                        <li><a href="/">Polski</a></li>
                        <li><a href="/">日本語</a></li>
                        <li><a href="/">Italiano</a></li>
                        <li><a href="/">繁體中文</a></li>
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
                            <?php $key = 1; foreach ($market_list as $item) { ?>
                                <tr>
                                    <td><?= $key ?></td>
                                    <td><?= $item['source'] ?></td>
                                    <td><?= $item['pair'] ?></td>
                                    <td><?= "$" . $item['volume'] ?></td>
                                    <td><?= "$" . $item['price'] ?></td>
                                    <td><?= round($item['volumes'], 2) . '%' ?></td>
                                    <td>Recently</td>
                                </tr>
                            <?php $key ++; } ?>
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
                                    <td><?= explode(' ', $item['old_date'])[0] ?></td>
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

<?php include 'footer.php'; ?>

