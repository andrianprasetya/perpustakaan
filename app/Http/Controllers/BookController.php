<?php

namespace App\Http\Controllers;

use App\Book;
use App\Libraries\ResponseStd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'books.created_at';
            $order = $request->has('order') ? $request->input('order') : 'DESC';
            $search = $request->input('search');
            $conditions = '1 = 1';
            if(!empty($search)){
                $conditions .= " AND books.name ILIKE '$search'";
            }
            if ($limit > 25) {
                $limit = 10;
            }
            $paged = Book::query()->select('*')
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);
            $countAll = Book::query()->count();

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
        $book = Book::query()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'is_active' => !empty($data['is_active']) ? $data['is_active'] : 0,
            'created_at' => Carbon::now(),
        ]);

        // Return to model.
        return $book;
    }

    protected function validator(array $data)
    {
        $arrayValidator = [
            'name' => [
                'required',
                'min:5',
                'max:70',
                'unique:books,name,NULL,id'],
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



}
