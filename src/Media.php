<?php

namespace Flysap\Media;

use Cartalyst\Tags\TaggableInterface;
use Cartalyst\Tags\TaggableTrait;
use Eloquent\Translatable\Translatable;
use Eloquent\Translatable\TranslatableTrait;
use Illuminate\Database\Eloquent\Model;

class Media extends Model implements TaggableInterface, Translatable {

    use TaggableTrait;

    use TranslatableTrait;

    public $table = 'media';

    protected $translation_id = 'media_id';

    protected $translationClass = MediaTranslation::class;

    public $translatedAttributes = [
        'title' => 'text',
        'description' => 'wysiwyg',
    ];

    public $timestamps = true;

    public $fillable = ['id', 'path', 'full_path', 'active'];

    /**
     * Manage show fields .
     *
     * @return array
     */
    public function skyShow() {
        return ['id', 'path' => ['closure' => function($path) {
            return '<img src="'.$path.'" width="200px">';
        }]];
    }

    /**
     * Get presenter .
     *
     * @return MediaPresenter
     */
    public function getPresenter() {
        return new MediaPresenter($this);
    }

    /**
     * Active scope .
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query) {
        return $query->whereActive(true);
    }

    /**
     * Inactive scope
     *
     * @param $query
     * @return mixed
     */
    public function scopeInactive($query) {
        return $query->whereActive(false);
    }
}