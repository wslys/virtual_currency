<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-3-20
 * Time: 下午11:19
 */

include "basic.php";

$page = isset($_GET['page'])?$_GET['page']:1;
$pageSize = 50;
$begin = ($page - 1) * $pageSize;

$sql = "SELECT * FROM currencys ORDER BY c_index LIMIT $begin, $pageSize";
$result = $db->getRows($sql);

$count_sql = "SELECT COUNT(*) AS c FROM currencys";
$count = $db->getRow($count_sql);
$page_count = (int)($count['c'] / $pageSize);

$title = "虚拟货币列表"
?>

<?php include 'head.php'; ?>

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
    <!--<header class="bs-header">
        <div class="container">
            <h1>数字货币 &amp; 列表信息</h1>
        </div>
    </header>-->

    <div class="container">
        <div class="row">
            <section id="content" class="col-lg-12">
                <h2>概览 <a id="overview"></a></h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>名称</th>
                        <th>流通市值</th>
                        <th>价格</th>
                        <th>流通数量</th>
                        <th>成交额(24h)</th>
                        <th>涨幅(24h)</th>
                        <th>最大币数</th>
                        <!--<th>价格趋势(7d)</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($result as $item) { ?>
                        <tr>
                            <td><?= $item['c_index'] ?></td>
                            <td>
                                <a href="details.php?c_id=<?=$item['c_index'] ?>">
                                    <?= strlen($item['name'])>15?substr($item['name'],0,15) . '....':$item['name'] ?>
                                </a>
                            </td>
                            <td><?= "$" . $item['market_cap'] ?></td>
                            <td><?= "$" . $item['price'] ?></td>
                            <td><?= "$" . $item['volume_24h'] ?></td>
                            <td><?= $item['circulating_supply'] ?></td>
                            <td><?= $item['change_24h'] . "%" ?></td>
                            <td><?= $item['max_supply'] ?></td>
                            <!-- <td><?= $item['price_graph_7d'] ?></td> -->
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <ul class="pager">
                    <li class="previous <?= $page<=1?"disabled":"" ?>"><a href="?page=<?=($page - 1) ?>">&larr; 上一页</a></li>
                    <li class="next <?= $page>=$page_count?"disabled":"" ?>"><a href="?page=<?=($page + 1) ?>">下一页 &rarr;</a></li>
                </ul>

                <div style="height: 45px;"></div>
            </section>
        </div>
    </div>

</main>

<?php include 'footer.php'; ?>

