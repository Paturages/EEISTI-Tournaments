<?php namespace App\Http\Controllers\Signups;

use App\Http\Controllers\Controller;
use App\Game;
use App\Entry;

/* Server-side rendering of pages */
class RenderedViewController extends Controller {
	/* Sends everything */
	function getAll($mobile) {
		$games = Game::getAll();
		$entries = [];
		foreach ($games as $game) {
			if ($game->num_players > 1) {
				$entries[] = Entry::getByTeamGame($game->rowid);
			} else {
				$entries[] = Entry::getBySoloGame($game->rowid);
			}
		}
		return view($mobile . 'games.detailed', [
			'games' => $games,
			'entries' => $entries
		]);
	}

	/* One game */
	function getOne($id_game, $mobile) {
		$game = Game::get($id_game);
		if ($game->num_players > 1) {
			$entries = Entry::getByTeamGame($id_game);
		} else {
			$entries = Entry::getBySoloGame($id_game);
		}
		return view($mobile . 'games.one', [
			'games' => $games,
			'entries' => $entries
		]);
	}

	/* Only games */
	function getGames($mobile) {
		return view($mobile . 'games.index', ['games' => Game::getAll()]);
	}
}

?>