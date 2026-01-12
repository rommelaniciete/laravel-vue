<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

import Button from '@/components/ui/button/Button.vue';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { useForm } from '@inertiajs/vue3';
import upload from '@/routes/upload';

interface Upload {
    id: number;
    name: string;
    type: 'file' | 'folder';
    path?: string;
    size?: number;
    mime_type?: string;
    user_id: number;
    parent_id?: number;
    created_at: string;
    updated_at: string;
}

interface Props {
    uploads: Upload[];
    currentFolder?: Upload;
    breadcrumbs: Upload[];
}

const props = defineProps<Props>();

const currentFolderId = ref(props.currentFolder?.id || null);

const editDialogOpen = ref(false);
const deleteDialogOpen = ref(false);
const editingUpload = ref<Upload | null>(null);
const deletingUpload = ref<Upload | null>(null);

const editForm = useForm({
    name: ''
});

const form = useForm({
    name: '',
    type: 'folder',
    file: null as File | null,
    parent_id: currentFolderId.value
});

const submit = () => {
    form.parent_id = currentFolderId.value;
    form.post(upload.store.url(), {
        onSuccess: () => {
            form.reset();
            form.parent_id = currentFolderId.value
            DialogOpen.value = (false);
        },
    });
};

const DialogOpen = ref(false);

function handleFile(event: Event) {
  const target = event.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    form.file = target.files[0]
  }
}

function formatFileSize(bytes?: number): string {
    if (!bytes) return '-';
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    if (bytes === 0) return '0 Bytes';
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
}

function formatDate(date: string): string {
    return new Date(date).toLocaleDateString();
}

function navigateToFolder(folder: Upload) {
    router.get('/upload', { folder: folder.id });
}

function goToFolder(folderId: number | null) {
    if (folderId === null) {
        router.get('/upload');
    } else {
        router.get('/upload', { folder: folderId });
    }
}

const filteredUploads = computed(() => {
    return props.uploads.filter(upload => upload.parent_id === currentFolderId.value);
});

function viewFile(upload: Upload) {
    window.open(`/upload/${upload.id}`, '_blank');
}

function openEditDialog(upload: Upload) {
    editingUpload.value = upload;
    editForm.name = upload.name;
    editDialogOpen.value = true;
}

function closeEditDialog() {
    editDialogOpen.value = false;
    editingUpload.value = null;
    editForm.reset();
}

function updateUpload() {
    if (!editingUpload.value) return;

    editForm.put(`/upload/${editingUpload.value.id}`, {
        onSuccess: () => {
            closeEditDialog();
        },
    });
}

function openDeleteDialog(upload: Upload) {
    deletingUpload.value = upload;
    deleteDialogOpen.value = true;
}

function closeDeleteDialog() {
    deleteDialogOpen.value = false;
    deletingUpload.value = null;
}

function deleteUpload() {
    if (!deletingUpload.value) return;

    router.delete(`/upload/${deletingUpload.value.id}`, {
        onSuccess: () => {
            closeDeleteDialog();
        },
    });
}



</script>


<template>
    <Head title="My Drive"/>

    <AppLayout>
    <div class="flex h-[calc(100vh-64px)] py-10">
        <!-- Sidebar -->
        <aside class="w-64 space-y-4 px-4 py-6 border-r">
            <Dialog v-model:open="DialogOpen">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button
                            class="w-full justify-start"
                            variant="outline"
                        >
                            + New
                        </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent class="w-40">
                        <DropdownMenuItem as-child>
                          <DialogTrigger as-child>
                            <button class="w-full text-left" @click="form.type = 'folder'">Folder</button>
                          </DialogTrigger>
                        </DropdownMenuItem>

                        <DropdownMenuItem as-child>
                          <DialogTrigger as-child>
                            <button class="w-full text-left" @click="form.type = 'file'">File</button>
                          </DialogTrigger>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Create New</DialogTitle>
                    </DialogHeader>

                    <form @submit.prevent="submit" class="space-y-3">
                        <Input v-model="form.name" placeholder="Name" />
                        <Input
                            v-if="form.type === 'file'"
                            type="file"
                            @change="handleFile"
                        />

                        <DialogFooter>
                            <DialogClose>
                                <Button variant="ghost">Cancel</Button>
                            </DialogClose>
                            <Button type="submit">Create</Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            <!-- Edit Dialog -->
            <Dialog v-model:open="editDialogOpen">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Edit {{ editingUpload?.type === 'folder' ? 'Folder' : 'File' }}</DialogTitle>
                    </DialogHeader>

                    <form @submit.prevent="updateUpload" class="space-y-3">
                        <Input v-model="editForm.name" placeholder="Name" />

                        <DialogFooter>
                            <DialogClose>
                                <Button variant="ghost" @click="closeEditDialog">Cancel</Button>
                            </DialogClose>
                            <Button type="submit">Update</Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            <!-- Delete Dialog -->
            <Dialog v-model:open="deleteDialogOpen">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Delete {{ deletingUpload?.type === 'folder' ? 'Folder' : 'File' }}</DialogTitle>
                    </DialogHeader>

                    <p class="text-sm text-muted-foreground">
                        Are you sure you want to delete "{{ deletingUpload?.name }}"?
                        {{ deletingUpload?.type === 'folder' ? 'This will also delete all contents inside this folder.' : '' }}
                        This action cannot be undone.
                    </p>

                    <DialogFooter>
                        <DialogClose>
                            <Button variant="ghost" @click="closeDeleteDialog">Cancel</Button>
                        </DialogClose>
                        <Button @click="deleteUpload" variant="destructive">Delete</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-4 p-6">
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-sm text-muted-foreground">
                <button @click="goToFolder(null)" class="hover:text-foreground">My Drive</button>
                <span v-for="(crumb, index) in props.breadcrumbs" :key="crumb.id" class="flex items-center">
                    <span class="mx-2">/</span>
                    <button @click="goToFolder(crumb.id)" class="hover:text-foreground">{{ crumb.name }}</button>
                </span>
            </div>

            <!-- File Table -->
            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Last Modified</TableHead>
                            <TableHead>Size</TableHead>
                            <TableHead>Actions</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow v-for="upload in filteredUploads" :key="upload.id">
                            <TableCell>
                                <button 
                                    v-if="upload.type === 'folder'"
                                    @click="navigateToFolder(upload)"
                                    class="text- hover:text-blue-800 font-medium"
                                >
                                      {{ upload.name }}
                                </button>
                                <button 
                                    v-else
                                    @click="viewFile(upload)"
                                >
                                     {{ upload.name }}
                                </button>
                            </TableCell>
                            <TableCell>{{ formatDate(upload.updated_at) }}</TableCell>
                            <TableCell>{{ upload.type === 'file' ? formatFileSize(upload.size) : '-' }}</TableCell>
                            <TableCell>
                                <div class="flex space-x-2">
                                    <Button 
                                        v-if="upload.type === 'file'"
                                        @click="viewFile(upload)" 
                                        variant="outline" 
                                        size="sm"
                                    >
                                        View
                                    </Button>
                                    <Button 
                                        @click="openEditDialog(upload)" 
                                        variant="outline" 
                                        size="sm"
                                    >
                                        Edit
                                    </Button>
                                    <Button 
                                        @click="openDeleteDialog(upload)" 
                                        variant="destructive" 
                                        size="sm"
                                    >
                                        Delete
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="filteredUploads.length === 0">
                            <TableCell colspan="4" class="text-center text-muted-foreground">
                                No items in this folder. Create your first folder or upload a file.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </main>
    </div>
</AppLayout>
</template>
