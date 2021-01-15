<?php

namespace App;

trait RecordsActivity
{
    public $oldAttributes = [];

    public static function bootRecordsActivity()
    {
        foreach(self::recordableEvents() as $event){
            static::$event(function($model) use ($event){
                $model->recordActivity($event . '_' . strtolower(class_basename($model)));
            });

            if($event === 'updated'){
                static::updating(function($model){
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    public static function recordableEvents()
    {
        if(isset(static::$recordableEvents)){
            return $recordableEvents = static::$recordableEvents;
        }

        return $recordableEvents = ['created', 'updated'];
    }

    public function activity()
    {
        if (get_class($this) === Project::class) {
            return $this->hasMany(Activity::class)->latest();
        }

        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    protected function activityChanges()
    {
        if($this->wasChanged()){
            return [
                'before' => array_except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
                'after'  => array_except($this->getChanges(), 'updated_at'),
            ];
        }
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
                'description' => $description,
                'changes'     => $this->activityChanges(),
                'project_id'  => class_basename($this) === 'Project' ? $this->id : $this->project_id,
                'user_id'     => ($this->project ?? $this)->owner->id,
            ]
        );
    }
}
