<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Services\AttachmentService;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        try {
            // Verificar la existencia del archivo adjunto
            if (!$this->attachmentService->attachmentExists($attachment)) {
                return response()->json(['error' => 'Attachment not found'], 404);
            }
            $filePath = $this->attachmentService->getAttachmentPath($attachment);

            return response()->download($filePath, $attachment->filename);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to download attachment: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Adjust file size as needed
        ]);

        $taskId = $request->task_id;
        $userId = auth()->id();
        $file = $request->file('file');

        try {
            $attachment = $this->attachmentService->storeAttachment($taskId, $userId, $file);
            return response()->json(['message' => 'Attachment stored successfully', 'attachment' => $attachment], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to store attachment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        try {
            $user = auth()->user();
            $this->attachmentService->deleteAttachment($attachment, $user);
            return response()->json(['message' => 'Attachment deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete attachment: ' . $e->getMessage()], 500);
        }
    }
}
