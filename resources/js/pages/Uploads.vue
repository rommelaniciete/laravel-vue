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
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogFooter,
    DialogClose
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
</script>
t

<template>
    <Head title="My Drive" />

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
                                <DialogTrigger class="w-full text-left">
                                    Folder
                                </DialogTrigger>
                            </DropdownMenuItem>

                            <DropdownMenuItem as-child>
                                <DialogTrigger class="w-full text-left">
                                    File
                                </DialogTrigger>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>

                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle> Choose file </DialogTitle>
                        </DialogHeader>
                        <Input type="file" />
                        <DialogFooter>
                          <DialogClose>
                            <Button variant="ghost">Cancel</Button>
                          </DialogClose>
                          <Button>Upload</Button>
                        </DialogFooter>
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
                                <TableCell>The 48 Laws of Power.pdf</TableCell>
                                <TableCell>You</TableCell>
                                <TableCell>Jan 9, 2026</TableCell>
                                <TableCell>2.4 MB</TableCell>
                            </TableRow>

                            <TableRow>
                                <TableCell> Projects</TableCell>
                                <TableCell>You</TableCell>
                                <TableCell>Jan 7, 2026</TableCell>
                                <TableCell>—</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </main>
        </div>
    </AppLayout>
</template>
