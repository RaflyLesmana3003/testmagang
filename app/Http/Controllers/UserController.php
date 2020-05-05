<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        // NOTE - load user model 
        $query = User::query();

        // NOTE - select query from client request
        $query->select(explode(",", $request->select));


        // ANCHOR - manual algorithm for my conditions query

        // NOTE - check if conditions request exist
        if (isset($request->conditions))
        {
            /* NOTE - loop to identify condition types
            you can request conditions like:
            - whereColumn
            - whereNull
            - whereNotIn
            - whereIn
            - whereNotBetween
            - whereBetween
            - orWhere 
            - orWhere function
                -   whereIn
                -   whereNotIn
            */

            foreach ($request->conditions[0] as $type)
            {
                if ($type['type'] == 'whereColumn')
                {
                    foreach ($type['data'] as $column)
                    {

                        $query->where(explode(",", $column) [0], explode(",", $column) [1], explode(",", $column) [2]);
                   
                    }
                }
                elseif ($type['type'] == 'whereNull')
                {

                    $query->whereNull($type['data']);

                }
                elseif ($type['type'] == 'whereNotIn')
                {

                    $query->whereNotIn($type['data'][0], explode(",", $type['data'][1]));

                }
                elseif ($type['type'] == 'whereIn')
                {

                    $query->whereIn($type['data'][0], explode(",", $type['data'][1]));

                }
                elseif ($type['type'] == 'whereNotBetween')
                {

                    $query->whereNotBetween($type['data'][0], explode(",", $type['data'][1]));

                }
                elseif ($type['type'] == 'whereBetween')
                {

                    $query->whereBetween($type['data'][0], explode(",", $type['data'][1]));

                }
                elseif ($type['type'] == 'orWhere')
                {
                    if (isset($type['function']) == 'whereNotin')
                    {
                        foreach ($type['function'] as $functiontype)
                        {
                            if ($functiontype['type'] == "whereNotIn")
                            {

                                $query->orWhere(function ($query) use ($functiontype) { 
                                   $query->whereNotIn($functiontype['data'][0], explode(",", $functiontype['data'][1]));
                                });

                            }
                            elseif ($functiontype['type'] == "whereIn")
                            {

                                $query->orWhere(function ($query) use ($functiontype) { 
                                    $query->whereIn($functiontype['data'][0], explode(",", $functiontype['data'][1]));
                                 });

                            }
                        }

                    }
                    else
                    {
                        foreach ($type['data'] as $column)
                        {

                            $query->orWhere(explode(",",$column)[0],explode(",",$column)[1],explode(",",$column)[2]);

                        }
                    }
                }
            }
        }

        // NOTE - you can custom orderBy too
        if (isset($request->order))
        {
            foreach ($request->order as $order)
            {

                $query->orderBy(explode(",", $order) [0], explode(",", $order) [1]);

            }
        }

        // NOTE then for limit dan set current page.
        $data = $query->paginate($request->limit, ['*'], 'page', $request->current_page);

        // NOTE then last, convert to json and return to client
        return json_encode($data);
    }
}

