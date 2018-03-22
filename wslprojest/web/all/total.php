<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>数据汇总</title>
    <script src="static/echarts.min.js"></script>
    <link rel="stylesheet" href="static/bootstrap.css">

    <script src="static/jquery-3.2.1.min.js"></script>
    <script src="static/bootstrap.js"></script>
</head>
<body>
<div class="container">

    <div class="panel panel-default">
        <div class="panel-heading">账户&nbsp;<strong>信息</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <p>总金额</p>
                    <p id="total_amount">16.12 元</p>
                    <!--<p>-->
                    <!--<a class="btn btn-success" href="#" role="button">提取现金</a>-->
                    <!--<a class="btn btn-default" href="#" role="button">查看账户流水</a>-->
                    <!--</p>-->
                    <p>&nbsp;</p>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="border-left:1px dashed #77787b">
                    <p>今日: 收入</p>
                    <p id="today_amount">00.00 元</p>
                </div>
            </div>
            <div class="row">
                <!--<div class="text-center">-->
                    <!--<div class="btn-group " role="group">-->
                        <!--<button type="button" class="btn btn-default" data-type="day_view">按日</button>-->
                        <!--<button type="button" class="btn btn-success" data-type="weeks_view">按周</button>-->
                        <!--<button type="button" class="btn btn-default" data-type="month_view">按月</button>-->
                    <!--</div>-->
                <!--</div>-->
                <div class="text-center">
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="main" style="width: 100%;height:400px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div style="height: 80px;"></div>
</div>
<script type="text/javascript">
    var api_url = "http://wx.mjyun.com/tempdata/all/basic_api.php";

    // 总金额及今日收入
    $.get(api_url + "?act=all_total", function(data, status){
        $('#today_amount').html((data.today_amount) + " 元");
        $('#total_amount').html((data.total_amount) + " 元");
    }, "JSON");

    $('div.text-center div.btn-group').on('click', 'button', function () {
        $('div.text-center div.btn-group button').each(function (i, ele) {
            $(ele).removeClass('btn-success');
            $(ele).addClass('btn-default');
        });
        $(this).addClass('btn-success');

        var type = $(this).data('type');
        if (type && type.length > 0) {
            httpGet(api_url + "?act=chart&view=" + type);
        }
    });

    // 基于准备好的dom，初始化echarts实例
    var myChart  = echarts.init(document.getElementById('main'));

    // 指定图表的配置项和数据
    var option  = {
        title: {
            text: '金额',
            subtext: '金额'
        },
        tooltip : {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        xAxis:  {
            type: 'category',
            boundaryGap: false,
            data: [],
            axisLabel:{
                interval:0,
                rotate:45,//倾斜度 -90 至 90 默认为0
                margin:2,
                textStyle:{
                    fontWeight:"bolder",
                    color:"#000000"
                }
            },
        },
        yAxis: {
            type: 'value',
            axisLabel: {
                formatter: '{value}'
            }
        },
        series: [
            {
                name:'金额',
                type:'line',
                data:[],
                label: {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                },
                markLine: {
                    data: [
                        {type: 'average', name: '平均金额'}
                    ]
                },
                itemStyle: {
                    normal: {
                        lineStyle: {
                            color: "#6eaaee" // 折线颜色
                        }
                    }
                }
            }
        ]
    };

    myChart.setOption(option);

    httpGet(api_url + "?act=all");

    function httpGet(url) {
        $.get(url, function(data, status){
            option.xAxis.data = [];
            option.series[0].data = [];

            console.log(data.data.length);

            for (var i=0; i<data.data.length; i++) {
                option.xAxis.data.push(data.data[i].to_time);
                option.series[0].data.push(data.data[i].fee);

                myChart.setOption(option);
            }
        }, "JSON");
    }

    function getTimestamp(time) {
        var timestamp = Date.parse(new Date());
        if (time) {
            timestamp = Date.parse(new Date(time));
        }else {
            timestamp = Date.parse(new Date());
        }

        return timestamp / 1000;
    }

    function isOk(start_timestamp, end_timestamp) {
        var difference = end_timestamp - start_timestamp;
        var time31day  = 31 * 24 * 60 * 60;

        if (difference < time31day) {
            return true;
        }
        return false;
    }

    // 多图表 自适应宽高
    window.addEventListener("resize", function () {
        myChart.resize();
    });
</script>

</body>
</html>

