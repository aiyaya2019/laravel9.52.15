<?php

namespace App\Http\Controllers\Open;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;

class AutoCreateController extends BaseController {

    /**
     * @Desc:不创建的表
     * @var array
     * @author: wanf
     * @Time: 2024/4/18 20:11
     */
    private $noCreate = ['admin', 'user', 'migrations', 'banner'];

    public function __construct() {
        parent::__construct();
    }

    /**
     * @Desc:自动创建model
     * @author: wanf
     * @Time: 2024/4/18 20:12
     */
    public function create() {
        // $this->createModel();
        $this->createLogic();

        return returnData();
    }

    /**
     * @Desc:获取所有数据表
     * @return array|mixed
     * @author: wanf
     * @Time: 2024/4/18 20:26
     */
    public function getTables() {
        $sql = 'SELECT table_name, table_comment, create_time, update_time FROM information_schema.tables WHERE table_schema = (SELECT DATABASE()) ORDER BY create_time DESC';

        $tables = Db::select($sql);

        $tables && $tables = objectToArray($tables);

        return $tables;
    }

    /**
     * @Desc:根据数据表名称批量创建model
     * @return false|void
     * @author: wanf
     * @Time: 2024/4/18 20:11
     */
    public function createModel() {
        $tables = $this->getTables();
        if (!$tables) {
            return false;
        }

        $content = <<<'CODE'
        <?php
        namespace App\Models;

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use App\Models\BaseModel;

        /**
         * %s模型
         */
        class %sModel extends BaseModel {

            protected $table = '%s';

        }
        CODE;

        $modelPath = sprintf("%s\Models\\", app_path());

        foreach ($tables as $value) {
            if (in_array($value['TABLE_NAME'], $this->noCreate)) {
                continue;
            }

            if (strstr($value['TABLE_NAME'], '_') !== false) {
                $fileName = str_replace(' ', '', ucwords(str_replace('_', ' ', $value['TABLE_NAME'])));
            } else {
                $fileName = ucfirst($value['TABLE_NAME']);
            }

            $file = sprintf('%s%sModel.php', $modelPath, $fileName);

            // 已有的model文件跳过
            if (file_exists($file)) {
                continue;
            }

            // 创建文件
            file_put_contents($file, sprintf($content, $value['TABLE_COMMENT'], $fileName, $value['TABLE_NAME']));

            recordLog(1, sprintf('%s 模型创建成功', $file));
        }

        return true;
    }

    /**
     * @Desc:根据数据表名称批量创建逻辑文件
     * @return bool
     * @author: wanf
     * @Time: 2024/4/19 17:25
     */
    public function createLogic() {
        $tables = $this->getTables();
        if (!$tables) {
            return false;
        }

        $content = <<<'CODE'
        <?php
        namespace App\Http\Logic;

        use App\Exceptions\HttpMsgException;
        use App\Models\%sModel;

        /**
         * %s逻辑模块
         */
        class %sLogic extends BaseLogic {

            protected $model;

            public function __construct() {
                parent::__construct();

                $this->model = new %sModel();
            }

            /**
             * @Desc:列表
             * @param $post
             * @return array * @throws HttpMsgException
             * @throws HttpMsgException
             * @author: auto create
             * @Time: %s
             */
            public function list($post = []) {
                $where = [];

                $total = $this->model->getTotal($where);

                $list = [];
                if ($total) {
                    $list = $this->model->getList($where, '*', $post['page'], $post['rows']);

                    if (empty($list)) {
                        throw new HttpMsgException('lang.fail');
                    }
                }

                return ['total' => $total, 'list' => $list];
            }

            /**
             * @Desc:添加
             * @param $post
             * @return array * @throws HttpMsgException
             * @throws HttpMsgException
             * @author: auto create
             * @Time: %s
             */
            public function add($post = []) {
                $result = $this->model->singleInsert($post);

                if (empty($result)) {
                    throw new HttpMsgException('lang.fail');
                }

                return ['id' => $result];
            }

            /**
             * @Desc:编辑
             * @param $post
             * @return bool * @throws HttpMsgException
             * @throws HttpMsgException
             * @author: auto create
             * @Time: %s
             */
            public function edit($post = []) {
                $id = $post['id'];
                unset($post['id']);

                $checkId = $this->model->getOne(['id' => $id]);

                if (empty($checkId)) {
                    throw new HttpMsgException('lang.fail');
                }

                $result = $this->model->singleSave(['id' => $id], $post);

                if (empty($result)) {
                    throw new HttpMsgException('lang.fail');
                }

                return true;
            }

            /**
             * @Desc:删除
             * @param $ids
             * @return bool * @throws HttpMsgException
             * @throws HttpMsgException
             * @author: auto create
             * @Time: %s
             */
            public function del($ids) {

                $result = $this->model->singleDelete(['id' => $ids]);

                if (empty($result)) {
                    throw new HttpMsgException('lang.fail');
                }

                return true;
            }

        }
        CODE;

        $logicPath = sprintf("%s\Http\Logic\\", app_path());
        $time = date('Y-m-d H:i:s');

        foreach ($tables as $value) {
            if (in_array($value['TABLE_NAME'], $this->noCreate)) {
                continue;
            }

            if (strstr($value['TABLE_NAME'], '_') !== false) {
                $fileName = str_replace(' ', '', ucwords(str_replace('_', ' ', $value['TABLE_NAME'])));
            } else {
                $fileName = ucfirst($value['TABLE_NAME']);
            }

            $file = sprintf('%s%sLogic.php', $logicPath, $fileName);

            // 已有的model文件跳过
            if (file_exists($file)) {
                continue;
            }

            // 创建文件
            file_put_contents($file, sprintf($content, $fileName, $value['TABLE_COMMENT'], $fileName, $fileName, $time, $time, $time, $time,));

            recordLog(1, sprintf('%s 逻辑文件创建成功', $file));
        }

        return true;
    }




}
