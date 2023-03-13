<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Nomenclatoare\CodCategorieTva;

class CodCategorieTvaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $lista_categorie_tva = [
            "" => " Nedefinit",
            "S" => "Cota normală şi cota redusă a TVA",
            "Z" => "TVA cota zero",
            "E" => "Scutire de TVA",
            "AE" => "TVA cu taxare inversă",
            "K" => "TVA pentru livrări intracomunitare",
            "G" => "TVA pentru exporturi",
            "O" => "Nu face obiectul TVA",
            "L" => "Taxele din Insulele Canare",
            "M" => "Taxele din Ceuta şi Melilla",
        ];

        foreach($lista_categorie_tva as $cod => $denumire) {
            CodCategorieTva::create(['cod' => $cod, 'denumire' => $denumire]);
        }

    }
}
