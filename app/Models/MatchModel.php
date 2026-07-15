<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchModel extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'tournament_id',
        'round',
        'court',
        'player_a1_id',
        'player_a2_id',
        'player_b1_id',
        'player_b2_id',
        'score_a',
        'score_b',
        'is_finished'
    ];

    public function playerA1() { return $this->belongsTo(Player::class, 'player_a1_id'); }
    public function playerA2() { return $this->belongsTo(Player::class, 'player_a2_id'); }
    public function playerB1() { return $this->belongsTo(Player::class, 'player_b1_id'); }
    public function playerB2() { return $this->belongsTo(Player::class, 'player_b2_id'); }
}