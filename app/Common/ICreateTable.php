<?php

namespace App\Common;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Query\Builder;

/**
 * 本インターフェースは
 * LaravelのModel定義を補うものです
 */

interface ICreateTable{
    
    /**
     * テーブル作成処理を実装します
     * @param Blueprint $table
     */
    public static function CreateTable(Blueprint $table);
    /**
     * テーブルデータ初期化処理を実装します。
     * @param Builder $table
     */
    public static function InitTable(Builder $table);

}
