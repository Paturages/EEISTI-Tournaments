<?php namespace App\Http\Controllers\Signups;

use App\Http\Controllers\Controller;
use App\Game;
use App\Entry;

/* Server-side rendering of pages */
class LightController extends Controller {
	/* Create form */
	function getCreateForm($id_game) {
		$game = Game::get($id_game);
		return view((($game->num_players > 1) ? 'light.form_multi' : 'light.form_solo'), [
			'game' => $game,
			'mode' => 'create'
		]);
	}

	/* Edit form */
	function getEditForm($id_entry) {
		$entry = Entry::get($id_entry);
		$form_entry = [
			'id' => $entry->rowid,
			'real_name' => empty($entry->real_name) ? [] : $entry->real_name,
			'name' => $entry->name,
			'campus' => $entry->campus
		];
		$game = Game::get($entry->id_game);
		if ($game->num_players > 1) {
			$l = count($entry->players);
			for ($i = 0 ; $i < $l ; $i++) {
				$form_entry['real_name'][] = $entry->players[$i]->real_name;
				$form_entry['p_name'][] = $entry->players[$i]->name;
				$form_entry['p_campus'][] = $entry->players[$i]->campus;
			}
		}
		return view((count($entry->players) > 0 ? 'light.form_multi' : 'light.form_solo'), [
			'game' => $game,
			'entry' => $form_entry,
			'mode' => 'edit'
		]);
	}

	/* Delete form */
	function getDeleteForm($id_entry) {
		$entry = Entry::get($id_entry);
		return view('light.verify', [
			'is_delete' => true,
			'entry' => $entry
		]);
	}

	function getVerifyForm($id_entry) {
		$entry = Entry::get($id_entry);
		if (empty($entry))
			return redirect('light');
		if ($entry->is_approved > 0)
			return redirect('light/'.$entry->id_game);
		return view('light.verify', [
			'is_delete' => false,
			'entry' => $entry
		]);
	}

	/* One game */
	function getOne($id_game) {
		$game = Game::get($id_game);
		if ($game->num_players > 1) {
			$entries = Entry::getByTeamGame($id_game);
		} else {
			$entries = Entry::getBySoloGame($id_game);
		}
		if (empty($entries)) {
			return view('light.empty', ['game' => $game]);
		}
		return view((($game->num_players > 1) ? 'light.multi' : 'light.solo'), [
			'game' => $game,
			'entries' => $entries
		]);
	}

	/* Only games */
	function getGames() {
		return view('light.menu', ['games' => Game::getAll()]);
	}
}

?>