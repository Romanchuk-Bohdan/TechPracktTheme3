<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    private array $teams = [
        1 => ['id' => 1, 'name' => 'Ferrari', 'principal' => 'Frederic Vasseur', 'championships' => 16, 'base' => 'Maranello, Italy'],
        2 => ['id' => 2, 'name' => 'Red Bull Racing', 'principal' => 'Christian Horner', 'championships' => 6, 'base' => 'Milton Keynes, UK'],
        3 => ['id' => 3, 'name' => 'Mercedes', 'principal' => 'Toto Wolff', 'championships' => 8, 'base' => 'Brackley, UK'],
    ];

    public function index()
    {
        return response()->json(array_values($this->teams));
    }

    public function store(Request $request)
    {
        $content = $request->json()->all();
        $newId = max(array_keys($this->teams)) + 1;

        $newTeam = [
            'id' => $newId,
            'name' => $content['name'] ?? 'Unknown Team',
            'principal' => $content['principal'] ?? 'Unknown Principal',
            'championships' => $content['championships'] ?? 0,
            'base' => $content['base'] ?? 'Unknown Base',
        ];

        $this->teams[$newId] = $newTeam;

        return response()->json([
            'message' => 'Команду успішно додано',
            'data' => $newTeam
        ], 201);
    }

    public function show($id)
    {
        if (!isset($this->teams[$id])) {
            return response()->json(['error' => 'Команду не знайдено'], 404);
        }

        return response()->json($this->teams[$id]);
    }

    public function update(Request $request, $id)
    {
        if (!isset($this->teams[$id])) {
            return response()->json(['error' => 'Команду не знайдено'], 404);
        }

        $content = $request->json()->all();
        $team = $this->teams[$id];

        if (isset($content['name'])) $team['name'] = $content['name'];
        if (isset($content['principal'])) $team['principal'] = $content['principal'];
        if (isset($content['championships'])) $team['championships'] = $content['championships'];
        if (isset($content['base'])) $team['base'] = $content['base'];

        $this->teams[$id] = $team;

        return response()->json([
            'message' => 'Команду оновлено',
            'data' => $team
        ]);
    }

    public function destroy($id)
    {
        if (!isset($this->teams[$id])) {
            return response()->json(['error' => 'Команду не знайдено'], 404);
        }

        $deletedTeam = $this->teams[$id];
        unset($this->teams[$id]);

        return response()->json([
            'message' => 'Команду видалено',
            'deleted_data' => $deletedTeam
        ]);
    }
}