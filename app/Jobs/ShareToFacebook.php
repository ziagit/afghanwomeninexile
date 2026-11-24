<?php

namespace App\Jobs;

use App\Models\Activity;
use App\Models\News;
use App\Services\FacebookPage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;
use Throwable;

// Posts a new News item or Activity to the Facebook Page in the background,
// so saving in the admin never waits on Facebook.
class ShareToFacebook implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [60, 300];

    public function __construct(public News|Activity $item)
    {
    }

    public function handle(FacebookPage $facebook): void
    {
        if ($this->item->facebook_post_id) {
            return;
        }

        $postId = $facebook->publish($this->message(), $this->link(), $this->item->image);

        $this->item->forceFill(['facebook_post_id' => $postId, 'facebook_error' => null])->save();
    }

    public function failed(Throwable $exception): void
    {
        $this->item->forceFill(['facebook_error' => Str::limit($exception->getMessage(), 500)])->save();
    }

    // English title and opening text, then the Dari title when one was entered.
    private function message(): string
    {
        $item = $this->item;
        $paragraphs = preg_split("/\R{2,}/", trim((string) $item->body)) ?: [];
        $excerpt = Str::limit(implode("\n\n", array_slice(array_filter(array_map('trim', $paragraphs)), 0, 2)), 600);

        return collect([$item->title, $excerpt, $item->title_fa])->filter()->implode("\n\n");
    }

    private function link(): string
    {
        return $this->item instanceof Activity
            ? route('activities.show', $this->item->id)
            : route('media.show', $this->item);
    }
}
