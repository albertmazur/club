<?php

namespace App\Repository\Eloquent;

use App\Enums\UserRole;
use App\Models\Comment;
use App\Repository\CommentRepository as Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CommentRepository implements Repository{
    private Comment $commentModel;

    public function __construct(Comment $comment)
    {
        $this->commentModel = $comment;
    }

    public function add(string $content, int $event_id)
    {
        $comment = new Comment();
        $comment->content = $content;
        $comment->user_id = Auth::id();
        $comment->event_id = $event_id;
        $comment->save();
    }

    public function get(int $id): Comment
    {
        return $this->commentModel->findOrFail($id);
    }

    public function delete(int $id): bool
    {
        $comment = $this->commentModel->findOrFail($id);
        $user = auth()->user();

        if ($user->role === UserRole::ADMIN->value || $user->role === UserRole::MODERATOR->value){
            return $comment->delete();
        }

        if ($user->id === $comment->user_id){
            return $comment->delete();
        }

        return false;
    }

    public function allPaginated(int $limit): LengthAwarePaginator
    {
        return $this->commentModel->orderBy('date')->orderBy('time')->paginate($limit);
    }

    public function all(): Collection
    {
        return $this->commentModel->all();
    }

    public function filterBy(?int $who, ?string $content, ?int $event): Collection
    {
        $query = $this->commentModel;

        if($who) $query = $query->where('user_id', '=', $who);
        if($event) $query = $query->where('event_id', '=', $event);
        if($content) $query = $query->where('content', 'like', $content.'%');

        return $query->get();
    }
}
