<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

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
import UploadController from '@/actions/App/Http/Controllers/UploadController';
import { Skeleton } from '@/components/ui/skeleton';
import upload from '@/routes/upload';

const form = useForm({
    name: '',
    type: 'folder',
    file: null as File | null,
    parent_id: ''
});

const submit = () => {
    form.post(upload.store.url(), {
        onSuccess: () => {
            form.reset();
        },
    });
};

function handleFile(event: Event) {
  const target = event.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    form.file = target.files[0]
  }
}

</script>


<template>
    <Head title="My Drive"/>

    <AppLayout>
    <div class="flex h-[calc(100vh-64px)] py-10">
        <!-- Sidebar -->
        <aside class="w-64 space-y-4 px-4 py-6">
            <Dialog>
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
        </aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-4 p-6">
            <!-- File Table -->
            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Owner</TableHead>
                            <TableHead>Last Modified</TableHead>
                            <TableHead>Size</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow>
                            <TableCell><Skeleton class="h-4 w-[100px]" /></TableCell>
                            <TableCell><Skeleton class="h-4 w-[200px]" /></TableCell>                                     
                            <TableCell><Skeleton class="h-4 w-[100px]" /></TableCell>
                            <TableCell><Skeleton class="h-4 w-[200px]" /></TableCell>
                        </TableRow>

                        <TableRow>
                            <TableCell> <Skeleton class="h-4 w-[200px]" /> </TableCell>
                            <TableCell><Skeleton class="h-4 w-[100px]" /></TableCell>
                            <TableCell><Skeleton class="h-4 w-[200px]" /></TableCell>
                            <TableCell><Skeleton class="h-4 w-[100px]" /></TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </main>
    </div>
</AppLayout>
</template>
