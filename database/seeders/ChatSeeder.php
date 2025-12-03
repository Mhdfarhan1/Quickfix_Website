<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $orders = DB::table('pemesanan')->get();

        foreach ($orders as $order) {

            $chatId = DB::table('chats')->insertGetId([
                'id_pemesanan' => $order->id_pemesanan,
                'id_user' => $order->id_pelanggan,
                'id_teknisi' => $order->id_teknisi,
                'created_at' => now()
            ]);

            for ($i = 1; $i <= rand(5, 15); $i++) {

                $sender = rand(0,1) ? 'user' : 'teknisi';
                $senderId = $sender == 'user'
                    ? $order->id_pelanggan
                    : DB::table('teknisi')->where('id_teknisi', $order->id_teknisi)->value('id_user');

                DB::table('chat_messages')->insert([
                    'id_chat' => $chatId,
                    'sender_type' => $sender,
                    'sender_id' => $senderId,
                    'message' => $faker->sentence(),
                    'created_at' => $faker->dateTimeBetween('-2 days', 'now')
                ]);
            }
        }
    }
}
    