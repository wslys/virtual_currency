<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2017/12/19
 * Time: 11:40
 *
 * mysql数据库操作类
 * 0、实现了单例模式
 * 1、该类一实例化，就可以自动连接上mysql数据库
 * 2、该类可以单独去设定要使用的连接编码
 * 3、该类可以单独设定要使用的数据库
 * 4、可以主动关闭数据库连接
 * 5、完成了DML、DQL操作的简单封装
 * 6、实现了简单的sql执行错误处理
 */

class MysqlDB{
    private $link = null;   #用于存储连接数据库成功后的“资源”
    protected $host;
    protected $port;
    protected $user;
    protected $password;
    protected $charset;
    protected $dbname;

    /** 单例实现 **/
    private static $instance = null; #用于存储该类的唯一实例

    private function __clone(){}     #禁止该类的实例对象进行克隆复制对象

    #对外提供一个创建该类实例的方法
    public static function getInstance($config){
        if(!(static::$instance instanceof self)){
            static::$instance = new self($config);
        }
        return static::$instance;
    }

    #实现单例的基础：私有化该类的构造方法
    public function __construct($config){
        $this->host = isset($config['host'])?$config['host']:'localhost';
        $this->port = isset($config['port'])?$config['port']:'3306';
        $this->user = isset($config['username'])?$config['username']:'root';
        $this->password = isset($config['password'])?$config['password']:'';
        $this->charset = isset($config['charset'])?$config['charset']:'utf8';
        $this->dbname = isset($config['dbname'])?$config['dbname']:'db_mvc';

        $this->link = @mysql_connect(
            "{$this->host}:{$this->port}","{$this->user}","$this->password")
        or die("连接失败！");

        $this->selectDB($this->dbname);
        $this->setCharset($this->charset);
    }

    function __destruct() {
	    $this->closeDB();
    }

    public function setCharset($charset){#1设置连接环境字符编码
        $sql = "set names {$charset}";
        $this->query($sql);
    }

    public function selectDB($dbname){#2选择要操作的数据库
        $sql = "use {$dbname}";
        $this->query($sql);
    }

    public function closeDB(){#3关闭数据库连接
        if(isset($this->link)){
		@mysql_close($this->link);
        }
    }

    public function execute($sql){#增删改
        $this->query($sql);
        return true;
    }

    public function getData($sql){#返回结果是一个标量值
        $result = $this->query($sql);
        $num = @mysql_fetch_array($result);
        @mysql_free_result($result);
        return $num[0];
    }

    public function getRow($sql){#返回结果是一个一维数组
        $result = $this->query($sql);
        $row    = @mysql_fetch_array($result);
        @mysql_free_result($result);
        return $row;
    }

    public function getRows($sql){#返回结果是一个二维数组
        $result = $this->query($sql);
        $arr = array();
        while($row = @mysql_fetch_array($result)){
            $arr[] = $row;
        }

        @mysql_free_result($result);
        return $arr;
    }

    private function query($sql){#错误处理并返回一个结果集
        $result = mysql_query($sql);
        if($result === false){
            echo "代码执行错误！请参考如下提示：";
            echo "<br />错误代号：".mysql_errno();
            echo "<br />错误内容：".mysql_error();
            echo "<br />错误代码：".$sql;

            die();
        }
        return $result;
    }
}

