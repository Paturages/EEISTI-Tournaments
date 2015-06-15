<?php namespace App\Http\Controllers\Signups;

use Validator;
use Session;
use Hash;
use Crypt;
use Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Game;
use App\Entry;

/* POST API */
class EntryAPIController extends Controller {
	private function generatePass() {
		$res = "";
		for ($i = 0 ; $i < 8 ; $i++) {
			$res .= chr(mt_rand(65,90));
		}
		return $res;
	}

    function submit($id_game, Request $req) {
        $game = Game::get($id_game);
        if ($game->num_players > 1)
            return $this->submitTeam($id_game, $req);
        else
            return $this->submitSolo($id_game, $req);
    }

    /* Re-send password if password forgotten */
    function forgot($id_entry, Request $req) {
        $new_password = $this->generatePass();
        $entry = Entry::getPassword($id_entry, $new_password);
        if (empty($entry)) {
            if ($req->is('light/*')) {
                return redirect()->back()->with('errors', ["Entrée non existante."])->with('entry', $entry);
            }
            return response()->json(["Entrée non existante."], 400);
        }
        Mail::send('email.forgot', ['password' => $new_password], function($message) use ($entry) {
            $message->to($entry->email)->subject('E-EISTI : Nouveau mot de passe pour '.$entry->name);
        });
        if ($req->is('light/*'))
            return redirect('light/')->with('message', 'Le nouveau code de confirmation a été réenvoyé par e-mail. Contacter le bureau en cas de problèmes.');
        return response()->json("success");
    }

    /* Solo game input (before approval) */
    function submitSolo($id_game, Request $req) {
        $entry = $req->all();
        $entry['id_game'] = $id_game;
        $vld = Validator::make($entry, [
            'real_name' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'campus' => 'required|in:Cergy,Pau,Mixte',
            'id_game' => 'required|numeric'
        ]);
        if ($vld->fails()) {
            if ($req->is('light/*')) {
                return redirect()->back()->with('errors', $vld->messages()->all())->with('entry', $entry);
            }
            return response()->json($vld->messages()->all(), 400);
        }
        $password = $this->generatePass();
	    $entry['password'] = Hash::make($password);
	    $entry['is_approved'] = 0;
        $entry['time'] = time();
        $entry['id_team'] = NULL;
	    $id = Entry::create($entry);
        Mail::send('email.simple', ['password' => $password, 'id' => $id, 'crpt_pass' => Crypt::encrypt($password)], function($message) use ($req) {
            $message->to($req->input('email'))->subject('E-EISTI : Mail de confirmation pour '.$req->input('name'));
        });
        if ($req->is('light/*'))
            return redirect('light/'.$id_game)->with('message', 'Le lien et le code de confirmation ont été envoyés par e-mail.');
	    return response()->json("success");
    }

