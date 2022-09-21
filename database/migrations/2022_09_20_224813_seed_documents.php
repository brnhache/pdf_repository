<?php

use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Seed documents table
        $doc_1 = new Document();
        $doc_1->user_id = User::where('email', 'user_a@email.com')->pluck('id')[0];
        $doc_1->name = 'Resume_2022_BrianHache.pdf';
        $doc_1->save();

        $doc_2 = new Document();
        $doc_2->user_id = User::where('email', 'user_b@email.com')->pluck('id')[0];
        $doc_2->name = 'BHache_Diploma.pdf';
        $doc_2->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('documents')) {
            Document::where('name', 'Resume_2022_BrianHache.pdf')->delete();
            Document::where('name', 'BHache_Diploma.pdf')->delete();
        }
    }
};
