<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\MatchModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TournamentController extends Controller
{
    //halaman bikin room
    public function createRoom()
    {
        return view('create_room'); 
    }

    // post create Room Baru
    public function storeRoom(Request $request)
    {
        // Generate kode room
        $code = strtoupper(Str::random(6));

        DB::table('tournaments')->insert([
            'code' => $code,
            'name' => $request->input('name', 'Turnamen Padel ' . $code),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tournament.index', ['code' => $code]);
    }
	
	//proses buat turnament
    public function start(Request $request, $code)
	{
		$tournament = DB::table('tournaments')->where('code', $code)->first();
		if (!$tournament) abort(404);

		$request->validate([
			'players' => 'required|array|min:4',
			'courts' => 'required|integer|min:1'
		]);

		$playerNames = $request->players;
		$count = count($playerNames);
		$totalCourts = (int) $request->courts;

		if ($count < 4) {
			return redirect()->back()->with('error', 'Jumlah pemain minimal harus 4 orang.');
		}

		// HAPUS data turnamen sebelumnya jika generate ulang
		MatchModel::where('tournament_id', $tournament->id)->delete();
		Player::where('tournament_id', $tournament->id)->delete();

		DB::transaction(function () use ($playerNames, $count, $tournament, $totalCourts) {
			$insertedPlayers = [];
			foreach ($playerNames as $name) {
				$insertedPlayers[] = Player::create([
					'tournament_id' => $tournament->id,
					'name' => $name,
					'points' => 0
				]);
			}

			$playerIds = collect($insertedPlayers)->pluck('id')->toArray();
			
			if ($count % 4 === 0){
				//amerikano existing
				$baseRounds = $count - 1;
				$matchesPerRound = $count / 4;
				$globalRound = 1;
				for ($r = 0; $r < $baseRounds; $r++) {
					$availableCourts = [];
					for ($m = 0; $m < $matchesPerRound; $m++) {
						$availableCourts[] = ($m % $totalCourts) + 1;
					}
					shuffle($availableCourts);
					for ($m = 0; $m < $matchesPerRound; $m++) {
						$p1 = $playerIds[($r + $m) % ($count - 1)];
						$p2 = $playerIds[($r + $m + 1) % ($count - 1)];
						$p3 = $playerIds[($r + $m + 2) % ($count - 1)];
						$p4 = $playerIds[$count - 1]; 
						MatchModel::create([
							'tournament_id' => $tournament->id,
							'round' => $globalRound,
							'court' => $availableCourts[$m],
							'player_a1_id' => $p1,
							'player_a2_id' => $p4,
							'player_b1_id' => $p2,
							'player_b2_id' => $p3,
							'is_finished' => 0
						]);
						$globalRound++;
					}
				}
			} else {
				$rounds = $count;
				$matchesPerRound = (int) floor($count / 4);
				$globalRound = 1;

				for ($r = 0; $r < $rounds; $r++) {
					$rotatedPlayers = array_merge(
						array_slice($playerIds, $r),
						array_slice($playerIds, 0, $r)
					);

					// Mengatur pembagian lapangan
					$availableCourts = [];
					for ($m = 0; $m < $matchesPerRound; $m++) {
						$availableCourts[] = ($m % $totalCourts) + 1;
					}
					shuffle($availableCourts);

					for ($m = 0; $m < $matchesPerRound; $m++) {
						$offset = $m * 4;
						
						$p1 = $rotatedPlayers[$offset];
						$p2 = $rotatedPlayers[$offset + 1];
						$p3 = $rotatedPlayers[$offset + 2];
						$p4 = $rotatedPlayers[$offset + 3];

						MatchModel::create([
							'tournament_id' => $tournament->id,
							'round'          => $globalRound,
							'court'          => $availableCourts[$m],
							'player_a1_id'   => $p1,
							'player_a2_id'   => $p4,
							'player_b1_id'   => $p2,
							'player_b2_id'   => $p3,
							'is_finished'    => 0
						]);
						$globalRound++;
					}
				}
			}
		
		});

		return redirect()->route('tournament.index', ['code' => $code, 'view' => 'matches', 'round' => 1]);
	}

	//halaman utama turnament
    public function index(Request $request, $code)
    {
        // Cari turnamen berdasarkan kode
        $tournament = DB::table('tournaments')->where('code', $code)->first();
        if (!$tournament) {
            abort(404, 'Turnamen tidak ditemukan.');
        }

        // FILTER Hanya ambil pemain dengan ID turnamen ini
        /* $players = Player::where('tournament_id', $tournament->id)
                         ->orderBy('points', 'desc')
                         ->orderBy('name', 'asc')
                         ->get(); */
						 
		$players = Player::where('players.tournament_id', $tournament->id)
		->select('players.*')
		->selectSub(function ($query) use ($tournament) {
			$query->from('matches')
				->selectRaw('COUNT(*)')
				->where('tournament_id', $tournament->id)
				->where('is_finished', 1)
				->where(function ($q) {
					$q->where(function ($sub) {
						$sub->whereRaw('(matches.player_a1_id = players.id OR matches.player_a2_id = players.id)')
							->whereRaw('matches.score_a > matches.score_b');
					})
					->orWhere(function ($sub) {
						$sub->whereRaw('(matches.player_b1_id = players.id OR matches.player_b2_id = players.id)')
							->whereRaw('matches.score_b > matches.score_a');
					});
				});
		}, 'wins_count')
		->orderBy('points', 'desc')
		->orderBy('wins_count', 'desc')
		->orderBy('name', 'asc')
		->get();
		
		if ($players->isEmpty()) {
			$view = 'setup';
		} else {
			$view = $request->query('view', 'setup');
		}
        
        $currentRound = $request->query('round', 1);

        // FILTER Hanya ambil pertandingan dengan ID turnamen ini
        $matches = MatchModel::where('tournament_id', $tournament->id)
                             ->where('round', $currentRound)
                             ->get();
        
        $totalRounds = MatchModel::where('tournament_id', $tournament->id)->max('round') ?? 1;
		
		return view('americano', compact('view', 'players', 'matches', 'currentRound', 'totalRounds', 'code', 'tournament'));
    }
	
	//proses insert update score
	public function updateScore(Request $request, $code, $matchId)
    {
        $tournament = DB::table('tournaments')->where('code', $code)->first();
        if (!$tournament) abort(404);

		$request->validate([
			'score_a' => 'required|integer|min:0',
			'score_b' => 'required|integer|min:0',
		]);
		
		//proses data
        DB::transaction(function () use ($request, $matchId, $tournament) {
            $match = MatchModel::where('tournament_id', $tournament->id)->findOrFail($matchId);

            if ($match->is_finished) {
                Player::where('id', $match->player_a1_id)->decrement('points', $match->score_a);
                Player::where('id', $match->player_a2_id)->decrement('points', $match->score_a);
                Player::where('id', $match->player_b1_id)->decrement('points', $match->score_b);
                Player::where('id', $match->player_b2_id)->decrement('points', $match->score_b);
            }

            $match->update([
                'score_a' => $request->score_a,
                'score_b' => $request->score_b,
                'is_finished' => 1
            ]);

            Player::where('id', $match->player_a1_id)->increment('points', $request->score_a);
            Player::where('id', $match->player_a2_id)->increment('points', $request->score_a);
            Player::where('id', $match->player_b1_id)->increment('points', $request->score_b);
            Player::where('id', $match->player_b2_id)->increment('points', $request->score_b);
        });

        return redirect()->route('tournament.index', ['code' => $code, 'view' => 'matches', 'round' => $request->query('round', 1)])
                         ->with('success', 'Skor berhasil diperbarui!');
    }
	
	//halaman history turnament
	public function history()
	{
		// ambil semua turnamen, diurutkan dari yang paling baru
		$tournaments = DB::table('tournaments')
			->orderBy('created_at', 'desc')
			->get();

		return view('tournament_history', compact('tournaments'));
	}
	
	public function destroy($code)
	{
		// Cari turnamen berdasarkan kode
		$tournament = DB::table('tournaments')->where('code', $code)->first();
		if (!$tournament) {
			return redirect()->back()->with('error', 'Turnamen tidak ditemukan.');
		}

		DB::transaction(function () use ($tournament) {
			// Hapus semua data yang berelasi dulu
			DB::table('matches')->where('tournament_id', $tournament->id)->delete();
			DB::table('players')->where('tournament_id', $tournament->id)->delete();
			
			// Hapus data utama turnamen
			DB::table('tournaments')->where('id', $tournament->id)->delete();
		});

		return redirect()->route('tournament.history')->with('success', 'Turnamen berhasil dihapus.');
	}
}