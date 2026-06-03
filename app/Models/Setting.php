<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['user_id', 'key', 'value', 'type'];
    public $timestamps = true;

    /**
     * Ambil nilai setting untuk user tertentu (atau global)
     * @param string $key
     * @param mixed $default
     * @param int|null $userId
     * @return mixed
     */
    public static function get(string $key, $default = null, ?int $userId = null)
    {
        if ($userId === null && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }
        
        $query = self::where('key', $key);
        if ($userId) {
            $userSetting = (clone $query)->where('user_id', $userId)->first();
            if ($userSetting) {
                return self::castValue($userSetting->value, $userSetting->type);
            }
        }
        
        $global = $query->whereNull('user_id')->first();
        if ($global) {
            return self::castValue($global->value, $global->type);
        }
        
        return $default;
    }
    
    /**
     * Set setting untuk user tertentu
     * @param string $key
     * @param mixed $value
     * @param int|null $userId
     * @param string $type
     * @return void
     */
    public static function set(string $key, $value, ?int $userId = null, string $type = 'text'): void
    {
        if ($userId === null && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }
        
        $record = self::where('user_id', $userId)->where('key', $key)->first();
        if ($record) {
            $record->value = $value;
            $record->type = $type;
            $record->save();
        } else {
            self::create([
                'user_id' => $userId,
                'key' => $key,
                'value' => $value,
                'type' => $type
            ]);
        }
    }
    
    /**
     * Cast value berdasarkan tipe
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    private static function castValue($value, string $type)
    {
        if ($type === 'boolean') {
            return (bool) $value;
        }
        if ($type === 'json') {
            return json_decode($value, true);
        }
        return $value;
    }
}