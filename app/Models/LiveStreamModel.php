<?php

namespace App\Models;

use CodeIgniter\Model;

class LiveStreamModel extends Model
{
    protected $table            = 'live_streams';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'live_url', 'is_active'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Callbacks
    protected $beforeInsert = ['deactivateOthers'];
    protected $beforeUpdate = ['deactivateOthers'];

    protected function deactivateOthers(array $data)
    {
        if (isset($data['data']['is_active']) && $data['data']['is_active']) {
            $this->db->table($this->table)
                ->where('id !=', 0)
                ->update(['is_active' => 0]);
        }

        return $data;
    }

    /**
     * Set a stream as active and deactivate all others.
     */
    public function setActive(int $id)
    {
        $this->db->transStart();
        
        // Deactivate any currently active stream
        $this->where('is_active', 1)->set(['is_active' => 0])->update();
        
        // Activate the specified stream
        $this->update($id, ['is_active' => 1]);
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

    /**
     * Get the current active stream.
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->first();
    }
}
