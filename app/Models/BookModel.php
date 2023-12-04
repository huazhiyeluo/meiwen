<?php namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model {
    protected $table      = 'ts_book';
    protected $primaryKey = 'book_id';

    protected $allowedFields = [];
    protected $beforeInsert  = ['beforeInsert'];
    protected $beforeUpdate  = ['beforeUpdate'];

    protected $db;

    public function __construct() {
        $this->db = db_connect();
    }

    protected function beforeInsert(array $data) {
        $data['data']['addtime'] = time();
        return $data;
    }

    protected function beforeUpdate(array $data) {
        return $data;
    }

    /**
     * [getArticles description] 单个文章
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getBook($condition = [], $field = "*") {
        $builder = $this->db->table('ts_book');
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
     * [getArticles description]
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getBooks($condition = [], $field = "*", $page = 1, $pageSize = 15) {
        $pageSize  = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_book');
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
     * [getArticles description]
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getBooksList($condition = [], $field = "*", $page = 1, $pageSize = 15) {
        $pageSize  = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db->table('ts_book');
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
     * [addArticle description] 新增书籍
     * @param [type] $data [description]
     */
    public function addBook($data) {
        $builder = $this->db->table('ts_book');
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function editBook($data, $where) {
        $builder = $this->db->table('ts_book');
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }

}