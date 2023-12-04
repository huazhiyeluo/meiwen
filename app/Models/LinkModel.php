<?php namespace App\Models;

use CodeIgniter\Model;

class LinkModel extends Model
{
    protected $table = 'ts_links';
    protected $primaryKey = 'link_id';

    protected $allowedFields = [];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected $db;
    protected $db_content;
    protected $db_forum;

    public function __construct()
    {
        $this->db = db_connect();
    }

    protected function beforeInsert(array $data)
    {
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        return $data;
    }

    /**
     * [getArticles description]
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getLinks($condition = [], $field = "*", $page = 1, $pageSize = 15)
    {
        $pageSize = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_links');
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

}
