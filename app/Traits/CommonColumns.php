<?php

namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait CommonColumns
{
    public function useCommonColumns(Blueprint $table){
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('last_updated_by')->nullable();
        $table->unsignedBigInteger('last_deleted_by')->nullable();

        //managed fields
        $table->integer('deleted_at')->nullable();
        $table->integer('created_at');
        $table->integer('updated_at');
    }
}
