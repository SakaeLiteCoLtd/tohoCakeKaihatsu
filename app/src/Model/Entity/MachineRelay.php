<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MachineRelay Entity
 *
 * @property int $id
 * @property string $relay_code
 * @property string $name
 * @property string|null $bik
 * @property int $delete_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property int|null $updated_staff
 *
 * @property \App\Model\Entity\RelayLog[] $relay_logs
 */
class MachineRelay extends Entity
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
        'relay_code' => true,
        'name' => true,
        'bik' => true,
        'delete_flag' => true,
        'created_at' => true,
        'updated_at' => true,
        'updated_staff' => true,
        'relay_logs' => true
    ];
}
