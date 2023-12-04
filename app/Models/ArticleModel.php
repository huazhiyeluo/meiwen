<?php namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model {
    protected $table      = 'ts_article_zuowen';
    protected $primaryKey = 'article_id';

    protected $db;
    protected $db_meiwen;
    protected $db_gushi;
    protected $db_zuowen;

    public function __construct() {
        $this->db        = db_connect();
        $this->db_meiwen = db_connect('meiwen');
        $this->db_gushi  = db_connect('gushi');
        $this->db_zuowen = db_connect('zuowen');
    }

    private function getTable($type) {
        $table = '';
        if ($type == 2) {
            $table = 'ts_article_meiwen';
        }
        if ($type == 3) {
            $table = 'ts_article_gushi';
        }
        if ($type == 4) {
            $table = 'ts_article_zuowen';
        }
        return $table;
    }

    private function getDb($type) {
        $db = '';
        if ($type == 2) {
            $db = $this->db_meiwen;
        }
        if ($type == 3) {
            $db = $this->db_gushi;
        }
        if ($type == 4) {
            $db = $this->db_zuowen;
        }
        return $db;
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
    public function getArticle($type, $condition = [], $field = "*") {
        $table   = $this->getTable($type);
        $builder = $this->db->table($table);
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
     * [getArticles description]  多个文章
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getArticles($type, $condition = [], $field = "*", $page = 1, $pageSize = 15) {
        $pageSize  = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $table   = $this->getTable($type);
        $builder = $this->db->table($table);
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
     * [getArticles description]  多个文章-带分页
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getArticlesList($type, $condition = [], $field = "*", $page = 1, $pageSize = 15) {
        $pageSize  = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $table   = $this->getTable($type);
        $builder = $this->db->table($table);
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
     * [addArticle description] 新增文章
     * @param [type] $data [description]
     */
    public function addArticle($type, $data) {
        $table   = $this->getTable($type);
        $builder = $this->db->table($table);
        $builder->insert($data);
        return $this->db->insertID();
    }


    public function editArticle($type, $data, $where) {
        $table   = $this->getTable($type);
        $builder = $this->db->table($table);
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    public function delArticle($type, $where) {
        $table   = $this->getTable($type);
        $builder = $this->db->table($table);
        $builder->delete($where);
        return $this->db->affectedRows();
    }

    /**
     * [getArticleContent description] - 文章 - 内容
     * @param  [type] $article_id [description]
     * @return [type]             [description]
     */
    public function getArticleContent($type, $article_id) {
        $index   = intval($article_id / 4000);
        $builder = $this->getDb($type)->table('ts_content_' . $index);
        $builder->select('*');
        $builder->where('article_id', $article_id);
        $query = $builder->get();
        $data  = $query->getRowArray();
        return $data;
    }

    /**
     * [getArticleContent description] - 文章 - 内容
     * @param  [type] $article_id [description]
     * @return [type]             [description]
     */
    public function getArticleContentMul($type, $article_id, $page = 1) {
        $index   = intval($article_id / 50000);
        $builder = $this->getDb($type)->table('ts_content_mul_' . $index);
        $builder->select('*');
        $builder->where('article_id', $article_id);
        $builder->where('page_num', $page);
        $query = $builder->get();
        $data  = $query->getRowArray();

        return $data;
    }

    /**
     * [getArticleContent description] - 文章 - 内容
     * @param  [type] $article_id [description]
     * @return [type]             [description]
     */
    public function getArticleContentMuls($type, $article_id) {
        $index   = intval($article_id / 50000);
        $builder = $this->getDb($type)->table('ts_content_mul_' . $index);
        $builder->select('*');
        $builder->where('article_id', $article_id);
        $query = $builder->get();
        $data  = $query->getResultArray();

        return $data;
    }


    /**
     * [getArticleContent description] - 文章 - 数量
     * @param  [type] $article_id [description]
     * @return [type]             [description]
     */
    public function getArticleContentMulCount($type, $article_id) {
        $index   = intval($article_id / 50000);
        $builder = $this->getDb($type)->table('ts_content_mul_' . $index);
        $builder->where('article_id', $article_id);
        $total = $builder->countAllResults();
        return $total;
    }

    /**
     * [addArticle description] 新增文章 -内容
     * @param [type] $data [description]
     */
    public function addArticleContent($type, $article_id, $data) {
        $index   = intval($article_id / 4000);
        $builder = $this->getDb($type)->table('ts_content_' . $index);
        $builder->insert($data);
        return $this->getDb($type)->affectedRows();
    }

    public function editArticleContent($type,$article_id, $data, $where) {
        $index   = intval($article_id / 4000);
        $builder = $this->getDb($type)->table('ts_content_' . $index);
        $builder->where($where);
        $builder->update($data);
        return $this->db->affectedRows();
    }


    public function delArticleContent($type, $article_id) {
        $index   = intval($article_id / 4000);
        $builder = $this->getDb($type)->table('ts_content_' . $index);
        $builder->delete(['article_id'=>$article_id]);
        return $this->db->affectedRows();
    }

    /**
     * [addArticle description] 新增文章 -内容
     * @param [type] $data [description]
     */
    public function addArticleMulContent($type, $article_id, $data) {
        $index   = intval($article_id / 50000);
        $builder = $this->getDb($type)->table('ts_content_mul_' . $index);
        $builder->insert($data);
        return $this->getDb($type)->affectedRows();
    }

    public function delArticleMulContent($type, $article_id) {
        $index   = intval($article_id / 50000);
        $builder = $this->getDb($type)->table('ts_content_mul_' . $index);
        $builder->delete(['article_id'=>$article_id]);
        return $this->db->affectedRows();
    }

    /**
     * [getArticleContent description] - 文章 - 内容 - 多
     * @param  [type] $article_id [description]
     * @return [type]             [description]
     */
    public function getArticleContentArr($type, $is_mul_page, $index, $article_ids) {
        if ($is_mul_page == 0) {
            $builder = $this->getDb($type)->table('ts_content_' . $index);
        }
        if ($is_mul_page == 1) {
            $builder = $this->getDb($type)->table('ts_content_mul_' . $index);
        }
        $builder->select('*');

        if ($is_mul_page == 1) {
            $builder->where('page_num', 1);
        }

        $builder->whereIn('article_id', $article_ids);
        $query = $builder->get();
        $data  = $query->getResultArray();
        return $data;
    }

    /**
     * 根据文章列表获取
     * @param  [type] $list [description]
     * @return [type]       [description]
     */
    public function getContentAll($type, $list) {
        $contentArr = [];
        $tableIndex = getContentTableIndex(array_column($list, 'is_mul_page', 'article_id'));
        if (isset($tableIndex[1])) {
            foreach ($tableIndex[1] as $index => $article_ids) {
                $tmpData    = $this->getArticleContentArr($type, 1, $index, $article_ids);
                $contentArr = array_merge($contentArr, $tmpData);
            }
        }
        if (isset($tableIndex[0])) {
            foreach ($tableIndex[0] as $index => $article_ids) {
                $tmpData    = $this->getArticleContentArr($type, 0, $index, $article_ids);
                $contentArr = array_merge($contentArr, $tmpData);
            }
        }

        $contentArr = array_column($contentArr, 'content', 'article_id');

        foreach ($list as $k => $v) {
            if (!$v['cover']) {
                $list[$k]['cover'] = getCover($contentArr[$v['article_id']]);
            }
            $list[$k]['desc'] = getInfo($contentArr[$v['article_id']]);
            unset($list[$k]['is_mul_page']);
        }
        return $list;
    }


    /**
     * 
     */
    /**
     * [getArticleContent description] - 文章 - 内容
     * @param  [type] $article_id [description]
     * @return [type]             [description]
     */
    public function getArticleContentTest($type, $article_id) {
        $index   = intval($article_id % 150);
        $builder = $this->getDb($type)->table('ts_content_copy_' . $index);
        $builder->select('*');
        $builder->where('article_id', $article_id);
        $query = $builder->get();
        $data  = $query->getRowArray();
        return $data;
    }



    public function getBySql($sql)
    {
        $this->db->query($sql);
    }
}