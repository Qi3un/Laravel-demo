<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Note;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class QueryController extends Controller {
	public function __construct() {

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
}