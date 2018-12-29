<?php

use Illuminate\Database\Seeder;

class ServerStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date('Y-m-d G:i:s', time());
        DB::table('server_statuses')->insert([
            'name' => 'leaderboard_updated',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
