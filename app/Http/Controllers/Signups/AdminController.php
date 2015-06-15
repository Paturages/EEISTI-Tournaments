<?php namespace App\Http\Controllers\Signups;

use App\Http\Controllers\Controller;
use App\Game;

class AdminController extends Controller {
	function createGame($game) {
		return Game::create($game);
	}
	function updateGame($game) {
		return Game::update($game);
	}
	function deleteGame($id) {
		return Game::delete($id);
	}
	function purgeGames($id) {
		return Game::purge($id);
	}
}

?>