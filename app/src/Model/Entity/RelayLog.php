<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RelayLog Entity
 *
 * @property int $id
 * @property string $factory_code
 * @property int $machine_num
 * @property int $machine_relay_id
 * @property \Cake\I18n\FrozenTime $datetime
 * @property int $status
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\MachineRelay $machine_relay
 */
class RelayLog extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'factory_code' => true,
        'machine_num' => true,
        'machine_relay_id' => true,
        'datetime' => true,
        'status' => true,
        'delete_flag' => true,
        'created_at' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'machine_relay' => true
    ];
}
