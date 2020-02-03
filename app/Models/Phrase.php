<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Phrase
 *
 * @property-read string|null $proposed_topic
 */
class Phrase extends Model
{
    protected $table = 'phrases';
    // id нужно из-за импорта
    protected $fillable = ['id', 'text', 'topic'];
    protected $hidden = ['user_id', 'proposed'];
    protected $appends = ['proposed_topic'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function proposed()
    {
        return $this->hasOne(ProposedPhrase::class, 'phrase_id');
    }

    /**
     * @return mixed
     * @uses getProposedTopicAttribute()
     * @uses \App\Models\Phrase::$proposed_topic
     */
    protected function getProposedTopicAttribute()
    {
        return optional($this->proposed)->topic;
    }

    /**
     * accept predicted topic
     *
     * @return $this
     * @throws \Exception
     */
    public function accept()
    {
        /** @var \App\Models\ProposedPhrase $proposed */
        $proposed = $this['proposed'];
        if($proposed){
            $this['topic'] = $proposed->topic;
            $proposed->delete();
            $this->unsetRelation('proposed');
        }

        return $this;
    }

    /**
     * reject predicted topic
     *
     * @return $this
     */
    public function reject()
    {
        optional($this['proposed'])->delete();
        $this->unsetRelation('proposed');

        return $this;
    }
}
