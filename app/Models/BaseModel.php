<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as DbBuilder;

class BaseModel extends Model {
    use HasFactory;

    protected $fillable = [
        'updated_at',
        'created_at',
    ];

    /**
     * @Desc:查询数量
     * @param string|array $condition 查询条件，包含whereIn查询
     *
     * @param string|array $condition 条件类型如下：
     * @param $condition1 = 'deleted = 0'; //原生
     * @param $condition2 = ['deleted' => 0, 'id' =>  1]; //键值对。['字段名' => 值]。
     * @param $condition3 = ['deleted' => 0, 'id' => [2]]; //键值对，值可以是一维数组，用于in查询。
     * @param $condition4 = [ ['deleted', '=', 0], ['id', 'in', [2, 3, 4]], ['一级', 'like', ['name', 'uuid']] ]; // 1、[ ['字段名'， '运算符'，值(可以是数组，用于in查询)] ]；2、[ ['值'， 'like'，['字段一', '字段二'], ...] ]
     */
    public function getTotal($condition = []) {
        $where = $this->whereHandle($condition);

        return DB::table($this->table)
            ->when(!empty($where), function(DbBuilder $query) use($where) {
                if (!empty($where['whereRaw'])) {
                    $query->whereRaw($where['whereRaw']);
                }
                if (!empty($where['where'])) {
                    $query->where($where['where']);
                }
                if (!empty($where['whereIn'])) {
                    foreach ($where['whereIn'] as $key => $value) {
                        $query->whereIn($key, $value);
                    }
                }
                if (!empty($where['whereLike'])) {
                    $whereLike = $where['whereLike'];
                    foreach ($whereLike as $value) {
                        $query->where(function ($query) use($value){
                            foreach ($value[2] as $val) {
                                $query->orWhere($val, 'like', "%$value[0]%");
                            }
                        });
                    }
                }
                return $query;
            })
            ->count();
    }

    /**
     * @Desc:查询并返回多条数据
     * @param string|array $condition 查询条件，包含whereIn查询
     * @param string|array $fields 查询字段
     * @param string $orderBy 排序
     * @param int $limit
     * @param string $groupBy
     * @param bool $toArray 是否转数组：默认true转数组
     * @return array|\Illuminate\Support\Collection
     *
     * @param string|array $condition 条件类型如下：
     * @param $condition1 = 'deleted = 0'; //原生
     * @param $condition2 = ['deleted' => 0, 'id' =>  1]; //键值对。['字段名' => 值]。
     * @param $condition3 = ['deleted' => 0, 'id' => [2]]; //键值对，值可以是一维数组，用于in查询。
     * @param $condition4 = [ ['deleted', '=', 0], ['id', 'in', [2, 3, 4]], ['一级', 'like', ['name', 'uuid']] ]; // 1、[ ['字段名'， '运算符'，值(可以是数组，用于in查询)] ]；2、[ ['值'， 'like'，['字段一', '字段二'], ...] ]
     */
    public function getAll($condition = [], $fields = '*', string $orderBy = '', int $limit = 0, string $groupBy = '', bool $toArray = true) {
        $where = $this->whereHandle($condition);

        $data = self::select($fields)
            ->when(!empty($where), function(Builder $query) use($where) {
                if (!empty($where['whereRaw'])) {
                    $query->whereRaw($where['whereRaw']);
                }
                if (!empty($where['where'])) {
                    $query->where($where['where']);
                }
                if (!empty($where['whereIn'])) {
                    foreach ($where['whereIn'] as $key => $value) {
                        $query->whereIn($key, $value);
                    }
                }
                if (!empty($where['whereLike'])) {
                    $whereLike = $where['whereLike'];
                    foreach ($whereLike as $value) {
                        $query->where(function ($query) use($value){
                            foreach ($value[2] as $val) {
                                $query->orWhere($val, 'like', "%$value[0]%");
                            }
                        });
                    }
                }
                return $query;
            })
            ->when($orderBy != '', function(Builder $query) use($orderBy) {
                return $query->orderByRaw($orderBy);
            })
            ->when($limit != 0, function(Builder $query) use($limit) {
                return $query->limit($limit);
            })
            ->when($groupBy != '', function(Builder $query) use($groupBy) {
                return $query->groupByRaw($groupBy);
            })
            ->get();

        $toArray && !empty($data) && $data = $data->toArray();

        return $data;
    }

