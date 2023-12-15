<?php

namespace App\Rules;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * 验证数据表$table是否存在对应$field值
 */
class ValueExistTableRule extends BaseRule {
    private $table = '';
    private $field = '';
    private $deleted = false;//是否增加删除条件 false否

    public function __construct(string $table, string $field, bool $deleted = false) {
        parent::__construct();

        $this->table = $table;
        $this->field = $field;
        $this->deleted = $deleted;
    }

    /**
     * @Desc:验证
     * @param $attribute
     * @param $value
     * @return bool
     * @author: wanf
     * @Time: 2023/12/15 16:47
     */
    public function passes($attribute, $value) {
        if (!is_array($value) || empty($this->table) || empty($this->field)) {
            return false;
        }

        $check = DB::table($this->table)
            ->whereIn($this->field, $value)
            ->when($this->deleted == true, function (Builder $query){
                $query->where('deleted', 0);
            })
            ->get();

        if (count($check) != count($value)) {
            return  false;
        }

        return true;
    }

    /**
     * @Desc:返回自定义的错误信息
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     * @author: wanf
     * @Time: 2023/12/15 16:47
     */
    public function message() {
        return __('lang.param_error');
    }
}
