<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\DocumentResource;
use App\Models\User;
use App\Models\Document;

class DocumentController extends Controller
{
    //upload file for a user
    public function uploadForUser(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'type' => 'required|in:type1,type2,type3,type4',
            'file' => 'required|mimes:jpg,png,jpeg,pdf,docx,doc',
            'type_data' => 'sometimes|json',
            'encryption_data' => 'sometimes|json',
        ]);

        $user_id = $request->route('user_id');
        $user = User::findOrFail($user_id);

        $path = $request->file('file')->store('user_files');


        $document =$user->documents()->create([
            'name' => $request->name,
            'type' => $request->type,
            'file' => $path,
            'type_data' => $request->type_data,
            'encryption_data' => $request->encryption_data,
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'file' => $document,
        ]);
    }

    // upload file for the auhtenticated user
    public function upload(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'type' => 'required|in:type1,type2,type3,type4',
            'file' => 'required|mimes:jpg,png,jpeg,pdf,docx,doc',
            'type_data' => 'sometimes|json',
            'encryption_data' => 'sometimes|json',
        ]);

        $user = auth()->user();

        $path = $request->file('file')->store('user_files');

        $document = $user->documents()->create([
            'name' => $request->name,
            'type' => $request->type,
            'file' => $path,
            'type_data' => $request->type_data,
            'encryption_data' => $request->encryption_data,
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'file' => $document,
        ]);
    }

    // download file
    public function download(Request $request)
    {
        $document_id = $request->route('document_id');
        $user = auth()->user();

        if ($user->hasRole('SuperAdmin')) {
            $document = Document::findOrFail($document_id);
        }
        else{
            $document = $user->documents()->findOrFail($document_id);
        }


        return response()->download(storage_path('app/'.$document->file));
    }

    // show all user files
    public function ShowUserDocs(Request $request)
    {
        $user_id = $request->route('user_id');
        $user = User::findOrFail($user_id);

        $documents = $user->documents;

        return response()->json([
            'documents' => DocumentResource::collection($documents),
        ]);
    }

    // show all files for the authenticated user
    public function Show(Request $request)
    {
        $user = auth()->user();

        $documents = $user->documents;

        return response()->json([
            'documents' => DocumentResource::collection($documents),
        ]);
    }

    // show a specific file
    public function ShowFile(Request $request)
    {
        $document_id = $request->route('document_id');
        $user = auth()->user();

        if ($user->hasRole('SuperAdmin')) {
            $document = Document::findOrFail($document_id);
        }
        else{
            $document = $user->documents()->findOrFail($document_id);
        }

        return response()->json([
            'document' => new DocumentResource($document),
        ]);
    }

    // delete a file
    public function delete(Request $request)
    {
        $document_id = $request->route('document_id');
        $user = auth()->user();

        if ($user->hasRole('SuperAdmin')) {
            $document = Document::findOrFail($document_id);
        }
        else{
            $document = $user->documents()->findOrFail($document_id);
        }

        $document->delete();

        return response()->json([
            'message' => 'File deleted successfully',
        ]);
    }
}
