<?php namespace App;

use Hash;
use Illuminate\Support\Facades\DB;

/*
* For now, the extra fields feature remains untested and not useful yet. Feel free to expand on the commented ideas, should you need extra fields (eg. LoL ranking, material contribution, acommodation... whatever comes to mind).
*/

class Entry {
/*
	private function insertFieldEntries($id_entry, $entries) {
		$l = count($entries);
		$entry_sql = "";
		$entry_array = [];
		for ($i = 0 ; $i < $l ; $i++) {
			$entry_sql += "(?," . $id_entry . ",?)";
			$entry_array[] = $entries->id_field;
			$entry_array[] = $entries->data;
			if ($i < $l-1) {
				$entry_sql += ",";
			}
		}
		DB::insert('INSERT INTO FieldEntries(id_field, id_entry, field) VALUES ' . $field_sql, $entries);
	}
*/
	static function create($entry) {
		DB::insert('INSERT INTO Entries(name, real_name, email, id_game, campus, time, id_team, password, is_approved) VALUES (:name, :real_name, :email, :id_game, :campus, :time, :id_team, :password, :is_approved)', $entry);
		$id = DB::getPdo()->lastInsertId();
//		if (!empty($entry->extra_fields)) {
//			this.insertFieldEntries($id, $entry->extra_entries);
//		}
		return $id;
	}

	static function getPassword($id, $email, $new_password) {
		$entry = DB::select('SELECT name, email FROM Entries WHERE rowid=?', [$id]);
		if (empty($entry) || $entry[0]->email !== $email)
			return null;
		DB::update("UPDATE Entries SET password=? WHERE rowid=? AND id_team=?", [Hash::make($new_password), $id]);

		return $entry[0];
	}

	static function get($id) {
		$entry = DB::select('SELECT rowid, real_name, name, id_game, campus, time, is_approved, email FROM Entries WHERE rowid=?', [$id]);
		if (empty($entry))
			return null;
//		$entry[0]->extra_fields = DB::select('SELECT id_field, field FROM FieldEntries WHERE id_entry=?', [$id]);
		$entry[0]->players = DB::select('SELECT rowid, real_name, name, campus, time FROM Entries WHERE id_team=?', [$id]);
		return $entry[0];
	}

	static function update($entry) {
		$pass = DB::select("SELECT password FROM Entries WHERE rowid=?", [$entry['id']]);
		if (empty($pass))
			return 0;
		if (Hash::check($entry['password'], $pass[0]->password)) {
			DB::update('UPDATE Entries SET name=?, real_name=?, campus=? WHERE rowid=?', [$entry['name'], $entry['real_name'], $entry['campus'], $entry['id']]);
			DB::update("UPDATE Entries SET password=? WHERE rowid=? AND id_team=?", [Hash::make($entry['password']), $entry['id'], $entry['id']]);
//			DB::delete('DELETE FROM FieldEntries WHERE id_entry=?', [$entry['id']]);
//			if (!empty($entry->extra_fields)) {
//				this.insertFieldEntries($entry['id'], $entry['entries']);
//			}
			return 1;
		}
		return 0;
	}

	static function approve($id, $pass) {
		$entry = DB::select("SELECT password FROM Entries WHERE rowid=?", [$id]);
		if (Hash::check($pass, $entry[0]->password)) {
			return DB::update("UPDATE Entries SET is_approved=1, password=? WHERE rowid=?", [Hash::make($pass), $id]);
		}
		return 0;
	}

	static function delete($id, $pass) {
		$entry = DB::select("SELECT password FROM Entries WHERE rowid=?", [$id]);
		if (Hash::check($pass, $entry[0]->password)) {
			DB::delete('DELETE FROM Entries WHERE rowid=?',[$id]);
			DB::delete('DELETE FROM Entries WHERE id_team=?',[$id]);
			return 1;
		}
		return 0;
	}

	static function getBySoloGame($id_game) {
		$entries = DB::select("SELECT rowid, real_name, name, id_game, campus, time, id_team FROM Entries WHERE id_game=? AND is_approved='1' ORDER BY time", [$id_game]);
//	Below was the result of a terrible optimization idea. You should probably just try joining tables rather than going through countless hoops like this.

//		$fields = DB::select('SELECT id_field, id_entry, field FROM FieldEntries WHERE id_field IN (SELECT rowid FROM Fields WHERE id_game=?) ORDER BY id_entry', [$id_game]);
//		$l = count($fields);
//		if ($l > 0) {
//			$i = 0;
//			foreach ($entries as $entry) {
//				$tmp_tab = [];
//				while (($i < $l) && ($fields[i]->id_entry == $entry->id)) {
//					$tmp_tab[] = ['id_field' => $fields[i]->id_field, 'data' => $fields[i]->field];
//					$i++;
//				}
//				$entry->extra_fields = $tmp_tab;
//			}
//		}
		return $entries;
	}

	static function getByTeamGame($id_game) {
		$teams = DB::select("SELECT rowid, name, id_game, campus, time, id_team FROM Entries WHERE id_game=? AND id_team IS NULL AND is_approved='1' ORDER BY time",[$id_game]);
		foreach ($teams as $team) {
//			$team->extra_fields = DB::select('SELECT * FROM FieldEntries WHERE id_entry=?', [$team->rowid]);
			$team->players = DB::select('SELECT rowid, real_name, name, id_game, campus, time, id_team FROM Entries WHERE id_team=? ORDER BY time', [$team->rowid]);
//			foreach ($team->players as $player) {
//				$player->extra_fields = DB::select('SELECT * FROM FieldEntries WHERE id_entry=?', [$player->rowid]);
//			}
		}
		return $teams;
	}
}

?>