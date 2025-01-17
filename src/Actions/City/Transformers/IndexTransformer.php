<?php

namespace Nnjeim\World\Actions\City\Transformers;

use Illuminate\Database\Eloquent\Collection;

trait IndexTransformer
{
    /**
     * @param  Collection  $cities
     * @param  array  $fields
     * @return \Illuminate\Support\Collection
     */
    protected function transform(Collection $cities, array $fields): \Illuminate\Support\Collection
    {
        return $cities
            ->map(
                function ($city) use ($fields) {
                    $return = $city->only($fields);

                    if (in_array('country', $fields)) {
                        $return = array_merge(
                            $return,
                            ['country' => \Lang::has('world::country.' . $city->country->iso2)
                                ? array_merge($city->country->only('id'), ['name' => trans('world::country.' . $city->country->iso2)])
                                :$city->country->only('id', 'name')]
                        );
                    }


                    if (in_array('state', $fields)) {
                        $return = array_merge(
                            $return,
                            ['state' => $city->state->only('id', 'name')]
                        );
                    }


                    $return = array_merge(
                        $return,
                        ['name' => \Lang::has('world::cities.' . $city->name) ? trans('world::cities.' . $city->name) : $city->name]
                    );

                    return $return;
                }
            );
    }
}
