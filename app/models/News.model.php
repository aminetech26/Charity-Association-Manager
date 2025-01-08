<?php
class NewsModel{
    use Model;
    protected $table = 'news';
    protected $allowedColumns = ['id','titre','contenu','thumbnail_url','date_publication'];

    public function getAllNewsArticles($limit = 10,$offset = 0){
        return $this->findAll($limit,$offset);
    }

    public function getArticleById($id){
        return $this->find(['id' => $id]);
    }

    public function getTotalNewsArticles(){
        return $this->getTotalCount();
    }
}