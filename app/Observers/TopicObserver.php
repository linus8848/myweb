<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;


// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic) {
        $topic->body = clean($topic->body, 'user_topic_body');
        $topic->excerpt = make_excerpt($topic->body);
    }

    public function saved(Topic $topic) {
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {
            dispatch(new TranslateSlug($topic));
        }
    }

    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function deleted(Topic $topic)
    {
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}