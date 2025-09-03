<?php

namespace App\Http\Controllers;

use App\Repository\StadiumRepository;
use App\Http\Requests\Search\SearchStadiumRequest;
use App\Http\Requests\Store\StoreStadiumRequest;
use App\Http\Requests\Update\UpdateStadiumRequest;

class StadiumController extends Controller
{
    private StadiumRepository $stadiumRepository;

    public function __construct(StadiumRepository $stadiumRepository)
    {
        $this->stadiumRepository = $stadiumRepository;
    }

    public function list()
    {
        $stadiums = $this->stadiumRepository->allPaginated(5);

        return view('stadium.list', [
            'stadiums' => $stadiums,
        ]);
    }

    public function index(SearchStadiumRequest $request)
    {
        $name = $request->validated()['name'] ?? null;
        $resultPaginator = $this->stadiumRepository->filterBy($name, 10);

        return view('dashboard.admin.stadium.main', [
            'stadiums' => $resultPaginator,
            'name' => $name
        ]);
    }

    public function store(StoreStadiumRequest $request)
    {
        $date = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')){
            $imagePath = $request->file('image')->store('stadium', 'public');
        }

        $this->stadiumRepository->add(
            $date['name'],
            $date['description'],
            $date['city'],
            $date['street'],
            $date['numberBuilding'],
            $date['places'],
            $imagePath
        );

        return back()->with('success', __('dashboard.stadium.added'));
    }

    public function edit(int $stadiumId)
    {
        $this->authorize('isAdmin', 'role');

        return view('dashboard.admin.stadium.edit', [
            'stadium' => $this->stadiumRepository->get($stadiumId)
        ]);
    }

    public function update(UpdateStadiumRequest $request)
    {
        $date = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')){
            $imagePath = $request->file('image')->store('stadium', 'public');
        }

        $this->stadiumRepository->update(
            $date['id'],
            $date['name'],
            $date['description'],
            $date['city'],
            $date['street'],
            $date['numberBuilding'],
            $date['places'],
            $imagePath
        );

        return redirect()->route('stadium.index')->with('success', __('app.save_changes'));
    }
}
