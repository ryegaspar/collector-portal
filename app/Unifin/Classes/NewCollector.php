<?php

namespace App\Unifin\Classes;

use App\Models\Lynx\Admin;
use App\Models\Lynx\Collector;
use App\Models\Lynx\Subsite;

class NewCollector
{
    protected $subsite;
    protected $first_name;
    protected $last_name;

    /**
     * NewCollector constructor.
     *
     * @param $subsite_id
     * @param $first_name
     * @param $last_name
     */
    public function __construct($subsite_id, $first_name, $last_name)
    {
        $this->subsite = Subsite::findOrFail($subsite_id);
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    /**
     * Generate desk, tiger id, lynx id for the collector.
     *
     * @return array
     */
    public function generateId()
    {
        $desk = $this->desk();

        $collectOneId = $this->tigerId($desk);

        $lynxId = $this->lynxId();

        return ([$desk, $collectOneId, $lynxId]);
    }

    /**
     * Generate collectone desk.
     *
     * @return mixed
     */
    protected function desk()
    {
        $collectorDesk = Collector::whereNull('date_terminated')->orderBy('desk')->pluck('desk')->filter(function ($value) {
            return ($value >= $this->subsite->min_desk_number && $value <= $this->subsite->max_desk_number);
        });

        $allDesk = collect(range($this->subsite->min_desk_number, $this->subsite->max_desk_number))
            ->map(function ($value) {
                return str_pad($value, 3, '0', STR_PAD_LEFT);
            });

        return ($allDesk->diff($collectorDesk))->first();
    }

    /**
     * Generate collectone id.
     *
     * @param $desk
     * @return string
     */
    protected function tigerId($desk)
    {
        if ($this->subsite->collectone_id_assignment_method == 1) {
            return $this->idByInitials();
        }

        return $this->idByPrefix($desk);
    }

    /**
     * Make a new id for collectone using initials.
     *
     * @return string
     */
    protected function idByInitials()
    {
        $next = 1;
        $collectOneId = $maybe_username = strtolower($this->first_name[0] . $this->last_name[0]) . $next;

        while (Collector::where('tiger_user_id', '=', $collectOneId)->where('active',
                true)->first() || Admin::where('tiger_user_id', '=',
                $collectOneId)->where('active', true)->first()) {

            $next++;

            if ($next > 9) {
                throw \Illuminate\Validation\ValidationException::withMessages(['first_name' => 'Max assignable for this ID has been attained, contact administrator']);
            }

            $collectOneId = substr($collectOneId, 0, 2) . $next;
        }

        return $collectOneId;
    }

    /**
     * Make a new id for collectone using prefix and a number.
     *
     * @param $desk
     * @return string
     */
    protected function idByPrefix($desk)
    {
        $prefixes = explode(",", $this->subsite->prefixes);

        $separator = (($this->subsite->max_desk_number + 1) - $this->subsite->min_desk_number) / (count($prefixes));

        $intDesk = (int)($desk) - $this->subsite->min_desk_number;
        $index = 0;

        while ($intDesk >= $separator) {
            $intDesk -= $separator;
            $index++;
        }

        if (count($prefixes) > $index){
            return $prefixes[$index] . str_pad($intDesk, 2, '0', STR_PAD_LEFT);
        }

        return $prefixes[count($prefixes) - 1] . str_pad($intDesk, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Make new id for lynx.
     *
     * @return string
     */
    protected function lynxId()
    {
        $username = $maybe_username = strtolower($this->first_name[0] . str_replace(" ", "", $this->last_name));
        $next = 2;

        while (Collector::where('username', '=', $username)->first()) {
            $username = $maybe_username.$next;
            $next++;
        }

        return $username;
    }
}