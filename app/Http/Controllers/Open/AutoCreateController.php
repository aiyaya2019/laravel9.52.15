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
    private $noCreate = ['admin', 'banner', 'migrations', 'user'];

    public function __construct() {
        parent::__construct();
    }

    /**
     * @Desc:自动创建model
     * @author: wanf
     * @Time: 2024/4/18 20:12
     */
    public function create() {
        $this->createModel();

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

        !$tables && $tables = objectToArray($tables);

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

        $modelPath = sprintf("%s\Models\\", app_path());
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




}
