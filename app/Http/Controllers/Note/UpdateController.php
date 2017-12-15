<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Note;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller {
	public function __construct() {

	}

	protected function update(Request $request, int $id) {
		/* check where note $id belongs to current user */
		if(DB::table('notes')->where("id", $id)->where("user_id", $request->user_id)->count() == 0) {
			return response(["error" => "note doesn't exist or desn't belong to current user"]);
		}

		/* update specified columns */
		$updatedRow = 0;
		if($request->title) {
			$updatedRow = DB::table('notes')->where("id", $id)
				->update(["title" => $request->title, "updated_at" => date("Y-m-d H:i:s")]);
		}
		if($request->description) {
			$updatedRow = DB::table('notes')->where("id", $id)
				->update(["description" => $request->description, "updated_at" => date("Y-m-d H:i:s")]);
		}
		if($request->completed) {
			$updatedRow = DB::table('notes')->where("id", $id)
				->update(["completed" => $request->completed, "updated_at" => date("Y-m-d H:i:s")]);
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
}