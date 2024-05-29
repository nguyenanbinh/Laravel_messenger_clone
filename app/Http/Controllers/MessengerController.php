<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessengerController extends Controller
{
    function index(): View
    {
        return view('messenger.index');
    }

    /**
     * Search users by their name or username
     *
     * @param Request $request
     * @return JsonResponse
     */
    function search(Request $request): JsonResponse
    {
        // Initialize the records variable
        $getRecords = null;

        // Get the input from the query string
        $input = $request['query'];

        // Query the database to get the users that match the input
        $records = User::where('id', '<>', auth()->user()->id)
            // Search users by their name
            ->where('name', 'like', '%' . $input . '%')
            // Search users by their username
            ->orWhere('user_name', 'like', '%' . $input . '%')
            // Get the users in batches of 10
            ->paginate(10);

        // If no records are found, show a message
        if ($records->total() < 1) {
            $getRecords .= "<p class='text-center'>Noting to show.</p>";
        }
        // Render each record as a search item component
        foreach ($records as $record) {
            $getRecords .= view('messenger.components.search-item', compact('record'))->render();
        }

        // Return the records and the last page number as a JSON response
        return response()->json(['records' => $getRecords, 'last_page' => $records->lastPage()]);
    }
}