    /* Team game input (before approval) */
    function submitTeam($id_game, Request $req) {
        $entry = $req->all();
        $entry['id_game'] = $id_game;
        $game = Game::get($id_game);
        $vld_approv = [
            'name' => 'required',
            'email' => 'required|email',
            'campus' => 'required|in:Cergy,Pau,Mixte',
            'real_name.0' => 'required',
            'p_name.0' => 'required',
            'p_campus.0' => 'required|in:Cergy,Pau,Mixte',
            'id_game' => 'required|numeric'
        ];
        for ($i = 1 ; $i < $game->num_players ; $i++) {
            $vld_approv['real_name.'.$i] = 'required_with:p_name.'.$i;
            $vld_approv['p_name.'.$i] = 'required_with:real_name.'.$i;
        }
    	$vld = Validator::make($entry, $vld_approv);
        if ($vld->fails()) {
            if ($req->is('light/*')) {
                return redirect()->back()->with('errors', $vld->messages()->all())->with('entry', $entry);
            }
            return response()->json($vld->messages()->all(), 400);
        }
        $password = $this->generatePass();
        $hash = Hash::make($password);
        $id = Entry::create([
            'name' => $req->input('name'),
            'campus' => $req->input('campus'),
            'email' => $req->input('email'),
            'id_game' => $id_game,
            'password' => $hash,
            'is_approved' => 0,
            'time' => time()
        ]);
        $i = 0;
        for ($i = 0 ; $i < $game->num_players ; $i++) {
            if ($req->input('p_name.'.$i))
                Entry::create([
                    'name' => $req->input('p_name.'.$i),
                    'real_name' => $req->input('real_name.'.$i),
                    'campus' => (!empty($req->input('p_campus.'.$i))) ? $req->input('p_campus.'.$i) : $req->input('campus'),
                    'email' => $req->input('email'),
                    'id_game' => $id_game,
                    'id_team' => $id,
                    'password' => $hash,
                    'time' => time()
                ]);
        }
        Mail::send('email.simple', ['password' => $password, 'id' => $id, 'crpt_pass' => Crypt::encrypt($password)], function($message) use ($req) {
            $message->to($req->input('email'))->subject('E-EISTI : Mail de confirmation pour '.$req->input('name'));
        });
        if ($req->is('light/*'))
            return redirect('light/'.$id_game)->with('message', 'Le lien et le code de confirmation ont été envoyés par e-mail.');
        return response()->json("success");
    }

    /* Solo player/Team approval code */
    function approve($id, $pass) {
    	return response()->json(Entry::approve($id, $pass));
    }

    /* Updating */
    function edit($id_entry, Request $req) {
        $entry = Entry::get($id_entry);
        if (!empty($entry->players))
            return $this->editTeam($entry->id_game, $id_entry, $entry->players, $entry->email, $req);
        else
            return $this->editSolo($entry->id_game, $id_entry, $req);
    }
    function editSolo($id_game, $id_entry, Request $req) {
        $entry = $req->all();
        $entry['id'] = $id_entry;
        $vld = Validator::make($entry, [
            'id' => 'required|numeric',
            'real_name' => 'required',
            'name' => 'required',
            'campus' => 'required|in:Cergy,Pau,Mixte',
            'password' => 'required'
        ]);
        if ($vld->fails()) {
            if ($req->is('light/*')) {
                return redirect()->back()->with('errors', $vld->messages()->all())->with('entry', $entry);
            }
            return response()->json($vld->messages()->all(), 400);
        }
        if (Entry::update($entry) > 0) {
            if ($req->is('light/*'))
                return redirect('light/'.$id_game);
            else
                return response()->json("success");
        }
        if ($req->is('light/*'))
            return redirect()->back()->with('errors', ["Mot de passe non valide."])->with('entry', $entry);
        return response()->json(["Mot de passe non valide."], 400);
    }
    function editTeam($id_game, $id_entry, $old_players, $email, Request $req) {
        $entry = $req->all();
        $entry['id'] = $id_entry;
        $game = Game::get($id_game);
        $vld_approv = [
            'name' => 'required',
            'password' => 'required',
            'campus' => 'required|in:Cergy,Pau,Mixte',
            'real_name.0' => 'required',
            'p_name.0' => 'required',
            'p_campus.0' => 'required|in:Cergy,Pau,Mixte',
            'id' => 'required|numeric'
        ];
        for ($i = 1 ; $i < $game->num_players ; $i++) {
            $vld_approv['real_name.'.$i] = 'required_with:p_name.'.$i;
            $vld_approv['p_name.'.$i] = 'required_with:real_name.'.$i;
        }
        $vld = Validator::make($entry, $vld_approv);
        if ($vld->fails()) {
            if ($req->is('light/*')) {
                return redirect()->back()->with('errors', $vld->messages()->all())->with('entry', $entry);
            }
            return response()->json($vld->messages()->all(), 400);
        }
        $team = $req->only('name', 'password', 'campus');
        $team['id'] = $id_entry;
        $team['real_name'] = '';
        if (Entry::update($team) == 0) {
            if ($req->is('light/*'))
                return redirect()->back()->with('errors', ["Mot de passe non valide."])->with('entry', $entry);
            return response()->json(["Mot de passe non valide."], 400);
        }
        $i = 0;
        while ($req->input('p_name.'.$i) || !empty($old_players[$i])) {
            if (empty($req->input('p_name.'.$i)))
                Entry::delete($old_players[$i]->rowid, $req->input('password'));
            else if (!empty($old_players[$i]))
                Entry::update([
                    'id' => $old_players[$i]->rowid,
                    'real_name' => $req->input('real_name.'.$i),
                    'name' => $req->input('p_name.'.$i),
                    'campus' => $req->input('p_campus.'.$i) || $players[0]['p_campus'],
                    'password' => $req->input('password')
                ]);
            else
                Entry::create([
                    'real_name' => $req->input('real_name.'.$i),
                    'name' => $req->input('p_name.'.$i),
                    'campus' => $req->input('p_campus.'.$i) || $players[0]['p_campus'],
                    'id_team' => $id_entry,
                    'password' => Hash::make($req->input('password')),
                    'time' => time(),
                    'email' => $email,
                    'id_game' => $id_game
                ]);
            $i++;
        }
        if ($req->is('light/*'))
            return redirect('light/'.$id_game);
        return response()->json("success");
    }

