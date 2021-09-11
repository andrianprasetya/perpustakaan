<?php

namespace App\Http\Controllers;

use App\Book;
use App\Libraries\ResponseStd;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'users.created_at';
            $order = $request->has('order') ? $request->input('order') : 'DESC';
            $search = $request->input('search');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND users.email ILIKE '$search'";
            }
            if ($limit > 25) {
                $limit = 10;
            }
            $paged = User::query()->select('*')
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);
            $countAll = User::query()->count();

            return ResponseStd::paginated($paged, $countAll);
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                return ResponseStd::fail($e->getMessage());
            }
        }
    }

    protected function create(array $data)
    {
        $book = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'created_at' => Carbon::now(),
        ]);
        // Return to model.
        return $book;
    }

    protected function validator(array $data)
    {
        $arrayValidator = [
            'email' => [
                'required',

                'unique:users,email,NULL,id'],
        ];
        // Create Validation.
        return Validator::make($data, $arrayValidator);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validator($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->create($request->all());
            DB::commit();
            return ResponseStd::okSingle($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            }

            return ResponseStd::fail($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $item = User::query()->find($request->input('id'));
            if (!$item) {
                throw new \Exception('Invalid Book Id');
            }

            $item->delete();
            DB::commit();

            return ResponseStd::okSingle($item);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__CLASS__ . ":" . __FUNCTION__ . ' ' . $e->getMessage());
            return ResponseStd::fail($e->getMessage());
        }
    }
}