    /**
     * @Desc:查询并返回一条数据
     * @param string|array $condition 查询条件，包含whereIn查询
     * @param string|array $fields 查询字段
     * @param string $orderBy 排序
     * @param bool $toArray 是否转数组：默认true转数组
     * @return array|Model|\Illuminate\Database\Query\Builder|mixed|object|null
     *
     * @param string|array $condition 条件类型如下：
     * @param $condition1 = 'deleted = 0'; //原生
     * @param $condition2 = ['deleted' => 0, 'id' =>  1]; //键值对。['字段名' => 值]。
     * @param $condition3 = ['deleted' => 0, 'id' => [2]]; //键值对，值可以是一维数组，用于in查询。
     * @param $condition4 = [ ['deleted', '=', 0], ['id', 'in', [2, 3, 4]], ['一级', 'like', ['name', 'uuid']] ]; // 1、[ ['字段名'， '运算符'，值(可以是数组，用于in查询)] ]；2、[ ['值'， 'like'，['字段一', '字段二'], ...] ]
     */
    public function getOne($condition = [], $fields = '*', string $orderBy = '', bool $toArray = true) {
        $where = $this->whereHandle($condition);

        $result = self::select($fields)
            ->when(!empty($where), function(Builder $query) use($where) {
                if (!empty($where['whereRaw'])) {
                    $query->whereRaw($where['whereRaw']);
                }
                if (!empty($where['where'])) {
                    $query->where($where['where']);
                }
                if (!empty($where['whereIn'])) {
                    foreach ($where['whereIn'] as $key => $value) {
                        $query->whereIn($key, $value);
                    }
                }
                if (!empty($where['whereLike'])) {
                    $whereLike = $where['whereLike'];
                    foreach ($whereLike as $value) {
                        $query->where(function ($query) use($value){
                            foreach ($value[2] as $val) {
                                $query->orWhere($val, 'like', "%$value[0]%");
                            }
                        });
                    }
                }
                return $query;
            })
            ->when($orderBy !== '', function (Builder $query) use($orderBy){
                return $query->orderByRaw($orderBy);
            })
            ->first();

        $toArray && !empty($result) && $result = $result->toArray();

        return $result;
    }

    /**
     * @Desc:获取某个字段成为一维数组
     * @param string|array $condition 查询条件，包含whereIn查询
     * @param string $field 查询字段
     * @param string $orderBy 排序
     * @param bool $isDistinct 是否去重：true去重(默认去重)，false不去重
     * @param int $limit 限制数量
     * @return array
     *
     * @param string|array $condition 条件类型如下：
     * @param $condition1 = 'deleted = 0'; //原生
     * @param $condition2 = ['deleted' => 0, 'id' =>  1]; //键值对。['字段名' => 值]。
     * @param $condition3 = ['deleted' => 0, 'id' => [2]]; //键值对，值可以是一维数组，用于in查询。
     * @param $condition4 = [ ['deleted', '=', 0], ['id', 'in', [2, 3, 4]], ['一级', 'like', ['name', 'uuid']] ]; // 1、[ ['字段名'， '运算符'，值(可以是数组，用于in查询)] ]；2、[ ['值'， 'like'，['字段一', '字段二'], ...] ]
     */
    public function getFieldColumn($condition = [], string $field = 'id', string $orderBy = '', bool $isDistinct = true, int $limit = 0) {
        $where = $this->whereHandle($condition);

        return self::when(!empty($where), function(Builder $query) use($where) {
                if (!empty($where['whereRaw'])) {
                    $query->whereRaw($where['whereRaw']);
                }
                if (!empty($where['where'])) {
                    $query->where($where['where']);
                }
                if (!empty($where['whereIn'])) {
                    foreach ($where['whereIn'] as $key => $value) {
                        $query->whereIn($key, $value);
                    }
                }
                if (!empty($where['whereLike'])) {
                    $whereLike = $where['whereLike'];
                    foreach ($whereLike as $value) {
                        $query->where(function ($query) use($value){
                            foreach ($value[2] as $val) {
                                $query->orWhere($val, 'like', "%$value[0]%");
                            }
                        });
                    }
                }
                return $query;
            })
            ->when($orderBy !== '', function (Builder $query) use($orderBy){
                return $query->orderByRaw($orderBy);
            })
            ->when($isDistinct, function (Builder $query){
                return $query->distinct();
            })
            ->when($limit != 0, function (Builder $query) use($limit){
                return $query->limit($limit);
            })
            ->pluck($field)
            ->toArray();
    }

