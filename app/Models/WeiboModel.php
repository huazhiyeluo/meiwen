<?php namespace App\Models;

use CodeIgniter\Model;

class WeiboModel extends Model
{
    protected $table = 'ts_weibo';
    protected $primaryKey = 'weibo_id';

    protected $db;
    protected $db_meiwen;
    protected $db_gushi;
    protected $db_zuowen;

    public function __construct()
    {
        $this->db = db_connect();
    }

    /**
     * [getWeibos description] 单个文章
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getWeibo($condition = [], $field = "*")
    {
        $builder = $this->db->table('ts_weibo');
        $builder->select($field);

        if (isset($condition['where']) && $condition['where']) {
            foreach ($condition['where'] as $k => $v) {
                $builder->where($k, $v);
            }
        }

        if (isset($condition['whereIn']) && $condition['whereIn']) {
            foreach ($condition['whereIn'] as $k => $v) {
                $builder->whereIn($k, $v);
            }
        }

        if (isset($condition['order']) && $condition['order']) {
            foreach ($condition['order'] as $k => $v) {
                $builder->orderBy($k, $v);
            }
        }

        $query = $builder->get();

        $data = $query->getRowArray();
        return $data;
    }

    /**
     * [getWeibos description]  多个文章
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getWeibos($condition = [], $field = "*", $page = 1, $pageSize = 15)
    {
        $pageSize = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_weibo');
        $builder->select($field);

        if (isset($condition['where']) && $condition['where']) {
            foreach ($condition['where'] as $k => $v) {
                $builder->where($k, $v);
            }
        }

        if (isset($condition['whereIn']) && $condition['whereIn']) {
            foreach ($condition['whereIn'] as $k => $v) {
                $builder->whereIn($k, $v);
            }
        }

        if (isset($condition['order']) && $condition['order']) {
            foreach ($condition['order'] as $k => $v) {
                $builder->orderBy($k, $v);
            }
        }

        $builder->limit($pageSize, $startSize);

        $query = $builder->get();

        $data = $query->getResultArray();
        return $data;
    }

    /**
     * [getWeibos description]  多个文章-带分页
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getWeibosList($condition = [], $field = "*", $page = 1, $pageSize = 15)
    {
        $pageSize = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_weibo');
        $builder->select($field);

        if (isset($condition['where']) && $condition['where']) {
            foreach ($condition['where'] as $k => $v) {
                $builder->where($k, $v);
            }
        }

        if (isset($condition['whereIn']) && $condition['whereIn']) {
            foreach ($condition['whereIn'] as $k => $v) {
                $builder->whereIn($k, $v);
            }
        }

        if (isset($condition['order']) && $condition['order']) {
            foreach ($condition['order'] as $k => $v) {
                $builder->orderBy($k, $v);
            }
        }

        $builderTotal = clone $builder;

        $total = $builderTotal->countAllResults();

        $builder->limit($pageSize, $startSize);

        $query = $builder->get();

        $list = $query->getResultArray();
        return ['total' => $total, 'list' => $list];
    }

    /**
     * [getWeibos description]  多个文章-带分页
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getWeiboCommentsList($condition = [], $field = "*", $page = 1, $pageSize = 15)
    {
        $pageSize = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_weibo_comment');
        $builder->select($field);

        if (isset($condition['where']) && $condition['where']) {
            foreach ($condition['where'] as $k => $v) {
                $builder->where($k, $v);
            }
        }

        if (isset($condition['whereIn']) && $condition['whereIn']) {
            foreach ($condition['whereIn'] as $k => $v) {
                $builder->whereIn($k, $v);
            }
        }

        if (isset($condition['order']) && $condition['order']) {
            foreach ($condition['order'] as $k => $v) {
                $builder->orderBy($k, $v);
            }
        }

        $builderTotal = clone $builder;

        $total = $builderTotal->countAllResults();

        $builder->limit($pageSize, $startSize);

        $query = $builder->get();

        $list = $query->getResultArray();
        return ['total' => $total, 'list' => $list];
    }

    public function addWeibo($data)
    {
        $builder = $this->db->table('ts_weibo');
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function editWeibo($data, $where)
    {
        $builder = $this->db->table('ts_weibo');
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    public function delWeibo($where)
    {
        $builder = $this->db->table('ts_weibo');
        $builder->delete($where);
        return $this->db->affectedRows();
    }

    public function addWeiboComment($data)
    {
        $builder = $this->db->table('ts_weibo_comment');
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function editWeiboComment($data, $where)
    {
        $builder = $this->db->table('ts_weibo_comment');
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    public function delWeiboComment($where)
    {
        $builder = $this->db->table('ts_weibo_comment');
        $builder->delete($where);
        return $this->db->affectedRows();
    }
}