    /* Deleting */
    function deletePass($id_entry, Request $req) {
        return $this->delete($id_entry, $req, null);
    }
    function delete($id_entry, Request $req, $crpt_pass) {
        $vld = Validator::make(['id' => $id_entry, 'password' => $req->input('password'), 'crpt' => $crpt_pass], [
            'id' => 'required|numeric',
            'password' => 'required_without:crpt',
            'crpt' => 'required_without:password'
        ]);
        if ($vld->fails()) {
            if ($req->is('light/*')) {
                if (!empty($crpt_pass))
                    return redirect('light')->with('message', 'Lien erroné.');
                return redirect()->back()->with('errors', $vld->messages()->all());
            }
            return response()->json($vld->messages()->all(), 400);
        }
        $id_game = $req->input('id_game');
        $password = empty($crpt_pass) ? $req->input('password') : Crypt::decrypt($crpt_pass);
        if (Entry::delete($id_entry, $password) > 0)
            if ($req->is('light/*'))
                return redirect('light')->with('message', 'Entrée supprimée.');
            else
                return response()->json("success");
        if ($req->is('light/*')) {
            if (!empty($crpt_pass))
                return redirect('light')->with('message', 'Lien erroné.');
            return redirect()->back()->with('errors', ["Mot de passe non valide."]);
        }
        return response()->json(["Mot de passe non valide."], 400);
    }

    /* Verifying an account */
    function verifyPass($id_entry, Request $req) {
        return $this->verify($id_entry, $req, null);
    }
    function verify($id_entry, Request $req, $crpt_pass) {
        $vld = Validator::make(['id' => $id_entry, 'password' => $req->input('password'), 'crpt' => $crpt_pass], [
            'id' => 'required|numeric',
            'password' => 'required_without:crpt',
            'crpt' => 'required_without:password'
        ]);
        if ($vld->fails()) {
            if ($req->is('light/*')) {
                if (!empty($crpt_pass))
                    return redirect('light')->with('message', 'Lien erroné.');
                return redirect()->back()->with('errors', $vld->messages()->all());
            }
            return response()->json($vld->messages()->all(), 400);
        }
        $id_game = $req->input('id_game');
        $password = empty($crpt_pass) ? $req->input('password') : Crypt::decrypt($crpt_pass);
        if (Entry::approve($id_entry, $password) > 0)
            if ($req->is('light/*'))
                return redirect('/')->with('message', 'Entrée confirmée.');
            else
                return response()->json("success");
        if ($req->is('light/*')) {
            if (!empty($crpt_pass))
                return redirect('light')->with('message', 'Lien erroné.');
            return redirect()->back()->with('errors', ["Mot de passe non valide."]);
        }
        return response()->json("Mot de passe non valide.");
    }
}

?>