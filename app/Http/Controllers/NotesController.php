<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Note;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotesController extends Controller {

	public function __construct() {	}

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

	protected function all(Request $request) {
		$notes = DB::table('notes')->where("user_id", $request->user_id)->get();
		return response()->json(["notes" => $notes], 200);
	}

	protected function byId(Request $request, int $id) {
		$note = DB::table('notes')->where("id", $id);

		if($note->count() == 0) {
			return response(["error" => "note with id '" . $id . "' doesn't exist"], 400);
		}
		else {
			return response()->json($note->first(), 200);
		}
	}

	protected function update(Request $request, int $id) {
		/* check where note $id belongs to current user */
		if(DB::table('notes')->where("id", $id)->where("user_id", $request->user_id)->count() == 0) {
			return response(["error" => "note doesn't exist or desn't belong to current user"]);
		}

		/* update specified columns */
		$updatedRow = 0;
		if($request->title != null) {
			$updatedRow = DB::table('notes')->where("id", $id)
				->update(["title" => $request->title, "updated_at" => date("Y-m-d H:i:s")]);
		}
		if($request->description != null) {
			$updatedRow = DB::table('notes')->where("id", $id)
				->update(["description" => $request->description, "updated_at" => date("Y-m-d H:i:s")]);
		}
		if($request->completed != null) {
			$updatedRow = DB::table('notes')->where("id", $id)
				->update(["completed" => ($request->completed === '1'), "updated_at" => date("Y-m-d H:i:s")]);
		}

		/* if updated, return updated note */
		if($updatedRow) {
			$note = DB::table('notes')->where('id', $id)->first();
			return response()->json($note, 200);
		}
		/* else, return some info */
		else {
			return response(["info" => "nothing has changed"], 200);
		}
	}

	protected function delete(Request $request, int $id) {
		/* check where note $id exists and belongs to current user */
		if(DB::table('notes')->where("id", $id)->where("user_id", $request->user_id)->count() == 0) {
			return response(["error" => "note doesn't exist or desn't belong to current user"]);
		}

		DB::table('notes')->where("id", $id)->where("user_id", $request->user_id)->delete();
		return response("", 200);
	}
}