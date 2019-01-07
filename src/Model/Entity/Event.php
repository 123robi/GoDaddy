<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Event Entity
 *
 * @property int $id
 * @property string $name
 * @property int $team_id
 * @property \Cake\I18n\FrozenTime $start
 * @property \Cake\I18n\FrozenTime $end
 * @property string $description
 * @property int $place_id
 *
 * @property \App\Model\Entity\Team $team
 * @property \App\Model\Entity\Place $place
 */
class Event extends Entity
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
    	'id' => true,
        'name' => true,
        'team_id' => true,
        'start' => true,
        'end' => true,
        'description' => true,
        'place_id' => true,
        'team' => true,
        'place' => true
    ];
}