    /**
     * @Desc:查询一个字段的值
     * @param string|array $condition 查询条件
     * @param string $field 字段
     * @param string $orderBy 排序
     *
     * @param string|array $condition 条件类型如下：
     * @param $condition1 = 'deleted = 0'; //原生
     * @param $condition2 = ['deleted' => 0, 'id' =>  1]; //键值对。['字段名' => 值]。
     * @param $condition3 = ['deleted' => 0, 'id' => [2]]; //键值对，值可以是一维数组，用于in查询。
     * @param $condition4 = [ ['deleted', '=', 0], ['id', 'in', [2, 3, 4]], ['一级', 'like', ['name', 'uuid']] ]; // 1、[ ['字段名'， '运算符'，值(可以是数组，用于in查询)] ]；2、[ ['值'， 'like'，['字段一', '字段二'], ...] ]
     */
    public function getValue($condition = [], string $field = 'id', string $orderBy = '') {
        $where = $this->whereHandle($condition);

        return self::when(!empty($where), function(Builder $query) use($where) {
                if (!empty($where['whereRaw'])) {
                    $query->whereRaw($where['whereRaw']);
                }
                if (!empty($where['where'])) {
                    $query->where($where['where']);
                }
                if (!empty($where['whereIn'])) {
                    foreach ($where['whereIn'] as $key => $value) {
                        $query->whereIn($key, $value);
                    }
                }
                if (!empty($where['whereLike'])) {
                    $whereLike = $where['whereLike'];
                    foreach ($whereLike as $value) {
                        $query->where(function ($query) use($value){
                            foreach ($value[2] as $val) {
                                $query->orWhere($val, 'like', "%$value[0]%");
                            }
                        });
                    }
                }
                return $query;
            })
            ->when($orderBy !== '', function (Builder $query) use($orderBy){
                return $query->orderByRaw($orderBy);
            })
            ->value($field);
    }

    /**
     * @Desc:更新数据
     * @param string|array $condition 更新条件，包含whereIn
     * @param array $data 更新数据
     * @return bool
     *
     * @param string|array $condition 条件类型如下：
     * @param $condition1 = 'deleted = 0'; //原生
     * @param $condition2 = ['deleted' => 0, 'id' =>  1]; //键值对。['字段名' => 值]。
     * @param $condition3 = ['deleted' => 0, 'id' => [2]]; //键值对，值可以是一维数组，用于in查询。
     * @param $condition4 = [ ['deleted', '=', 0], ['id', 'in', [2, 3, 4]], ['一级', 'like', ['name', 'uuid']] ]; // 1、[ ['字段名'， '运算符'，值(可以是数组，用于in查询)] ]；2、[ ['值'， 'like'，['字段一', '字段二'], ...] ]
     */
    public function singleSave($condition, array $data) {
        if (empty($condition)) {
            return false;
        }

        !isset($data[self::UPDATED_AT]) && $data[self::UPDATED_AT] = date('Y-m-d H:i:s');

        $where = $this->whereHandle($condition);

        return self::when(!empty($where), function(Builder $query) use($where) {
                if (!empty($where['whereRaw'])) {
                    $query->whereRaw($where['whereRaw']);
                }
                if (!empty($where['where'])) {
                    $query->where($where['where']);
                }
                if (!empty($where['whereIn'])) {
                    foreach ($where['whereIn'] as $key => $value) {
                        $query->whereIn($key, $value);
                    }
                }
                if (!empty($where['whereLike'])) {
                    $whereLike = $where['whereLike'];
                    foreach ($whereLike as $value) {
                        $query->where(function ($query) use($value){
                            foreach ($value[2] as $val) {
                                $query->orWhere($val, 'like', "%$value[0]%");
                            }
                        });
                    }
                }
                return $query;
            })
            ->update($data);
    }

