<?php

namespace App\Repository\Eloquent;

use App\Models\Submission;
use App\Repository\SubmissionRepository as Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SubmissionRepository implements Repository{
    private Submission $submissionModel;

    public function __construct(Submission $submission)
    {
        $this->submissionModel = $submission;
    }

    public function add(string $reason, string $content, int $comment_id)
    {
        $submission = new Submission();
        $submission->reason = $reason;
        $submission->content = $content;
        $submission->comment_id = $comment_id;
        $submission->save();
    }

    public function get(int $id): Submission{

        return $this->submissionModel->findOrFail($id);
    }

    public function allPaginated(int $limit): LengthAwarePaginator
    {
        return $this->submissionModel->paginate($limit);
    }

    public function all(): Collection{
        return $this->submissionModel->all();
    }

    public function filterBy(?string $content, string $reason = null): Collection{
        $query = $this->submissionModel->where('content', 'like', $content.'%');

        if($reason) $query = $query->where('reason', '=', $reason);

        return $query->get();
    }

    public function deleteWithComment(int $id, bool $deleteComment){
        $submission = $this->submissionModel->get( $id);
        if($deleteComment) $submission->comment->delete();
        $submission->delete();
    }
}
