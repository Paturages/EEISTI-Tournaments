<?php namespace App\Http\Controllers\Signups;

use App\Http\Controllers\Controller;
use App\Game;
use App\Entry;

/* GET API for other interfaces */
class ReadController extends Controller {
	/* Sends everything. Typically can be used in an interface where you load and render everything and hide/show fields as the user goes. */
	function getAll() {
		$games = Game::getAll();
		$entries = [];
		foreach ($games as $game) {
			if ($game->num_players > 1) {
				$entries[] = Entry::getByTeamGame($game->rowid);
			} else {
				$entries[] = Entry::getBySoloGame($game->rowid);
			}
		}
		return response()->json([
			'games' => $games,
			'entries' => $entries
		]);
	}

	/* One game. Should be used whenever an user requests a particular game. */
	function getOne($id_game) {
		$game = Game::get($id_game);
		if ($game->num_players > 1) {
			$entries = Entry::getByTeamGame($id_game);
		} else {
			$entries = Entry::getBySoloGame($id_game);
		}
		return response()->json($entries);
	}

	/* Only games */
	function getGames() {
		return response()->json(Game::getAll());
	}
}

?>