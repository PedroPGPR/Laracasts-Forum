<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\ConvertMarkdownToHtml;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'post_id', 'body', 'html'])]
class Comment extends Model
{
    use HasFactory;
    use ConvertMarkdownToHtml;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
