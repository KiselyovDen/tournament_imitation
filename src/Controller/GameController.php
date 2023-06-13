<?php

namespace App\Controller;

use App\Enum\GameType;
use App\GameResultProcessors\GameResultProcessorCreator;
use App\Model\DivisionTableModel;
use App\Registry\GameRegistry;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Validation;

class GameController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(GameRepository $gameRepository, TeamRepository $teamRepository): Response
    {
        $teams = $teamRepository->findAll();
        $games = $gameRepository->findAll();

        $gameRegistry = GameRegistry::getInstance();
        $gameRegistry->loadGames($games);

        return $this->render(
            'index.html.twig',
            [
                'divisions' => $gameRegistry->getDivisionGames($teams),
                'playOff' => $gameRegistry->getPlayOffGames(),
                'winners' => $gameRegistry->getWinnersTable()
            ]
        );
    }

    #[Route('/create-divisions-table', name: 'create-divisions-table', methods: 'POST')]
    public function createDivisionsTables(Request $request, DivisionTableModel $divisionTable): Response
    {
        $teamsCount = (int)$request->request->get('teams_count', 0);

        $validator = Validation::createValidator();
        $violations = $validator->validate($teamsCount, [
            new Range(['min' => 8, 'max' => 20]),
        ]);

        if($violations->count() > 0) {
            $this->addFlash('error', $violations->get(0)->getMessage());
            $this->addFlash('tmp_teams_count', $teamsCount);
            return $this->redirectToRoute('index');
        }

        $divisionTable->createDivisions($teamsCount);

        return $this->redirectToRoute('index');
    }

    #[Route('/generate-results/{step}', name: 'generate-results')]
    public function generateResults(string $step, GameResultProcessorCreator $gameProcessorCreator): Response
    {
        $type = GameType::tryFrom(strtoupper($step));
        if (!$type) {
            return $this->redirectToRoute('index');
        }
        if (!in_array($type, [GameType::DIVISION, GameType::QUARTER, GameType::HALF], true)) {
            return $this->redirectToRoute('index');
        }

        try {
            $processor = $gameProcessorCreator->createProcessor($type);
            $processor->process();
        } catch (Exception) {

        } finally {
            return $this->redirectToRoute('index');
        }
    }
}