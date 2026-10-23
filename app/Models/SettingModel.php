<?php

namespace App\Models;

use CodeIgniter\Model;

// On/off switches the Admin controls (stored as key/value pairs).
class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'setting_key';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['setting_key', 'setting_value'];

    public function getValue(string $key, string $default = ''): string
    {
        $row = $this->find($key);

        return $row['setting_value'] ?? $default;
    }

    public function setValue(string $key, string $value): void
    {
        $this->save(['setting_key' => $key, 'setting_value' => $value]);
    }

    // Shortcut for the "1 / 0" switches.
    public function isOn(string $key): bool
    {
        return $this->getValue($key, '0') === '1';
    }
}