    /**
     * @Desc:删除数据
     * @param string|array $condition 条件，包含whereIn
     * @param bool $isSoftDelete 默认false 硬删除 true 软删除
     * @param bool $isUpdateTime 默认true 更新修改时间 false 不更新修改时间
     * @return bool|null
     *
     * @param string|array $condition 条件类型如下：
     * @param $condition1 = 'deleted = 0'; //原生
     * @param $condition2 = ['deleted' => 0, 'id' =>  1]; //键值对。['字段名' => 值]。
     * @param $condition3 = ['deleted' => 0, 'id' => [2]]; //键值对，值可以是一维数组，用于in查询。
     * @param $condition4 = [ ['deleted', '=', 0], ['id', 'in', [2, 3, 4]], ['一级', 'like', ['name', 'uuid']] ]; // 1、[ ['字段名'， '运算符'，值(可以是数组，用于in查询)] ]；2、[ ['值'， 'like'，['字段一', '字段二'], ...] ]
     */
    public function singleDelete($condition, bool $isSoftDelete = false, bool $isUpdateTime = true) {
        if (empty($condition)) {
            return false;
        }
        $where = $this->whereHandle($condition);

        $result = self::when(!empty($where), function(Builder $query) use($where) {
            if (!empty($where['whereRaw'])) {
                $query->whereRaw($where['whereRaw']);
            }
            if (!empty($where['where'])) {
                $query->where($where['where']);
            }
            if (!empty($where['whereIn'])) {
                foreach ($where['whereIn'] as $key => $value) {
                    $query->whereIn($key, $value);
                }
            }
            if (!empty($where['whereLike'])) {
                $whereLike = $where['whereLike'];
                foreach ($whereLike as $value) {
                    $query->where(function ($query) use($value){
                        foreach ($value[2] as $val) {
                            $query->orWhere($val, 'like', "%$value[0]%");
                        }
                    });
                }
            }
            return $query;
        });

        // 软删
        if ($isSoftDelete) {
            $data = [ $this->softDeleteKey => 1 ];

            $isUpdateTime == true && $data[self::UPDATED_AT] = date('Y-m-d H:i:s');

            return $result->update($data);
        }

        return $result->delete();// 硬删
    }

    /**
     * @Desc:where条件参数处理
     * @param string|array $condition
     * @return array
     * @author: wanf
     * @Time: 2023/4/21 9:09
     */
    protected function whereHandle($condition) {
        if (empty($condition)) {
            return [];
        }
        $whereRaw = '';//原生条件
        $where = [];//
        $whereIn = [];
        $whereLike = [];

        !is_array($condition) && $whereRaw = $condition;

        if (is_array($condition)) {
            foreach ($condition as $key => $value) {
                !is_array($value) && $where[] = [$key, '=', $value];

                if (is_array($value)) {
                    if (is_numeric($key)) {
                        if (!is_array($value[2])) {
                            $where[] = [$value[0], $value[1], $value[2]];//['deleted', '=', 0]
                        } else {
                            $value[1] == 'like' && !empty($value[0]) && $whereLike[] = $value;//[ ['name', 'like', ['q', 'w']] ]
                            $value[1] != 'like' && $whereIn[$value[0]] = $value[2];//[ ['id', 'in', $ids] ]
                        }
                    } else {
                        $whereIn[$key] = $value;
                    }
                }
            }
        }

        return [
            'whereRaw' => $whereRaw,
            'where' => $where,
            'whereIn' => $whereIn,
            'whereLike' => $whereLike,
        ];
    }

    /**
     * @Desc:获取AUTO_INCREMENT值或下一个自增id值
     * @author: wanf
     * @Time: 2023/7/24 9:40
     */
    public function getAutoIncrement() {
        $data = DB::select('show create table ' .$this->table);
        $data = objectToArray($data);
        $regex = '/AUTO_INCREMENT=\d+/';

        if (preg_match($regex, $data[0]['Create Table'], $matches)) {
            // 获取AUTO_INCREMENT值
            $val = explode('=', $matches[0])[1];
        } else {
            // 获取最大id值，如果max('id')==null, 则下一条数据自增id==1
            $maxId = $this->max('id') ?: 0;
            $val = $maxId + 1;
        }

        return $val;
    }



}
