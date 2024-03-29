<?php

namespace Database\Seeders;

use App\Models\EntryNote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MarkSeeder::class);
        $this->call(MeasureUnitSeeder::class);
        $this->call(MaterialSeeder::class);
        $this->call(RequestSeeder::class);
        $this->call(ExitNoteSeeder::class);
        $this->call(EntryNoteSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(QuotationSeeder::class);
        $this->call(PurchaseOrderSeeder::class);
        $this->call(PurchaseSeeder::class);

    }
}
