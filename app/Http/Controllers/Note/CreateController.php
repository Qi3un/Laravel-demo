<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Note;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateController extends Controller {
	public function __construct() {

	}

	protected function validator(array $data) {
		return Validator::make($data, [
			'title' => 'required|string',
			'description' => 'required|string'
		]);
	}

	protected function create(Request $request) {
		$validator = $this->validator($request->all());

		if($validator->fails()) {
			return response([ "error" => $validator->errors() ], 400);
		}
		else {

			$note = new Note;
			$note->title = $request->title;
			$note->description = $request->description;
			$note->user_id = $request->user_id;
			$note->save();

			return response([ "id" => $note->id ], 200);
		}
	}
}