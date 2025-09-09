<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertPersonalAccessTokensData extends Migration
{
    public function up()
    {
        DB::table('personal_access_tokens')->insert([
            [
                'id' => 1,
                'tokenable_type' => 'Vendor\\Models\\Customers',
                'tokenable_id' => 1,
                'name' => 1752464665,
                'token' => '949a0b5424d624062f8153f720eacb794598c79ba6fdc98f6b2e7ed7c8e16cfe',
                'abilities' => '["*"]',
                'last_used_at' => '2025-07-14 02:55:23',
                'expires_at' => null,
                'created_at' => '2025-07-14 00:44:25',
                'updated_at' => '2025-07-14 02:55:23',
            ],
            [
                'id' => 2,
                'tokenable_type' => 'Vendor\\Models\\Admin\\Users_Admin',
                'tokenable_id' => 1,
                'name' => 1752464680,
                'token' => '79caeac86318f49ebed15b83cc020420853b8899f208028e44c70ef955c11f74',
                'abilities' => '["*"]',
                'last_used_at' => '2025-07-14 02:54:45',
                'expires_at' => null,
                'created_at' => '2025-07-14 00:44:40',
                'updated_at' => '2025-07-14 02:54:45',
            ],
            [
                'id' => 3,
                'tokenable_type' => 'Vendor\\Models\\Admin\\Users_Admin',
                'tokenable_id' => 1,
                'name' => 1753414981,
                'token' => '4550088d4c040814ef14d4ca66c3a72cedbed00412a96d48ea69a31e2b96eb4d',
                'abilities' => '["*"]',
                'last_used_at' => '2025-07-25 00:43:01',
                'expires_at' => null,
                'created_at' => '2025-07-25 00:43:01',
                'updated_at' => '2025-07-25 00:43:01',
            ],
            [
                'id' => 4,
                'tokenable_type' => 'Vendor\\Models\\Admin\\Users_Admin',
                'tokenable_id' => 1,
                'name' => 1754523580,
                'token' => '512347c681c0c1da2b1f18deb246d884ca7b48bbdd6ecf10fa6765249f45d182',
                'abilities' => '["*"]',
                'last_used_at' => '2025-08-06 21:25:09',
                'expires_at' => null,
                'created_at' => '2025-08-06 20:39:40',
                'updated_at' => '2025-08-06 21:25:09',
            ],
            [
                'id' => 9,
                'tokenable_type' => 'Vendor\\Models\\Admin\\Users_Admin',
                'tokenable_id' => 1,
                'name' => 1754677786,
                'token' => '5527c0e3ae98bf9d93d788df4f842fe06da6cb1c7bf9b3bea75c70e3a5bc6d72',
                'abilities' => '["*"]',
                'last_used_at' => '2025-08-09 18:46:55',
                'expires_at' => null,
                'created_at' => '2025-08-08 15:29:46',
                'updated_at' => '2025-08-09 18:46:55',
            ],
            [
                'id' => 10,
                'tokenable_type' => 'Vendor\\Models\\Admin\\Users_Admin',
                'tokenable_id' => 1,
                'name' => 1757385592,
                'token' => 'be3704ab9d8227a4f243eded3296dd21b224459a284467fed8c364d707b4e94e',
                'abilities' => '["*"]',
                'last_used_at' => '2025-09-09 00:10:42',
                'expires_at' => null,
                'created_at' => '2025-09-08 23:39:52',
                'updated_at' => '2025-09-09 00:10:42',
            ],
            [
                'id' => 11,
                'tokenable_type' => 'Vendor\\Models\\Customers',
                'tokenable_id' => 1,
                'name' => 1757385845,
                'token' => '961a94380c5d0c87b758a5c436f60221e5c076eef7a5523a373610ffe65bef19',
                'abilities' => '["*"]',
                'last_used_at' => '2025-09-08 23:54:35',
                'expires_at' => null,
                'created_at' => '2025-09-08 23:44:05',
                'updated_at' => '2025-09-08 23:54:35',
            ],
            [
                'id' => 12,
                'tokenable_type' => 'Vendor\\Models\\Customers',
                'tokenable_id' => 1,
                'name' => 1757386493,
                'token' => '59b0daeaee71390f3712418d9bd41b31d01090a20183a37805909cd5365019b8',
                'abilities' => '["*"]',
                'last_used_at' => '2025-09-08 23:58:13',
                'expires_at' => null,
                'created_at' => '2025-09-08 23:54:53',
                'updated_at' => '2025-09-08 23:58:13',
            ],
            [
                'id' => 13,
                'tokenable_type' => 'Vendor\\Models\\Customers',
                'tokenable_id' => 3,
                'name' => 1757386908,
                'token' => '43f47fbda8493473aad7db4b86592417a3858757962529950c271a0125ed0f02',
                'abilities' => '["*"]',
                'last_used_at' => '2025-09-09 00:02:19',
                'expires_at' => null,
                'created_at' => '2025-09-09 00:01:48',
                'updated_at' => '2025-09-09 00:02:19',
            ],
            [
                'id' => 14,
                'tokenable_type' => 'Vendor\\Models\\Customers',
                'tokenable_id' => 1,
                'name' => 1757386950,
                'token' => '309880ccdbbd3f51ceb9f02e08de12db9b51ffd6b0e0623e99ac824451ebb8ba',
                'abilities' => '["*"]',
                'last_used_at' => '2025-09-09 00:11:00',
                'expires_at' => null,
                'created_at' => '2025-09-09 00:02:30',
                'updated_at' => '2025-09-09 00:11:00',
            ],
            [
                'id' => 15,
                'tokenable_type' => 'Vendor\\Models\\Customers',
                'tokenable_id' => 1,
                'name' => 1757387467,
                'token' => '8d26cd88557eee62890db4c98d17a7afa397713f89fea910142573764bab1990',
                'abilities' => '["*"]',
                'last_used_at' => '2025-09-09 00:14:40',
                'expires_at' => null,
                'created_at' => '2025-09-09 00:11:07',
                'updated_at' => '2025-09-09 00:14:40',
            ],
            [
                'id' => 16,
                'tokenable_type' => 'Vendor\\Models\\Customers',
                'tokenable_id' => 1,
                'name' => 1757387769,
                'token' => '5ddb91db621edb7c2f50e6d785c8ea33211b41cd58d6c001a81823ea2bb6635a',
                'abilities' => '["*"]',
                'last_used_at' => '2025-09-09 00:16:12',
                'expires_at' => null,
                'created_at' => '2025-09-09 00:16:09',
                'updated_at' => '2025-09-09 00:16:12',
            ]
        ]);
    }

    public function down()
    {
        DB::table('personal_access_tokens')->truncate();
    }
}
