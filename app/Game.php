<?php namespace App;

use Illuminate\Support\Facades\DB;

/*
* For now, the extra fields feature remains untested and not useful yet. Feel free to expand on the commented ideas, should you need extra fields (eg. LoL ranking, material contribution, acommodation... whatever comes to mind).
*/

class Game {
/*
	private function insertFields($id_game, $fields) {
		$l = count($names);
		$field_sql = "";
		for ($i = 0 ; $i < $l ; $i++) {
			$field_sql += "(" . $id . ",?,?)";
			if ($i < $l-1) {
				$field_sql += ",";
			}
		}
		DB::insert('INSERT INTO Fields(id_game, field, description) VALUES ' . $field_sql, $fields->data, $fields->description);
	}
*/
	static function create($game) {
		DB::insert('INSERT INTO Games(name, num_players, nickname_field, multicampus) VALUES (:name, :num_players, :nickname_field, :multicampus)', $game);
		$id = DB::getPdo()->lastInsertId();
//		if (!empty($game->extra_fields)) {
//			this.insertFields($id, $game->extra_fields);
//		}
		return $id;
	}

	static function get($id) {
		$game = DB::select('SELECT rowid,* FROM Games WHERE rowid=?', [$id])[0];
//		$game->extra_fields = DB::select('SELECT rowid, field, description FROM Fields WHERE id_game = ?', [$id]);
		return $game;
	}

	static function update($game) {
		DB::update('UPDATE Games SET name=:name, num_players=:num_players, nickname_field=:nickname_field, multicampus=:multicampus WHERE rowid=:rowid', $game);
//		DB::delete('DELETE FROM Fields WHERE id_game=:rowid', $game);
//		if (!empty($game->extra_fields)) {
//			this.insertFields($game->rowid, $game->extra_fields);
//		}
		return 1;
	}

	static function delete($id) {
		return DB::delete('DELETE FROM Games WHERE rowid=?', [$id]);
	}

	static function getAll() {
		$games = DB::select('SELECT rowid,* FROM Games ORDER BY rowid', []);
//	Below was the result of a terrible optimization idea. You should probably just try joining tables rather than going through countless hoops like this.

//		$fields = DB::select('SELECT id_game, field, description FROM Fields ORDER BY id_game', []);
//		$l = count($fields);
//		if ($l > 0) {
//			$i = 0;
//			foreach ($games as $game) {
//				$tmp_tab = [];
//				while (($i < $l) && ($fields[$i]->id_game == $game->rowid)) {
//					$tmp_tab[] = $fields[$i]->field;
//					$i++;
//				}
//				$game->extra_fields = $tmp_tab;
//			}
//		}
		return $games;
	}

	static function purge($id) {
		return DB::delete('DELETE FROM Entries WHERE id_game=?', [$id]);
	}
}

?>