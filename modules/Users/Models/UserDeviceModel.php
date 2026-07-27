<?php

namespace Modules\Users\Models;

use CodeIgniter\Model;

class UserDeviceModel extends Model
{
    protected $table            = 'tb_user_devices';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'device_key',
        'device_name',
        'user_agent',
        'ip_address',
        'session_id',
        'is_active_session',
        'is_blocked',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Maximum allowed unique devices per student before triggering suspicious alert
     */
    public const MAX_ALLOWED_DEVICES = 2;

    /**
     * Registers or updates a device for a user.
     * Enforces single active session per user.
     *
     * @return array [ 'status' => bool, 'is_new_device' => bool, 'total_devices' => int, 'is_suspicious' => bool, 'message' => string ]
     */
    public function registerOrUpdateDevice(int $userId, string $deviceKey, string $deviceName, string $userAgent, string $ipAddress, string $sessionId): array
    {
        // 1. Check if device is already registered for this user
        $existing = $this->where('user_id', $userId)
                         ->where('device_key', $deviceKey)
                         ->first();

        // 2. Count total registered unique devices for this user
        $totalDevices = $this->where('user_id', $userId)->countAllResults();

        $isNewDevice = false;

        if ($existing) {
            // Check if device is blocked
            if (!empty($existing['is_blocked'])) {
                return [
                    'status' => false,
                    'is_new_device' => false,
                    'total_devices' => $totalDevices,
                    'is_suspicious' => true,
                    'message' => 'هذا الجهاز محظور من الدخول. يرجى التواصل مع الدعم الفني عبر الواتساب.'
                ];
            }

            // Update existing device active session and info
            $this->update($existing['id'], [
                'device_name'       => $deviceName,
                'user_agent'        => $userAgent,
                'ip_address'        => $ipAddress,
                'session_id'        => $sessionId,
                'is_active_session' => 1,
                'updated_at'        => date('Y-m-d H:i:s')
            ]);
        } else {
            $isNewDevice = true;
            $totalDevices++;

            // If total devices exceed max allowed (e.g. > 3 unique devices)
            if ($totalDevices > 3) {
                return [
                    'status' => false,
                    'is_new_device' => true,
                    'total_devices' => $totalDevices,
                    'is_suspicious' => true,
                    'message' => 'عذراً، لقد تجاوزت الحد الأقصى المسموح به للأجهزة المصرح بها (جهاز واحد لكل طالب). يرجى التواصل مع الدعم الفني لإلغاء تفعيل الأجهزة القديمة.'
                ];
            }

            // Save new device
            $this->insert([
                'user_id'           => $userId,
                'device_key'        => $deviceKey,
                'device_name'       => $deviceName,
                'user_agent'        => $userAgent,
                'ip_address'        => $ipAddress,
                'session_id'        => $sessionId,
                'is_active_session' => 1,
            ]);
        }

        // 3. SINGLE ACTIVE SESSION ENFORCEMENT:
        // Set all OTHER devices for this user to is_active_session = 0
        $this->where('user_id', $userId)
             ->where('device_key !=', $deviceKey)
             ->set(['is_active_session' => 0])
             ->update();

        $isSuspicious = ($totalDevices > self::MAX_ALLOWED_DEVICES);

        return [
            'status'        => true,
            'is_new_device' => $isNewDevice,
            'total_devices' => $totalDevices,
            'is_suspicious' => $isSuspicious,
            'message'       => 'تم تسجيل الجهاز بنجاح.'
        ];
    }

    /**
     * Verifies if the current user session is still the active single session.
     */
    public function isSessionActive(int $userId, string $sessionId): bool
    {
        $activeDevice = $this->where('user_id', $userId)
                             ->where('is_active_session', 1)
                             ->first();

        if (!$activeDevice) {
            return true;
        }

        return ($activeDevice['session_id'] === $sessionId);
    }

    /**
     * Resets all registered devices for a user (Admin feature).
     */
    public function resetUserDevices(int $userId): bool
    {
        return $this->where('user_id', $userId)->delete();
    }

    /**
     * Gets all users with device count and suspicious flag for admin dashboard.
     */
    public function getSuspiciousUsersList(): array
    {
        $builder = $this->db->table('tb_user_devices d');
        $builder->select('d.user_id, u.full_name, u.email, u.mobile, COUNT(DISTINCT d.device_key) as device_count, MAX(d.updated_at) as last_activity, MAX(d.is_blocked) as has_blocked_device');
        $builder->join('users u', 'u.id = d.user_id', 'left');
        $builder->groupBy('d.user_id');
        $builder->orderBy('device_count', 'DESC');

        return $builder->get()->getResultArray();
    }
}
