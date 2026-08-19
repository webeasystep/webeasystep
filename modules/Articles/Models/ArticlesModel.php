<?php

namespace Modules\Articles\Models;

use App\Models\BaseModel;

class ArticlesModel extends BaseModel
{
    protected $table = 'articles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'slug', 'description', 'content', 'image', 'sort', 'active'];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    /**
     * Get paginated active articles
     */
    public function getActiveArticles(int $perPage = 9)
    {
        return $this->where('active', 1)
            ->orderBy('sort', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate($perPage);
    }

    /**
     * Get single article by slug or ID
     */
    public function getArticleBySlug(string $slug)
    {
        $article = $this->where('slug', $slug)
            ->where('active', 1)
            ->first();

        if (!$article && is_numeric($slug)) {
            $article = $this->where('id', (int)$slug)
                ->where('active', 1)
                ->first();
        }

        return $article;
    }

    /**
     * Get recent articles excluding current
     */
    public function getRecentArticles(?int $excludeId = null, int $limit = 4): array
    {
        $builder = $this->where('active', 1);

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->orderBy('id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResult();
    }

    /**
     * Search active articles by query
     */
    public function searchArticles(string $query, int $perPage = 9)
    {
        return $this->where('active', 1)
            ->groupStart()
                ->like('title', $query)
                ->orLike('description', $query)
                ->orLike('content', $query)
            ->groupEnd()
            ->orderBy('sort', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate($perPage);
    }
}
