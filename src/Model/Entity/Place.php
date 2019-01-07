<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Place Entity
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $latlng
 * @property int $team_id
 *
 * @property \App\Model\Entity\Team $team
 * @property \App\Model\Entity\Event[] $events
 */
class Place extends Entity
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
        'name' => true,
        'address' => true,
        'latlng' => true,
        'team_id' => true,
        'team' => true,
        'events' => true
    ];
}
