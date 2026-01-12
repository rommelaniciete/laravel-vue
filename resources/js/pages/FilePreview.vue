<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

interface Upload {
    id: number;
    name: string;
    type: string;
    mime_type: string;
}

const props = defineProps<{
    upload: Upload;
    fileUrl: string;
}>();
</script>

<template>
    <AppLayout>
        <div class="mx-auto max-w-5xl px-4 py-6">
            <!-- Header with back button -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="truncate text-xl font-bold">{{ upload.name }}</h1>
            </div>

            <!-- Preview content centered -->
            <div
                class="bg flex min-h-[60vh] items-center justify-center rounded-lg p-4"
            >
                <div class="w-full">
                    <img
                        v-if="upload.mime_type.startsWith('image/')"
                        :src="fileUrl"
                        alt="Preview"
                        class="mx-auto max-h-[70vh] max-w-full object-contain"
                    />

                    <iframe
                        v-else-if="upload.mime_type === 'application/pdf'"
                        :src="fileUrl"
                        class="h-[70vh] w-full rounded border"
                    ></iframe>

                    <video
                        v-else-if="upload.mime_type.startsWith('video/')"
                        :src="fileUrl"
                        controls
                        class="mx-auto max-h-[70vh] max-w-full"
                    ></video>

                    <audio
                        v-else-if="upload.mime_type.startsWith('audio/')"
                        :src="fileUrl"
                        controls
                        class="mx-auto w-full"
                    ></audio>

                    <p v-else class="text-center text-gray-500">
                        Preview not available.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
