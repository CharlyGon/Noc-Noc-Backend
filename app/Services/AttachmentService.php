<?php

namespace App\Services;

use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AttachmentService
{
    /**
     * Store a newly created attachment in storage.
     *
     * @param int $taskId
     * @param int $userId
     * @param UploadedFile $file
     * @return Attachment
     */
    public function storeAttachment(int $taskId, int $userId, UploadedFile $file): Attachment
    {
        try {
            $filename = $file->getClientOriginalName();
            $path = $file->store('attachments');

            return Attachment::create([
                'task_id' => $taskId,
                'user_id' => $userId,
                'filename' => $filename,
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            throw new \Exception("Error al guardar el archivo adjunto: " . $e->getMessage());
        }
    }

    /**
     * Remove the specified attachment from storage.
     *
     * @param Attachment $attachment
     * @return void
     */
    public function deleteAttachment(Attachment $attachment, User $user)
    {
        try {

            if ($this->userCanDeleteAttachment($attachment, $user)) {
                $attachment->delete();
            } else {
                throw new \Exception('Unauthorized to delete attachment');
            }
        } catch (\Exception $e) {
            throw new \Exception("Error al eliminar el archivo adjunto: " . $e->getMessage());
        }
    }

    protected function userCanDeleteAttachment(Attachment $attachment, User $user)
    {
        try {

            return $user->id === $attachment->task->assigned_to ||
                $user->id === $attachment->user_id ||
                $user->isAdmin();
        } catch (\Exception $e) {
            throw new \Exception("Error al verificar si el usuario puede eliminar el archivo adjunto: " . $e->getMessage());
        }
    }

    /**
     * Check if the attachment file exists.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return bool
     */
    public function attachmentExists(Attachment $attachment): bool
    {
        try {
            $filePath = $this->getAttachmentPath($attachment);

            return Storage::exists($filePath);
        } catch (\Exception $e) {
            throw new \Exception("Error al verificar si el archivo adjunto existe: " . $e->getMessage());
        }
    }

    /**
     * Get the path of the attachment file.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return string
     */
    public function getAttachmentPath(Attachment $attachment): string
    {
        return $attachment->path;
    }
}
