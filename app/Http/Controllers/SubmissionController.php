<?php

namespace App\Http\Controllers;

use App\Repository\SubmissionRepository;
use App\Http\Requests\Delete\DeleteSubmissionRequest;
use App\Http\Requests\Search\SearchSubmissionRequest;
use App\Http\Requests\Store\StoreSubmissionRequest;

class SubmissionController extends Controller
{
    private SubmissionRepository $submissionRepository;

    public function __construct(SubmissionRepository $submissionRepository)
    {
        $this->submissionRepository = $submissionRepository;
    }

    public function index(SearchSubmissionRequest $request)
    {
        $data = $request->validated();

        $content = $data['content'] ?? null;
        $reason = $data['reason'] ?? null;

        return view('dashboard.submission.submission', [
            'submissions' => $this->submissionRepository->filterBy($content, $reason),
            'content' => $content,
            'reason' => $reason
        ]);
    }

    public function store(StoreSubmissionRequest $request)
    {
        $data = $request->validated();

        $this->submissionRepository->add($data['reason'], $data['description'], $data['comment_id']);

        return back()->with('success', __('app.added_submission'));
    }

    public function destroy(DeleteSubmissionRequest $request)
    {
        $date = $request->validated();

        $deleteComment = $date['action'] === 'submission_and_comment';
        $this->submissionRepository->deleteWithComment($date['id'], $deleteComment);

        return back()->with('success', __('dashboard.comment.deleted_comments_submission'));
    }
}
