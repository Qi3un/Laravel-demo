<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Note;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller {
	public function __construct() {

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