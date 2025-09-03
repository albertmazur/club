<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Repository\CommentRepository;
use App\Http\Requests\Store\StoreCommentRequest;
use App\Http\Requests\Delete\DeleteCommentRequest;
use App\Http\Requests\Search\SearchCommentRequest;
use App\Http\Requests\Search\SearchMyCommentRequest;

class CommentController extends Controller
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index(SearchCommentRequest $request)
    {
        $data = $request->validated();

        $content = $data['content']  ?? null;
        $who = $data['who'] ?? null;
        $event = $data['event'] ?? null;

        $comments = $this->commentRepository->filterBy($who, $content,$event);

        return view('dashboard.comment.comment', [
            'comments' => $comments,
            'content' => $content,
            'who' => $who,
            'event' => $event,
            'users' => User::all(),
            'events' => Event::all()
        ]);
    }

    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();

        $this->commentRepository->add($data['content'], $data['event_id']);

        return back()->with('success', __('dashboard.comment.add'));
    }

    public function destroy(DeleteCommentRequest $request)
    {
        $id = $request->validated()['id'];
        
        $data = $this->commentRepository->delete($id) 
            ? ['success' => __('dashboard.comment.deleted')]
            : ['error' => __('dashboard.comment.delete_error')];

        return back()->with($data);
    }

    public function myComments(SearchMyCommentRequest $request)
    {
        $data = $request->validated();
        $content = $data['content'] ?? null;
        $eventId = $data['eventId'] ?? null;

        $comments = $this->commentRepository->filterBy(Auth::id(), $content, $eventId);

        return view('dashboard.client.comment', [
            'comments' => $comments,
            'content' => $content,
            'eventId' => $eventId,
            'events' => Event::all()
        ]);
    }
}
