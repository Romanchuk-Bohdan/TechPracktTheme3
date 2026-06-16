<?php

namespace src\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/teams', name: 'api_teams_')]
class TeamController extends AbstractController
{
    private array $teams = [
        1 => ['id' => 1, 'name' => 'Ferrari', 'principal' => 'Frederic Vasseur', 'championships' => 16, 'base' => 'Maranello, Italy'],
        2 => ['id' => 2, 'name' => 'Red Bull Racing', 'principal' => 'Christian Horner', 'championships' => 6, 'base' => 'Milton Keynes, UK'],
        3 => ['id' => 3, 'name' => 'Mercedes', 'principal' => 'Toto Wolff', 'championships' => 8, 'base' => 'Brackley, UK'],
    ];

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json(array_values($this->teams));
    }

    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true) ?? [];
        $newId = max(array_keys($this->teams)) + 1;

        $newTeam = [
            'id' => $newId,
            'name' => $content['name'] ?? 'Unknown Team',
            'principal' => $content['principal'] ?? 'Unknown Principal',
            'championships' => $content['championships'] ?? 0,
            'base' => $content['base'] ?? 'Unknown Base',
        ];

        $this->teams[$newId] = $newTeam;

        return $this->json([
            'message' => 'Команду успішно додано',
            'data' => $newTeam
        ], 201);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        if (!isset($this->teams[$id])) {
            return $this->json(['error' => 'Команду не знайдено'], 404);
        }

        return $this->json($this->teams[$id]);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'])]
    public function update(Request $request, int $id): JsonResponse
    {
        if (!isset($this->teams[$id])) {
            return $this->json(['error' => 'Команду не знайдено'], 404);
        }

        $content = json_decode($request->getContent(), true) ?? [];
        $team = $this->teams[$id];

        if (isset($content['name'])) $team['name'] = $content['name'];
        if (isset($content['principal'])) $team['principal'] = $content['principal'];
        if (isset($content['championships'])) $team['championships'] = $content['championships'];
        if (isset($content['base'])) $team['base'] = $content['base'];

        $this->teams[$id] = $team;

        return $this->json([
            'message' => 'Команду оновлено',
            'data' => $team
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        if (!isset($this->teams[$id])) {
            return $this->json(['error' => 'Команду не знайдено'], 404);
        }

        $deletedTeam = $this->teams[$id];
        unset($this->teams[$id]);

        return $this->json([
            'message' => 'Команду видалено',
            'deleted_data' => $deletedTeam
        ]);
    }
}
